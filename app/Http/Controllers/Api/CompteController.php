<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompteController extends Controller
{

  public function index()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        // Tous les comptes avec la banque jointe
        $comptes = Compte::with('banque')->get();
    } else {
        // Comptes de l'utilisateur seulement
        $comptes = Compte::where('user_id', $user->id)->with('banque')->get();
    }

    // Ajouter un champ nom_banque pour chaque compte
    $comptes->map(function ($c) {
        $c->nom_banque = $c->banque ? $c->banque->nom : "-";
        return $c;
    });

    return response()->json($comptes);
}




    public function show($id)
    {
        $compte = Compte::find($id);
        if (!$compte) return response()->json(['message' => 'Compte introuvable'], 404);
        return response()->json($compte);
    }


    public function update(Request $request, $id)
    {
        $compte = Compte::find($id);
        if (!$compte) return response()->json(['message' => 'Compte introuvable'], 404);

        $request->validate([
            'solde' => 'required|numeric',
        ]);

        $compte->solde = $request->solde;
        $compte->save();

        return response()->json($compte);
    }


    public function store(Request $request)
    {
        $request->validate([
            'rib' => 'required|string|unique:comptes|min:6',
            'solde' => 'required|numeric',
            'type' => 'required|string',
            'banque_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $compte = Compte::create($request->all());
        return response()->json($compte, 201);
    }


    public function destroy($id)
    {
        $compte = Compte::find($id);
        if (!$compte) return response()->json(['message' => 'Compte introuvable'], 404);

        if ($compte->solde != 0) {
            return response()->json(['message' => 'Impossible de supprimer un compte non vide'], 400);
        }

        $compte->delete();
        return response()->json(['message' => 'Compte supprimé']);
    }
}
