@extends('layout')

@section('header')
<title>Home</title>
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Setor
                </div>
                <h2 class="page-title">
                    Visão geral
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
                            <div class="subheader">participação Ativa</div>
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
                            <div class="subheader">Doações</div>
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
                            <div class="subheader">Voluntários</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalVoluntariosMes }}</div>
                        </div>
                    </div>
                    <div id="chart-card3" class="chart-sm"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Solicitações</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalSolicitacoesMes }}</div>
                        </div>
                    </div>
                    <div id="chart-card4" class="chart-sm"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Solicitações no mês</h3>
                        <div id="chart-donut" class="chart-lg"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Doações no mês</h3>
                        <div id="chart-mentions" class="chart-lg"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="card-title">Resumo do mês</h3>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="chart-comparacao"></div>
                            </div>
                            <div class="col-md-auto">
                                <div class="divide-y divide-y-fill">
                                    <div class="px-3">
                                        <div class="text-secondary">
                                            <span class="status-dot bg-primary"></span> Doações
                                        </div>
                                        <div class="h2"></div>
                                    </div>
                                    <div class="px-3">
                                        <div class="text-secondary">
                                            <span class="status-dot bg-green"></span> Voluntários
                                        </div>
                                        <div class="h2"></div>
                                    </div>
                                </div>
                            </div>
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
    document.addEventListener("DOMContentLoaded", function() {
        // Dados fornecidos pelo backend
        const labels = @json($labelsDoacoesMes);
        const totalDoacoes = @json($totalDoacoesCard2);

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
        // Dados fornecidos pelo backend
        const labels = @json($labelsVoluntariosMes);
        const totalVoluntarios = @json($totalVoluntariosCard3);

        window.ApexCharts && (new ApexCharts(document.getElementById('chart-card3'), {
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
                name: "Voluntários diárias",
                data: totalVoluntarios // Dados dinâmicos para o gráfico
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
        // Dados fornecidos pelo backend
        const labels = @json($labelsSolicitacoesMes);
        const totalSolicitacoes = @json($totalSolicitacoesCard4);

        window.ApexCharts && (new ApexCharts(document.getElementById('chart-card4'), {
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
                position: 'left',
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-comparacao'), {
            chart: {
                type: "line",
                fontFamily: 'inherit',
                height: 288,
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
                name: "Doações",
                data: @json($totalDoacoesPorDiaMes)
            }, {
                name: "Voluntários",
                data: @json($totalVoluntariosPorDiaMes)
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
                categories: @json($labelsGrafico3),
                tooltip: {
                    enabled: false
                },
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
            colors: [tabler.getColor("primary"), tabler.getColor("green")],
            legend: {
                show: true,
            },
        })).render();
    });
</script>
@endsection
