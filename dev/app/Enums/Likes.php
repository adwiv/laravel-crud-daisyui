<?php

namespace App\Enums;

enum Likes: string
{
    case reading = 'reading';
    case writing = 'writing';
    case drawing = 'drawing';
    case cooking = 'cooking';
    case dancing = 'dancing';
    case singing = 'singing';
    case other = 'other';

    public function label(): string 
    {
        return match ($this) {
            self::reading => 'Reading',
            self::writing => 'Writing',
            self::drawing => 'Drawing',
            self::cooking => 'Cooking',
            self::dancing => 'Dancing',
            self::singing => 'Singing',
            self::other => 'Other',
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
