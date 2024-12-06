<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Evento;
use App\Models\Habilidade;
use App\Models\Instituicao;
use App\Models\Voluntario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('pt_BR');
        $user = Auth::user();
        $instituicao = Instituicao::where('id_usuario', $user->id)->first();

        $card1 = DB::table('evento')
        ->where('id_instituicao', $instituicao->id_instituicao)
        ->where('data_hora_limite_evento', '>=', Carbon::now())
        ->count();
        $totalEventos = DB::table('evento')
            ->where('id_instituicao', $instituicao->id_instituicao)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $card11 = ($totalEventos > 0) ? round(($card1 / $totalEventos) * 100, 2) : 0;

        $card2 = DB::table('voluntario_has_evento as ve')
        ->join('evento', 've.id_evento', '=', 'evento.id_evento')
        ->where('evento.id_instituicao', $instituicao->id_instituicao)
        ->whereMonth('ve.updated_at', Carbon::now()->month)
        ->select(DB::raw('DATE(ve.updated_at) as date'), DB::raw('COUNT(*) as total_ajudas'))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
        // Extrair rótulos e quantidades para o gráfico
        $labelsAjudasMes = $card2->pluck('date')->toArray();
        $totalAjudasPorDiaMes = $card2->pluck('total_ajudas')->toArray();
        // Somar todas as doações do mês inteiro
        $totalAjudasMes = array_sum($totalAjudasPorDiaMes);

        //card3
        $hoje = Carbon::today()->addDay();
        $inicioMesAtual = $hoje->copy()->firstOfMonth();
        $inicioMesAnterior = $hoje->copy()->subMonth()->firstOfMonth();
        $fimMesAnterior = $hoje->copy()->subMonth()->endOfMonth();
        // Buscar metas concluídas para o mês atual
        $metasMesAtual = DB::table('evento_has_habilidade as eh')
        ->join('evento', 'eh.id_evento', '=', 'evento.id_evento')
        ->where('evento.id_instituicao', $instituicao->id_instituicao)
        ->whereRaw('eh.quantidade_voluntario >= eh.meta_evento')
        ->whereBetween('eh.updated_at', [$inicioMesAtual, $hoje])
        ->select(DB::raw('DATE(eh.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
        ->groupBy('dia')
        ->get();
        // Buscar metas concluídas para o mês anterior
        $metasMesAnterior = DB::table('evento_has_habilidade as eh')
        ->join('evento', 'eh.id_evento', '=', 'evento.id_evento')
        ->where('evento.id_instituicao', $instituicao->id_instituicao)
        ->whereRaw('eh.quantidade_voluntario >= eh.meta_evento')
        ->whereBetween('eh.updated_at', [$inicioMesAnterior, $fimMesAnterior])
        ->select(DB::raw('DATE(eh.updated_at) as dia'), DB::raw('COUNT(*) as quantidade'))
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

        $card4 = DB::table('voluntario_has_evento as ve')
        ->join('evento', 've.id_evento', '=', 'evento.id_evento')
        ->where('evento.id_instituicao', $instituicao->id_instituicao)
        ->whereDate('ve.updated_at', Carbon::today())
        ->count();

        // Obter as ajudas e agrupar por mês
        $ajudas = DB::table('voluntario_has_evento as ve')
        ->join('evento', 've.id_evento', '=', 'evento.id_evento')
        ->where('evento.id_instituicao', $instituicao->id_instituicao)
        ->whereYear('ve.updated_at', Carbon::now()->year) // Filtra pelo ano atual
        ->select(
            DB::raw('MONTH(ve.updated_at) as month'),
            DB::raw('COUNT(ve.id_evento) as total_ajudas')
        )
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();
        // Extrair rótulos e quantidades para o gráfico de arrecadações
        $labels = [];
        $totalAjudas = [];
        foreach ($ajudas as $ajuda) {
        $labels[] = Carbon::create()->month($ajuda->month)->translatedFormat('M'); // Mês abreviado
        $totalAjudas[] = $ajuda->total_ajudas; // Total de ajudas por mês
        }

        // Obter as ajudas e agrupar por mês
        $metas = DB::table('evento_has_habilidade as eh')
        ->join('evento', 'eh.id_evento', '=', 'evento.id_evento')
        ->where('evento.id_instituicao', $instituicao->id_instituicao)
        ->whereRaw('eh.quantidade_voluntario >= eh.meta_evento')
        ->whereYear('eh.updated_at', Carbon::now()->year) // Filtra pelo ano atual
        ->select(
            DB::raw('MONTH(eh.updated_at) as month'),
            DB::raw('COUNT(eh.id_evento) as total_metas')
        )
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();
        // Extrair rótulos e quantidades para o gráfico de arrecadações
        $labelsMetasAno = [];
        $totalMetasAno = [];
        foreach ($metas as $meta) {
        $labelsMetasAno[] = Carbon::create()->month($meta->month)->translatedFormat('M'); // Mês abreviado
        $totalMetasAno[] = $meta->total_metas; // Total de metas por mês
        }

        $eventosAtivos = Evento::where('data_hora_limite_evento', '>', Carbon::now())
        ->where('id_instituicao', $instituicao->id_instituicao)
        ->orderBy('data_hora_limite_evento', 'asc')
        ->with([
            'habilidades' => function ($query) {
                $query->select('descricao_habilidade')
                    ->withPivot('meta_evento');
            },
            'endereco' => function ($query) {
                $query->select('*');
            }
        ])
        ->paginate(5, ['*'], 'eventosAtivosPage');

        $limite = request()->input('limit');
        $query = DB::table('voluntario_has_evento as ve')
            ->join('evento as e', 've.id_evento', '=', 'e.id_evento')
            ->join('voluntario as v', 've.id_voluntario', '=', 'v.id_voluntario')
            ->join('contato as c', 'v.id_contato', '=', 'c.id_contato')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->where('e.id_instituicao', $instituicao->id_instituicao)
            ->whereDate('ve.updated_at', Carbon::today())
            ->select(
                'u.*',
                've.*',
                'e.*',
                'c.telefone_contato'
            );

        // Verificar se há um termo de busca no request
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($query) use ($search) {
                $query->where('u.name', 'like', "%{$search}%")
                    ->orWhere('ve.habilidade_voluntario', 'like', "%{$search}%");
                    // Adicione mais campos se precisar
            });
        }
        // Definir a lógica de limite e paginação
        if ($limite) {
            // Se 'limit' está definido, use get() e limite a quantidade de registros
            $todosVoluntarios = $query->take($limite)->get();
        } else {
            // Caso contrário, usa paginação normalmente com 5 itens por página
            $todosVoluntarios = $query->paginate(5, ['*'], 'voluntariosPage');
        }

        $habilidades = Habilidade::all();

        return view('evento', compact(
            'card1',
            'card11',
            'labelsAjudasMes',
            'totalAjudasPorDiaMes',
            'totalAjudasMes',
            'card4',
            'labelsMetas',
            'totalMetas',
            'dadosAtualMetas',
            'dadosAnteriorMetas',
            'labels',
            'labelsMetasAno',
            'totalMetasAno',
            'totalAjudas',
            'eventosAtivos',
            'todosVoluntarios',
            'habilidades'
        ))
        ->with('i', (request()->input('eventosAtivosPage', 1) - 1) * 5)
        ->with('j', (request()->input('voluntariosPage', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $instituicao = Instituicao::where('id_usuario', $user->id)->first();

        $messages = [
            'nome.required' => 'O campo nome do evento é obrigatório.',
            'nome.max' => 'O nome do evento pode ter no máximo 100 caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.max' => 'A descrição do evento pode ter no máximo 150',
            'data_hora_evento.required' => 'A data e hora do evento são obrigatórias.',
            'data_hora_limite.required' => 'A data e hora de expiração são obrigatórias.',
            'data_hora_limite.date' => 'A data e hora limite devem ser uma data válida.',
            'data_hora_limite.after' => 'A data e hora limite devem ser no futuro.',
            'habilidade_nome.required' => 'É necessário informar pelo menos uma habilidade.',
            'habilidade_nome.array' => 'O campo habilidades deve ser um array.',
            'habilidade_nome.*.string' => 'Cada habilidade deve ser um nome válido.',
            'meta_evento.required' => 'A meta de cada habilidade é obrigatória.',
            'meta_evento.array' => 'O campo metas deve ser um array.',
            'meta_evento.*.numeric' => 'Cada meta deve ser um valor numérico.',
            'meta_evento.*.min' => 'Cada meta deve ter no mínimo 1.',
        ];

        $request->validate([
            'nome' => 'required|max:100',
            'descricao' => 'required|max:150',
            'data_hora_evento' => 'required|date',
            'data_hora_limite' => 'required|date|after:now',
            'habilidade_nome' => 'required|array',
            'habilidade_nome.*' => 'string|max:255',
            'meta_evento' => 'required|array',
            'meta_evento.*' => 'numeric|min:1',
        ], $messages);

        $evento = null;
        // Verifica se o checkbox de endereço foi marcado
        if ($request->has('endereco')) {
            $messages = [
                'cep.required' => 'O campo cep é obrigatório.',
                'rua.required' => 'O campo rua é obrigatório.',
                'estado.required' => 'O campo estado é obrigatório.',
                'numero.required' => 'O campo número é obrigatório.',
                'bairro.required' => 'O campo bairro é obrigatório.',
                'cidade.required' => 'O campo cidade é obrigatório.',
                'complemento.required' => 'O campo complemento é obrigatório.',
                'complemento.max' => 'O complemento pode ter no máximo 50 caracteres.',
                'estado.required' => 'O campo estado é obrigatório.',
            ];
            $request->validate([
                'rua' => 'nullable|string|max:50',
                'cep' => 'nullable|string|max:9',
                'numero' => 'nullable|string',
                'bairro' => 'nullable|string|max:50',
                'cidade' => 'nullable|string|max:50',
                'estado' => 'nullable|string|max:2',
                'complemento' => 'nullable|string|max:50',
            ],$messages);

            // Cria o endereço se o checkbox for marcado
            $endereco = Endereco::create([
                'cep_endereco'=> $request->input('cep'),
                'complemento_endereco'=> $request->input('complemento'),
                'cidade_endereco'=> $request->input('cidade'),
                'logradouro_endereco'=> $request->input('rua'),
                'estado_endereco'=> $request->input('estado'),
                'bairro_endereco'=> $request->input('bairro'),
                'numero_endereco'=> $request->input('numero'),
            ]);

            // Adiciona o id_endereco ao evento
            $evento = Evento::create([
                'nome_evento' => $request->input('nome'),
                'descricao_evento' => $request->input('descricao'),
                'data_hora_evento' => $request->input('data_hora_evento'),
                'data_hora_limite_evento' => $request->input('data_hora_limite'),
                'id_instituicao' => $instituicao->id_instituicao,
                'id_endereco' => $endereco->id_endereco, // Associa o endereço ao evento
            ]);
        } else {
            // Cria o evento sem o endereço
            $evento = Evento::create([
                'nome_evento' => $request->input('nome'),
                'descricao_evento' => $request->input('descricao'),
                'data_hora_evento' => $request->input('data_hora_evento'),
                'data_hora_limite_evento' => $request->input('data_hora_limite'),
                'id_instituicao' => $instituicao->id_instituicao,
            ]);
        }

        $habilidadesNomes = $request->input('habilidade_nome');
        $habilidadesMetas = $request->input('meta_evento');

        // Verificar se existem habilidades
        if ($habilidadesNomes && $habilidadesMetas) {
            foreach ($habilidadesNomes as $index => $nomeHabilidade) {
                $metaHabilidade = $habilidadesMetas[$index];

                // Verificar se a habilidade já existe
                $habilidade = Habilidade::where('descricao_habilidade', $nomeHabilidade)->first();

                // Associar o evento à habilidade na tabela intermediária utilizando o método "habilidades()"
                $evento->habilidades()->attach($habilidade->id_habilidade, [
                    'meta_evento' => $metaHabilidade,
                    'quantidade_voluntario' => 0, // Inicia com 0
                ]);
            }
        }

        return redirect()->route('evento.index')
            ->with('success', 'Evento criado com sucesso!');
    }

    public function update(Request $request, Evento $evento)
    {
        // Busca o endereço relacionado ao evento
        $endereco = Endereco::where('id_endereco', $evento->id_endereco)->first();

        $messages = [
            'nome.required' => 'O campo nome do evento é obrigatório.',
            'nome.max' => 'O nome do evento pode ter no máximo 100 caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.max' => 'A descrição do evento pode ter no máximo 150 caracteres.',
            'data_hora_evento.required' => 'A data e hora do evento são obrigatórias.',
            'data_hora_limite.required' => 'A data e hora de expiração são obrigatórias.',
            'data_hora_limite.date' => 'A data e hora limite devem ser uma data válida.',
            'data_hora_limite.after' => 'A data e hora limite devem ser no futuro.',
            'habilidade_nome.required' => 'É necessário informar pelo menos uma habilidade.',
            'habilidade_nome.array' => 'O campo habilidades deve ser um array.',
            'habilidade_nome.*.string' => 'Cada habilidade deve ser um nome válido.',
            'meta_evento.required' => 'A meta de cada habilidade é obrigatória.',
            'meta_evento.array' => 'O campo metas deve ser um array.',
            'meta_evento.*.numeric' => 'Cada meta deve ser um valor numérico.',
            'meta_evento.*.min' => 'Cada meta deve ter no mínimo 1.',
        ];

        $request->validate([
            'nome' => 'required|max:100',
            'descricao' => 'required|max:150',
            'data_hora_evento' => 'required|date',
            'data_hora_limite' => 'required|date|after:now',
            'habilidade_nome' => 'required|array',
            'habilidade_nome.*' => 'string|max:255',
            'meta_evento' => 'required|array',
            'meta_evento.*' => 'numeric|min:1',
        ], $messages);

        // Verifica se o checkbox de endereço foi marcado
        if ($request->has('endereco')) {
            $messages = [
                'cep.required' => 'O campo cep é obrigatório.',
                'rua.required' => 'O campo rua é obrigatório.',
                'estado.required' => 'O campo estado é obrigatório.',
                'numero.required' => 'O campo número é obrigatório.',
                'bairro.required' => 'O campo bairro é obrigatório.',
                'cidade.required' => 'O campo cidade é obrigatório.',
                'complemento.required' => 'O campo complemento é obrigatório.',
                'complemento.max' => 'O complemento pode ter no máximo 50 caracteres.',
                'estado.required' => 'O campo estado é obrigatório.',
            ];
            $request->validate([
                'rua' => 'nullable|string|max:50',
                'cep' => 'nullable|string|max:9',
                'numero' => 'nullable|string',
                'bairro' => 'nullable|string|max:50',
                'cidade' => 'nullable|string|max:50',
                'estado' => 'nullable|string|max:2',
                'complemento' => 'nullable|string|max:50',
            ], $messages);

            $endereco->update([
                'cep_endereco'=> $request->input('cep'),
                'complemento_endereco'=> $request->input('complemento'),
                'cidade_endereco'=> $request->input('cidade'),
                'logradouro_endereco'=> $request->input('rua'),
                'estado_endereco'=> $request->input('estado'),
                'bairro_endereco'=> $request->input('bairro'),
                'numero_endereco'=> $request->input('numero'),
            ]);

            $evento->update([
                'nome_evento' => $request->input('nome'),
                'descricao_evento' => $request->input('descricao'),
                'data_hora_evento' => $request->input('data_hora_evento'),
                'data_hora_limite_evento' => $request->input('data_hora_limite'),
                'id_endereco' => $endereco->id_endereco,
            ]);
        } else {
            $evento->update([
                'nome_evento' => $request->input('nome'),
                'descricao_evento' => $request->input('descricao'),
                'data_hora_evento' => $request->input('data_hora_evento'),
                'data_hora_limite_evento' => $request->input('data_hora_limite'),
            ]);
        }

        // Processar habilidades associadas
        $habilidadesNomes = $request->input('habilidade_nome');
        $habilidadesMetas = $request->input('meta_evento');

        if ($habilidadesNomes && $habilidadesMetas) {
            $syncData = [];

            foreach ($habilidadesNomes as $index => $nomeHabilidade) {
                $metaHabilidade = $habilidadesMetas[$index];

                $habilidade = Habilidade::where('descricao_habilidade', $nomeHabilidade)->first();

                if ($habilidade) {
                    // Verificar se a habilidade já existe no evento
                    $habilidadeExistente = $evento->habilidades()
                        ->where('evento_has_habilidade.id_habilidade', $habilidade->id_habilidade)
                        ->first();

                    $syncData[$habilidade->id_habilidade] = [
                        'meta_evento' => $metaHabilidade,
                        'quantidade_voluntario' => $habilidadeExistente
                            ? $habilidadeExistente->pivot->quantidade_voluntario // Mantém o valor existente
                            : 0, // Se for nova, inicia com 0
                    ];
                }
            }

            $evento->habilidades()->sync($syncData);
        }

        return redirect()->route('evento.index')
            ->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('evento.index')
            ->with('success', 'Card de evento deletado com sucesso!!');
    }

    public function listagemEvento()
    {
        $user = Auth::user();
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();

        if (!$voluntario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voluntário não encontrado.',
            ], 404);
        }

        // Obter IDs dos eventos em que o voluntário já está inscrito
        $eventosInscritos = DB::table('voluntario_has_evento')
            ->where('id_voluntario', $voluntario->id_voluntario)
            ->pluck('id_evento')
            ->toArray();

        $eventos = Evento::select(
            'id_evento',
            'descricao_evento',
            'data_hora_evento',
            'id_instituicao',
            'id_endereco',
            'data_hora_limite_evento',
            'nome_evento'
        )
        ->with([
            'instituicao.usuario:id,name,email',
            'instituicao.contato:id_contato,telefone_contato,whatsapp_contato',
            'instituicao.endereco',
            'instituicao',
            'habilidades:id_habilidade,descricao_habilidade',
            'voluntarios'
        ])
        ->whereNotIn('id_evento', $eventosInscritos) // Exclui eventos já inscritos
        ->get()
        ->filter(function ($evento) {
            // Convertendo para objetos Carbon para garantir comparações corretas
            $dataLimite = Carbon::parse($evento->data_hora_limite_evento);
            $dataEvento = Carbon::parse($evento->data_hora_evento);
            $dataAtual = now();

            // Verifica se a data do evento ou do limite já passou
            $dataLimitePassada = $dataAtual->greaterThan($dataLimite);
            $dataEventoPassada = $dataAtual->greaterThan($dataEvento);

            // Verifica se todas as metas das habilidades não foram cumpridas
            $metasCumpridas = $evento->habilidades->every(function ($habilidade) {
                return $habilidade->pivot->quantidade_voluntario >= $habilidade->pivot->meta_evento;
            });

            // Exclui o evento se passou do limite, da data do evento ou se todas as metas foram cumpridas
            return !$dataLimitePassada && !$dataEventoPassada && !$metasCumpridas;
        })
        ->values(); // Converte para array

        $eventos = $eventos->map(function ($evento) {
            // Decodificando o JSON de funcionamento_instituicao da instituição
            $funcionamento = json_decode($evento->instituicao->funcionamento_instituicao, true);

            // Agrupando os dias com os mesmos horários
            $horariosAgrupados = [];
            foreach ($funcionamento as $dia => $horario) {
                if ($horario['funciona']) {
                    $horarioTexto = "{$horario['abertura']} até {$horario['fechamento']}";
                    $horariosAgrupados[$horarioTexto][] = $dia;
                }
            }

            // Formatação da string de funcionamento com agrupamento de dias consecutivos
            $diasFuncionamento = '';
            foreach ($horariosAgrupados as $horario => $dias) {
                $diasAgrupados = [];
                $diasConsecutivos = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

                foreach ($diasConsecutivos as $dia) {
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
                'id_evento' => $evento->id_evento,
                'nome_evento' => $evento->nome_evento,
                'descricao_evento' => $evento->descricao_evento,
                'data_hora_limite_evento' => $evento->data_hora_limite_evento,
                'data_evento' => \Carbon\Carbon::parse($evento->data_hora_evento)->format('d/m/Y'),
                'hora_evento' => \Carbon\Carbon::parse($evento->data_hora_evento)->format('H:i'),
                'instituicao' => [
                    'id_instituicao' => $evento->instituicao->id_instituicao ?? null,
                    'nome' => $evento->instituicao->usuario->name ?? 'Nome não disponível',
                    'email' => $evento->instituicao->usuario->email ?? 'Email não disponível',
                    'telefone' => $evento->instituicao->contato->telefone_contato ?? 'Telefone não disponível',
                    'whatsapp' => $evento->instituicao->contato->whatsapp_contato ?? 'Whatsapp não disponível',
                    'endereco' => $evento->instituicao->endereco ?
                        "{$evento->instituicao->endereco->cidade_endereco}, {$evento->instituicao->endereco->bairro_endereco}, {$evento->instituicao->endereco->logradouro_endereco}, {$evento->instituicao->endereco->numero_endereco}, {$evento->instituicao->endereco->complemento_endereco}, {$evento->instituicao->endereco->cep_endereco}, {$evento->instituicao->endereco->estado_endereco}" :
                        'Mesmo endereço da instituição',
                    'funcionamento_instituicao' => [
                        'horario' => $diasFuncionamento,
                    ],
                ],
                'habilidades' => $evento->habilidades->map(function ($habilidade) {
                    return [
                        'id_habilidade' => $habilidade->id_habilidade,
                        'descricao_habilidade' => $habilidade->descricao_habilidade,
                        'meta' => $habilidade->pivot->meta_evento ?? null,
                        'quantidade' => $habilidade->pivot->quantidade_voluntario ?? null,
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => $eventos,
            'status' => 'success',
            'message' => 'Listagem solicitada com sucesso!',
        ], 200);
    }


    public function inscreveEvento(Request $request)
    {
        $user = Auth::user();
        $voluntario = Voluntario::where('id_usuario', $user->id)->first();

        // Valida os dados recebidos
        $validator = Validator::make($request->all(), [
            'id_evento' => 'required|integer|exists:evento,id_evento',
            'id_habilidade' => 'required|integer|exists:habilidade,id_habilidade',
        ]);

        // Retorna erros de validação, se houver
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $habilidade = Habilidade::where('id_habilidade', $request->id_habilidade)->first();
        DB::table('evento_has_habilidade')
                    ->where('id_evento', $request->id_evento)
                    ->where('id_habilidade', $habilidade->id_habilidade)
                    ->update([
                        'quantidade_voluntario' => DB::raw('quantidade_voluntario + ' . 1),
                        'updated_at' => now(),
                    ]);

        DB::table('voluntario_has_evento')->insert([
            'id_voluntario' => $voluntario->id_voluntario,
            'id_evento' => $request->id_evento,
            'habilidade_voluntario' => $habilidade->descricao_habilidade,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Inscrição registrada com sucesso!',
        ], 200);
    }
}
