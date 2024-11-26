<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Voluntario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    // Função de login
    public function login(Request $request)
    {
        // Valida os dados recebidos
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Retorna erro se a validação falhar
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        // Tenta autenticar com os dados passados
        if (!Auth::attempt($request->only('email', 'password'))) {
            //FAZER A VALIDAÇÃO SE É VOLUNTÁRIO E NÃO INSTITUIÇÃO
            return response()->json(['message' => 'Dados de Login Inválidos'], 401);
        }

        // Busca o usuário pelo email e gera um token
        $user = User::where('email', $request['email'])->firstOrFail();

        if (Voluntario::where('id_usuario', $user->id)->exists()) {

            $token = $user->createToken('auth_token')->plainTextToken;

            // Retorna sucesso no login com o token e dados do usuário
            return response()->json([
                'status' => 'success',
                'message' => 'Login realizado com sucesso!',
                'data' => [
                    'id' => $user->id,
                    'nome' => $user->name,
                    'email' => $user->email,
                    'tokenAuth' => $token
                ],
            ], 200);
        }
    }

    // Função de logout
    public function logout(Request $request)
    {
        // Pega o usuário logado e deleta seus tokens
        $user = Auth::user()->id;
        $user->tokens()->delete();

        return response()->json(['message' => 'Usuário efetuou logout com sucesso'], 200);
    }

    // Função para atualizar o token de autenticação
    public function atualizarToken(Request $request)
    {
        // Pega o usuário logado e deleta os tokens antigos
        $user = Auth::user()->id;
        $user->tokens()->delete();

        // Gera um novo token e retorna
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

}
