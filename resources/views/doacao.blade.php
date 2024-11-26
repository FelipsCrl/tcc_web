@extends('layout')

@section('header')
<title>Doação</title>
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Setor
                </div>
                <h2 class="page-title">
                    Doações
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <span class="d-none"> <!--d-sm-inline-->
                        <a href="#" class="btn btn-dark">
                            New view
                        </a>
                    </span>
                    <a href="#" class="btn btn-primary d-none d-sm-inline-block" id="criarDoacaoBtn" data-bs-toggle="modal" data-bs-target="#modal-report">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Criar doação
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Doações Ativas</div>
                        </div>
                        <div class="h1 mb-3">{{ $card1 }}</div> <!-- Valor dinâmico -->
                        <div class="d-flex mb-2">
                            <div>Em relação ao mês</div>
                            <div class="ms-auto">
                                <span class="text-primary d-inline-flex align-items-center lh-1">
                                    {{$card11}}%
                                </span>
                            </div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: {{ $card11 }}%" role="progressbar" aria-valuenow="{{ $card11 }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $card11 }}% Complete">
                                <span class="visually-hidden">{{ $card11 }}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Quantidade de Doações Mensais</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalDoacoesMes }}</div> <!-- Valor dinâmico -->
                        </div>
                    </div>
                    <div id="chart-card2" class="chart-sm"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Metas Concluídas</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{ $totalMetas}}</div> <!-- Valor dinâmico -->
                        </div>
                        <div id="chart-card3" class="chart-sm"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Quantidade de Doações Hoje</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{ $card4 }}</div> <!-- Valor dinâmico -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Arrecadações</h3>
                        <div id="chart-mentions" class="chart-lg"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Tipos de recebimento e quantidade das doações (3 meses)</h3>
                        <div id="chart-donut" class="chart-lg"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cards de doações ativas</h3>
                    </div>
                    <div id="table-default" class="table-responsive">
                        <table class="table table-vcenter table-mobile-md card-table">
                            <thead>
                                <tr>
                                    <th><button class="table-sort" data-sort="sort-name">Nome</button></th>
                                    <th><button class="table-sort" data-sort="sort-date">Data e Hora limite</button></th>
                                    <th class="w-1">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="table-tbody">
                                @forelse ($doacoesAtivas as $doacao)
                                <tr>
                                    <td data-label="Nome" class="sort-name">
                                        <div class="d-flex py-1 align-items-center">
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">{{ $doacao->nome_doacao }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-date="{{ \Carbon\Carbon::parse($doacao->data_hora_limite_doacao)->format('dmY') }}" class="sort-date">
                                        <div>{{ \Carbon\Carbon::parse($doacao->data_hora_limite_doacao)->format('d/m/Y') }}</div>
                                        <div class="text-secondary">{{ \Carbon\Carbon::parse($doacao->data_hora_limite_doacao)->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="#" class="btn btn-editar"
                                               data-id="{{$doacao->id_doacao}}"
                                               data-nome="{{$doacao->nome_doacao}}"
                                               data-categorias="{{ implode(', ', $doacao->categorias->pluck('descricao_categoria')->toArray()) }}"
                                               data-metas="{{ implode(', ', $doacao->categorias->pluck('pivot.meta_doacao_categoria')->toArray()) }}"
                                               data-coleta="{{$doacao->coleta_doacao ? 'Sim' : 'Não'}}"
                                               data-observacao="{{$doacao->observacao_doacao}}"
                                               data-data-hora-limite="{{$doacao->data_hora_limite_doacao}}">
                                               Editar
                                            </a>
                                            <a href="#" class="btn btn-exibir"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-simple"
                                                data-id="{{$doacao->id_doacao}}"
                                                data-nome="{{$doacao->nome_doacao}}"
                                                data-categorias="{{ implode(', ', $doacao->categorias->pluck('descricao_categoria')->toArray()) }}"
                                                data-metas="{{ implode(', ', $doacao->categorias->pluck('pivot.meta_doacao_categoria')->toArray()) }}"
                                                data-coleta="{{$doacao->coleta_doacao ? 'Sim' : 'Não'}}"
                                                data-observacao="{{$doacao->observacao_doacao}}"
                                                data-data-hora-limite="{{$doacao->data_hora_limite_doacao}}">
                                                Exibir
                                            </a>
                                            <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="{{ $doacao->id_doacao }}">Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Nenhuma doação ativa encontrada</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        @if ($doacoesAtivas->hasPages())
                            <ul class="pagination m-0 ms-auto">
                                {{-- Paginação --}}
                                @if ($doacoesAtivas->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Anterior</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $doacoesAtivas->previousPageUrl() }}">Anterior</a>
                                    </li>
                                @endif

                                @foreach ($doacoesAtivas->getUrlRange(1, $doacoesAtivas->lastPage()) as $page => $url)
                                    @if ($page == $doacoesAtivas->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                @if ($doacoesAtivas->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $doacoesAtivas->nextPageUrl() }}">Próximo</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Próximo</a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Todas as arrecadações aprovadas hoje</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <form method="GET" action="{{ route('doacao.index') }}">
                                <div class="text-secondary">
                                    Mostrar
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" name="limit" class="form-control form-control-sm" size="3" value="{{ request('limit') }}" aria-label="Invoices count">
                                    </div>
                                    resultado(s)
                                </div>
                            </form>
                            <form method="GET" action="{{ route('doacao.index') }}" class="ms-auto text-secondary">
                                <div>
                                    Busca:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" name="search" class="form-control form-control-sm" aria-label="Search invoice" value="{{ request('search') }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <!--Pelo card ou pelo perfil-->
                                    <th>Meio de doação</th>
                                    <th>Nome do card (se houver)</th>
                                    <th>Categoria</th>
                                    <th>Doador</th>
                                    <th>Quantidade</th>
                                    <th>Meio de Recebimento</th>
                                    <th class="text-center">Ver mais</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todasDoacoes as $doacao)
                                <tr>
                                    <td><span class="text-secondary">{{ $loop->iteration }}</span></td>
                                    <td>{{ $doacao->card_doacao == 0 ? 'Doar Agora' : 'Card' }}</td>
                                    <td>{{ $doacao->nome_doacao ?? 'Não tem card'}}</td>
                                    <td>{{ $doacao->categoria_doacao ?? ''}}</td>
                                    <td>{{ $doacao->name ?? ''}}</td>
                                    <td>{{ $doacao->quantidade_doacao ?? ''}}</td>
                                    <td>{{ $doacao->data_hora_coleta == null ? 'Entrega' : 'Coleta' }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-detalhamento" href="#" data-bs-toggle="modal" data-bs-target="#modal-dados-doacao"
                                            data-meio-nome-doacao="{{ $doacao->card_doacao == 0 ? 'Doar Agora' : ('Card, ' . ($doacao->nome_doacao ?? 'Não tem card')) }}"
                                            data-categoria-quantidade="{{ ($doacao->categoria_doacao ?? '') . ', ' . ($doacao->quantidade_doacao ?? '') }}"
                                            data-meio-recebimento="{{ $doacao->data_hora_coleta == null ? 'Entrega' : 'Coleta' }}"
                                            data-nome-doador="{{ $doacao->name }}"
                                            data-telefone="{{ $doacao->telefone_contato }}"
                                            data-email="{{ $doacao->email }}">
                                            Detalhamento
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        @if ($todasDoacoes instanceof \Illuminate\Pagination\LengthAwarePaginator && $todasDoacoes->hasPages())
                            <ul class="pagination m-0 ms-auto">
                                {{-- Botão "Anterior" --}}
                                @if ($todasDoacoes->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M15 6l-6 6l6 6" />
                                            </svg> Anterior
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $todasDoacoes->previousPageUrl() }}" rel="prev">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M15 6l-6 6l6 6" />
                                            </svg> Anterior
                                        </a>
                                    </li>
                                @endif

                                {{-- Números das páginas --}}
                                @foreach ($todasDoacoes->getUrlRange(1, $todasDoacoes->lastPage()) as $page => $url)
                                    @if ($page == $todasDoacoes->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                {{-- Botão "Próximo" --}}
                                @if ($todasDoacoes->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $todasDoacoes->nextPageUrl() }}" rel="next">
                                            Próximo
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 6l6 6l-6 6" />
                                            </svg>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                            Próximo
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 6l6 6l-6 6" />
                                            </svg>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Ação do form será dinâmica: create ou edit -->
        <form id="doacaoForm" class="modal-content" method="POST">
            @csrf
            <!-- Campo escondido para o método PUT em edições -->
            <input type="hidden" id="doacaoMethod" name="_method" value="POST">

            <div class="modal-header">
                <h5 class="modal-title">Nova doação</h5> <!-- O título será alterado -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" id="doacaoNome" name="nome" placeholder="Nome do card de doação">
                </div>

                <!-- Categoria e Meta -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title d-flex align-items-center">Categoria das Doações
                                      <a class="btn btn-icon ms-3" id="addCategoriaBtn">
                                        <!-- Botão para adicionar nova categoria -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                          <line x1="12" y1="5" x2="12" y2="19"></line>
                                          <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                      </a>
                                    </h5>
                                  </div>

                                  <div id="categoriasContainer">
                                    <!-- As categorias serão preenchidas aqui -->
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outras informações -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Coleta</label>
                            <div class="form-check mt-3">
                                <input class="form-check-input" id="coleta" name="coleta" type="checkbox" value="0" onchange="toggleDay()">
                                <label class="form-check-label" for="coleta">
                                  Será possível coletar as doações
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Data e hora de expiração</label>
                            <input type="datetime-local" id="dataHoraLimite" name="data_hora_limite" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div>
                            <label class="form-label">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao" placeholder="Detalhe as informações sobre as doações" rows="3" maxlength="150"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                <button type="submit" class="btn btn-primary ms-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg> <span id="modalSubmitBtn">Criar doação</span>
                </button>
            </div>
        </form>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                </svg>
                <h3>Tem certeza?</h3>
                <div class="text-secondary">Você realmente quer excluir a doação? Lembre-se que não terá como recuperar depois!!</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Cancelar
                            </a>
                        </div>
                        <div class="col">
                            <!-- Formulário para deletar a doação -->
                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dados do card de doação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6 datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nome</div>
                            <div class="datagrid-content" data-info="nome"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Categorias e Metas</div>
                            <div class="datagrid-content" data-info="categorias"></div>
                        </div>
                    </div>
                    <div class="col-md-6 datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Coleta</div>
                            <div class="datagrid-content" data-info="coleta"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Observação</div>
                            <div class="datagrid-content" data-info="observacao"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-dados-doacao" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do doador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12 datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Meio e o nome do card (se houver)</div>
                            <div class="datagrid-content" data-info="meio-nome-doacao"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Categoria da doação e quantidade</div>
                            <div class="datagrid-content" data-info="categoria-quantidade-doacao"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Meio de recebimento</div>
                            <div class="datagrid-content" data-info="meio-recebimento-doacao"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nome do doador</div>
                            <div class="datagrid-content" data-info="nome-doador"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Telefone</div>
                            <div class="datagrid-content" data-info="telefone-doador"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Email</div>
                            <div class="datagrid-content" data-info="email-doador"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('template/dist/libs/list.js/dist/list.min.js?1692870487')}}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('modal-danger');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botão que acionou o modal
            var donationId = button.getAttribute('data-id'); // Obtém a ID da doação

            // Define a ação do formulário de exclusão com a rota correta
            var form = document.getElementById('deleteForm');
            form.action = '/doacao/' + donationId; // Define a rota de exclusão
        });
    });
