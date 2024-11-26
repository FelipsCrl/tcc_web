@extends('layout')

@section('header')
<title>Voluntário</title>
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Setor
                </div>
                <h2 class="page-title">
                    Voluntários
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
                            <div class="subheader">Voluntariado</div>
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
                            <div class="subheader">Ajuda mensal</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalVoluntariosMes }}</div>
                        </div>
                    </div>
                    <div id="chart-card2" class="chart-sm"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Voluntários nos dois meses</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{ $totalVoluntariados }}</div>
                        </div>
                        <div id="chart-card3" class="chart-sm"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Ajuda recebida hoje</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{ $totalVoluntariosHoje }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Habilidades dos voluntários no ano</h3>
                        <div id="chart-habilidades" class="chart-lg"></div>
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
        const labels = @json($labelsVoluntariosMes);
        const totalVoluntarios = @json($totalVoluntariosPorDiaMes);

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
        const labels = @json($labelsVoluntariados);
        const dadosMesAtual = @json($dadosAtualVoluntariados);
        const dadosMesAnterior = @json($dadosAnteriorVoluntariados);

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
        const chartData = @json(array_column($dados, 'percentual'));
        const categories = @json(array_column($dados, 'habilidade'));

        window.ApexCharts && (new ApexCharts(document.getElementById('chart-habilidades'), {
            series: [{
                name: 'Habilidade',
                data: chartData
            }],
            chart: {
                type: 'bar',
                height: 450
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 7,
                    distributed: true,
                    barHeight: '70%'
                }
            },
            colors: ['#004ECC'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val + "%";
                },
                style: {
                    fontSize: '14px',
                    fontFamily: 'Arial',
                },
            },
            xaxis: {
                categories: categories,
                labels: {
                    style: {
                        colors: '#000',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '14px'
                    }
                }
            },
            legend: {
                show: false,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + "%";
                    }
                }
            }
        })).render();
    });
</script>
@endsection
