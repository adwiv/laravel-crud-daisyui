<?php

namespace Adwiv\Laravel\CrudGenerator\Console;

use Adwiv\Laravel\CrudGenerator\CrudHelper;
use BackedEnum;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ViewMakeCommand extends GeneratorCommand
{
    use CrudHelper;

    protected $name = 'crud:view';
    protected $type = 'View';
    protected $view = 'view';

    protected $viewName = null;
    protected $viewType = null;
    protected $resourceType = null;

    protected function getStub(): string
    {
        $nested = $this->resourceType !== 'regular' ? '.nested' : '';
        return $this->resolveStubPath("/stubs/views/{$this->viewType}{$nested}.stub");
    }

    protected function getPath($name)
    {
        $path = str_replace('.', DIRECTORY_SEPARATOR, $this->getNameInput());
        return $this->viewPath("$path.blade.php");
    }

    protected final function buildClass($name)
    {
        $this->viewName = $this->getNameInput();
        $segments = explode('.', $this->viewName);

        // Validate the view name
        if (count($segments) < 2) $this->fail("Invalid view name. Must be in the format of: [<prefix>.]<model>.<view>");

        // Validate the view type
        $allowedViewTypes = ['index', 'edit', 'show'];
        $this->viewType = array_pop($segments);
        if (!in_array($this->viewType, $allowedViewTypes)) $this->fail("Invalid view name. Must end in one of: index, edit, show");

        $modelViewPrefix = array_pop($segments);
        $guessModel = Str::studly(Str::singular($modelViewPrefix));
        $modelFullName = $this->getCrudModel($guessModel . $this->type);

        $viewPrefix = substr($this->viewName, 0, strrpos($this->viewName, '.'));
        return $this->buildView($viewPrefix, $modelFullName);
    }

    private function buildView($viewPrefix, $modelFullName)
    {
        $modelBaseName = class_basename($modelFullName);
        /** @var Model $modelInstance */
        $modelInstance = new $modelFullName();
        $table = $modelInstance->getTable();

        // Get the resource type
        $this->resourceType = $this->getCrudControllerType($table);

        // Check if the model has a parent model
        $parentBaseName = $parentFullName = $parentTable = $parentKeyName = null;
        if ($this->resourceType !== 'regular') {
            $parentFullName = $this->getCrudParentModel($table) or $this->fail("No parent model even though resource type is $this->resourceType");
            $parentBaseName = class_basename($parentFullName);
            $parentInstance = new $parentFullName();
            $parentTable = $parentInstance->getTable();
            $parentKeyName = Schema::hasColumn($parentTable, 'name') ? 'name' : $parentInstance->getKeyName();
        }

        // Get the route prefix
        $routePrefix = $this->getCrudRoutePrefix($modelBaseName, $parentBaseName, $viewPrefix);
        $routePrefixParts = explode('.', $routePrefix);
        $modelRoutePrefix = array_pop($routePrefixParts);
        $parentRoutePrefix = array_pop($routePrefixParts);
        $parentRouteFullPrefix = implode('.', $routePrefixParts) . '.' . $parentRoutePrefix;

        $modelVariable = Str::singular($modelRoutePrefix);
        $modelKeyName = Schema::hasColumn($table, 'name') ? 'name' : $modelInstance->getKeyName();

        $this->copyBladeFiles();

        $ignore = ['id', 'uid', 'uuid', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];
        $fields = $this->getVisibleFields($modelFullName, $ignore);
        $replace = $this->buildViewReplacements($modelFullName, $fields, $modelVariable, $parentTable);

        $homeroute = $this->getHomeRoute();

        $parentModelVariable = $parentRoutePrefix ? Str::singular($parentRoutePrefix) : '';
        $shallowRoutePrefix = $routePrefix;
        if ($this->resourceType === 'shallow') {
            $routePrefixParts = explode('.', $shallowRoutePrefix);
            if (count($routePrefixParts) >= 2) {
                array_splice($routePrefixParts, -2, 1);
                $shallowRoutePrefix = implode('.', $routePrefixParts);
            }
        }

        $replace = array_merge(
            $replace,
            [
                '{{ homeroute }}' => $homeroute,
                '{{ routeprefix }}' => $routePrefix,
                '{{ shallowrouteprefix }}' => $shallowRoutePrefix,
                '{{ parentrouteprefix }}' => $parentRouteFullPrefix,
                '{{ nestedRouteParams }}' => $this->resourceType === 'nested' ? "[\$$parentModelVariable, \$$modelVariable]" : "\$$modelVariable",
                '{{ model }}' => $modelBaseName,
                '{{ modelKey }}' => $modelKeyName,
                '{{ modelVariable }}' => $modelVariable,
                '{{ pluralModelTitle }}' => Str::title(Str::snake(Str::pluralStudly($modelBaseName), ' ')),
                '{{ lcpluralModelTitle }}' => Str::lower(Str::snake(Str::pluralStudly($modelBaseName), ' ')),
                '{{ pluralModelVariable }}' => $modelRoutePrefix,
                '{{ parentModel }}' => $parentBaseName ?? '',
                '{{ parentModelKey }}' => $parentKeyName,
                '{{ parentModelVariable }}' => $parentModelVariable,
                '{{ pluralParentModelTitle }}' => $parentBaseName ? Str::title(Str::snake(Str::pluralStudly($parentBaseName), ' ')) : '',
                '{{ pluralParentModelVariable }}' => $parentRoutePrefix ? $parentRoutePrefix : '',
            ],
        );

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($modelFullName)
        );
    }

    protected function buildViewReplacements($modelClass, $fields, $modelVariable, $parentTable): array
    {
        if ($this->viewType == 'index') return $this->buildIndexViewReplacements($modelClass, $fields, $modelVariable, $parentTable);
        if ($this->viewType == 'edit') return $this->buildEditViewReplacements($modelClass, $fields, $modelVariable, $parentTable);
        if ($this->viewType == 'show') return $this->buildShowViewReplacements($modelClass, $fields, $modelVariable, $parentTable);
        $this->fail("Unknown view type '$this->viewType'.");
    }

    /**
     * Copy the script stubs to view directory
     */
    protected function copyBladeFiles()
    {
        // copy all view components
        $src = __DIR__ . '/../stubs/views/components';
        $dest = $this->laravel->resourcePath('views/components');
        $this->copyDir("$src/daisyui/", "$dest/daisyui/");
        // copy all view scripts
        $src = __DIR__ . '/../stubs/views/js';
        $dest = $this->laravel->resourcePath('js');
        $this->copyDir("$src/", "$dest/");
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view to create.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite if file exists.'],
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Use the specified model class.'],
            ['parent', 'p', InputOption::VALUE_REQUIRED, 'Use the specified parent class.'],
            ['regular', null, InputOption::VALUE_NONE, 'Generate a regular controller.'],
            ['shallow', null, InputOption::VALUE_NONE, 'Generate a shallow resource controller.'],
            ['nested', null, InputOption::VALUE_NONE, 'Generate a nested resource controller.'],
            ['prefix', null, InputOption::VALUE_REQUIRED, 'Prefix path for views and routes.'],
            ['viewprefix', null, InputOption::VALUE_REQUIRED, 'Prefix path for the views used.'],
            ['routeprefix', null, InputOption::VALUE_REQUIRED, 'Prefix path for the routes used.'],
            ['homeroute', null, InputOption::VALUE_REQUIRED, 'Route name for the home page.'],
        ];
    }

    protected function buildIndexViewReplacements($modelClass, $fields, $modelVariable, $parentTable): array
    {
        $count = 0;
        $HEAD = $BODY = "";
        $modelInstance = new $modelClass();
        $castTypes = $modelInstance->getCasts();
        foreach ($fields as $field => $columnInfo) {
            if (in_array($field, ['id', 'uid', 'uuid', 'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'])) continue;

            $castType = $castTypes[$field] ?? null;
            $fieldName = ucwords(Str::replace(['_', '-', '.'], ' ', $field));
            $fieldValue = "\$$modelVariable->$field";
            if ($castType) $castType = explode(':', $castType)[0];

            if ($castType == 'array' || $columnInfo->type == 'set' || $castType === 'boolean') {
                $fieldValue = "json_encode(\${$modelVariable}->{$field})";
            }

            if ($castType === 'date' || $castType === 'immutable_date') {
                if ($columnInfo->isNullable())
                    $fieldValue = "\${$modelVariable}->{$field}?->format('Y-m-d')";
                else
                    $fieldValue = "\${$modelVariable}->{$field}->format('Y-m-d')";
            }

            if ($foreignKey = $columnInfo->foreign) {
                list($foreignTable, $foreignField) = preg_split('/,/', $foreignKey);
                if ($foreignTable == $parentTable) continue; // Skip foreign keys that point to the parent table
                $relation = Str::camel(Str::replaceEnd("_$foreignField", '', $field));
                if ($columnInfo->isNullable()) $relation = $relation . "?";
                $fieldValue = "\${$modelVariable}->{$relation}->name";
            }

            $HEAD .= "                    <th class=\"\">$fieldName</th>\n";
            $BODY .= "                    <td class=\"\">{{ $fieldValue }}</td>\n";
            $count++;

            // Add boolean indicator for nullable datetime fields
            if ($columnInfo->type === 'timestamp' && $columnInfo->isNullable() && Str::endsWith($field, '_at')) {
                $fieldName = substr($fieldName, 0, -3);
                $HEAD .= "                    <th class=\"\">$fieldName</th>\n";
                $BODY .= "                    <td class=\"\">{{ json_encode($fieldValue != null) }}</td>\n";
                $count++;
            }
        }
        $emptyCount = $count + 1;
        $EMPTY = "                        <td colspan=\"$emptyCount\" class=\"text-center\">No records found</td>";

        return [
            '{{ HEAD }}' => trim($HEAD),
            '{{ BODY }}' => trim($BODY),
            '{{ EMPTY }}' => trim($EMPTY),
        ];
    }

    protected function buildShowViewReplacements($modelClass, $fields, $modelVariable, $parentTable): array
    {
        $FIELDS = "";
        $modelInstance = new $modelClass();
        $castTypes = $modelInstance->getCasts();

        foreach ($fields as $field => $columnInfo) {
            if (in_array($field, ['password', 'remember_token'])) continue;

            $castType = $castTypes[$field] ?? null;
            $fieldName = ucwords(Str::replace(['_', '-', '.'], ' ', $field));
            $fieldValue = "\$$modelVariable->$field";
            if ($castType) $castType = explode(':', $castType)[0];

            if ($castType == 'array' || $columnInfo->type == 'set' || $castType === 'boolean') {
                $fieldValue = "json_encode(\${$modelVariable}->{$field})";
            }

            if ($castType === 'date' || $castType === 'immutable_date') {
                if ($columnInfo->isNullable())
                    $fieldValue = "\${$modelVariable}->{$field}?->format('Y-m-d')";
                else
                    $fieldValue = "\${$modelVariable}->{$field}->format('Y-m-d')";
            }

            if ($foreignKey = $columnInfo->foreign) {
                list($foreignTable, $foreignField) = preg_split('/,/', $foreignKey);
                if ($foreignTable == $parentTable) continue; // Skip foreign keys that point to the parent table
                $relation = Str::camel(Str::replaceEnd("_$foreignField", '', $field));
                if ($columnInfo->isNullable()) $relation = $relation . "?";
                $fieldValue = "\${$modelVariable}->{$relation}->name";
            }

            $FIELDS .= "
                <tr>
                    <td nowrap>$fieldName</td>
                    <td width=\"80%\">{{ $fieldValue }}</td>
                </tr>";

            // Add boolean indicator for nullable datetime fields
            if ($columnInfo->type === 'timestamp' && $columnInfo->isNullable() && Str::endsWith($field, '_at')) {
                $fieldName = substr($fieldName, 0, -3);
                $FIELDS .= "
                <tr>
                    <td nowrap>$fieldName</td>
                    <td width=\"80%\">{{ json_encode($fieldValue != null) }}</td>
                </tr>";
            }
        }

        return ['{{ FIELDS }}' => trim($FIELDS)];
    }

    protected function buildEditViewReplacements($modelClass, $fields, $modelVariable, $parentTable): array
    {
        $FIELDS = "";
        $modelInstance = new $modelClass();
        $modelCasts = $modelInstance->getCasts();

        /**
         * @var string $field
         * @var ColumnInfo $columnInfo
         */
        foreach ($fields as $field => $columnInfo) {
            if (in_array($field, ['id', 'uid', 'uuid', 'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'])) continue;

            $castType = $modelCasts[$field] ?? null;
            $formInputType = $columnInfo->formInputType();
            $fieldName = Str::title(Str::snake(Str::camel($field), ' '));
            $required = $columnInfo->notNull ? ' required' : '';

            if ($castType === 'boolean') {
                $FIELDS .= <<<END
                <x-daisyui.select label="$fieldName" name="$field" :options="['FALSE','TRUE']"{$required}/>
END;
            } else if ($columnInfo->type == 'enum' || $columnInfo->type == 'set') {
                $type = $columnInfo->type == 'set' ? 'checkbox' : 'radio';
                $choiceName = $columnInfo->type == 'set' ? $field . "[]" : $field;
                $pluralFieldVar = Str::plural($field);

                $isBackedEnum = false;
                if ($castType) {
                    $enumClass = last(explode(':', $castType));
                    $isBackedEnum = is_subclass_of($enumClass, BackedEnum::class);
                }

                if ($isBackedEnum) {
                    $FIELDS .= <<<END
                <x-daisyui.$type label="$fieldName" name="$choiceName"{$required} :options="$enumClass::array()"/>
END;
                } else {
                    $options = [];
                    foreach ($columnInfo->values as $value) {
                        $ucValue = Str::title(Str::snake(Str::camel($value), ' '));
                        $options[] = "'$value'=>'$ucValue'";
                    }
                    $options = implode(",", $options);

                    $FIELDS .= <<<END
                @php
                    \$$pluralFieldVar = [$options];
                @endphp
               
                <x-daisyui.$type label="$fieldName" name="$choiceName"{$required} :options="\$$pluralFieldVar"/>
END;
                }
            } else if ($foreignKey = $columnInfo->foreign) {
                list($foreignTable, $foreignField) = preg_split('/,/', $foreignKey);
                if ($foreignTable == $parentTable) continue; // Skip foreign keys that point to the parent table
                $foreignClass = $this->getModelForTable($foreignTable);
                $valueKey = $foreignField !== 'id' ? " valueKey=\"$foreignField\"" : '';

                $FIELDS .= <<<END
                <x-daisyui.select label="$fieldName" name="$field" :options="$foreignClass::all()"{$valueKey}{$required}/>
END;
            } else if (
                $formInputType == 'textarea' ||
                ($formInputType == 'string' && $columnInfo->length > 255)
            ) {
                $FIELDS .= <<<END
                <x-daisyui.textarea label="$fieldName" name="$field" rows="5"{$required}/>
END;
            } else {
                $type = '';
                if ($formInputType == 'date') $type = 'type="date"';
                if ($formInputType == 'time') $type = 'type="time"';
                if ($formInputType == 'datetime') $type = 'type="datetime-local" step="1"';
                if ($formInputType == 'integer') $type = 'type="number"';
                if ($formInputType == 'numeric') {
                    $step = 1 / pow(10, $columnInfo->precision);
                    $type = 'type="number" step="' . $step . '"';
                }

                if ($columnInfo->unsigned && ($formInputType == 'integer' || $formInputType == 'numeric')) {
                    $type .= ' min="0"';
                }

                $lcFieldVar = strtolower($field);
                if ($lcFieldVar == 'email' || Str::endsWith($lcFieldVar, '_email')) $type = 'type="email"';
                if (in_array($lcFieldVar, ['password', 'password_confirmation'])) $type = 'type="password"';
                if (in_array($lcFieldVar, ['phone', 'mobile']) || Str::endsWith($lcFieldVar, ['_phone', '_mobile'])) $type = 'type="tel"';
                if (in_array($lcFieldVar, ['url', 'link']) || Str::endsWith($lcFieldVar, ['_url', '_link'])) $type = 'type="url"';

                $FIELDS .= <<<END
                <x-daisyui.input $type label="$fieldName" name="$field"{$required}/>
END;
            }
            $FIELDS .= "\n\n";
        }

        $FIELDS = trim($FIELDS);

        return ['{{ FIELDS }}' => trim($FIELDS)];
    }
}
