<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransaction extends Model
{
    protected $table = 'student_transactions';
    protected $fillable = ['student_id', 'transaction'];
    protected function casts(): array
    {
        return [
            'transaction' => 'array',
        ];
    }
}