</script>
<script>
    document.querySelectorAll('.btn-exibir').forEach(function(button) {
        button.addEventListener('click', function () {
            const nome = this.getAttribute('data-nome');
            const categorias = this.getAttribute('data-categorias');
            const metas = this.getAttribute('data-metas');
            const coleta = this.getAttribute('data-coleta');
            const observacao = this.getAttribute('data-observacao');

            document.querySelector('#modal-simple [data-info="nome"]').textContent = nome;

            // Formatar categorias e metas em uma lista
            const categoriasArray = categorias.split(', ');
            const metasArray = metas.split(', ');

            // Gerar HTML para as categorias e metas
            let categoriasMetasHtml = '';
            for (let i = 0; i < categoriasArray.length; i++) {
                categoriasMetasHtml += `<div>${categoriasArray[i]}: ${metasArray[i] || 'N/A'}</div>`;
            }

            document.querySelector('#modal-simple [data-info="categorias"]').innerHTML = categoriasMetasHtml;
            document.querySelector('#modal-simple [data-info="coleta"]').textContent = coleta;
            document.querySelector('#modal-simple [data-info="observacao"]').textContent = observacao;
        });
    });
</script>
<script>
    document.getElementById('criarDoacaoBtn').addEventListener('click', function () {
        // Resetar o título do modal
        document.querySelector('.modal-title').textContent = 'Nova doação';

        // Resetar o botão de submit
        document.getElementById('modalSubmitBtn').textContent = 'Criar doação';

        // Garantir que o método seja POST e a ação correta para criar
        document.getElementById('doacaoMethod').value = 'POST';
        document.getElementById('doacaoForm').action = '/doacao';  // ou sua rota de criação

        // Resetar os valores dos campos do formulário
        document.getElementById('doacaoNome').value = '';
        document.getElementById('observacao').value = '';
        document.getElementById('dataHoraLimite').value = '';
        document.getElementById('coleta').checked = false;

        // Limpar as categorias
        document.getElementById('categoriasContainer').innerHTML = '';  // Remove todas as categorias existentes
    });
    document.getElementById('addCategoriaBtn').addEventListener('click', function() {
        const container = document.getElementById('categoriasContainer');
        const exemploApagado = document.getElementById('exemploApagado');

        if (exemploApagado) {
          exemploApagado.remove();
        }

        const newCategory = document.createElement('div');
        newCategory.classList.add('mb-3', 'categoria-item', 'd-flex', 'align-items-center');
        newCategory.innerHTML = `
          <div class="input-group mb-2">
            <input type="text" class="form-control" name="categoria_nome[]" placeholder="Nome da Categoria">
            <input type="number" class="form-control" name="categoria_meta[]" placeholder="Meta  obs.: se for 0 não terá meta">
            <button type="button" class="btn btn-icon btn-outline-danger removeCategoriaBtn">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <line x1="4" y1="7" x2="20" y2="7"></line>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                <path d="M9 7v-3h6v3"></path>
              </svg>
            </button>
          </div>
        `;
        container.appendChild(newCategory);

        newCategory.querySelector('.removeCategoriaBtn').addEventListener('click', function() {
          this.closest('.categoria-item').remove();
        });
      });
      document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', function () {
            const doacaoId = this.dataset.id;
            const doacaoNome = this.dataset.nome;
            const categorias = this.dataset.categorias.split(',');
            const metas = this.dataset.metas.split(',');
            const coleta = this.dataset.coleta;
            const observacao = this.dataset.observacao;
            const dataHoraLimite = this.dataset.dataHoraLimite;

            // Alterar o título e ação do modal
            document.querySelector('.modal-title').textContent = 'Editar doação';
            document.getElementById('modalSubmitBtn').textContent = 'Salvar alterações';

            // Alterar o formulário para método PUT e ação de update
            document.getElementById('doacaoMethod').value = 'PUT';
            document.getElementById('doacaoForm').action = `/doacao/${doacaoId}`;

            // Preencher os campos do formulário
            document.getElementById('doacaoNome').value = doacaoNome;
            document.getElementById('observacao').value = observacao;
            document.getElementById('dataHoraLimite').value = dataHoraLimite;
            document.getElementById('coleta').checked = (coleta === 'Sim');

            // Limpar e preencher categorias
            const categoriasContainer = document.getElementById('categoriasContainer');
            categoriasContainer.innerHTML = ''; // Limpar categorias existentes

            categorias.forEach((categoria, index) => {
                const newCategory = document.createElement('div');
                newCategory.classList.add('mb-3', 'categoria-item', 'd-flex', 'align-items-center');
                newCategory.innerHTML = `
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="categoria_nome[]" value="${categoria.trim()}" placeholder="Nome da Categoria">
                        <input type="number" class="form-control" name="categoria_meta[]" value="${metas[index].trim()}" placeholder="Meta">
                        <button type="button" class="btn btn-icon btn-outline-danger removeCategoriaBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="4" y1="7" x2="20" y2="7"></line>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                <path d="M9 7v-3h6v3"></path>
                            </svg>
                        </button>
                    </div>
                `;
                categoriasContainer.appendChild(newCategory);
            });

            // Abrir o modal
            const myModal = new bootstrap.Modal(document.getElementById('modal-report'));
            myModal.show();
        });
    });
