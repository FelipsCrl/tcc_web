<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\Voluntario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitacaoController extends Controller
{

    public function index(Request $request)
    {
        Carbon::setLocale('pt_BR');
        $user = Auth::user();
        $instituicao = Instituicao::where('id_usuario', $user->id)->first();

        $card1 = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->where('d.card_doacao', 0)
            ->whereBetween('vd.updated_at', [Carbon::today(), Carbon::tomorrow()])
            ->select(DB::raw('count(*) as total'))

            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->whereBetween('iv.updated_at', [Carbon::today(), Carbon::tomorrow()])
                    ->select(DB::raw('count(*) as total'))
            )
            ->sum('total');
        $totalSolicita = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->where('d.card_doacao', 0)
            ->whereMonth('vd.updated_at', Carbon::now()->month)
            ->select(DB::raw('count(*) as total'))

            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->whereMonth('iv.updated_at', Carbon::now()->month)
                    ->select(DB::raw('count(*) as total'))
            )
            ->sum('total');
        $card11 = ($totalSolicita > 0) ? round(($card1 / $totalSolicita) * 100, 2) : 0;

        // Card2
        $card2 = DB::table('voluntario_has_doacao as vd')
        ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
        ->where('d.id_instituicao', $instituicao->id_instituicao)
        ->where('d.card_doacao', 0)
        ->whereMonth('vd.updated_at', Carbon::now()->month)
        ->whereYear('vd.updated_at', Carbon::now()->year)
        ->select(DB::raw('DATE(vd.updated_at) as date'), DB::raw('COUNT(*) as total_solicitacoes'))
        ->groupBy('date')
        ->unionAll( // Substituir UNION por UNION ALL
            DB::table('instituicao_has_voluntario as iv')
                ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                ->where('iv.id_instituicao', $instituicao->id_instituicao)
                ->whereMonth('iv.updated_at', Carbon::now()->month)
                ->whereYear('iv.updated_at', Carbon::now()->year)
                ->select(DB::raw('DATE(iv.updated_at) as date'), DB::raw('COUNT(*) as total_solicitacoes'))
                ->groupBy('date')
        )
        ->orderBy('date', 'asc')
        ->get();
        // Agrupar os dados por data e somar os totais
        $groupedData = $card2
        ->groupBy('date') // Agrupa os resultados pela data
        ->map(function ($items) {
            return $items->sum('total_solicitacoes'); // Soma os valores para cada data
        });
        // Extrair rótulos e quantidades para o gráfico
        $labelsSolicitacoesMes = $groupedData->keys()->toArray(); // Datas como rótulos
        $totalSolicitacoesPorDiaMes = $groupedData->values()->toArray(); // Totais por dia
        // Somar todas as solicitações do mês inteiro
        $totalSolicitacoesMes = array_sum($totalSolicitacoesPorDiaMes);

        // Card3
        $hoje = Carbon::today()->addDay();
        $inicioMesAtual = $hoje->copy()->firstOfMonth();
        $inicioMesAnterior = $hoje->copy()->subMonth()->firstOfMonth();
        $fimMesAnterior = $hoje->copy()->subMonth()->endOfMonth();
        // Solicitações em espera para o mês atual
        $solicitacoesMesAtual = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->where('vd.situacao_solicitacao_doacao', '=', 0) // Em espera
            ->where('d.card_doacao', 0)
            ->whereBetween('vd.updated_at', [$inicioMesAtual, $hoje])
            ->select(DB::raw('DATE(vd.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
            ->groupBy('dia')
            ->unionAll( // Substituído UNION por UNION ALL
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 0) // Em espera
                    ->whereBetween('iv.updated_at', [$inicioMesAtual, $hoje])
                    ->select(DB::raw('DATE(iv.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
                    ->groupBy('dia')
            )
            ->get();
        // Agrupar e somar quantidades por dia para o mês atual
        $dadosMesAtualAgrupados = $solicitacoesMesAtual
            ->groupBy('dia')
            ->map(function ($group) {
                return $group->sum('quantidade');
            })
            ->toArray();
        // Solicitações em espera para o mês anterior
        $solicitacoesMesAnterior = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->where('vd.situacao_solicitacao_doacao', '=', 0) // Em espera
            ->where('d.card_doacao', 0)
            ->whereBetween('vd.updated_at', [$inicioMesAnterior, $fimMesAnterior])
            ->select(DB::raw('DATE(vd.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
            ->groupBy('dia')
            ->unionAll( // Substituído UNION por UNION ALL
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 0) // Em espera
                    ->whereBetween('iv.updated_at', [$inicioMesAnterior, $fimMesAnterior])
                    ->select(DB::raw('DATE(iv.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
                    ->groupBy('dia')
            )
            ->get();
        // Agrupar e somar quantidades por dia para o mês anterior
        $dadosMesAnteriorAgrupados = $solicitacoesMesAnterior
            ->groupBy('dia')
            ->map(function ($group) {
                return $group->sum('quantidade');
            })
            ->toArray();
        // Criar arrays de datas e dados para o gráfico
        $labelsSolicitacoes = [];
        $dadosAtualSolicitacoes = [];
        $dadosAnteriorSolicitacoes = [];
        for ($dia = 1; $dia <= $hoje->daysInMonth; $dia++) {
            $dataAtual = $inicioMesAtual->copy()->day($dia)->toDateString();
            $dataAnterior = $inicioMesAnterior->copy()->day($dia)->toDateString();

            $labelsSolicitacoes[] = $dataAtual;
            $dadosAtualSolicitacoes[] = $dadosMesAtualAgrupados[$dataAtual] ?? 0;
            $dadosAnteriorSolicitacoes[] = $dadosMesAnteriorAgrupados[$dataAnterior] ?? 0;
        }
        // Total de solicitações em espera para o mês atual
        $totalSolicitacoes = array_sum($dadosAtualSolicitacoes);

        $card4 = DB::table('voluntario_has_doacao as vd')
        ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
        ->where('d.id_instituicao', $instituicao->id_instituicao)
        ->where('d.card_doacao', 0)
        ->where('vd.situacao_solicitacao_doacao', '=', -1) // Recusadas
        ->whereMonth('vd.updated_at', Carbon::now()->month)
        ->select(DB::raw('COUNT(*) as quantidade'))
        ->union(
            DB::table('instituicao_has_voluntario as iv')
                ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                ->where('iv.id_instituicao', $instituicao->id_instituicao)
                ->where('iv.situacao_solicitacao_voluntario', '=', -1) // Recusadas
                ->whereMonth('iv.updated_at', Carbon::now()->month)
                ->select(DB::raw('COUNT(*) as quantidade'))
        )
        ->get()
        ->sum('quantidade');

        // Obter as solicitações de voluntários e doações, agrupadas por mês
        $solicitacoes = DB::table('instituicao_has_voluntario as iv')
        ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
        ->where('iv.id_instituicao', $instituicao->id_instituicao)
        ->whereMonth('iv.updated_at', Carbon::now()->month)
        ->select(
            DB::raw('MONTH(iv.updated_at) as month'),
            DB::raw('COUNT(iv.id_voluntario) as total_solicitacoes'),
            DB::raw('"Voluntário" as tipo')
        )
        ->groupBy('month')
        ->union(
            DB::table('voluntario_has_doacao as vd')
                ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
                ->where('d.id_instituicao', $instituicao->id_instituicao)
                ->where('d.card_doacao', 0)
                ->whereMonth('vd.updated_at', Carbon::now()->month)
                ->select(
                    DB::raw('MONTH(vd.updated_at) as month'),
                    DB::raw('COUNT(*) as total_solicitacoes'),
                    DB::raw('"Doação" as tipo')
                )
                ->groupBy('month')
        )
        ->orderBy('month', 'asc')
        ->get();
        // Organizar os dados para as séries do gráfico
        $voluntarioData = [];
        $doacaoData = [];
        $labels = [];

        foreach ($solicitacoes as $solicitacao) {
            $month = Carbon::create()->month($solicitacao->month)->translatedFormat('M'); // Mês abreviado
            $labels[] = $month;

            if ($solicitacao->tipo == 'Voluntário') {
                $voluntarioData[] = $solicitacao->total_solicitacoes;
            } elseif ($solicitacao->tipo == 'Doação') {
                $doacaoData[] = $solicitacao->total_solicitacoes;
            }
        }

        $tipoSolicitaDoacao = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.card_doacao', 0)
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('vd.updated_at', Carbon::now()->month)
            ->count();
        $tipoSolicitaVoluntario =  DB::table('instituicao_has_voluntario as iv')
            ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
            ->where('iv.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('iv.updated_at', Carbon::now()->month)
            ->count();

        $searchTermVoluntario = $request->input('searchVoluntario', '');
        $limitVoluntario = $request->input('limitVoluntario');
        $queryVoluntario = DB::table('instituicao_has_voluntario as iv')
            ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('endereco as e', 'v.id_endereco', '=', 'e.id_endereco')
            ->join('contato as c', 'v.id_contato', '=', 'c.id_contato')
            ->where('iv.id_instituicao', $instituicao->id_instituicao)
            ->where('iv.situacao_solicitacao_voluntario', '=', 0) // Em espera
            ->where(function ($query) use ($searchTermVoluntario) {
                $query->where('iv.id_voluntario', 'like', "%$searchTermVoluntario%")
                    ->orWhere('u.name', 'like', "%$searchTermVoluntario%");
            })
            ->select(
                'u.*',
                'iv.*',
                'iv.updated_at as updated_at_iv',
                'c.telefone_contato',
                'e.*'
            );
            // Definir a lógica de limite e paginação
            if ($limitVoluntario) {
                // Se 'limit' está definido, use get() e limite a quantidade de registros
                $solicitacaoVoluntario = $queryVoluntario->take($limitVoluntario)->get();
            } else {
                // Caso contrário, usa paginação normalmente com 5 itens por página
                $solicitacaoVoluntario = $queryVoluntario->paginate(5, ['*'], 'solicitacoesVoluntarioPage');
            }

        $searchTermDoacao = $request->input('searchDoacao', '');
        $limitDoacao = $request->input('limitDoacao');
        $queryDoacao =  DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->join('voluntario as v', 'vd.id_voluntario', '=', 'v.id_voluntario')
            ->join('endereco as e', 'v.id_endereco', '=', 'e.id_endereco')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('contato as c', 'v.id_contato', '=', 'c.id_contato')
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->where('vd.situacao_solicitacao_doacao', '=', 0) // Em espera
            ->where('d.card_doacao', 0)
            ->whereMonth('vd.updated_at', Carbon::now()->month)
            ->whereYear('vd.updated_at', Carbon::now()->year)
            ->where(function ($query) use ($searchTermDoacao) {
                $query->where('vd.id_doacao', 'like', "%$searchTermDoacao%")
                    ->orWhere('vd.categoria_doacao', 'like', "%$searchTermDoacao%")
                    ->orWhere('u.name', 'like', "%$searchTermDoacao%");
            })
            ->select(
                'u.*',
                'vd.*',
                'vd.updated_at as updated_at_vd',
                'c.telefone_contato',
                'e.*'
            );
            // Definir a lógica de limite e paginação
            if ($limitDoacao) {
                // Se 'limit' está definido, use get() e limite a quantidade de registros
                $solicitacaoDoacao = $queryDoacao->take($limitDoacao)->get();
            } else {
                // Caso contrário, usa paginação normalmente com 5 itens por página
                $solicitacaoDoacao = $queryDoacao->paginate(5, ['*'], 'solicitacoesDoacaoPage');
            }

        return view('solicitacao',compact(
            'card1',
            'card11',
            'labelsSolicitacoesMes',
            'totalSolicitacoesPorDiaMes',
            'totalSolicitacoesMes',
            'labelsSolicitacoes',
            'totalSolicitacoes',
            'dadosAtualSolicitacoes',
            'dadosAnteriorSolicitacoes',
            'card4',
            'tipoSolicitaDoacao',
            'tipoSolicitaVoluntario',
            'labels',
            'voluntarioData',
            'doacaoData',
            'solicitacaoVoluntario',
            'solicitacaoDoacao'
        ))
        ->with('i', (request()->input('solicitacoesDoacaoPage', 1) - 1) * 5)
        ->with('j', (request()->input('solicitacoesVoluntarioPage', 1) - 1) * 5);
    }

    public function update(Request $request)
    {
        $dataIds = json_decode($request->input('data_ids'), true);

        if (isset($dataIds['Instituicao']) && isset($dataIds['Voluntario'])) {
            $instituicaoId = $dataIds['Instituicao'];
            $voluntarioId = $dataIds['Voluntario'];
            $acaoSolicitacao = $dataIds['Acao'];

            DB::table('instituicao_has_voluntario')
            ->where('id_voluntario', $voluntarioId)
            ->where('id_instituicao', $instituicaoId)
            ->update(['situacao_solicitacao_voluntario' => $acaoSolicitacao]);

        } else if(isset($dataIds['Doacao']) && isset($dataIds['Voluntario'])) {
            $doacaoId = $dataIds['Doacao'];
            $voluntarioId = $dataIds['Voluntario'];
            $acaoSolicitacao = $dataIds['Acao'];

            DB::table('voluntario_has_doacao')
            ->where('id_voluntario', $voluntarioId)
            ->where('id_doacao', $doacaoId)
            ->update(['situacao_solicitacao_doacao' => $acaoSolicitacao]);
        }
        else{
            return redirect()->route('solicitacao.index')->withErrors('Não foi possível realizar essa ação!');
        }

        return redirect()->route('solicitacao.index')->with('success', 'Solicitação alterada com sucesso!');
    }

    public function listagemSolicitacao()
    {
        $user = Auth::user();

        // Obtém o voluntário autenticado
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();

        // Solicitações de doações
        $doacoes = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->join('instituicao as i', 'd.id_instituicao', '=', 'i.id_instituicao')
            ->join('users as u_voluntario', 'i.id_usuario', '=', 'u_voluntario.id') // Join para o responsável pela instituição
            ->join('users as u_instituicao', 'i.id_usuario', '=', 'u_instituicao.id') // Join para a instituição
            ->where('d.card_doacao', 0)
            ->where('vd.id_voluntario', $voluntario->id_voluntario)
            ->select(
                'u_voluntario.name as nome_voluntario', // Nome do voluntário
                'u_instituicao.name as nome_instituicao', // Nome da instituição
                'vd.categoria_doacao as categoria',
                'vd.created_at as data',
                'vd.situacao_solicitacao_doacao as situacao'
            )
            ->get()
            ->map(function ($doacao) {
                // Formata a data para dd/mm/aaaa
                $doacao->data = Carbon::parse($doacao->data)->format('d/m/Y');
                return $doacao;
            });

        // Solicitações de voluntariado
        $voluntariados = DB::table('instituicao_has_voluntario as iv')
            ->join('instituicao as i', 'iv.id_instituicao', '=', 'i.id_instituicao')
            ->join('users as u_voluntario', 'iv.id_voluntario', '=', 'u_voluntario.id') // Join para o voluntário
            ->join('users as u_instituicao', 'i.id_usuario', '=', 'u_instituicao.id') // Join para o responsável pela instituição
            ->where('iv.id_voluntario', $voluntario->id_voluntario)
            ->select(
                'u_voluntario.name as nome_voluntario', // Nome do voluntário
                'u_instituicao.name as nome_instituicao', // Nome da instituição
                'iv.habilidade_voluntario as habilidade',
                'iv.created_at as data',
                'iv.situacao_solicitacao_voluntario as situacao'
            )
            ->get()
            ->map(function ($voluntariado) {
                // Formata a data para dd/mm/aaaa
                $voluntariado->data = Carbon::parse($voluntariado->data)->format('d/m/Y');
                return $voluntariado;
            });

        // Combina as duas coleções
        $solicitacoes = $doacoes->merge($voluntariados);

        // Retorna os dados no formato JSON
        return response()->json([
            'data' => $solicitacoes,
            'status' => 'success',
            'message' => 'Listagem solicitada com sucesso!',
        ], 200);
    }



}
