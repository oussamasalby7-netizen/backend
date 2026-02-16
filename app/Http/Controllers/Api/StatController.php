<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Compte;

class StatController extends Controller
{
    // GET /api/stats/{userId}
    public function show($userId)
    {
        //transactions 
        $transactions = Transaction::where('user_id', $userId)->get();

        // compte le plus reçu (favori)
        $favori = Transaction::where('user_id', $userId)
            ->whereNotNull('compte_dest')
            ->selectRaw('compte_dest, SUM(montant) as total')
            ->groupBy('compte_dest')
            ->orderByDesc('total')
            ->first();

        // contact fréquent (envoyé)
        $contactFrequent = Transaction::where('user_id', $userId)
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

    // Optionnel: GET /api/user-stats?user_id=1
    public function userStats()
    {
        $userId = request()->query('user_id');
        return $this->show($userId);
    }
}
