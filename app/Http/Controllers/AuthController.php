<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function authenticate(Request $request)
    {
        // verificando que el usuario aya introducido un nickname y password
        if (!$request->query('nickname') && !$request->query('password')) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Ingrese un nombre de usuario y contraseña'
            ], 400);
        }

        $user = User::where('nickname', $request->query('nickname'))
            ->first(['id', 'name', 'image', 'nickname', 'password', 'token', 'is_admin']);

        if ($user) {
            if (password_verify($request->query('password'), $user->password)) {
                return response()->json($user);
            }
        }

        return response()->json([
            'success' => false,
            'mensaje' => 'Credenciales incorrectos'
        ], 400);
    }
}
