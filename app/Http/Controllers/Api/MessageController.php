<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    
    public function index()
    {
        return response()->json(
            Message::orderBy('id', 'desc')->get()
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $msg = Message::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'sujet' => $validated['sujet'],
            'message' => $validated['message'],
            'statut' => 'en_attente',
            'date_creation' => now(),
        ]);

        return response()->json($msg, 201);
    }


    public function update(Request $request, $id)
    {
        $msg = Message::findOrFail($id);

        $msg->update($request->only([
            'nom',
            'email',
            'sujet',
            'message',
            'statut'
        ]));

        return response()->json($msg);
    }


    public function destroy($id)
    {
        $msg = Message::findOrFail($id);
        $msg->delete();

        return response()->json([
            'message' => 'Message supprimé avec succès'
        ]);
    }


    public function show($id)
    {
        return response()->json(
            Message::findOrFail($id)
        );
    }
}
