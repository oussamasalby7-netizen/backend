<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

 public function index(Request $request)
{
    // Si l'utilisateur connecté est admin, on récupère tout
    $user = auth()->user();

    if ($user->role === 'admin') {
        $transactions = Transaction::orderBy('id', 'desc')->get();
    } else {
        // Sinon, l'utilisateur normal ne voit que ses transactions
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    return response()->json($transactions);
}


    // POST /api/transactions
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'compte_source' => 'nullable|string',
            'compte_dest' => 'nullable|string',
            'montant' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required',
            'statut' => 'nullable|string',
            'user_id' => 'required|integer',
        ]);

        $transaction = Transaction::create([
            'type' => $request->type,
            'compte_source' => $request->compte_source,
            'compte_dest' => $request->compte_dest,
            'montant' => $request->montant,
            'description' => $request->description,
            'date' => $request->date,
            'statut' => $request->statut ?? 'validé',
            'user_id' => $request->user_id,
        ]);


        return response()->json($transaction, 201);
    }

    // DELETE transaction
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'message' => 'Transaction supprimée'
        ]);
    }
}
