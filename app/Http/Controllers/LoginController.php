<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ];

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (Instituicao::where('id_usuario', $user->id)->exists()) {
                $request->session()->regenerate();

                $instituicao = Instituicao::where('id_usuario', $user->id)->first();

                $instituicaoController = new InstituicaoController();
                $instituicaoController->verificaPerfil($request, $instituicao);

                // Verifica se o usuário já preencheu o perfil
                if (!$request->session()->has('perfil_preenchido_' . $user->id)) {
                    // Se não tiver, redireciona para o preenchimento do perfil
                    return redirect()->route('instituicao.index')->with('success', 'Login, realizado com sucesso!! Por favor, preencha seu perfil.');
                }

                // Se já tiver preenchido, redireciona para a página inicial
                return redirect()->route('home')->with('success', 'Login realizado com sucesso!!');
            }

            return redirect()->route('login')->withErrors('Usuário não é uma instituição!');
        }

        return back()->withErrors(['email' => 'As credenciais fornecidas estão incorretas']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Você saiu da sua conta!');
    }
}

