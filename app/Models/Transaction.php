<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['branch_id', 'year', 'transaction'];
    // protected $casts = ['transaction' => 'array'];

    protected function casts(): array
    {
        return [
            'transaction' => 'array',
        ];
    }

    /* protected function casts(): array
    {
        return [
            'options' => AsCollection::class,
        ];
    } */

    public function branch() : BelongsTo {
        return $this->belongsTo(Branch::class);
    }
}
