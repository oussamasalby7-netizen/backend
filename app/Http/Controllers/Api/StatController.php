<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Compte;

class StatController extends Controller
{

    public function show($userId)
    {
        $favori = Transaction::where('user_id', $userId)//=>
            ->whereNotNull('compte_dest')
            ->selectRaw('compte_dest, SUM(montant) as total')
            ->groupBy('compte_dest')
            ->orderByDesc('total')
            ->first();


        $contactFrequent = Transaction::where('user_id', $userId)//<=
            ->whereNotNull('compte_source')
            ->selectRaw('compte_source, SUM(montant) as total')
            ->groupBy('compte_source')
            ->orderByDesc('total')
            ->first();

        return response()->json([
            "id" => (string)$userId,
            "user_id" => (string)$userId,
            "favori" => $favori ? [
                "compte_dest" => $favori->compte_dest,
                "total" => (float)$favori->total
            ] : null,
            "contact_frequent" => $contactFrequent ? [
                "compte_source" => $contactFrequent->compte_source,
                "total" => (float)$contactFrequent->total
            ] : null
        ]);
    }


    public function userStats()
    {
        $userId = request()->query('user_id');
        return $this->show($userId);
    }
}
