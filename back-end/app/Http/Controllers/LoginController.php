<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        
        $user = User::firstOrCreate(['email' => $request->email]);

        if (!$user) {
            return response()->json(['message' => 'Could not process a user with that email.'], 401);
        }

        
        $loginCode = rand(111111, 999999);
        $user->update(['login_code' => $loginCode]);

        
        Mail::raw("Seu código de login é: {$loginCode}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Código de Verificação');
        });

        return response()->json(['message' => 'Código enviado para o email.']);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        $user = User::where('email', $request->email)
                    ->where('login_code', $request->login_code)
                    ->first();

        if ($user) {
            $user->update(['login_code' => null]);

            return response()->json(['token' => $user->createToken('LoginToken')->plainTextToken], 200);
        }

        return response()->json(['message' => 'Código de verificação inválido'], 401);
    }
}
