<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'code_banque'
    ];
}
