<?php

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */

namespace Adwiv\Laravel\CrudGenerator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ColumnInfo
{
    public string $name;
    public string $type;
    public int $length = 0;
    public int $precision = 0;
    public bool $unsigned;
    public bool $notNull;
    public bool $unique;
    public ?string $foreign;
    public array $values = [];

    public function __construct(array $column, array $uniques, array $foreign)
    {
        // echo "Column: " . print_r($column, true) . "\n";
        $this->name = $column['name'];
        $this->type = $column['type_name'];
        $this->extractFieldLength($column['type']);
        $this->unsigned = str_contains($column['type'], 'unsigned');
        $this->notNull = $column['nullable'] === false;
        $this->unique = in_array($this->name, $uniques);
        $this->foreign = $foreign[$this->name] ?? null;

        if ($this->type == 'tinyint' && $this->length == 1) {
            $this->type = 'boolean';
            $this->length = 0;
        }
        if ($this->type == 'enum' || $this->type == 'set') {
            if (!preg_match("/^{$this->type}\((.*)\)$/", $column['type'], $matches)) die("Invalid {$this->type} value");
            $this->values = str_getcsv($matches[1], ',', "'");
        }
    }

    public function isNullable(): bool
    {
        return !$this->notNull;
    }

    public function isUuid(): bool
    {
        return $this->type == 'char' && $this->length == 36;
    }

    public function isUlid(): bool
    {
        return $this->type == 'char' && $this->length == 26;
    }

    private function extractFieldLength($type): void
    {
        // Use preg_match to extract the length within parentheses
        if (preg_match('/\((\d+)\)/', $type, $matches)) {
            $this->length = (int)$matches[1];
        }

        if (preg_match('/\((\d+),(\d+)\)/', $type, $matches)) {
            $this->length = (int)$matches[1];
            $this->precision = (int)$matches[2];
        }
    }

    public function validationType()
    {
        switch ($this->type) {
            case 'year':
            case 'smallint':
            case 'integer':
            case 'bigint':
            case 'smallint':
                return 'integer';
            case 'decimal':
                return "decimal:{$this->precision}";
            case 'float':
            case 'double':
                return 'numeric';
            case 'varchar':
            case 'string':
            case 'ascii_string':
            case 'enum':
            case 'text':
            case 'guid':
                return 'string';
            case 'char':
                return $this->length == 36 ? 'uuid' : ($this->length == 26 ? 'ulid' : 'string');
            case 'tinyint':
            case 'boolean':
                return 'boolean';
            case 'date':
            case 'date_immutable':
                return 'date_format:Y-m-d';
            case 'timestamp':
            case 'datetime':
            case 'datetime_immutable':
                return 'date_format:Y-m-d\\TH:i:s';
            case 'time':
            case 'time_immutable':
                return 'date_format:H:i:s';
            case 'array':
            case 'simple_array':
            case 'json':
            case 'set':
                return 'array';
        }
        return $this->type;
    }

    public function formInputType()
    {
        switch ($this->type) {
            case 'char':
            case 'varchar':
            case 'string':
            case 'guid':
            case 'ascii_string':
            case 'tinytext':
                return 'string';
            case 'text':
            case 'mediumtext':
            case 'longtext':
                return 'textarea';
            case 'date':
            case 'date_immutable':
                return 'date';
            case 'time':
            case 'time_immutable':
                return 'time';
            case 'timestamp':
            case 'datetime':
            case 'datetime_immutable':
                return 'datetime';
            case 'smallint':
            case 'integer':
            case 'bigint':
            case 'mediumint':
            case 'int':
                return 'integer';
            case 'decimal':
            case 'float':
            case 'double':
                return 'numeric';
            case 'tinyint':
            case 'boolean':
                return 'boolean';
            case 'enum':
                return 'radio';
            case 'set':
                return 'checkbox';
            default:
                return 'unknown';
        }
    }

    public function castType()
    {
        switch ($this->type) {
            case 'year':
                return 'integer';
            case 'tinyint':
            case 'boolean':
                return 'boolean';
            case 'decimal':
                return "decimal:{$this->precision}";
            case 'date':
            case 'date_immutable':
                return 'date:Y-m-d';
            case 'timestamp':
            case 'datetime':
            case 'datetime_immutable':
                return 'datetime';
            case 'json':
            case 'array':
            case 'simple_array':
                return 'array';
        }
        return null;
    }

    /**
     * @return ColumnInfo[]
     * @throws Exception
     */
    public static function fromTable($table): array
    {
        assert(Schema::hasTable($table), "Database table '$table' does not exist.");

        $uniques = self::getUniqueColumns($table);
        $foreign = self::getForeignColumns($table);

        $columns = [];
        foreach (Schema::getColumns($table) as $column) {
            $columnName = $column['name'];
            $columnInfo = new ColumnInfo($column, $uniques, $foreign);
            $columns[$columnName] = $columnInfo;
        }
        return $columns;
    }

    public static function getEnumColumns(string $table): array
    {
        $columns = self::fromTable($table);
        return array_filter($columns, fn($column) => $column->type === 'enum' || $column->type === 'set');
    }

    public static function getUniqueColumns($table): array
    {
        $uniques = [];
        foreach (Schema::getIndexes($table) as $index) {
            if ($index['unique'] && count($index['columns']) == 1) {
                $uniques[] = $index['columns'][0];
            }
        }
        return $uniques;
    }

    public static function getForeignColumns($table): array
    {
        $foreign = [];
        foreach (Schema::getForeignKeys($table) as $foreignKey) {
            if (count($foreignKey['columns']) == 1) {
                $localColumn = $foreignKey['columns'][0];
                $foreignTable = $foreignKey['foreign_table'];
                $foreignColumn = $foreignKey['foreign_columns'][0];
                $foreign[$localColumn] = "$foreignTable,$foreignColumn";
            }
        }
        return $foreign;
    }

    public static function getReferencingKeys($refTable)
    {
        $keys = [];
        $schema = "";

        $db = config('database.default');
        if ($db == 'mysql') {
            $schema = config('database.connections.mysql.database');
        } else if ($db == 'pgsql') {
            $schema = config('database.connections.pgsql.database');
        } else if ($db == 'mariadb') {
            $schema = config('database.connections.mariadb.database');
        } else if ($db == 'sqlsrv') {
            $schema = config('database.connections.sqlsrv.database');
        }
        echo "Schema: $schema\n";
        foreach (Schema::getTables() as $table) {
            if ($schema != $table['schema']) continue;

            $tableName = $table['name'];
            $columns = self::fromTable($tableName);
            $foreignKeys = Schema::getForeignKeys($tableName);

            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey['foreign_table'] == $refTable) {
                    if (count($foreignKey['columns']) == 1) {
                        $key = [
                            'table' => $tableName,
                            'key' => $foreignKey['columns'][0],
                            'ref' => $foreignKey['foreign_columns'][0],
                        ];
                        $key['unique'] = $columns[$key['key']]->unique;
                        $keys[] = $key;
                    }
                }
            }
        }
        return $keys;
    }
}
