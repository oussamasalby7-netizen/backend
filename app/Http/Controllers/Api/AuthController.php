<?php
namespace App\Http\Controllers\Api;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use App\Models\User;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Support\Facades\Auth;

 class AuthController extends Controller
 {
     public function register(Request $request)
     {

         $validator = Validator::make($request->all(), [
             'nom' => 'required|string|max:255',
             'email' => 'required|string|email|unique:users',
             'mot_de_passe' => 'required|string|min:3',

         ]);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
          }

            $user = User::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'mot_de_passe' => $request->mot_de_passe,
             'role' => 'client'
         ]);

          
          $token = Auth::login($user);

           return response()->json([
            'message' => 'Inscription réussie !',
             'user' => $user,
                'token' => $token
            ], 201);
        }


        public function login(Request $request)
        {
           $credentials = [
    'email' => $request->email,
    'password' => $request->mot_de_passe
];

if (!$token = Auth::attempt($credentials)) {
    return response()->json(['error' => 'Email ou mot de passe incorrect'], 401);
}




            return response()->json([
                'message' => 'Connexion réussie !',
                'user' => Auth::user(),
                'token' => $token
           ]);
     }


        public function logout(Request $request)
        {
           Auth::logout();
           return response()->json(['message' => 'Déconnexion réussie !']);
        }
   }
