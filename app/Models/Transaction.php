<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transactionel_id',
        'transactionel_type',
        'operation',
    ];

    public function transactionel(): MorphTo
    {
        return $this->morphTo();
    }
}
