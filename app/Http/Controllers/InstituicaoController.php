<?php

namespace App\Http\Controllers;

use App\Models\CategoriaDoacao;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\Habilidade;
use App\Models\Instituicao;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InstituicaoController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $instituicao = Instituicao::with(['usuario', 'contato', 'endereco'])
            ->where('id_usuario', $userId)
            ->first();

        // Decodifica o JSON de funcionamento
        $funcionamento = json_decode($instituicao->funcionamento_instituicao, true);

        $categorias = CategoriaDoacao::whereHas('doacoes', function ($query) use ($instituicao) {
            $query->where('id_instituicao', $instituicao->id_instituicao);
        })->get();

        $habilidades = Habilidade::all();

        return view('perfil', compact('instituicao', 'funcionamento', 'categorias', 'habilidades'));
    }

    public function create(){
        return view('cadastro');
    }

    public function store(Request $request)
    {
        // Mensagens de validação personalizadas
        $messages = [
            'nome.required' => 'O campo nome é obrigatório.',
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.unique' => 'O CNPJ informado já está cadastrado.',
            'cnpj.min' => 'O CNPJ deve ter 18 caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.unique' => 'O email informado já está cadastrado.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'senha.max' => 'A senha pode ter no máximo 60 caracteres.',
            'cep.required' => 'O campo cep é obrigatório.',
            'rua.required' => 'O campo rua é obrigatório.',
            'estado.required' => 'O campo estado é obrigatório.',
            'numero.required' => 'O campo número é obrigatório.',
            'bairro.required' => 'O campo bairro é obrigatório.',
            'cidade.required' => 'O campo cidade é obrigatório.',
            'estado.required' => 'O campo estado é obrigatório.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'telefone.min' => 'O telefone deve ter 16 caracteres.',
            'complemento.required' => 'O campo complemento é obrigatório.',
            'complemento.max' => 'O complemento pode ter no máximo 50 caracteres.',
        ];

        // Validação dos campos
        $request->validate([
            'nome' => 'required|string|max:80',
            'cnpj' => 'required|string|min:18|max:18|unique:instituicao,cnpj_instituicao',
            'email' => 'required|email|max:80|unique:users,email',
            'senha' => 'required|min:6|max:40',
            'rua' => 'required|string|max:50',
            'cep' => 'required|string|max:9',
            'numero' => 'required|string',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'estado' => 'required|string|max:2',
            'complemento' => 'required|string|max:50',
            'telefone' => 'required|string|min:15|max:15',
        ], $messages);


        // Criar o usuário primeiro
        $usuario = User::create([
            'name' => $request->input('nome'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('senha')), // Criptografando a senha
        ]);

        // Criar o contanto
        $contato = Contato::create([
            'telefone_contato'=> $request->input('telefone'),
        ]);

        // Criar o endereco
        $endereco = Endereco::create([
            'cep_endereco'=> $request->input('cep'),
            'complemento_endereco'=> $request->input('complemento'),
            'cidade_endereco'=> $request->input('cidade'),
            'logradouro_endereco'=> $request->input('rua'),
            'estado_endereco'=> $request->input('estado'),
            'bairro_endereco'=> $request->input('bairro'),
            'numero_endereco'=> $request->input('numero'),
        ]);

        $idUsuario = $usuario->id;
        $idContato = $contato->id_contato;
        $idEndereco = $endereco->id_endereco;

        // Criar a instituição com o id do usuário recém-criado
        Instituicao::create([
            'id_usuario' => $idUsuario,  // Associando o ID do usuário criado
            'id_contato'=> $idContato,  // Associando o ID do contato criado
            'id_endereco'=> $idEndereco,  // Associando o ID do endereço criado
            'cnpj_instituicao' => $request->input('cnpj'),
        ]);

        // Redirecionamento
        return redirect()->route('login')->with('success', 'Instituição cadastrada com sucesso!');
    }

    public function update(Request $request, Instituicao $instituicao)
    {
        $messages = [
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'senha.max' => 'A senha pode ter no máximo 60 caracteres.',
            'cep.required' => 'O campo cep é obrigatório.',
            'rua.required' => 'O campo rua é obrigatório.',
            'estado.required' => 'O campo estado é obrigatório.',
            'numero.required' => 'O campo número é obrigatório.',
            'bairro.required' => 'O campo bairro é obrigatório.',
            'cidade.required' => 'O campo cidade é obrigatório.',
            'complemento.required' => 'O campo complemento é obrigatório.',
            'complemento.max' => 'O complemento pode ter no máximo 50 caracteres.',
            'estado.required' => 'O campo estado é obrigatório.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'telefone.min' => 'O telefone deve ter 16 caracteres.',
            'whatsapp.min' => 'O telefone deve ter 16 caracteres.',
            'facebook.max' => 'O link do perfil do facebook pode ter no máximo 60 caracteres.',
            'instagram.max' => 'O link do perfil do instagram pode ter no máximo 60 caracteres.',
            'site.max' => 'O link do site institucional pode ter no máximo 60 caracteres.',
            'image_perfil.image' => 'O arquivo enviado deve ser uma imagem.',
            'image_perfil.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'descricao.required' => 'O campo sobre a instituição é obrigatório.',
            'descricao.max' => 'Sobre a instituição pode ter no máximo 200 caracteres.',
            'funcionamento.required' => 'Os campos sobre o funcionamento da instituição é obrigatório.',
        ];

        $usuario = User::find($instituicao->id_usuario);
        $endereco = Endereco::find($instituicao->id_endereco);
        $contato = Contato::find($instituicao->id_contato);

        switch ($request->input('menu')) {
            case 1:
                $request->validate([
                    'image_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'descricao' => 'required|string|max:200',
                ],$messages);

                $usuario->updateProfilePhoto($request->file('imagem_perfil'));

                $instituicao->update([
                    'descricao_instituicao' => $request->input('descricao'),
                ]);

                return redirect()->route('instituicao.index')->with('success', 'Menu conta do perfil atualizado com sucesso!!');
                break;
            case 2:
                $request->validate([
                    'rua' => 'required|string|max:50',
                    'cep' => 'required|string|max:9',
                    'numero' => 'required|string',
                    'bairro' => 'required|string|max:50',
                    'cidade' => 'required|string|max:50',
                    'estado' => 'required|string|max:2',
                    'complemento' => 'required|string|max:50',
                ],$messages);

                $endereco->update([
                    'cep_endereco'=> $request->input('cep'),
                    'complemento_endereco'=> $request->input('complemento'),
                    'cidade_endereco'=> $request->input('cidade'),
                    'logradouro_endereco'=> $request->input('rua'),
                    'estado_endereco'=> $request->input('estado'),
                    'bairro_endereco'=> $request->input('bairro'),
                    'numero_endereco'=> $request->input('numero'),
                ]);

                return redirect()->route('instituicao.index')->with('success', 'Menu endereço do perfil atualizado com sucesso!!');
                break;
            case 3:
                $request->validate([
                    'telefone' => 'required|string|min:15|max:15',
                    'whatsapp' => 'nullable|string|min:15|max:15',
                    'instagram' => 'nullable|string|max:60',
                    'facebook   ' => 'nullable|string|max:60',
                    'site' => 'nullable|string|max:60',
                ],$messages);

                $contato->update(array_filter([
                    'telefone_contato' => $request->input('telefone'),
                    'whatsapp_contato' => $request->input('whatsapp'),
                    'instagram_contato' => $request->input('instagram'),
                    'facebook_contato' => $request->input('facebook'),
                    'site_contato' => $request->input('site'),
                ]));

                return redirect()->route('instituicao.index')->with('success', 'Menu contato do perfil atualizado com sucesso!!');
                break;
            case 4:
                $request->validate([
                    'funcionamento' => 'required|array',
                    'funcionamento.*.abertura' => 'nullable|date_format:H:i',
                    'funcionamento.*.fechamento' => 'nullable|date_format:H:i',
                    'funcionamento.*.funciona' => 'nullable|string',
                ], $messages);

                // Processar os dados recebidos
                $diasSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
                $funcionamento = [];

                foreach ($diasSemana as $dia) {
                    $dados = $request->input('funcionamento.' . $dia, []);
                    $funcionamento[$dia] = [
                        'abertura' => $dados['abertura'] ?? null,
                        'fechamento' => $dados['fechamento'] ?? null,
                        'funciona' => isset($dados['funciona']) && $dados['funciona'] == 'true' ? false : true,
                    ];
                }

                // Salvar o funcionamento como JSON no banco de dados
                $instituicao->update([
                    'funcionamento_instituicao' => json_encode($funcionamento),
                ]);

                return redirect()->route('instituicao.index')->with('success', 'Menu funcionamento do perfil atualizado com sucesso!!');

                break;
            case 5:
                $request->validate([
                    'categoria' => 'required',
                ],$messages);
                break;
            case 6:
                /*$request->validate([
                    'nome_instituicao' => 'required',
                    'cnpj_instituicao' => 'required',
                ],$messages);

                return redirect()->route('perfil')->with('success', 'Menu doações do perfil atualizado com sucesso!!');
                */
                break;
            case 7:
                if ($request->has('current_password') && $request->has('new_password')) {
                    $request->validate([
                        'current_password' => 'required',
                        'new_password' => 'required|min:8',
                        'confirm_password' => 'required|same:new_password',
                    ]);

                    // Verificar se a senha atual está correta
                    if (!Hash::check($request->current_password, $usuario->password)) {
                        return redirect()->back()->withErrors(['current_password' => 'A senha atual está incorreta!']);
                    }

                    // Atualizar a senha
                    $usuario->password = Hash::make($request->new_password);
                    $usuario->save();

                    return redirect()->back()->with('success', 'Senha alterada com sucesso!');
                }

                // Outras atualizações, se necessário
                return redirect()->back()->withErrors(['error' => 'Falha ao atualizar senha.']);
        }

    }

    public function verificaPerfil(Request $request, Instituicao $instituicao)
    {
        // Obtém o usuário logado (instituição) para realizar a verificação do perfil
        $user = Auth::user();

        if ($instituicao) {
            $preenchido = !empty($instituicao->descricao_instituicao) &&
                        !empty($instituicao->funcionamento_instituicao);

            if ($preenchido) {
                // Marca na sessão que o perfil foi preenchido
                $request->session()->put('perfil_preenchido_' . $user->id, true);
            }
        }
    }

    public function listagemInstituicao()
    {
        $instituicoes = Instituicao::select(
            'id_instituicao',
            'id_usuario',
            'id_contato',
            'id_endereco',
            'descricao_instituicao',
            'funcionamento_instituicao',
            'cnpj_instituicao'
        )
        ->with([
            'usuario:id,name,email',
            'contato:id_contato,telefone_contato,whatsapp_contato',
            'endereco',
        ])
        ->get()
        ->map(function ($instituicao) {
            // Verificar se o campo funcionamento_instituicao não está vazio ou nulo
            $funcionamento = $instituicao->funcionamento_instituicao ? json_decode($instituicao->funcionamento_instituicao, true) : [];

            $horariosAgrupados = [];
            if (!empty($funcionamento)) {
                foreach ($funcionamento as $dia => $horario) {
                    if ($horario['funciona']) {
                        $horarioTexto = "{$horario['abertura']} até {$horario['fechamento']}";
                        $horariosAgrupados[$horarioTexto][] = $dia;
                    }
                }
            }

            $diasFuncionamento = '';
            foreach ($horariosAgrupados as $horario => $dias) {
                $diasFuncionamento .= implode(', ', $dias) . ": $horario\n";
            }

            return [
                'id_instituicao' => $instituicao->id_instituicao ?? null,
                'descricao' => $instituicao->descricao_instituicao ?? null,
                'nome' => $instituicao->usuario->name ?? 'Nome não disponível',
                'email' => $instituicao->usuario->email ?? 'Email não disponível',
                'telefone' => $instituicao->contato->telefone_contato ?? 'Telefone não disponível',
                'whatsapp' => $instituicao->contato->whatsapp_contato ?? 'Whatsapp não disponível',
                'facebook' => $instituicao->contato->facebook_contato ?? 'Facebook não disponível',
                'instagram' => $instituicao->contato->instagram_contato ?? 'Instagram não disponível',
                'site' => $instituicao->contato->site_contato ?? 'Site não disponível',
                'endereco' => $instituicao->endereco ?
                    "{$instituicao->endereco->cidade_endereco}, {$instituicao->endereco->bairro_endereco}, {$instituicao->endereco->logradouro_endereco}, {$instituicao->endereco->numero_endereco}, {$instituicao->endereco->complemento_endereco}, {$instituicao->endereco->cep_endereco}, {$instituicao->endereco->estado_endereco}" :
                    'Mesmo endereço da instituição',
                'funcionamento_instituicao' => [
                    'horario' => $diasFuncionamento,
                ],
            ];
        });

    return response()->json([
        'data' => $instituicoes,
        'status' => 'success',
        'message' => 'Listagem solicitada com sucesso!',
    ], 200);

    }
}
