<?php

namespace App\Http\Controllers;

use App\Models\Contato;
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

class VoluntarioController extends Controller
{
    public function index()
    {
        Carbon::setLocale('pt_BR');
        $user = Auth::user();
        $instituicao = Instituicao::where('id_usuario', $user->id)->first();

        $card1 = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereBetween('ve.updated_at', [Carbon::today(), Carbon::tomorrow()])
            ->select(DB::raw('count(*) as total'))

            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 1)
                    ->whereBetween('iv.updated_at', [Carbon::today(), Carbon::tomorrow()])
                    ->select(DB::raw('count(*) as total'))
            )
            ->sum('total');
        $totalVoluntarios = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('ve.updated_at', Carbon::now()->month)
            ->select(DB::raw('count(*) as total'))

            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 1)
                    ->whereMonth('iv.updated_at', Carbon::now()->month)
                    ->select(DB::raw('count(*) as total'))
            )
            ->sum('total');
        $card11 = ($totalVoluntarios > 0) ? round(($card1 / $totalVoluntarios) * 100, 2) : 0;

        //Card2
        $card2 = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('ve.updated_at', Carbon::now()->month)
            ->select(DB::raw('DATE(ve.updated_at) as date'), DB::raw('COUNT(*) as total_voluntarios'))
            ->groupBy('date')

            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 1)
                    ->whereMonth('iv.updated_at', Carbon::now()->month)
                    ->select(DB::raw('DATE(iv.updated_at) as date'), DB::raw('COUNT(*) as total_voluntarios'))
                    ->groupBy('date')
            )
            ->orderBy('date', 'asc')
            ->get();
        // Agrupar os dados por data e somar os totais
        $groupedData = $card2->groupBy('date')->map(function ($items) {
            return $items->sum('total_voluntarios');
        });
        // Extrair rótulos e quantidades para o gráfico
        $labelsVoluntariosMes = $groupedData->keys()->toArray();
        $totalVoluntariosPorDiaMes = $groupedData->values()->toArray();
        // Somar todas as doações do mês inteiro
        $totalVoluntariosMes = array_sum($totalVoluntariosPorDiaMes);

        // Card3
        $hoje = Carbon::today()->addDay();
        $inicioMesAtual = $hoje->copy()->firstOfMonth();
        $inicioMesAnterior = $hoje->copy()->subMonth()->firstOfMonth();
        $fimMesAnterior = $hoje->copy()->subMonth()->endOfMonth();
        // Solicitações em espera para o mês atual
        $VoluntariadosMesAtual = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereBetween('ve.updated_at', [$inicioMesAtual, $hoje])
            ->select(DB::raw('DATE(ve.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
            ->groupBy('dia')
            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 1)
                    ->whereBetween('iv.updated_at', [$inicioMesAtual, $hoje])
                    ->select(DB::raw('DATE(iv.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
                    ->groupBy('dia')
            )
            ->get();
        // Solicitações em espera para o mês anterior
        $VoluntariadosMesAnterior = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereBetween('ve.updated_at', [$inicioMesAnterior, $fimMesAnterior])
            ->select(DB::raw('DATE(ve.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
            ->groupBy('dia')
            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 1)
                    ->whereBetween('iv.updated_at', [$inicioMesAnterior, $fimMesAnterior])
                    ->select(DB::raw('DATE(iv.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
                    ->groupBy('dia')
            )
            ->get();
        // Formatar dados para o gráfico
        $dadosMesAtual = $VoluntariadosMesAtual->pluck('quantidade', 'dia')->toArray();
        $dadosMesAnterior = $VoluntariadosMesAnterior->pluck('quantidade', 'dia')->toArray();
        // Criar arrays de datas e dados para o gráfico
        $labelsVoluntariados = [];
        $dadosAtualVoluntariados = [];
        $dadosAnteriorVoluntariados = [];
        for ($dia = 1; $dia <= $hoje->daysInMonth; $dia++) {
            $dataAtual = $inicioMesAtual->copy()->day($dia)->toDateString();
            $dataAnterior = $inicioMesAnterior->copy()->day($dia)->toDateString();

            $labelsVoluntariados[] = $dataAtual;
            $dadosAtualVoluntariados[] = $dadosMesAtual[$dataAtual] ?? 0;
            $dadosAnteriorVoluntariados[] = $dadosMesAnterior[$dataAnterior] ?? 0;
        }
        // Total de solicitações em espera para o mês atual
        $totalVoluntariados = array_sum($dadosAtualVoluntariados);

        //Card4
        $totalVoluntariosHoje = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('ve.updated_at', Carbon::today())
            ->select(DB::raw('count(*) as total'))

            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 1)
                    ->whereMonth('iv.updated_at', Carbon::today())
                    ->select(DB::raw('count(*) as total'))
            )
            ->sum('total');

        //Gráfico de habilidades
        $habilidades = Habilidade::pluck('descricao_habilidade')->toArray();
        // Consultas para calcular o total de voluntários por habilidade em ambas as tabelas
        $dadosInstituicao = DB::table('instituicao_has_voluntario as iv')
            ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
            ->where('iv.id_instituicao', $instituicao->id_instituicao)
            ->where('iv.situacao_solicitacao_voluntario', '=', 1)
            ->whereYear('iv.updated_at', Carbon::now()->year)
            ->select('iv.habilidade_voluntario', DB::raw('COUNT(*) as total'))
            ->groupBy('habilidade_voluntario')
            ->pluck('total', 'habilidade_voluntario');
        $dadosEvento = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereYear('ve.updated_at', Carbon::now()->year)
            ->select('ve.habilidade_voluntario', DB::raw('COUNT(*) as total'))
            ->groupBy('habilidade_voluntario')
            ->pluck('total', 'habilidade_voluntario');
        // Combinar os dados das duas tabelas para cada habilidade
        $totaisPorHabilidade = [];
        foreach ($habilidades as $habilidade) {
            $totaisPorHabilidade[$habilidade] = ($dadosInstituicao[$habilidade] ?? 0) + ($dadosEvento[$habilidade] ?? 0);
        }
        // Calcular o total geral de voluntários
        $totalVoluntarios = array_sum($totaisPorHabilidade);
        // Calcular a porcentagem para cada habilidade
        $dados = [];
        foreach ($totaisPorHabilidade as $habilidade => $total) {
            $dados[] = [
                'habilidade' => $habilidade,
                'percentual' => $totalVoluntarios > 0 ? round(($total / $totalVoluntarios) * 100, 2) : 0
            ];
        }


        return view('voluntario', compact(
            'card1',
            'card11',
            'labelsVoluntariosMes',
            'totalVoluntariosPorDiaMes',
            'totalVoluntariosMes',
            'labelsVoluntariados',
            'totalVoluntariados',
            'dadosAtualVoluntariados',
            'dadosAnteriorVoluntariados',
            'totalVoluntariosHoje',
            'dados',
        ));
    }

    public function listagemHabilidade()
    {
        $habilidades = Habilidade::select('id_habilidade', 'descricao_habilidade')->get();

        return response()->json([
            'data' => $habilidades,
            'status' => 'success',
            'message' => 'Listagem solicitada com sucesso!',
        ], 200);
    }

    public function dadosVoluntario()
    {
        $user = Auth::user();

        $voluntario = Voluntario::where('id_usuario', $user->id)
            ->select(
                'id_voluntario',
                'id_usuario',
                'id_contato',
                'id_endereco',
                'cpf_voluntario'
            )
            ->with([
                'usuario:id,name,email',
                'contato:id_contato,telefone_contato,whatsapp_contato',
                'endereco:id_endereco,cep_endereco,complemento_endereco,cidade_endereco,logradouro_endereco,estado_endereco,bairro_endereco,numero_endereco',
                'habilidades:id_habilidade,descricao_habilidade'
            ])
            ->get();

        return response()->json([
            'data' => $voluntario,
            'status' => 'success',
            'message' => 'Listagem solicitada com sucesso!',
        ], 200);
    }

    public function atualizarHabilidades(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'habilidades' => 'required|array',
            'habilidades.*' => 'integer|exists:habilidade,id_habilidade',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'variavel' => $request->habilidades
            ], 400);
        }

        // Obtém o usuário autenticado
        $user = Auth::user();

        // Encontre o voluntário associado ao usuário
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();

        if (!$voluntario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voluntário não encontrado.',
            ], 404);
        }

        // Atualize as habilidades com sync
        $voluntario->habilidades()->sync($request->habilidades);

        // Retorna a resposta com a lista de habilidades atualizadas
        return response()->json([
            'message' => 'Habilidades atualizadas com sucesso!',
            'habilidades' => $voluntario->habilidades()->pluck('habilidade.id_habilidade'),
        ], 200);
    }

    public function atualizarCredenciais(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),  // Verifica se o email é único, excluindo o do usuário autenticado
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // Obtém o usuário autenticado
        $user = Auth::user();
        $user = User::find($user->id);

        // Atualiza as credenciais do usuário
        $user->name = $request->nome;
        $user->email = $request->email;
        $user->save();

        // Retorna uma resposta de sucesso
        return response()->json([
            'status' => 'success',
            'message' => 'Credenciais atualizadas com sucesso!',
        ], 200);
    }

    public function atualizarContato(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'telefone' => 'required|string|min:15|max:15',
            'whatsapp' => 'nullable|string|min:15|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();
        $contato = Contato::find($voluntario->id_contato);

        $contato->telefone_contato = $request->telefone;
        if ($request->whatsapp) {
            $contato->whatsapp_contato = $request->whatsapp;
        }
        $contato->save();

        // Retorna uma resposta de sucesso
        return response()->json([
            'status' => 'success',
            'message' => 'Contato atualizado com sucesso!',
        ], 200);
    }

    public function atualizarEndereco(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'rua' => 'required|string|max:50',
            'cep' => 'required|string|max:9',
            'numero' => 'required|string',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'estado' => 'required|string|max:2',
            'complemento' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();
        $endereco = Endereco::find($voluntario->id_endereco);

        $endereco->update([
            'cep_endereco'=> $request->cep,
            'complemento_endereco'=> $request->complemento,
            'cidade_endereco'=> $request->cidade,
            'logradouro_endereco'=> $request->rua,
            'estado_endereco'=> $request->estado,
            'bairro_endereco'=> $request->bairro,
            'numero_endereco'=> $request->numero,
        ]);

        // Retorna uma resposta de sucesso
        return response()->json([
            'status' => 'success',
            'message' => 'Endereço atualizado com sucesso!',
        ], 200);
    }

    public function cadastro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:80',
            'cpf' => 'required|string|min:14|max:14|unique:voluntario,cpf_voluntario',
            'email' => 'required|email|max:80|unique:users,email',
            'senha' => 'required|min:6|max:40',
            'habilidades' => 'required|array',
            'habilidades.*' => 'integer|exists:habilidade,id_habilidade',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $usuario = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => bcrypt($request->senha), // Criptografando a senha
        ]);

        $idUsuario = $usuario->id;

        $voluntario = Voluntario::create([
            'id_usuario' => $idUsuario,  // Associando o ID do usuário criado
            'cpf_voluntario' => $request->cpf,
        ]);

        if (!$voluntario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao criar o voluntário.',
            ], 500);
        }

        $voluntario->habilidades()->sync($request->habilidades);

        return response()->json([
            'status' => 'success',
            'message' => 'Conta criada com sucesso!',
        ], 200);
    }

    public function atualizarSenha(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'senhaNova' => 'required|min:6',
            'senhaAntiga' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $user = User::find(Auth::user()->id);

        if (!Hash::check($request->senhaAntiga, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'A senha atual está incorreta!'
            ], 400);
        }

        // Atualizar a senha
        $user->password = Hash::make($request->senhaNova);
        $user->save();

        // Retorna uma resposta de sucesso
        return response()->json([
            'status' => 'success',
            'message' => 'Senha atualizada com sucesso!',
        ], 200);
    }

}
