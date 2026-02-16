<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'phone2FA',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    // ------------------ Hash automatique ------------------
    public function setMotDePasseAttribute($value)
    {
        $this->attributes['mot_de_passe'] = bcrypt($value);
    }

    // ------------------ Auth ------------------
    // Laravel Auth utilisera ce champ pour vérifier le mot de passe
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // ------------------ JWT Methods ------------------
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // ------------------ Relationships ------------------
    public function comptes()
    {
        return $this->hasMany(Compte::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
