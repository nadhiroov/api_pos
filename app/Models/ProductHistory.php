<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    protected $fillable = ['product_id', 'in', 'out'];
    protected function casts(): array
    {
        return [
            'in' => 'array',
            'out' => 'array',
        ];
    }
}
