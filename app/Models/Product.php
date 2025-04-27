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

    function categories() : BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }

    function branches() : BelongsTo {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
