<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'compte_source',
        'compte_dest',
        'montant',
        'description',
        'date',
        'statut',
        'user_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'montant' => 'decimal:2',
    ];
}
