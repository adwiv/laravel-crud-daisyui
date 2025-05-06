<?php

namespace App\Enums;

enum Degree: string
{
    case graduate = 'graduate';
    case postgrad = 'postgrad';
    case doctorate = 'doctorate';

    public function label(): string 
    {
        return match ($this) {
            self::graduate => 'Graduate',
            self::postgrad => 'Postgrad',
            self::doctorate => 'Doctorate',
        };
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), array_map(fn($role) => $role->label(), self::cases()));
    }
}
