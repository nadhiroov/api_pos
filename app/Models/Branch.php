<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['shop_id', 'name', 'address', 'phone', 'user_id'];

    function shops() : BelongsTo {
        return $this->belongsTo(Shop::class);
    }
}
