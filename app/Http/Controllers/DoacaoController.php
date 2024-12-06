<?php

namespace App\Http\Controllers;

use App\Models\CategoriaDoacao;
use App\Models\Doacao;
use App\Models\Instituicao;
use App\Models\Voluntario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DoacaoController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('pt_BR');
        $user = Auth::user();
        $instituicao = Instituicao::where('id_usuario', $user->id)->first();

        $card1 = DB::table('doacao')
        ->where('id_instituicao', $instituicao->id_instituicao)
        ->where('data_hora_limite_doacao', '>=', Carbon::now())
        ->where('card_doacao', '=', 1)
        ->count();

        $totalDoacoes = DB::table('doacao')
            ->where('id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('card_doacao', '=', 1)
            ->count();

        $card11 = ($totalDoacoes > 0) ? round(($card1 / $totalDoacoes) * 100, 2) : 0;

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
        $totalDoacoesPorDiaMes = $card2->pluck('total_doacoes')->toArray();
        // Somar todas as doações do mês inteiro
        $totalDoacoesMes = array_sum($totalDoacoesPorDiaMes);

        $hoje = Carbon::today()->addDay();
        $inicioMesAtual = $hoje->copy()->firstOfMonth();
        $inicioMesAnterior = $hoje->copy()->subMonth()->firstOfMonth();
        $fimMesAnterior = $hoje->copy()->subMonth()->endOfMonth();
        // Buscar metas concluídas para o mês atual
        $metasMesAtual = DB::table('doacao_has_categoria_doacao as dc')
        ->join('doacao', 'dc.id_doacao', '=', 'doacao.id_doacao')
        ->where('doacao.id_instituicao', $instituicao->id_instituicao)
        ->where('doacao.card_doacao', '=', 1)
        ->whereRaw('dc.quantidade_doacao_categoria >= dc.meta_doacao_categoria')
        ->whereBetween('dc.updated_at', [$inicioMesAtual, $hoje])
        ->select(DB::raw('DATE(dc.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
        ->groupBy('dia')
        ->get();
        // Buscar metas concluídas para o mês anterior
        $metasMesAnterior = DB::table('doacao_has_categoria_doacao as dc')
        ->join('doacao', 'dc.id_doacao', '=', 'doacao.id_doacao')
        ->where('doacao.id_instituicao', $instituicao->id_instituicao)
        ->where('doacao.card_doacao', '=', 1)
        ->whereRaw('dc.quantidade_doacao_categoria >= dc.meta_doacao_categoria')
        ->whereBetween('dc.updated_at', [$inicioMesAnterior, $fimMesAnterior])
        ->select(DB::raw('DATE(dc.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
        ->groupBy('dia')
        ->get();
        // Formatar dados para o gráfico
        $dadosMesAtual = $metasMesAtual->pluck('quantidade', 'dia')->toArray();
        $dadosMesAnterior = $metasMesAnterior->pluck('quantidade', 'dia')->toArray();
        // Criar arrays de datas e dados
        $labelsMetas = [];
        $dadosAtualMetas = [];
        $dadosAnteriorMetas = [];
        for ($dia = 1; $dia <= $hoje->daysInMonth; $dia++) {
            $dataAtual = $inicioMesAtual->copy()->day($dia)->toDateString();
            $dataAnterior = $inicioMesAnterior->copy()->day($dia)->toDateString();

            $labelsMetas[] = $dataAtual;
            $dadosAtualMetas[] = $dadosMesAtual[$dataAtual] ?? 0;
            $dadosAnteriorMetas[] = $dadosMesAnterior[$dataAnterior] ?? 0;
        }
        $totalMetas = array_sum($dadosAtualMetas);

        $card4 = DB::table('voluntario_has_doacao as vd')
        ->join('doacao', 'vd.id_doacao', '=', 'doacao.id_doacao')
        ->where('doacao.id_instituicao', $instituicao->id_instituicao)
        ->where('vd.situacao_solicitacao_doacao', '=', 1)
        ->whereDate('vd.updated_at', Carbon::today())
        ->count();

        $doacoesAtivas = Doacao::where('data_hora_limite_doacao', '>', Carbon::now())
        ->where('id_instituicao', $instituicao->id_instituicao)
        ->orderBy('data_hora_limite_doacao', 'asc')
        ->with(['categorias' => function ($query) {
            $query->select('descricao_categoria') // Seleciona os campos da categoria
                ->withPivot('meta_doacao_categoria'); // Inclui a meta da tabela intermediária
        }])
        ->paginate(5, ['*'], 'doacoesAtivasPage');

        $limite = request()->input('limit');
        $query = DB::table('voluntario_has_doacao as vd')
        ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
        ->join('voluntario as v', 'vd.id_voluntario', '=', 'v.id_voluntario')
        ->join('contato as c', 'v.id_contato', '=', 'c.id_contato')
        ->join('users as u', 'v.id_usuario', '=', 'u.id')
        ->where('d.id_instituicao', $instituicao->id_instituicao)
        ->where('vd.situacao_solicitacao_doacao', '=', 1)
        ->whereDate('vd.updated_at', Carbon::today())
        ->select(
            'u.*', // Usuário do voluntário
            'vd.*', // Informações sobre a doação voluntária
            'd.*',  // Informações sobre a doação
            'c.telefone_contato' //Informações sobre o telefone do voluntário
        );
        // Verificar se há um termo de busca no request
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($query) use ($search) {
                $query->where('u.name', 'like', "%{$search}%")
                    ->orWhere('vd.categoria_doacao', 'like', "%{$search}%");
                    // Adicione mais campos se precisar
            });
        }
        // Definir a lógica de limite e paginação
        if ($limite) {
            // Se 'limit' está definido, use get() e limite a quantidade de registros
            $todasDoacoes = $query->take($limite)->get();
        } else {
            // Caso contrário, usa paginação normalmente com 5 itens por página
            $todasDoacoes = $query->paginate(5, ['*'], 'todasDoacoesPage');
        }

        // Obter as arrecadações e agrupar por mês e tipo de doação
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


        // Consulta para capturar os tipos de recebimento (Coleta com voluntário, Entrega na instituição)
        $tiposDeRecebimento = DB::table('voluntario_has_doacao as vd')
        ->join('doacao as d', 'vd.id_doacao', '=', 'd.id_doacao')
        ->where('d.id_instituicao', $instituicao->id_instituicao)
        ->where('vd.situacao_solicitacao_doacao', '=', 1)
        ->select(
            DB::raw("SUM(CASE WHEN vd.data_hora_coleta IS NOT NULL THEN 1 ELSE 0 END) as total_coleta"),
            DB::raw("SUM(CASE WHEN vd.data_hora_coleta IS NULL THEN 1 ELSE 0 END) as total_entrega")
        )
        ->first();


        return view('doacao', compact(
            'doacoesAtivas',
            'todasDoacoes',
            'labels',
            'totalCard',
            'totalDoarAgora',
            'tiposDeRecebimento',
            'card1',
            'card11',
            'labelsDoacoesMes',
            'totalDoacoesPorDiaMes',
            'totalDoacoesMes',
            'labelsMetas',
            'totalMetas',
            'dadosAtualMetas',
            'dadosAnteriorMetas',
            'card4',
        ))
        ->with('i', (request()->input('doacoesAtivasPage', 1) - 1) * 5)
        ->with('j', (request()->input('todasDoacoesPage', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
        // Definir mensagens de erro personalizadas para validação
        $messages = [
            'nome.required' => 'O campo nome da doação é obrigatório.',
            'nome.max' => 'O nome da doação pode ter no máximo 100 caracteres.',
            'data_hora_limite.required' => 'A data e hora limite são obrigatórias.',
            'data_hora_limite.date' => 'A data e hora limite devem ser uma data válida.',
            'data_hora_limite.after' => 'A data e hora limite devem ser no futuro.',
            'categoria_nome.required' => 'O nome da categoria é obrigatório.',
            'categoria_nome.*.required' => 'Cada categoria deve ter um nome válido.',
            'categoria_nome.*.string' => 'Cada categoria deve ser um nome válido.',
            'categoria_meta.required' => 'A meta da categoria é obrigatória.',
            'categoria_meta.*.required' => 'Cada meta deve ser um valor numérico válido.',
            'categoria_meta.*.numeric' => 'Cada meta deve ser um valor numérico.',
            'categoria_meta.*.min' => 'Cada meta deve ter no mínimo 1.',
            'observacao.required' => 'O campo observação é obrigatório para melhor entendimento sobre a doação.',
        ];

        // Validação dos dados de entrada
        $request->validate([
            'nome' => 'required|string|max:100',
            'data_hora_limite' => 'required|date|after:now',
            'observacao' => 'required',
            'categoria_nome' => 'required|array|min:1',
            'categoria_nome.*' => 'required|string|max:45', // Ajuste conforme o limite da tabela categoria_doacao
            'categoria_meta' => 'required|array|min:1',
            'categoria_meta.*' => 'required|numeric|min:1',
            'coleta' => 'numeric' // Verifique se é 0 ou 1 (booleano)
        ], $messages);

        $user = Auth::user();
        $instituicao = Instituicao::where('id_usuario', $user->id)->first();

        // Criar a doação
        $doacao = Doacao::create([
            'id_instituicao' => $instituicao->id_instituicao, // Assumindo que a instituição do usuário logado está disponível
            'observacao_doacao' => $request->input('observacao'),
            'data_hora_limite_doacao' => $request->input('data_hora_limite'),
            'nome_doacao' => $request->input('nome'),
            'coleta_doacao' => $request->input('coleta'),
            'card_doacao' => '1'
        ]);

        // Recuperar as listas de nomes de categorias e metas
        $categoriasNomes = $request->input('categoria_nome');
        $categoriasMetas = $request->input('categoria_meta');

        // Verificar se existem categorias
        if ($categoriasNomes && $categoriasMetas) {
            foreach ($categoriasNomes as $index => $nomeCategoria) {
                $metaCategoria = $categoriasMetas[$index];

                // Verificar se a categoria já existe
                $categoria = CategoriaDoacao::create([
                    'descricao_categoria' => $nomeCategoria
                ]);

                // Associar a doação à categoria na tabela intermediária utilizando o método "categorias()"
                $doacao->categorias()->attach($categoria->id_categoria, [
                    'meta_doacao_categoria' => $metaCategoria,
                    'quantidade_doacao_categoria' => 0, // Inicia com 0
                ]);
            }
        }

        // Retornar sucesso (ajustar rota de redirecionamento conforme necessário)
        return redirect()->route('doacao.index')
            ->with('success', 'Doação criada com sucesso!!');
    }

    public function update(Request $request, Doacao $doacao)
    {
        // Definir mensagens de erro personalizadas para validação
        $messages = [
            'nome.required' => 'O campo nome da doação é obrigatório.',
            'nome.max' => 'O nome da doação pode ter no máximo 100 caracteres.',
            'data_hora_limite.required' => 'A data e hora limite são obrigatórias.',
            'data_hora_limite.date' => 'A data e hora limite devem ser uma data válida.',
            'data_hora_limite.after' => 'A data e hora limite devem ser no futuro.',
            'categoria_nome.required' => 'O nome da categoria é obrigatório.',
            'categoria_nome.*.required' => 'Cada categoria deve ter um nome válido.',
            'categoria_meta.required' => 'A meta da categoria é obrigatória.',
            'categoria_meta.*.required' => 'Cada meta deve ser um valor numérico válido.',
            'categoria_meta.*.min' => 'Cada meta deve ter no mínimo 1.',
            'observacao.required' => 'O campo observação é obrigatório para melhor entendimento sobre a doação.',
        ];

        // Validação dos dados de entrada
        $request->validate([
            'nome' => 'required|string|max:100',
            'data_hora_limite' => 'required|date|after:now',
            'observacao' => 'required',
            'categoria_nome' => 'required|array|min:1',
            'categoria_nome.*' => 'required|string|max:45', // Ajuste conforme o limite da tabela categoria_doacao
            'categoria_meta' => 'required|array|min:1',
            'categoria_meta.*' => 'required|numeric|min:1',
            'coleta' => 'numeric' // Verifique se é 0 ou 1 (booleano)
        ], $messages);

        // Atualizar a doação
        $doacao->update([
            'nome_doacao' => $request->input('nome'),
            'observacao_doacao' => $request->input('observacao'),
            'data_hora_limite_doacao' => $request->input('data_hora_limite'),
            'coleta_doacao' => $request->input('coleta'),
        ]);

        // Recuperar as listas de nomes de categorias e metas
        $categoriasNomes = $request->input('categoria_nome');
        $categoriasMetas = $request->input('categoria_meta');

        // Sincronizar as categorias da doação
        if ($categoriasNomes && $categoriasMetas) {
            foreach ($categoriasNomes as $index => $nomeCategoria) {
                $metaCategoria = $categoriasMetas[$index];

                // Verificar se a categoria já existe
                $categoria = CategoriaDoacao::firstOrCreate([
                    'descricao_categoria' => $nomeCategoria
                ]);

                // Atualizar ou adicionar a relação sem remover os dados existentes
                $doacao->categorias()->updateExistingPivot($categoria->id_categoria, [
                    'meta_doacao_categoria' => $metaCategoria,
                ], false);
            }
        }

        // Redirecionar com mensagem de sucesso
        return redirect()->route('doacao.index')
            ->with('success', 'Card de doação atualizado com sucesso!!');
    }

    public function destroy(Doacao $doacao)
    {
        $doacao->delete();
        return redirect()->route('doacao.index')
            ->with('success', 'Card de doação deletada com sucesso!!');
    }

    public function listagemDoacao()
    {
        $doacoes = Doacao::select(
                'id_doacao',
                'id_instituicao',
                'nome_doacao',
                'observacao_doacao',
                'data_hora_limite_doacao',
                'coleta_doacao',
                'card_doacao'
            )
            ->where('card_doacao', 1)
            ->with([
                'instituicao.usuario:id,name,email',
                'instituicao.contato:id_contato,telefone_contato,whatsapp_contato',
                'instituicao.endereco',
                'instituicao',
                'categorias:id_categoria,descricao_categoria',
            ])
            ->get()
            ->filter(function ($doacao) {
                $dataLimitePassada = now()->greaterThan($doacao->data_hora_limite_doacao);
                $metasCumpridas = $doacao->categorias->every(function ($categoria) {
                    return $categoria->pivot->quantidade_doacao_categoria >= $categoria->pivot->meta_doacao_categoria;
                });

                return !$dataLimitePassada && !$metasCumpridas;
            })
            ->map(function ($doacao) {
                $funcionamento = json_decode($doacao->instituicao->funcionamento_instituicao, true);
                $horariosAgrupados = [];
                foreach ($funcionamento as $dia => $horario) {
                    if ($horario['funciona']) {
                        $horarioTexto = "{$horario['abertura']} até {$horario['fechamento']}";
                        $horariosAgrupados[$horarioTexto][] = $dia;
                    }
                }

                $diasFuncionamento = '';
                foreach ($horariosAgrupados as $horario => $dias) {
                    $diasAgrupados = [];
                    $diasConsecutivos = [];
                    $diasSemConsecutivos = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

                    foreach ($diasSemConsecutivos as $dia) {
                        if (in_array($dia, $dias)) {
                            $diasConsecutivos[] = $dia;
                        } else {
                            if ($diasConsecutivos) {
                                $diasAgrupados[] = count($diasConsecutivos) > 1
                                    ? implode(' a ', [$diasConsecutivos[0], end($diasConsecutivos)])
                                    : $diasConsecutivos[0];
                                $diasConsecutivos = [];
                            }
                        }
                    }

                    if ($diasConsecutivos) {
                        $diasAgrupados[] = count($diasConsecutivos) > 1
                            ? implode(' a ', [$diasConsecutivos[0], end($diasConsecutivos)])
                            : $diasConsecutivos[0];
                    }

                    $diasFuncionamento .= implode(', ', $diasAgrupados) . ": $horario\n";
                }

                return [
                    'id_doacao' => $doacao->id_doacao,
                    'nome_doacao' => $doacao->nome_doacao,
                    'observacao_doacao' => $doacao->observacao_doacao,
                    'data_hora_limite_doacao' => $doacao->data_hora_limite_doacao,
                    'coleta_doacao' => $doacao->coleta_doacao,
                    'card_doacao' => $doacao->card_doacao,
                    'instituicao' => [
                        'id_instituicao' => $doacao->instituicao->id_instituicao ?? null,
                        'nome' => $doacao->instituicao->usuario->name ?? 'Nome não disponível',
                        'email' => $doacao->instituicao->usuario->email ?? 'Email não disponível',
                        'telefone' => $doacao->instituicao->contato->telefone_contato ?? 'Telefone não disponível',
                        'whatsapp' => $doacao->instituicao->contato->whatsapp_contato ?? 'Whatsapp não disponível',
                        'endereco' => $doacao->instituicao->endereco ?
                            "{$doacao->instituicao->endereco->cidade_endereco}, {$doacao->instituicao->endereco->bairro_endereco}, {$doacao->instituicao->endereco->logradouro_endereco}, {$doacao->instituicao->endereco->numero_endereco}, {$doacao->instituicao->endereco->complemento_endereco}, {$doacao->instituicao->endereco->cep_endereco}, {$doacao->instituicao->endereco->estado_endereco}" :
                            'Endereço não disponível',
                        'funcionamento_instituicao' => [
                            'horario' => $diasFuncionamento,
                        ],
                    ],
                    'categorias' => $doacao->categorias->map(function ($categoria) {
                        return [
                            'id_categoria' => $categoria->id_categoria,
                            'descricao_categoria' => $categoria->descricao_categoria,
                            'meta' => $categoria->pivot->meta_doacao_categoria ?? null,
                            'quantidade' => $categoria->pivot->quantidade_doacao_categoria ?? null,
                        ];
                    }),
                ];
            })
            ->values(); // Remove os índices numéricos.

        return response()->json([
            'data' => $doacoes,
            'status' => 'success',
            'message' => 'Listagem solicitada com sucesso!',
        ], 200);
    }


    public function realizaDoacao(Request $request)
    {
        $user = Auth::user();
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();

        // Valida os dados recebidos
        $validator = Validator::make($request->all(), [
            'id_doacao' => 'required|integer|exists:doacao,id_doacao',
            'id_categoria' => 'required|integer|exists:categoria_doacao,id_categoria',
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

        $categoria = CategoriaDoacao::where('id_categoria', $request->id_categoria)->first();
        DB::table('doacao_has_categoria_doacao')
                    ->where('id_doacao', $request->id_doacao)
                    ->where('id_categoria', $categoria->id_categoria)
                    ->update([
                        'quantidade_doacao_categoria' => DB::raw('quantidade_doacao_categoria + ' . $request->quantidade_doacao),
                        'updated_at' => now(),
                    ]);

        try {
            // Verifica se o voluntário já fez uma doação para o mesmo id_doacao
            $doacaoExistente = DB::table('voluntario_has_doacao')
                ->where('id_voluntario', $voluntario->id_voluntario)
                ->where('id_doacao', $request->id_doacao)
                ->first();

            if ($doacaoExistente) {
                // Atualiza os campos se o registro já existir
                DB::table('voluntario_has_doacao')
                    ->where('id_voluntario', $voluntario->id_voluntario)
                    ->where('id_doacao', $request->id_doacao)
                    ->update([
                        'situacao_solicitacao_doacao' => 0,
                        'data_hora_coleta' => $request->data_hora_coleta,
                        'categoria_doacao' => $categoria->descricao_categoria,
                        'quantidade_doacao' => $request->quantidade_doacao,
                        'updated_at' => now(),
                    ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Doação registrada com sucesso!',
                ], 200);
            } else {
                // Insere os dados se o registro não existir
                DB::table('voluntario_has_doacao')->insert([
                    'id_voluntario' => $voluntario->id_voluntario,
                    'id_doacao' => $request->id_doacao,
                    'situacao_solicitacao_doacao' => 0,
                    'data_hora_coleta' => $request->data_hora_coleta,
                    'categoria_doacao' => $categoria->descricao_categoria,
                    'quantidade_doacao' => $request->quantidade_doacao,
                    'created_at' => now(),
                    'updated_at' => now(),
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

}
