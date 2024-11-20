<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']); // Criptografando a senha antes de criar o usuário

        $user = User::create($data);

        if ($user) {
            return $this->success($user, 'Usuário cadastrado com sucesso!', 201);
        }

        return $this->error('Erro ao cadastrar usuário', 400);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return $this->success([
                'user' => $user,
                'access_token' => $token,
            ], 'Login realizado com sucesso!', 200);
        }

        return $this->error('Credenciais inválidas', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success([], 'Logout realizado com sucesso!', 200);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        $newToken = $user->createToken('authToken')->plainTextToken;

        return $this->success([
            'success' => true,
            'message' => 'Token atualizado com sucesso!',
            'access_token' => $newToken,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function me(Request $request)
    {
        return $this->success($request->user(), 'Usuário autenticado', 200);
    }
}
