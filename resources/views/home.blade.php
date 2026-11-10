@extends('layouts.medilab')

@section('title', 'Home')

@section('content')
<div class="section-title">
    <h2>Bem-vindo</h2>
    <p>Acompanhe um resumo dos seus dados biomédicos cadastrados.</p>
</div>

<div class="row gy-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Dados biomédicos</p>
                        <h3 class="mb-0">{{ $totalDadosBiomedicos }}</h3>
                    </div>
                    <i class="bi bi-clipboard2-pulse fs-2 text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Medições registradas</p>
                        <h3 class="mb-0">{{ $totalMedicoes }}</h3>
                    </div>
                    <i class="bi bi-graph-up-arrow fs-2 text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Última medição</p>
                        <h5 class="mb-0">
                            {{ $ultimaMedicao?->data_hora?->format('d/m/Y H:i') ?? 'Nenhuma' }}
                        </h5>
                    </div>
                    <i class="bi bi-clock-history fs-2 text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title mb-0">Medições por dia</h5>
                    <a href="{{ route('medicao.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Nova medição
                    </a>
                </div>

                @if ($totalMedicoes > 0)
                    <div style="height: 320px;">
                        <canvas id="chartMedicoesPorDia"></canvas>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Registre medições para visualizar a evolução no gráfico.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Dados por categoria</h5>

                @if ($totalDadosBiomedicos > 0)
                    <div style="height: 320px;">
                        <canvas id="chartDadosPorCategoria"></canvas>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Cadastre dados biomédicos para visualizar categorias.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Medições por dado biomédico</h5>

                @if ($totalMedicoes > 0)
                    <div style="height: 340px;">
                        <canvas id="chartMedicoesPorDado"></canvas>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        As medições registradas aparecerão agrupadas por dado biomédico.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ route('principal') }}" class="btn btn-outline-primary">Ir para o painel</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const chartData = @json($chartData);

    const palette = [
        '#1977cc',
        '#2eca6a',
        '#f6c23e',
        '#e74a3b',
        '#36b9cc',
        '#858796',
        '#6f42c1',
        '#fd7e14'
    ];

    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    boxWidth: 12,
                    usePointStyle: true
                }
            }
        }
    };

    function hasChartData(values) {
        return Array.isArray(values) && values.some((value) => Number(value) > 0);
    }

    function renderLineChart() {
        const data = chartData.medicoesPorDia;

        if (!document.getElementById('chartMedicoesPorDia') || !hasChartData(data.values)) {
            return;
        }

        new Chart(document.getElementById('chartMedicoesPorDia'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Medições',
                    data: data.values,
                    borderColor: '#1977cc',
                    backgroundColor: 'rgba(25, 119, 204, 0.12)',
                    pointBackgroundColor: '#1977cc',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                ...baseOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    function renderCategoryChart() {
        const data = chartData.dadosPorCategoria;

        if (!document.getElementById('chartDadosPorCategoria') || !hasChartData(data.values)) {
            return;
        }

        new Chart(document.getElementById('chartDadosPorCategoria'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: palette
                }]
            },
            options: {
                ...baseOptions,
                cutout: '62%'
            }
        });
    }

    function renderBiomedicalDataChart() {
        const data = chartData.medicoesPorDado;

        if (!document.getElementById('chartMedicoesPorDado') || !hasChartData(data.values)) {
            return;
        }

        new Chart(document.getElementById('chartMedicoesPorDado'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Medições',
                    data: data.values,
                    backgroundColor: '#2eca6a',
                    borderRadius: 6
                }]
            },
            options: {
                ...baseOptions,
                plugins: {
                    ...baseOptions.plugins,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    renderLineChart();
    renderCategoryChart();
    renderBiomedicalDataChart();
</script>
@endsection