</script>
<script>
    function toggleDay() {
        const checkbox = document.getElementById('coleta');

        if (checkbox.checked) {
            checkbox.value = '1';
        } else {
            checkbox.value = '0';
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modal-dados-doacao');

        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botão que acionou o modal

            // Captura os dados do botão
            var meioNomeDoacao = button.getAttribute('data-meio-nome-doacao');
            var categoriaQuantidade = button.getAttribute('data-categoria-quantidade');
            var meioRecebimento = button.getAttribute('data-meio-recebimento');
            var nomeDoador = button.getAttribute('data-nome-doador');
            var telefone = button.getAttribute('data-telefone');
            var email = button.getAttribute('data-email');

            // Insere os dados no modal
            modal.querySelector('[data-info="meio-nome-doacao"]').textContent = meioNomeDoacao;
            modal.querySelector('[data-info="categoria-quantidade-doacao"]').textContent = categoriaQuantidade;
            modal.querySelector('[data-info="meio-recebimento-doacao"]').textContent = meioRecebimento;
            modal.querySelector('[data-info="nome-doador"]').textContent = nomeDoador;
            modal.querySelector('[data-info="telefone-doador"]').textContent = telefone;
            modal.querySelector('[data-info="email-doador"]').textContent = email;
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dados fornecidos pelo backend
        const labels = @json($labelsDoacoesMes);
        const totalDoacoes = @json($totalDoacoesPorDiaMes);

        window.ApexCharts && (new ApexCharts(document.getElementById('chart-card2'), {
            chart: {
                type: "area",
                fontFamily: 'inherit',
                height: 40.0,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: true
                },
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: .16,
                type: 'solid'
            },
            stroke: {
                width: 2,
                lineCap: "round",
                curve: "smooth",
            },
            series: [{
                name: "Arrecadações Diárias",
                data: totalDoacoes // Dados dinâmicos para o gráfico
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                strokeDashArray: 4,
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                type: 'datetime',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            labels: labels,
            colors: [tabler.getColor("primary")],
            legend: {
                show: false,
            },
        })).render();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dados vindos do backend (PHP) para o gráfico
        const labels = @json($labelsMetas);
        const dadosMesAtual = @json($dadosAtualMetas);
        const dadosMesAnterior = @json($dadosAnteriorMetas);

        // Inicialização do gráfico
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-card3'), {
            chart: {
                type: "line",
                fontFamily: 'inherit',
                height: 40.0,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: true
                },
            },
            fill: {
                opacity: 1,
            },
            stroke: {
                width: [2, 1],
                dashArray: [0, 3],
                lineCap: "round",
                curve: "smooth",
            },
            series: [{
                name: "Mês Atual",
                data: dadosMesAtual
            }, {
                name: "Mês Anterior",
                data: dadosMesAnterior
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                strokeDashArray: 4,
            },
            xaxis: {
                categories: labels, // Usar as datas como rótulos
                type: 'datetime',
                labels: {
                    formatter: function(value, timestamp) {
                        const date = new Date(timestamp); // Certifique-se que timestamp esteja em UTC
                        const day = String(date.getUTCDate()).padStart(2, '0'); // Usar UTC
                        const month = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"][date.getUTCMonth()]; // Usar UTC
                        const year = date.getUTCFullYear(); // Usar UTC
                        return `${day} ${month} ${year}`;
                    },
                },
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            colors: [tabler.getColor("primary"), tabler.getColor("gray-600")],
            legend: {
                show: false,
            },
        })).render();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions'), {
            chart: {
                type: "bar",
                fontFamily: 'inherit',
                height: 240,
                parentHeightOffset: 0,
                toolbar: {
                    show: false,
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                stacked: true,
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: 1,
            },
            series: [{
                name: "Card",
                data: @json($totalCard) // Total de doações por card
            }, {
                name: "Doar Agora",
                data: @json($totalDoarAgora) // Total de doações por "doar agora"
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                padding: {
                    top: -20,
                    right: 0,
                    left: -4,
                    bottom: -4
                },
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                categories: @json($labels),  // Categorias de meses
                type: 'category',
            },
            yaxis: {
                labels: {
                    padding: 4,
                    formatter: function(value) {
                        return Math.floor(value); // Exibe apenas inteiros
                    }
                },
            },
            colors: [tabler.getColor("primary"), tabler.getColor("green")],
            legend: {
                show: true,
            },
        })).render();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
         // Captura os valores de "Coleta com voluntário" e "Entrega na instituição"
        var totalColeta = {{ $tiposDeRecebimento->total_coleta }};
        var totalEntrega = {{ $tiposDeRecebimento->total_entrega }};
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-donut'), {
            series: [totalColeta, totalEntrega],
            labels: ["Coleta com voluntário", "Entrega na instituição"],
            chart: {
                width: 380,
                type: 'donut',
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        show: false
                    }
                }
            }],
            colors: [tabler.getColor("primary"), tabler.getColor("primary", 0.5)],
            legend: {
                position: 'right',
                offsetY: 0,
                height: 230,
                customLegendItems: ['Coleta','Entrega']
            },
            tooltip: {
                fillSeriesColor: false
            },
        })).render();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const list = new List('table-default', {
            sortClass: 'table-sort',
            listClass: 'table-tbody',
            valueNames: [ 'sort-name', { attr: 'data-date', name: 'sort-date' },]
        });
        })
</script>
@endsection
