<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //public function register(Request $request)
    public function register(Request $request)
    {
        return User::create([
             'name'=>$request->input('name'),
             'email'=>$request->input('email'),
             'password'=>Hash::make($request->input('password'))
        ]);
     }
 
     public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                throw new \Exception('Identifiants invalides');
            }

            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;

            $cookie = cookie('jwt', $token, 60 * 24);

            return response([
                'message' => 'Connexion réussie',
                'user_id' => $user->id,
            ])->withCookie($cookie);

        } catch (\Exception $e) {
            \Log::error('Erreur de connexion: ' . $e->getMessage());

            return response([
                'message' => 'Échec de la connexion',
                'error' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
 
     public function logout(Request $request)
     {
       $cookie=Cookie::forget('jwt');
 
       return response([
         'message'=>'Succes'
       ])->withCookie($cookie);
     }
 
     public function user(){
         return 'Authentificated user';
     }
}
