<?php

namespace App\Http\Controllers;

use App\Models\Habilidade;
use App\Models\Instituicao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
