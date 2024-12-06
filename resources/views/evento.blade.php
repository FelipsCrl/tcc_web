@extends('layout')

@section('header')
<title>Evento</title>
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Setor
                </div>
                <h2 class="page-title">
                    Eventos
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
                    <a href="#" id="criarEventoBtn" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>                                    Criar evento
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
                            <div class="subheader">Eventos Ativos</div>
                        </div>
                        <div class="h1 mb-3">{{ $card1 }}</div>
                        <div class="d-flex mb-2">
                            <div>Em relação ao mês</div>
                            <div class="ms-auto">
                                <span class="text-primary d-inline-flex align-items-center lh-1">
                                    {{ $card11 }}%
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
                            <div class="subheader">Quantidade de ajuda mensal</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalAjudasMes }}</div>
                        </div>
                    </div>
                    <div id="chart-card2" class="chart-sm"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Metas Concluidas</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{ $totalMetas }}</div>
                        </div>
                        <div id="chart-card3" class="chart-sm"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Quantidade de ajuda hoje</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{ $card4 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Quantidade de voluntários</h3>
                        <div id="chart-mentions" class="chart-lg"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Quantidade de metas atingidas</h3>
                        <div id="chart-completion-tasks-2"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cards de eventos ativos</h3>
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
                                @forelse ($eventosAtivos as $evento)
                                <tr>
                                    <td data-label="Nome" class="sort-name">
                                        <div class="d-flex py-1 align-items-center">
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">{{ $evento->nome_evento }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-date="{{ \Carbon\Carbon::parse($evento->data_hora_limite_evento)->format('YmdHis') }}" class="sort-date">
                                        <div>{{ \Carbon\Carbon::parse($evento->data_hora_limite_evento)->format('d/m/Y') }}</div>
                                        <div class="text-secondary">{{ \Carbon\Carbon::parse($evento->data_hora_limite_evento)->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">

                                            <a class="btn" id="edit-btn" data-bs-toggle="modal" data-bs-target="#modal-edit-evento"
                                                data-id="{{$evento->id_evento}}"
                                                data-nome="{{$evento->nome_evento}}"
                                                data-descricao="{{$evento->descricao_evento}}"
                                                data-habilidades="{{ implode(', ', $evento->habilidades->pluck('descricao_habilidade')->toArray()) }}"
                                                data-metas="{{ implode(', ', $evento->habilidades->pluck('pivot.meta_evento')->toArray()) }}"
                                                data-endereco="{{ $evento->endereco
                                                    ? $evento->endereco->cidade_endereco . ', ' .
                                                      $evento->endereco->bairro_endereco . ', ' .
                                                      $evento->endereco->logradouro_endereco . ', ' .
                                                      $evento->endereco->numero_endereco . ', ' .
                                                      $evento->endereco->complemento_endereco . ', ' .
                                                      $evento->endereco->cep_endereco . ', ' .
                                                      $evento->endereco->estado_endereco
                                                    : '' }}"
                                                data-data-hora-limite ="{{ \Carbon\Carbon::parse($evento->data_hora_limite_evento)->format('d/m/Y H:i') }}"
                                                data-data-hora-evento="{{ \Carbon\Carbon::parse($evento->data_hora_evento)->format('d/m/Y H:i') }}">
                                                Editar
                                            </a>
                                            <a href="#" class="btn btn-exibir"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-simple"
                                                data-id="{{$evento->id_evento}}"
                                                data-nome="{{$evento->nome_evento}}"
                                                data-descricao="{{$evento->descricao_evento}}"
                                                data-habilidades="{{ implode(', ', $evento->habilidades->pluck('descricao_habilidade')->toArray()) }}"
                                                data-metas="{{ implode(', ', $evento->habilidades->pluck('pivot.meta_evento')->toArray()) }}"
                                                data-endereco="{{ $evento->endereco
                                                    ? $evento->endereco->cidade_endereco . ', ' .
                                                      $evento->endereco->bairro_endereco . ', ' .
                                                      $evento->endereco->logradouro_endereco . ', ' .
                                                      $evento->endereco->numero_endereco . ', ' .
                                                      $evento->endereco->complemento_endereco . ', ' .
                                                      $evento->endereco->cep_endereco . ', ' .
                                                      $evento->endereco->estado_endereco
                                                    : 'Mesmo local da instituição' }}"
                                                data-data-hora-evento="{{ \Carbon\Carbon::parse($evento->data_hora_evento)->format('d/m/Y H:i') }}">
                                                Exibir
                                            </a>
                                            <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="{{ $evento->id_evento }}">Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Nenhum evento ativo encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        @if ($eventosAtivos->hasPages())
                            <ul class="pagination m-0 ms-auto">
                                {{-- Paginação --}}
                                @if ($eventosAtivos->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Anterior</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $eventosAtivos->previousPageUrl() }}">Anterior</a>
                                    </li>
                                @endif

                                @foreach ($eventosAtivos->getUrlRange(1, $eventosAtivos->lastPage()) as $page => $url)
                                    @if ($page == $eventosAtivos->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                @if ($eventosAtivos->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $eventosAtivos->nextPageUrl() }}">Próximo</a>
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
                        <h3 class="card-title">Todos os volutários aprovados hoje</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <form method="GET" action="{{ route('evento.index') }}">
                                <div class="text-secondary">
                                    Mostrar
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" name="limit" class="form-control form-control-sm" size="3" value="{{ request('limit')}}" aria-label="Invoices count">
                                    </div>
                                    resultado(s)
                                </div>
                            </form>
                            <form method="GET" action="{{ route('evento.index') }}" class="ms-auto text-secondary">
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
                                    <th class="w-1">No.</th>
                                    <th>Nome do card</th>
                                    <th>Habilidade</th>
                                    <th>Voluntariado</th>
                                    <th class="text-center">Ver mais</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todosVoluntarios as $voluntario)
                                <tr>
                                    <td><span class="text-secondary">{{ $loop->iteration }}</span></td>
                                    <td>{{ $voluntario->nome_evento}}</td>
                                    <td>{{ $voluntario->habilidade_voluntario}}</td>
                                    <td>{{ $voluntario->name }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-detalhamento" href="#" data-bs-toggle="modal" data-bs-target="#modal-dados-voluntario"
                                            data-nome-voluntario="{{ $voluntario->name }}"
                                            data-nome-evento="{{ $voluntario->nome_evento }}"
                                            data-habilidade="{{ $voluntario->habilidade_voluntario }}"
                                            data-telefone="{{ $voluntario->telefone_contato }}"
                                            data-email="{{ $voluntario->email }}">
                                            Detalhamento
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        @if ($todosVoluntarios instanceof \Illuminate\Pagination\LengthAwarePaginator && $todosVoluntarios->hasPages())
                            <ul class="pagination m-0 ms-auto">
                                {{-- Botão "Anterior" --}}
                                @if ($todosVoluntarios->onFirstPage())
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
                                        <a class="page-link" href="{{ $todosVoluntarios->previousPageUrl() }}" rel="prev">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M15 6l-6 6l6 6" />
                                            </svg> Anterior
                                        </a>
                                    </li>
                                @endif

                                {{-- Números das páginas --}}
                                @foreach ($todosVoluntarios->getUrlRange(1, $todosVoluntarios->lastPage()) as $page => $url)
                                    @if ($page == $todosVoluntarios->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                {{-- Botão "Próximo" --}}
                                @if ($todosVoluntarios->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $todosVoluntarios->nextPageUrl() }}" rel="next">
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
        <form action="{{route('evento.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" placeholder="Nome do card de evento">
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <div class="form-label">Selecione as habilidades para o evento</div>
                                <select class="select-states form-select" id="select-habilidades" multiple>
                                    <option value="">Selecione uma habilidade</option>
                                    @foreach ($habilidades as $habilidade)
                                        <option value="{{ \Illuminate\Support\Str::slug($habilidade->descricao_habilidade, '-') }}">
                                            {{ $habilidade->descricao_habilidade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Habilidades Selecionadas</h5>
                                    <div id="habilidades-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Local do evento</label>
                                <div class="form-check mt-3">
                                    <label class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkEndereco" name="endereco">
                                        <span class="form-check-label">
                                            Endereço
                                        </span>
                                        <span class="form-check-description">
                                            Diferente do local da instituição
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="formEndereco" style="display: none;">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Insira o endereço do local:</h5>

                                    <div class="row g-3">
                                        <!-- CEP e Estado -->
                                        <div class="col-md-4">
                                            <div class="form-label">CEP</div>
                                            <input type="text" id="cep" class="form-control" name="cep" onblur="buscarEndereco()" placeholder="CEP" value="" maxlength="9">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label">Estado</div>
                                            <input type="text" id="estado"class="form-control" name="estado" placeholder="Sigla" maxlength="2" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label">Cidade</div>
                                            <input type="text" id="cidade" class="form-control" name="cidade" placeholder="Cidade" value="">
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <!-- Rua e Número -->
                                        <div class="col-md-8">
                                            <div class="form-label">Rua</div>
                                            <input type="text" id="rua" class="form-control" name="rua" placeholder="Rua" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label">Número</div>
                                            <input type="text" id="numero" class="form-control" name="numero" placeholder="Número" value="">
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <!-- Bairro e Complemento -->
                                        <div class="col-md-6">
                                            <div class="form-label">Bairro</div>
                                            <input type="text" id="bairro" class="form-control" name="bairro" placeholder="Bairro" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label">Complemento</div>
                                            <input type="text" id="complemento" class="form-control" name="complemento" placeholder="Complemento" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Data e hora do evento</label>
                                <input type="datetime-local" class="form-control" name="data_hora_evento">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Data e hora de expiração</label>
                                <input type="datetime-local" class="form-control" name="data_hora_limite">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label class="form-label">Descrição</label>
                                <textarea class="form-control" placeholder="Detalhe as informações sobre as doações" rows="3" maxlength="150" name="descricao"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg> Criar evento
                    </button>
                </div>
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
                <div class="text-secondary">Você realmente quer excluir o evento? Lembre-se que não terá como recuperar depois!!</div>
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
                <h5 class="modal-title">Detalhes do Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12 datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nome do Evento</div>
                            <div class="datagrid-content" data-info="nome"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Data e Hora do Evento</div>
                            <div class="datagrid-content" data-info="data-hora-evento"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Habilidades e Metas</div>
                            <div class="datagrid-content" data-info="habilidades"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Descrição</div>
                            <div class="datagrid-content" data-info="descricao"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Endereço</div>
                            <div class="datagrid-content" data-info="endereco"></div>
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
<div class="modal modal-blur fade" id="modal-dados-voluntario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do voluntariado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12 datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nome do Evento</div>
                            <div class="datagrid-content" data-info="nome-evento"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Habilidade</div>
                            <div class="datagrid-content" data-info="habilidade-voluntario"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nome do voluntário</div>
                            <div class="datagrid-content" data-info="nome-voluntario"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Telefone</div>
                            <div class="datagrid-content" data-info="telefone-voluntario"></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Email</div>
                            <div class="datagrid-content" data-info="email-voluntario"></div>
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
<div class="modal modal-blur fade" id="modal-edit-evento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nome do Evento -->
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" placeholder="Nome do evento" value="">
                    </div>

                    <!-- Habilidades Selecionadas -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Habilidades para o evento</label>
                                <select class="form-select" id="seleciona-habilidades" multiple>
                                    <option value="">Selecione uma habilidade</option>
                                    @foreach ($habilidades as $habilidade)
                                        <option value="{{ \Illuminate\Support\Str::slug($habilidade->descricao_habilidade, '-') }}">
                                            {{ $habilidade->descricao_habilidade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Habilidades Selecionadas Container -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Habilidades Selecionadas</h5>
                                    <div id="habilidades-div"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço do Evento -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Local do evento</label>
                                <div class="form-check mt-3">
                                    <label class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkEndereco" name="endereco">
                                        <span class="form-check-label">Endereço</span>
                                        <span class="form-check-description">Diferente do local da instituição</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Endereço Container -->
                        <div class="col-lg-12" id="formEndereco" style="">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Insira o endereço do local:</h5>

                                    <!-- CEP, Estado, Cidade -->
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">CEP</label>
                                            <input type="text" id="cep" class="form-control" name="cep" value="" maxlength="9" onblur="buscarEndereco()">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Estado</label>
                                            <input type="text" id="estado" class="form-control" name="estado" value="" maxlength="2">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Cidade</label>
                                            <input type="text" id="cidade" class="form-control" name="cidade" value="">
                                        </div>
                                    </div>

                                    <!-- Rua, Número, Bairro, Complemento -->
                                    <div class="row g-3 mt-2">
                                        <div class="col-md-8">
                                            <label class="form-label">Rua</label>
                                            <input type="text" id="rua" class="form-control" name="rua" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Número</label>
                                            <input type="text" id="numero" class="form-control" name="numero" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bairro</label>
                                            <input type="text" id="bairro" class="form-control" name="bairro" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Complemento</label>
                                            <input type="text" id="complemento" class="form-control" name="complemento" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data e Hora do Evento -->
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label">Data e hora do evento</label>
                            <input type="datetime-local" class="form-control" name="data_hora_evento" value="">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Data e hora de expiração</label>
                            <input type="datetime-local" class="form-control" name="data_hora_limite" value="">
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" placeholder="Detalhe as informações sobre as doações" rows="3" maxlength="150" name="descricao"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer com Botões -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar alterações</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<!-- Libs JS -->
<script src="{{asset('template/dist/libs/list.js/dist/list.min.js?1692870487')}}" defer></script>
<script src="{{asset('template/dist/libs/litepicker/dist/litepicker.js?1692870487')}}" defer></script>
<script src="{{asset('template/dist/libs/tom-select/dist/js/tom-select.base.min.js?1692870487')}}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('modal-danger');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botão que acionou o modal
            var eventoId = button.getAttribute('data-id'); // Obtém a ID do evento

            // Define a ação do formulário de exclusão com a rota correta
            var form = document.getElementById('deleteForm');
            form.action = '/evento/' + eventoId; // Define a rota de exclusão
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectHabilidades = document.getElementById('seleciona-habilidades');
        const container = document.getElementById('habilidades-div');
        let tomSelectInstance = selectHabilidades.tomSelect || null;

        // Função para criar input HTML
        const criarInput = (habilidade, meta) => `
            <div class="mb-3 habilidade-group" data-habilidade="${habilidade}">
                <label class="form-label">${habilidade}</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="meta_evento[]" value='${meta}' placeholder="Meta para ${habilidade}">
                    <input type="hidden" class="form-control" name="habilidade_nome[]" value='${habilidade.replace(/\s+/g, '-')}' >
                </div>
            </div>`;

        // Função para popular habilidades no modal
        const populateHabilidades = (habilidadesSelecionadas, metasSelecionadas) => {
            const habilidadesArray = habilidadesSelecionadas.split(', ').map(h => h.trim());
            const metasArray = metasSelecionadas.split(', ');

            //container.innerHTML = ''; // Limpa o container de inputs

            habilidadesArray.forEach((habilidade, index) => {
                container.innerHTML += criarInput(habilidade, metasArray[index] || '');
            });

            // Atualiza o TomSelect com as habilidades selecionadas
            if (tomSelectInstance) {
                tomSelectInstance.clear();
                habilidadesArray.forEach(habilidade => {
                    const option = Array.from(selectHabilidades.options).find(opt => opt.text === habilidade);
                    if (option) tomSelectInstance.addItem(option.value);
                });
            } else {
                console.error("TomSelect instance is not initialized");
            }
        };

        // Inicializa o TomSelect se ainda não foi inicializado
        if (!tomSelectInstance && window.TomSelect) {
            tomSelectInstance = new TomSelect(selectHabilidades, {
                copyClassesToDropdown: false,
                controlInput: '<input>',
                onItemAdd: function(value, item) {
                    const habilidade = item.textContent.trim();
                    if (!container.querySelector(`[data-habilidade="${habilidade}"]`)) {
                        container.innerHTML += criarInput(habilidade, '');
                    }
                },
                onItemRemove: function(value) {
                    const habilidade = Array.from(selectHabilidades.options).find(opt => opt.value === value).text.trim();
                    const inputGroup = container.querySelector(`[data-habilidade="${habilidade}"]`);
                    if (inputGroup) {
                        inputGroup.remove();
                    }
                },
            });
        }

        // Configura os botões de edição para carregar as habilidades e informações do evento no modal
        const editarBtns = document.querySelectorAll('#edit-btn');
        editarBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');
                const descricao = this.getAttribute('data-descricao');
                const endereco = this.getAttribute('data-endereco');
                const dataHoraEvento = formatDateTime(this.getAttribute('data-data-hora-evento'));
                const dataHoraLimite = formatDateTime(this.getAttribute('data-data-hora-limite'));

                const modal = document.getElementById('modal-edit-evento');

                modal.querySelector('input[name="nome"]').value = nome;
                modal.querySelector('textarea[name="descricao"]').value = descricao;
                modal.querySelector('input[name="data_hora_evento"]').value = dataHoraEvento;
                modal.querySelector('input[name="data_hora_limite"]').value = dataHoraLimite;
                document.getElementById('form-edit').action = `/evento/${id}`;

                const checkEndereco = modal.querySelector('#checkEndereco');
                const formEndereco = modal.querySelector('#formEndereco');

                if (endereco) {
                    checkEndereco.checked = true;
                    formEndereco.style.display = 'block';
                    const enderecoArray = endereco.split(', ');
                    modal.querySelector('input[name="cep"]').value = enderecoArray[5] || '';
                    modal.querySelector('input[name="estado"]').value = enderecoArray[6] || '';
                    modal.querySelector('input[name="cidade"]').value = enderecoArray[0] || '';
                    modal.querySelector('input[name="rua"]').value = enderecoArray[2] || '';
                    modal.querySelector('input[name="numero"]').value = enderecoArray[3] || '';
                    modal.querySelector('input[name="bairro"]').value = enderecoArray[1] || '';
                    modal.querySelector('input[name="complemento"]').value = enderecoArray[4] || '';
                } else {
                    checkEndereco.checked = false;
                    formEndereco.style.display = 'none';
                }
            });
        });

        function formatDateTime(dateStr) {
            const [day, month, yearAndTime] = dateStr.split('/');
            const [year, time] = yearAndTime.split(' ');
            return `${year}-${month}-${day}T${time}`;
        }

        const modalEditEvento = document.getElementById('modal-edit-evento');
        modalEditEvento.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const habilidades = button.getAttribute('data-habilidades');
            const metas = button.getAttribute('data-metas');
            populateHabilidades(habilidades, metas);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectHabilidades = document.getElementById('select-habilidades');
        const container = document.getElementById('habilidades-container');

        // Função para criar o input de habilidade
        const criarInput = (habilidade) => `
            <div class="mb-3 habilidade-group">
                <label class="form-label">${habilidade}</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="meta_evento[]" value='' placeholder="Meta para ${habilidade}">
                    <input type="hidden" class="form-control" name="habilidade_nome[]" value= '${habilidade.replace(/\s+/g, '-')}' >
                </div>
            </div>`;

        // Evento de alteração no select
        selectHabilidades.addEventListener('change', () => {
            container.innerHTML = '';  // Limpar os inputs antigos

            // Adicionar inputs para as habilidades selecionadas
            Array.from(selectHabilidades.selectedOptions).forEach(option => {
                container.innerHTML += criarInput(option.text);
            });
        });
    });
