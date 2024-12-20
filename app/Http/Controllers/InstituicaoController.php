<?php

namespace App\Http\Controllers;

use App\Models\CategoriaDoacao;
use App\Models\Contato;
use App\Models\Doacao;
use App\Models\Endereco;
use App\Models\Habilidade;
use App\Models\Instituicao;
use App\Models\User;
use App\Models\Voluntario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

        if (!$this->validarCnpj($request->input('cnpj'))) {
            return redirect()->back()
                ->withErrors(['cnpj' => 'O CNPJ informado é inválido.'])
                ->withInput();
        }

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
        $instituicao = Instituicao::create([
            'id_usuario' => $idUsuario,  // Associando o ID do usuário criado
            'id_contato'=> $idContato,  // Associando o ID do contato criado
            'id_endereco'=> $idEndereco,  // Associando o ID do endereço criado
            'cnpj_instituicao' => $request->input('cnpj'),
        ]);

        // Criar o 'Doar Agora' da instituição, logo após o cadastro
        Doacao::create([
            'id_instituicao' => $instituicao->id_instituicao,
            'observacao_doacao' => null,
            'data_hora_limite_doacao' => null,
            'nome_doacao' => null,
            'coleta_doacao' => null,
            'card_doacao' => '0'
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
                    'nome' => 'required|string|max:80',
                    'image_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'descricao' => 'required|string|max:200',
                ],$messages);

                if($request->imagem_perfil)
                $usuario->updateProfilePhoto($request->file('imagem_perfil'));

                $usuario->update([
                    'name' => $request->nome
                ]);

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
                        'new_password' => 'required|min:6',
                        'confirm_password' => 'required|same:new_password',
                    ], ['confirm_password.same'=> 'As senhas não coincidem!']);

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
            'contato:id_contato,telefone_contato,whatsapp_contato,instagram_contato,facebook_contato,site_contato',
            'endereco',
        ])
        ->get()
        ->map(function ($instituicao) {
            // Lista de dias da semana em ordem
            $diasSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];

            // Verificar se o campo funcionamento_instituicao não está vazio ou nulo
            $funcionamento = $instituicao->funcionamento_instituicao ? json_decode($instituicao->funcionamento_instituicao, true) : [];

            $horariosAgrupados = [];
            if (!empty($funcionamento)) {
                // Reorganizar o funcionamento dos dias de acordo com a ordem correta
                foreach ($diasSemana as $dia) {
                    if (isset($funcionamento[$dia]) && $funcionamento[$dia]['funciona']) {
                        $horarioTexto = "{$funcionamento[$dia]['abertura']} até {$funcionamento[$dia]['fechamento']}";
                        $horariosAgrupados[$horarioTexto][] = $dia;
                    }
                }
            }

            // Gerar o texto final dos dias e horários de funcionamento
            $diasFuncionamento = '';
            foreach ($horariosAgrupados as $horario => $dias) {
                // Gerar o texto final para os dias ordenados
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
                    'horario' => trim($diasFuncionamento),
                ],
            ];
        });

        return response()->json([
            'data' => $instituicoes,
            'status' => 'success',
            'message' => 'Listagem solicitada com sucesso!',
        ], 200);
    }


    public function inscreveInstituicao(Request $request)
    {
        Carbon::setLocale('pt_BR');
        $user = Auth::user();
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();

        // Valida os dados recebidos
        $validator = Validator::make($request->all(), [
            'id_instituicao' => 'required|integer|exists:instituicao,id_instituicao',
            'id_habilidade' => 'required|integer|exists:habilidade,id_habilidade',
        ]);

        // Retorna erros de validação, se houver
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verifica se já existe uma solicitação para a mesma instituição
        $solicitacaoExistente = DB::table('instituicao_has_voluntario')
            ->where('id_instituicao', $request->id_instituicao)
            ->where('id_voluntario', $voluntario->id_voluntario)
            ->first();

        if ($solicitacaoExistente) {
            // Verifica a situação e a data da solicitação existente
            if (
                $solicitacaoExistente->situacao_solicitacao_voluntario == 0 || // Solicitação em espera
                now()->diffInDays($solicitacaoExistente->updated_at) < 30 // Menos de 30 dias
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Você já possui uma solicitação recente ou em andamento para esta instituição!',
                ], 422);
            }

            // Caso a situação não esteja em espera e já tenham passado 30 dias, atualiza o registro
            DB::table('instituicao_has_voluntario')
                ->where('id_instituicao', $request->id_instituicao)
                ->where('id_voluntario', $voluntario->id_voluntario)
                ->update([
                    'habilidade_voluntario' => Habilidade::where('id_habilidade', $request->id_habilidade)->value('descricao_habilidade'),
                    'situacao_solicitacao_voluntario' => 0, // Define como nova solicitação em espera
                    'updated_at' => Carbon::now(),
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Inscrição registrada com sucesso!',
            ], 200);
        }

        // Caso não exista solicitação, cria um novo registro
        $habilidade = Habilidade::where('id_habilidade', $request->id_habilidade)->first();

        DB::table('instituicao_has_voluntario')->insert([
            'id_instituicao' => $request->id_instituicao,
            'id_voluntario' => $voluntario->id_voluntario,
            'habilidade_voluntario' => $habilidade->descricao_habilidade,
            'situacao_solicitacao_voluntario' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Inscrição registrada com sucesso!',
        ], 200);
    }

    public function doarAgora(Request $request)
    {
        Carbon::setLocale('pt_BR');
        $user = Auth::user();
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();

        // Valida os dados recebidos
        $validator = Validator::make($request->all(), [
            'id_instituicao' => 'required|integer|exists:instituicao,id_instituicao',
            'categoria' => 'required|string|max:45',
            'quantidade_doacao' => 'required|int|min:1',
            'data_hora_coleta' => 'nullable|date|after:now',
        ]);

        // Retorna erros de validação, se houver
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $doacaoInstituicao = DB::table('doacao as d')
                ->where('d.card_doacao', 0)
                ->where('d.id_instituicao', $request->id_instituicao)
                ->first();;

        try {
            // Verifica se o voluntário já fez uma doação para o mesmo id_doacao
            $doacaoExistente = DB::table('voluntario_has_doacao as vd')
                ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
                ->where('vd.id_voluntario', $voluntario->id_voluntario)
                ->where('vd.id_doacao', $doacaoInstituicao->id_doacao)
                ->where('d.card_doacao', 0)
                ->where('d.id_instituicao', $request->id_instituicao)
                ->first();

            if ($doacaoExistente) {
                $doacaoEmEspera = DB::table('voluntario_has_doacao as vd')
                ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
                ->where('vd.id_voluntario', $voluntario->id_voluntario)
                ->where('vd.id_doacao', $doacaoInstituicao->id_doacao)
                ->where('vd.situacao_solicitacao_doacao', '=', '0')
                ->where('d.card_doacao', 0)
                ->where('d.id_instituicao', $request->id_instituicao)
                ->first();

                if (!$doacaoEmEspera) {
                    // Atualiza os campos se o registro já existir
                    DB::table('voluntario_has_doacao')
                    ->where('id_voluntario', $voluntario->id_voluntario)
                    ->where('id_doacao', $doacaoInstituicao->id_doacao)
                    ->update([
                        'situacao_solicitacao_doacao' => 0,
                        'data_hora_coleta' => $request->data_hora_coleta,
                        'categoria_doacao' => $request->categoria,
                        'quantidade_doacao' => $request->quantidade_doacao,
                        'updated_at' => Carbon::now(),
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Doação registrada com sucesso!',
                    ], 200);
                }
                else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Já existe uma doação em análise para essa instituição!',
                    ], 422);
                }
            } else {
                // Insere os dados se o registro não existir
                DB::table('voluntario_has_doacao')->insert([
                    'id_voluntario' => $voluntario->id_voluntario,
                    'id_doacao' => $doacaoInstituicao->id_doacao,
                    'situacao_solicitacao_doacao' => 0,
                    'data_hora_coleta' => $request->data_hora_coleta,
                    'categoria_doacao' => $request->categoria,
                    'quantidade_doacao' => $request->quantidade_doacao,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Doação registrada com sucesso!',
                ], 200);
            }
        } catch (\Exception $e) {
            // Retorna erro caso algo falhe no banco de dados
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao registrar ou atualizar a doação.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    private function validarCnpj($cnpj)
    {
        // Remove caracteres não numéricos
        $cnpj = preg_replace('/\D/', '', $cnpj);

        // Verifica se possui 14 dígitos
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Elimina CNPJs inválidos conhecidos
        if (in_array($cnpj, [
            '00000000000000', '11111111111111', '22222222222222',
            '33333333333333', '44444444444444', '55555555555555',
            '66666666666666', '77777777777777', '88888888888888',
            '99999999999999'
        ])) {
            return false;
        }

        // Validação dos dígitos verificadores
        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            $c = 0;
            for ($p = $t - 7, $i = 0; $i < $t; $i++, $p--) {
                $d += $cnpj[$i] * ($p > 1 ? $p : 9);
                $c++;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) {
                return false;
            }
        }

        return true;
    }

}
