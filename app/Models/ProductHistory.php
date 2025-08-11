<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
