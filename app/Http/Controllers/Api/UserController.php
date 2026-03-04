<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function index()
    {
        return response()->json(User::all());
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|min:3',
            'role' => 'in:admin,client'
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'mot_de_passe' => bcrypt($validated['mot_de_passe']),
            'role' => $validated['role'] ?? 'client',
        ]);

        return response()->json($user, 201);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé'
        ]);
    }


    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'nom' => 'string|max:255',
        'email' => 'email|unique:users,email,'.$id,
        'mot_de_passe' => 'string|min:3',
        'phone2FA' => 'string|nullable',
    ]);

    if ($request->has('mot_de_passe')) {
        $user->mot_de_passe = bcrypt($request->mot_de_passe);
    }

    if ($request->has('nom')) $user->nom = $request->nom;
    if ($request->has('email')) $user->email = $request->email;
    if ($request->has('phone2FA')) $user->phone2FA = $request->phone2FA;

    $user->save();

    return response()->json($user);
}}