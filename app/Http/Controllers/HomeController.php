<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        Carbon::setLocale('pt_BR');
        $user = Auth::user();
        $instituicao = Instituicao::where('id_usuario', $user->id)->first();

        $card1 = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->whereBetween('vd.updated_at', [Carbon::today(), Carbon::tomorrow()])
            ->select(DB::raw('count(*) as total'))
            ->union(
                DB::table('voluntario_has_evento as ve')
                    ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
                    ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
                    ->where('e.id_instituicao', $instituicao->id_instituicao)
                    ->whereBetween('ve.updated_at', [Carbon::today(), Carbon::tomorrow()])
                    ->select(DB::raw('count(*) as total'))
            )
            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->whereBetween('iv.updated_at', [Carbon::today(), Carbon::tomorrow()])
                    ->select(DB::raw('count(*) as total'))
            )
            ->sum('total');
        $totalTudo = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('vd.updated_at', Carbon::now()->month)
            ->select(DB::raw('count(*) as total'))
            ->union(
                DB::table('voluntario_has_evento as ve')
                    ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
                    ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
                    ->where('e.id_instituicao', $instituicao->id_instituicao)
                    ->whereMonth('ve.updated_at', Carbon::now()->month)
                    ->select(DB::raw('count(*) as total'))
            )
            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->whereMonth('iv.updated_at', Carbon::now()->month)
                    ->select(DB::raw('count(*) as total'))
            )
            ->sum('total');
        $card11 = ($totalTudo > 0) ? round(($card1 / $totalTudo) * 100, 2) : 0;

        $card2 = DB::table('voluntario_has_doacao')
        ->join('doacao', 'voluntario_has_doacao.id_doacao', '=', 'doacao.id_doacao')
        ->where('doacao.id_instituicao', $instituicao->id_instituicao)
        ->where('voluntario_has_doacao.situacao_solicitacao_doacao', '=', 1) // Somente doações confirmadas
        ->whereMonth('voluntario_has_doacao.updated_at', Carbon::now()->month)
        ->select(DB::raw('DATE(voluntario_has_doacao.updated_at) as date'), DB::raw('COUNT(*) as total_doacoes'))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
        // Extrair rótulos e quantidades para o gráfico
        $labelsDoacoesMes = $card2->pluck('date')->toArray();
        $totalDoacoesCard2 = $card2->pluck('total_doacoes')->toArray();
        // Somar todas as doações do mês inteiro
        $totalDoacoesMes = array_sum($totalDoacoesCard2);

        $card3 = DB::table('voluntario_has_evento as ve')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('ve.updated_at', Carbon::now()->month)
            ->whereYear('ve.updated_at', Carbon::now()->year)
            ->select(DB::raw('DATE(ve.updated_at) as date'), DB::raw('COUNT(*) as total_voluntarios'))
            ->groupBy('date')

            ->union(
                DB::table('instituicao_has_voluntario as iv')
                    ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                    ->where('iv.id_instituicao', $instituicao->id_instituicao)
                    ->where('iv.situacao_solicitacao_voluntario', '=', 1)
                    ->whereMonth('iv.updated_at', Carbon::now()->month)
                    ->whereYear('iv.updated_at', Carbon::now()->year)
                    ->select(DB::raw('DATE(iv.updated_at) as date'), DB::raw('COUNT(*) as total_voluntarios'))
                    ->groupBy('date')
            )
            ->orderBy('date', 'asc')
            ->get();
        // Agrupar os dados por data e somar os totais
        $groupedData = $card3->groupBy('date')->map(function ($items) {
            return $items->sum('total_voluntarios');
        });
        // Extrair rótulos e quantidades para o gráfico
        $labelsVoluntariosMes = $groupedData->keys()->toArray();
        $totalVoluntariosCard3 = $groupedData->values()->toArray();
        // Somar todas as doações do mês inteiro
        $totalVoluntariosMes = array_sum($totalVoluntariosCard3);

        $card4 = DB::table('voluntario_has_doacao as vd')
        ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
        ->where('d.id_instituicao', $instituicao->id_instituicao)
        ->where('d.card_doacao', 0)
        ->whereMonth('vd.created_at', Carbon::now()->month)
        ->whereYear('vd.created_at', Carbon::now()->year)
        ->select(DB::raw('DATE(vd.created_at) as date'), DB::raw('COUNT(*) as total_solicitacoes'))
        ->groupBy('date')
        ->unionAll( // Substituir UNION por UNION ALL
            DB::table('instituicao_has_voluntario as iv')
                ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
                ->where('iv.id_instituicao', $instituicao->id_instituicao)
                ->whereMonth('iv.created_at', Carbon::now()->month)
                ->whereYear('iv.created_at', Carbon::now()->year)
                ->select(DB::raw('DATE(iv.created_at) as date'), DB::raw('COUNT(*) as total_solicitacoes'))
                ->groupBy('date')
        )
        ->orderBy('date', 'asc')
        ->get();
        // Agrupar os dados por data e somar os totais
        $groupedData = $card4
        ->groupBy('date') // Agrupa os resultados pela data
        ->map(function ($items) {
            return $items->sum('total_solicitacoes'); // Soma os valores para cada data
        });
        // Extrair rótulos e quantidades para o gráfico
        $labelsSolicitacoesMes = $groupedData->keys()->toArray(); // Datas como rótulos
        $totalSolicitacoesCard4 = $groupedData->values()->toArray(); // Totais por dia
        // Somar todas as solicitações do mês inteiro
        $totalSolicitacoesMes = array_sum($totalSolicitacoesCard4);

        //Gráfico de solicitações
        $tipoSolicitaDoacao = DB::table('voluntario_has_doacao as vd')
            ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
            ->where('d.card_doacao', 0)
            ->where('d.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('vd.created_at', Carbon::now()->month)
            ->count();
        $tipoSolicitaVoluntario =  DB::table('instituicao_has_voluntario as iv')
            ->join('voluntario as v', 'iv.id_voluntario', '=', 'v.id_voluntario')
            ->where('iv.id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('iv.created_at', Carbon::now()->month)
            ->count();

        //Gráfico de doação
        $arrecadacoes = DB::table('voluntario_has_doacao as vd')
        ->join('doacao', 'vd.id_doacao', '=', 'doacao.id_doacao')
        ->where('vd.situacao_solicitacao_doacao', '=', 1)
        ->where('doacao.id_instituicao', $instituicao->id_instituicao)
        ->whereYear('vd.updated_at', Carbon::now()->year) // Filtra pelo ano atual
        ->select(
            DB::raw('MONTH(vd.updated_at) as month'),
            DB::raw('SUM(CASE WHEN doacao.card_doacao = 1 THEN 1 ELSE 0 END) as total_card'),
            DB::raw('SUM(CASE WHEN doacao.card_doacao = 0 THEN 1 ELSE 0 END) as total_doar_agora')
        )
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();
        // Extrair rótulos e dados para o gráfico
        $labels = [];
        $totalCard = [];
        $totalDoarAgora = [];
        foreach ($arrecadacoes as $arrecadacao) {
            $labels[] = Carbon::create()->month($arrecadacao->month)->translatedFormat('M'); // Mês abreviado
            $totalCard[] = $arrecadacao->total_card; // Total de doações via card
            $totalDoarAgora[] = $arrecadacao->total_doar_agora; // Total de doações via "doar agora"
        }

        //Gráfico de comparação
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();
        $datas = [];
        for ($data = $inicioMes; $data->lte($fimMes); $data->addDay()) {
            $datas[] = $data->format('Y-m-d');
        }
        // Consultar as doações do mês
        $doacoesMes = DB::table('voluntario_has_doacao')
            ->join('doacao', 'voluntario_has_doacao.id_doacao', '=', 'doacao.id_doacao')
            ->where('doacao.id_instituicao', $instituicao->id_instituicao)
            ->where('voluntario_has_doacao.situacao_solicitacao_doacao', 1)
            ->whereMonth('voluntario_has_doacao.updated_at', Carbon::now()->month)
            ->select(DB::raw('DATE(voluntario_has_doacao.updated_at) as date'), DB::raw('COUNT(*) as total_doacoes'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        // Consultar os voluntários do mês
        $voluntariosMes = DB::table('voluntario_has_evento as ve')
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
        // Mapear os dados de doações e voluntários para incluir os dias sem valores
        $dadosAgrupados = array_map(function ($data) use ($doacoesMes, $voluntariosMes) {
            return [
                'date' => $data,
                'total_doacoes' => $doacoesMes->firstWhere('date', $data)->total_doacoes ?? 0,
                'total_voluntarios' => $voluntariosMes->firstWhere('date', $data)->total_voluntarios ?? 0,
            ];
        }, $datas);
        // Separar os dados para o gráfico
        $labelsGrafico3 = array_map(function ($item) {
            return date('j M', strtotime($item['date'])); // Formato: dia (número) e mês (abreviado)
        }, $dadosAgrupados);
        $totalDoacoesPorDiaMes = array_column($dadosAgrupados, 'total_doacoes');
        $totalVoluntariosPorDiaMes = array_column($dadosAgrupados, 'total_voluntarios');

        return view('home', compact(
            'card1',
            'card11',
            'labelsDoacoesMes',
            'totalDoacoesCard2',
            'totalDoacoesMes',
            'labelsVoluntariosMes',
            'totalVoluntariosCard3',
            'totalVoluntariosMes',
            'labelsSolicitacoesMes',
            'totalSolicitacoesCard4',
            'totalSolicitacoesMes',
            'tipoSolicitaDoacao',
            'tipoSolicitaVoluntario',
            'labels',
            'totalCard',
            'totalDoarAgora',
            'totalDoacoesPorDiaMes',
            'totalVoluntariosPorDiaMes',
            'labelsGrafico3'
        ));
    }
}
