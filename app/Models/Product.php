<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $appends = ['image_url']; // Accessor untuk URL gambar

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('assets/images/products/empty-shopping-bag.gif');
    }

    function category() : BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }

    function branch() : BelongsTo {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
