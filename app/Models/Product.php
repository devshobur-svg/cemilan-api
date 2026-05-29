<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'category', 'image', 'has_variants', 'variants', 'stock'];

    protected $casts = [
        'variants' => 'array',
        'has_variants' => 'boolean'
    ];
}