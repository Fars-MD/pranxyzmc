<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'avatar',
        'rating',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }
}
