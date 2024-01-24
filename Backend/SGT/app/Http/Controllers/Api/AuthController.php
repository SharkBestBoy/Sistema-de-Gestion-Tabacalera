<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            //validación de los datos
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed'
            ]);

            //alta del usuario
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response($user, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            // Manejar errores de validación
            return response($e->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24);
            return response(["token" => $token], Response::HTTP_OK)->withoutCookie($cookie);
        } else {
            return response(["message" => "Credenciales inválidas"], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function userProfile(Request $request)
    {
        return response()->json([
            "message" => "userProfile OK",
            "userData" => auth()->user()
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        $cookie = Cookie::forget('cookie_token');
        return response(["message" => "Cierre de sesión OK"], Response::HTTP_OK)->withCookie($cookie);
    }

    public function allUsers()
    {
        
        $users = User::all();
        return response()->json([
            "users" => $users
        ]);
    }

    public function deleteUserByEmail($email)
    {
        try {
            // Validar si el usuario tiene permisos para eliminar
            $this->authorize('delete', User::class);

            // Validar el formato del correo electrónico

            // Buscar y eliminar el usuario por correo electrónico
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->delete();
                return response(["message" => "Usuario eliminado correctamente"], Response::HTTP_OK);
            } else {
                return response(["message" => "Usuario no encontrado"], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            // Manejar cualquier excepción
            return response(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
