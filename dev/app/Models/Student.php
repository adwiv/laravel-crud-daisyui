<?php

namespace App\Models;

use App\Enums\Degree;
use App\Enums\Likes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Student extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date:Y-m-d',
            'degree' => Degree::class,
            'likes' => Likes::class,
            'is_active' => 'boolean',
        ];
    }

    public function findByEmail($email): ?self
    {
        return self::where('email', $email)->first();
    }

    public function findByEmailOrFail($email): self
    {
        return self::where('email', $email)->firstOrFail();
    }
    
    
    
    
}
