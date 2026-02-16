<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'user_id',
        'favori',
        'contact_frequent',
        'total_transactions'
    ];

    protected $casts = [
        'favori' => 'array',
        'contact_frequent' => 'array',
        'total_transactions' => 'array',
    ];
}
