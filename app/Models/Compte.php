<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = ['rib', 'solde', 'type', 'banque_id', 'user_id'];

    public function banque()
    {
        return $this->belongsTo(Banque::class);
    }
}
