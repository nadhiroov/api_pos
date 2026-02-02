<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['branch_id', 'year', 'transaction', 'user_id'];
    protected function casts(): array
    {
        return [
            'transaction' => 'array',
        ];
    }

    public function branch() : BelongsTo {
        return $this->belongsTo(Branch::class);
    }

    public function products()
    {
        $items = Arr::get($this->transaction, 'items', []);
        $ids = collect($items)->pluck('product_id')->all();
        return Product::whereIn('id', $ids);
    }

    public function getProductItemsAttribute()
    {
        return $this->products()->get();
    }
}
