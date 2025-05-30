<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['shop_id', 'name'];

    public function shop() : BelongsTo {
        return $this->belongsTo(Shop::class);
    }

    public function products() : HasMany {
        return $this->hasMany(Product::class);
    }
}
