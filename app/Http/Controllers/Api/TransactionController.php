<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $transactions = Transaction::orderBy('id', 'desc')->get();
        } else {
            $transactions = Transaction::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return response()->json($transactions);
    }


    public function store(Request $request)
{
    $request->validate([
        'type' => 'required|string',
        'compte_source' => 'nullable|string',
        'compte_dest' => 'nullable|string',
        'montant' => 'required|numeric',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'statut' => 'nullable|string',
        'user_id' => 'required|integer',
    ]);

    DB::beginTransaction();

    try {
        $montant = $request->montant;

        
        if ($request->type === 'depot') {
            $destCompte = Compte::where('rib', $request->compte_dest)->first();

            if (!$destCompte) {
                return response()->json(['message' => 'Compte destinataire introuvable'], 404);
            }

            $destCompte->solde += $montant;
            $destCompte->save();

            $transaction = Transaction::create([
                'type' => $request->type,
                'compte_source' => $request->compte_source,
                'compte_dest' => $request->compte_dest,
                'montant' => $montant,
                'description' => $request->description,
                'date' => $request->date,
                'statut' => $request->statut ?? 'validé',
                'user_id' => $request->user_id,
            ]);

            DB::commit();
            return response()->json($transaction, 201);
        }


        $sourceCompte = Compte::where('rib', $request->compte_source)->first();
        if (!$sourceCompte) {
            return response()->json(['message' => 'Compte source introuvable'], 404);
        }

        if ($sourceCompte->solde < $montant) {
            return response()->json(['message' => 'Solde insuffisant'], 400);
        }


        $sourceCompte->solde -= $montant;
        $sourceCompte->save();


        if (!empty($request->compte_dest)) {
            $destCompte = Compte::where('rib', $request->compte_dest)->first();

            if ($destCompte) {
                $destCompte->solde += $montant;
                $destCompte->save();
            }
        }

        $transaction = Transaction::create([
            'type' => $request->type,
            'compte_source' => $request->compte_source,
            'compte_dest' => $request->compte_dest,
            'montant' => $montant,
            'description' => $request->description,
            'date' => $request->date,
            'statut' => $request->statut ?? 'validé',
            'user_id' => $request->user_id,
        ]);

        DB::commit();

        return response()->json($transaction, 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Erreur lors de la transaction',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction supprimée']);
    }
}
