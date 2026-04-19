<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioContent extends Model
{
    protected $fillable = [
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}
