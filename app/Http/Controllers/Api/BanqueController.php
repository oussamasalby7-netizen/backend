<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BanqueController extends Controller
{

    public function index()
    {
        $banques = Banque::all();
        return response()->json(['banques' => $banques]);
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'code_banque' => 'required|string|max:255|unique:banques',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $banque = Banque::create($validatedData);
        return response()->json(['banque' => $banque], 201);
    }


    public function show(string $id)
    {
        $banque = Banque::find($id);
        if (!$banque) {
            return response()->json(['message' => 'Banque not found'], 404);
        }

        return response()->json(['banque' => $banque]);
    }


    public function update(Request $request, string $id)
    {
        $banque = Banque::find($id);
        if (!$banque) {
            return response()->json(['message' => 'Banque not found'], 404);
        }

        try {
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'code_banque' => 'required|string|max:255|unique:banques,code_banque,' . $id,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $banque->update($validatedData);
        return response()->json(['banque' => $banque]);
    }

    
    public function destroy(string $id)
    {
        $banque = Banque::find($id);
        if (!$banque) {
            return response()->json(['message' => 'Banque not found'], 404);
        }

        $banque->delete();
        return response()->json(['message' => 'Banque deleted successfully']);
    }
}
