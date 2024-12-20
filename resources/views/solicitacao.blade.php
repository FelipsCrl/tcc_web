@extends('layout')

@section('header')
<title>Solicitação</title>
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Setor
                </div>
                <h2 class="page-title">
                    Solicitações
                </h2>
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
                            <div class="subheader">Solicitantes</div>
                        </div>
                        <div class="h1 mb-3">{{ $card1 }}</div>
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
                            <div class="subheader">Solicitações mensais</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalSolicitacoesMes }}</div>
                        </div>
                    </div>
                    <div id="chart-card2" class="chart-sm"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Solicitantes em espera</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{ $totalSolicitacoes }}</div>
                        </div>
                        <div id="chart-card3" class="chart-sm"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Solicitações recusadas no mês</div>
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
                        <h3 class="card-title">Solicitações</h3>
                        <div id="chart-mentions" class="chart-lg"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Tipo de solicitações</h3>
                        <div id="chart-donut" class="chart-lg"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Solicitações de voluntariado recebidas para análise</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <form method="GET" action="{{ route('solicitacao.index') }}">
                                <div class="text-secondary">
                                    Mostrar
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" name="limitVoluntario" class="form-control form-control-sm" size="3" value="{{ request('limit')}}" aria-label="Invoices count">
                                    </div>
                                    resultado(s)
                                </div>
                            </form>
                            <form method="GET" action="{{ route('solicitacao.index') }}" class="ms-auto text-secondary">
                                <div>
                                    Busca:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" name="searchVoluntario" class="form-control form-control-sm" aria-label="Search invoice" value="{{ request('search') }}">
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
                                    <th>Habilidade</th>
                                    <th>Solicitante</th>
                                    <th>Data de envio</th>
                                    <th class="w-1 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($solicitacaoVoluntario as $solicitacao)
                                <tr>
                                    <td><span class="text-secondary">{{ $loop->iteration }}</span></td>
                                    <td>{{ $solicitacao->habilidade_voluntario }}</td>
                                    <td>{{ $solicitacao->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($solicitacao->updated_at_iv)->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-voluntario"
                                            data-nome="{{$solicitacao->name}}"
                                            data-telefone="{{$solicitacao->telefone_contato}}"
                                            data-habilidade="{{ $solicitacao->habilidade_voluntario }}"
                                            data-endereco="{{ $solicitacao->id_endereco
                                                ? $solicitacao->cidade_endereco . ', ' .
                                                  $solicitacao->bairro_endereco . ', ' .
                                                  $solicitacao->logradouro_endereco . ', ' .
                                                  $solicitacao->numero_endereco . ', ' .
                                                  $solicitacao->complemento_endereco . ', ' .
                                                  $solicitacao->cep_endereco . ', ' .
                                                  $solicitacao->estado_endereco
                                                : 'Não inserido' }}">
                                            Dados da solicitação
                                        </a>
                                        <a href="" class="btn btn-success"
                                            data-ids='@json(["Instituicao" => $solicitacao->id_instituicao, "Voluntario" => $solicitacao->id_voluntario, "Acao" => 1])'
                                            data-bs-toggle="modal" data-bs-target="#modal-success">
                                            Aceitar
                                        </a>
                                        <a href="" class="btn btn-danger"
                                            data-ids='@json(["Instituicao" => $solicitacao->id_instituicao, "Voluntario" => $solicitacao->id_voluntario, "Acao" => -1])'
                                            data-bs-toggle="modal" data-bs-target="#modal-danger">
                                            Recusar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        @if ($solicitacaoVoluntario instanceof \Illuminate\Pagination\LengthAwarePaginator && $solicitacaoVoluntario->hasPages())
                            <ul class="pagination m-0 ms-auto">
                                {{-- Botão "Anterior" --}}
                                @if ($solicitacaoVoluntario->onFirstPage())
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
                                        <a class="page-link" href="{{ $solicitacaoVoluntario->previousPageUrl() }}" rel="prev">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M15 6l-6 6l6 6" />
                                            </svg> Anterior
                                        </a>
                                    </li>
                                @endif

                                {{-- Números das páginas --}}
                                @foreach ($solicitacaoVoluntario->getUrlRange(1, $solicitacaoVoluntario->lastPage()) as $page => $url)
                                    @if ($page == $solicitacaoVoluntario->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                {{-- Botão "Próximo" --}}
                                @if ($solicitacaoVoluntario->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $solicitacaoVoluntario->nextPageUrl() }}" rel="next">
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Solicitações de doação recebidas para análise</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <form method="GET" action="{{ route('solicitacao.index') }}">
                                <div class="text-secondary">
                                    Mostrar
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" name="limitDoacao" class="form-control form-control-sm" size="3" value="{{ request('limit')}}" aria-label="Invoices count">
                                    </div>
                                    resultado(s)
                                </div>
                            </form>
                            <form method="GET" action="{{ route('solicitacao.index') }}" class="ms-auto text-secondary">
                                <div>
                                    Busca:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" name="searchDoacao" class="form-control form-control-sm" aria-label="Search invoice" value="{{ request('search') }}">
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
                                    <th>Categoria</th>
                                    <th>Quantidade</th>
                                    <th>Solicitante</th>
                                    <th>Data de envio</th>
                                    <th class="w-1 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($solicitacaoDoacao as $solicitacao)
                                <tr>
                                    <td><span class="text-secondary">{{ $loop->iteration }}</span></td>
                                    <td>{{ $solicitacao->categoria_doacao }}</td>
                                    <td>{{ $solicitacao->quantidade_doacao }}</td>
                                    <td>{{ $solicitacao->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($solicitacao->updated_at_vd)->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-doador"
                                            data-nome="{{$solicitacao->name}}"
                                            data-telefone="{{$solicitacao->telefone_contato}}"
                                            data-categoria="{{ $solicitacao->categoria_doacao }}"
                                            data-quantidade="{{ $solicitacao->quantidade_doacao }}"
                                            data-meio="{{ $solicitacao->data_hora_coleta == null ? 'Entrega' : 'Coleta' }}"
                                            data-endereco="{{ $solicitacao->id_endereco
                                                ? $solicitacao->cidade_endereco . ', ' .
                                                  $solicitacao->bairro_endereco . ', ' .
                                                  $solicitacao->logradouro_endereco . ', ' .
                                                  $solicitacao->numero_endereco . ', ' .
                                                  $solicitacao->complemento_endereco . ', ' .
                                                  $solicitacao->cep_endereco . ', ' .
                                                  $solicitacao->estado_endereco
                                                : 'Não inserido' }}">
                                            Dados da solicitação
                                        </a>
                                        <a href="" class="btn btn-success"
                                            data-ids='@json(["Doacao" => $solicitacao->id_doacao, "Voluntario" => $solicitacao->id_voluntario, "Acao" => 1])'
                                            data-bs-toggle="modal" data-bs-target="#modal-success">
                                            Aceitar
                                        </a>
                                        <a href="" class="btn btn-danger"
                                            data-ids='@json(["Doacao" => $solicitacao->id_doacao, "Voluntario" => $solicitacao->id_voluntario, "Acao" => -1])'
                                            data-bs-toggle="modal" data-bs-target="#modal-danger">
                                            Recusar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        @if ($solicitacaoDoacao instanceof \Illuminate\Pagination\LengthAwarePaginator && $solicitacaoDoacao->hasPages())
                            <ul class="pagination m-0 ms-auto">
                                {{-- Botão "Anterior" --}}
                                @if ($solicitacaoDoacao->onFirstPage())
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
                                        <a class="page-link" href="{{ $solicitacaoDoacao->previousPageUrl() }}" rel="prev">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M15 6l-6 6l6 6" />
                                            </svg> Anterior
                                        </a>
                                    </li>
                                @endif

                                {{-- Números das páginas --}}
                                @foreach ($solicitacaoDoacao->getUrlRange(1, $solicitacaoDoacao->lastPage()) as $page => $url)
                                    @if ($page == $solicitacaoDoacao->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                {{-- Botão "Próximo" --}}
                                @if ($solicitacaoDoacao->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $solicitacaoDoacao->nextPageUrl() }}" rel="next">
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
<div class="modal modal-blur fade" id="modal-voluntario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dados da solicitação de voluntariado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Nome</div>
                        <div class="datagrid-content" id="modal-nome">Não informado</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Telefone</div>
                        <div class="datagrid-content" id="modal-telefone">Não informado</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Habilidade</div>
                        <div class="datagrid-content" id="modal-habilidade">Não informado</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Endereço</div>
                        <div class="datagrid-content" id="modal-endereco">Não informado</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-doador" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dados da solicitação de doação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nome</div>
                                <div class="datagrid-content" id="modal-nome">--</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Telefone</div>
                                <div class="datagrid-content" id="modal-telefone">--</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Endereço</div>
                                <div class="datagrid-content" id="modal-endereco">--</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Categoria de Doação</div>
                                <div class="datagrid-content" id="modal-categoria">--</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Quantidade</div>
                                <div class="datagrid-content" id="modal-quantidade">--</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Meio de Recebimento</div>
                                <div class="datagrid-content" id="modal-meio">--</div>
                            </div>
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
<div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                <h3>Certeza?</h3>
                <div class="text-secondary">Você realmente quer aceitar a solicitação? Lembre-se que não terá como reverter a ação!!</div>
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
                            <form id="updateForm" action="/solicitacao/update" method="POST">
                                @csrf
                                @method("PUT")
                                <input type="hidden" class="data_ids" name="data_ids" value="">
                                <button type="submit" class="btn btn-success w-100">
                                    Aceitar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <h3>Certeza?</h3>
                <div class="text-secondary">Você realmente quer recusar a solicitação? Lembre-se que não terá como reverter a ação!!</div>
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
                            <form id="updateForm" action="/solicitacao/update" method="POST">
                                @csrf
                                @method("PUT")
                                <input type="hidden" class="data_ids" name="data_ids" value="">
                                <button type="submit" class="btn btn-danger w-100">
                                    Recusar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-voluntario');
        const modalNome = document.getElementById('modal-nome');
        const modalTelefone = document.getElementById('modal-telefone');
        const modalHabilidade = document.getElementById('modal-habilidade');
        const modalEndereco = document.getElementById('modal-endereco');

        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
            button.addEventListener('click', function () {
                modalNome.textContent = this.getAttribute('data-nome') || 'Não informado';
                modalTelefone.textContent = this.getAttribute('data-telefone') || 'Não informado';
                modalHabilidade.textContent = this.getAttribute('data-habilidade') || 'Não informado';
                modalEndereco.textContent = this.getAttribute('data-endereco') || 'Não informado';
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modal-doador');

        modal.addEventListener('show.bs.modal', (event) => {
            // Botão que disparou o modal
            const button = event.relatedTarget;

            // Dados do botão
            const nome = button.getAttribute('data-nome');
            const telefone = button.getAttribute('data-telefone');
            const categoria = button.getAttribute('data-categoria');
            const quantidade = button.getAttribute('data-quantidade');
            const meio = button.getAttribute('data-meio');
            const endereco = button.getAttribute('data-endereco');

            // Elementos do modal
            modal.querySelector('#modal-nome').textContent = nome || 'Não informado';
            modal.querySelector('#modal-telefone').textContent = telefone || 'Não informado';
            modal.querySelector('#modal-categoria').textContent = categoria || 'Não informado';
            modal.querySelector('#modal-quantidade').textContent = quantidade || 'Não informado';
            modal.querySelector('#modal-meio').textContent = meio || 'Não informado';
            modal.querySelector('#modal-endereco').textContent = endereco || 'Não informado';
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var updateModal = document.getElementById('modal-danger');
        updateModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // O botão que acionou o modal
            var dataIds = button.getAttribute('data-ids'); // Obtém o atributo 'data-ids' do botão

            // Seleciona o input dentro do modal com id 'data_ids'
            var formInput = updateModal.querySelector('input[name="data_ids"]');
            // Define o valor do input com o valor de dataIds
            formInput.value = dataIds;
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        var updateModal = document.getElementById('modal-success');
        updateModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // O botão que acionou o modal
            var dataIds = button.getAttribute('data-ids'); // Obtém o atributo 'data-ids' do botão

            // Seleciona o input dentro do modal com id 'data_ids'
            var formInput = updateModal.querySelector('input[name="data_ids"]');
            // Define o valor do input com o valor de dataIds
            formInput.value = dataIds;
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dados fornecidos pelo backend
        const labels = @json($labelsSolicitacoesMes);
        const totalSolicitacoes = @json($totalSolicitacoesPorDiaMes);

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
                name: "Solicitações diárias",
                data: totalSolicitacoes // Dados dinâmicos para o gráfico
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
        const labels = @json($labelsSolicitacoes);
        const dadosMesAtual = @json($dadosAtualSolicitacoes);
        const dadosMesAnterior = @json($dadosAnteriorSolicitacoes);
        console.log(dadosMesAnterior, dadosMesAtual);

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
    // @formatter:off
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
                    enabled: true
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
                name: "Voluntários",
                data: @json($voluntarioData), // Dados para a série "Voluntários"
            }, {
                name: "Doações",
                data: @json($doacaoData), // Dados para a série "Doações"
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
                categories: @json($labels), // Rótulos dos meses
                type: 'category', // Para lidar com categorias (meses)
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            colors: [tabler.getColor("primary"), tabler.getColor("green", 0.8)],
            legend: {
                show: true,
            },
        })).render();
    });
    // @formatter:on
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var totalDoacao = {{ $tipoSolicitaDoacao }};
        var totalVoluntario = {{ $tipoSolicitaVoluntario }};

        window.ApexCharts && (new ApexCharts(document.getElementById('chart-donut'), {
            series: [totalDoacao, totalVoluntario],
            labels: ["Doação", "Voluntariado"],
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
                customLegendItems: ["Doação", "Voluntariado"]
            },
            tooltip: {
                fillSeriesColor: false
            },
        })).render();
    });
</script>
@endsection