</script>
<script>
    // Função para formatar o CEP
    document.getElementById('cep').addEventListener('input', function (e) {
        let x = e.target.value.replace(/\D/g, '');
        x = x.replace(/^(\d{5})(\d)/, '$1-$2');
        e.target.value = x;
    });
</script>
<script>
    function buscarEndereco() {
        const cep = document.getElementById('cep').value.replace(/\D/g, '');

        // Consulta a API ViaCEP
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    // Preencher os campos do endereço com os dados da API
                    document.getElementById('rua').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            })
            .catch(() => {
                //alert('Erro ao buscar o CEP!');
            });
    }
</script>
<script>
    document.getElementById('checkEndereco').addEventListener('change', function() {
        var formEndereco = document.getElementById('formEndereco');
        if (this.checked) {
            formEndereco.style.display = 'block';
        } else {
            formEndereco.style.display = 'none';
        }
    });
</script>
<script>
    document.querySelectorAll('.btn-exibir').forEach(function(button) {
        button.addEventListener('click', function () {
            const nome = this.getAttribute('data-nome');
            const descricao = this.getAttribute('data-descricao');
            const habilidades = this.getAttribute('data-habilidades');
            const metas = this.getAttribute('data-metas');
            const dataHoraEvento = this.getAttribute('data-data-hora-evento');
            const endereco = this.getAttribute('data-endereco');

            document.querySelector('#modal-simple [data-info="nome"]').textContent = nome;
            document.querySelector('#modal-simple [data-info="descricao"]').textContent = descricao;
            document.querySelector('#modal-simple [data-info="endereco"]').textContent = endereco;

            // Exibindo habilidades e metas
            const habilidadesArray = habilidades.split(', ');
            const metasArray = metas.split(', ');
            let habilidadesMetasHtml = '';

            for (let i = 0; i < habilidadesArray.length; i++) {
                habilidadesMetasHtml += `<div>${habilidadesArray[i]}: ${metasArray[i] || 'N/A'}</div>`;
            }

            document.querySelector('#modal-simple [data-info="habilidades"]').innerHTML = habilidadesMetasHtml;

            document.querySelector('#modal-simple [data-info="data-hora-evento"]').textContent = dataHoraEvento;
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectElements = document.querySelectorAll('.select-states');

        selectElements.forEach(function(selectElement) {
            if (!selectElement.tomSelect && window.TomSelect) {
                new TomSelect(selectElement, {
                    copyClassesToDropdown: false,
                    controlInput: '<input>',
                    render: {
                        item: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                        option: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                        no_results: function(data, escape) {
                            return '<div class="no-results">Nenhum resultado encontrado para "' + escape(data.input) + '"</div>';
                        },
                    },
                });
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modal-dados-voluntario');

        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botão que acionou o modal

            // Captura os dados do botão
            var nomeVoluntario = button.getAttribute('data-nome-voluntario');
            var nomeEvento = button.getAttribute('data-nome-evento');
            var telefone = button.getAttribute('data-telefone');
            var habilidade = button.getAttribute('data-habilidade');
            var email = button.getAttribute('data-email');

            // Insere os dados no modal
            modal.querySelector('[data-info="nome-voluntario"]').textContent = nomeVoluntario;
            modal.querySelector('[data-info="nome-evento"]').textContent = nomeEvento;
            modal.querySelector('[data-info="habilidade-voluntario"]').textContent = habilidade;
            modal.querySelector('[data-info="telefone-voluntario"]').textContent = telefone;
            modal.querySelector('[data-info="email-voluntario"]').textContent = email;
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dados fornecidos pelo backend
        const labels = @json($labelsAjudasMes);
        const totalAjudas = @json($totalAjudasPorDiaMes);

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
                name: "Ajudas Diárias",
                data: totalAjudas // Dados dinâmicos para o gráfico
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
                    easing: '   ',
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
                name: "Total Ajudas",
                data: @json($totalAjudas) // Total de ajudas por mês do backend
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
                categories: @json($labels),  // Categorias de meses fixos
                type: 'category',
            },
            yaxis: {
                labels: {
                    padding: 4,
                    formatter: function (value) {
                        return Math.floor(value); // Exibe apenas inteiros
                    }
                },
            },
            colors: [tabler.getColor("primary")],
            legend: {
                show: false,
            },
        })).render();
    });
</script>
<script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function() {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-completion-tasks-2'), {
            chart: {
                type: "line",
                fontFamily: 'inherit',
                height: 240,
                parentHeightOffset: 0,
                toolbar: {
                    show: false,
                },
                animations: {
                    enabled: true
                },
            },
            fill: {
                opacity: 1,
            },
            stroke: {
                width: 2,
                lineCap: "round",
                curve: "smooth",
            },
            series: [{
                name: "Metas completas",
                data: @json($totalMetasAno)
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
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                categories: @json($labelsMetasAno),  // Categorias de meses fixos
                type: 'category',
            },
            yaxis: {
                labels: {
                    padding: 4,
                    formatter: function (value) {
                        return Math.floor(value); // Exibe apenas inteiros
                    }
                },
            },
            colors: [tabler.getColor("primary")],
            legend: {
                show: false,
            },
        })).render();
    });
    // @formatter:on
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
