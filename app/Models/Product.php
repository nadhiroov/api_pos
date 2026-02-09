<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'branch_id',
        'category_id',
        'name',
        'sku',
        'price',
        'image',
        'description',
        'unit',
        'stock',
        'barcode',
    ];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? route('product.image', $this->image) : asset('assets/images/products/empty-shopping-bag.gif');
    }

    function category() : BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }

    function branch() : BelongsTo {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    function history() : HasOne {
        return $this->hasOne(ProductHistory::class);
    }
}
