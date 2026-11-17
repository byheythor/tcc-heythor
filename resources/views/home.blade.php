@extends('layouts.medilab')

@section('title', 'Home')

@section('content')
<div class="section-title">
    <h2>Bem-vindo</h2>
    <p>Acompanhe um resumo dos seus dados biomédicos cadastrados.</p>
</div>

<div id="evolucao-medicoes" class="card border-0 shadow-sm mb-4" style="scroll-margin-top: 150px;">
    <div class="card-body">
        <h5 class="card-title mb-2">Evolução das medições</h5>
        <p class="text-muted">Escolha um dado biomédico para acompanhar seus valores ao longo do tempo.</p>

        @if ($dadosBiomedicos->isNotEmpty())
            <form method="GET" action="{{ route('home') }}#evolucao-medicoes" class="row g-3 align-items-end mb-4">
                <div class="col-lg-4">
                    <label for="evolucao-dado" class="form-label">Dado biomédico</label>
                    <select id="evolucao-dado" name="dado_biomedico_id" class="form-select" required>
                        @foreach ($dadosBiomedicos as $dado)
                            <option value="{{ $dado->id }}" @selected($dadoSelecionado?->id === $dado->id)>{{ $dado->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label for="evolucao-inicio" class="form-label">Data inicial</label>
                    <input id="evolucao-inicio" type="date" name="data_inicio" class="form-control" value="{{ $filtros['data_inicio'] ?? '' }}">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label for="evolucao-fim" class="form-label">Data final</label>
                    <input id="evolucao-fim" type="date" name="data_fim" class="form-control" value="{{ $filtros['data_fim'] ?? '' }}">
                </div>
                <div class="col-lg-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                    <a href="{{ route('home', ['dado_biomedico_id' => $dadoSelecionado->id]) }}#evolucao-medicoes" class="btn btn-outline-secondary">Todo o histórico</a>
                </div>
            </form>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <h6 class="mb-0">
                    {{ $dadoSelecionado->nome }}@if ($dadoSelecionado->unidade_medida) — {{ $dadoSelecionado->unidade_medida }}@endif
                </h6>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <a href="{{ route('medicao.index', ['dado_biomedico_id' => $dadoSelecionado->id]) }}">Ver medições</a>
                    <span class="text-muted">Total no período: <strong>{{ $chartData['evolucao']['totalPeriodo'] }}</strong></span>
                </div>
            </div>

            @if ($chartData['evolucao']['naoNumericas'] > 0)
                <div class="alert alert-warning" role="status">
                    Medições sem um valor numérico representável: <strong>{{ $chartData['evolucao']['naoNumericas'] }}</strong>.
                    Esses registros continuam disponíveis na listagem e não aparecem como pontos no gráfico.
                </div>
            @endif

            @if ($chartData['evolucao']['pontos']->isNotEmpty())
                <div id="evolucao-chart-container" class="chart-container chart-container--evolution">
                    <canvas id="chartEvolucaoMedicoes" role="img" aria-label="Evolução das medições de {{ $dadoSelecionado->nome }}">
                        Consulte os valores pelo link Ver medições.
                    </canvas>
                </div>
                <div id="evolucao-chart-error" class="alert alert-info" role="status" hidden>
                    Não foi possível carregar o gráfico. Consulte os valores pelo link Ver medições.
                </div>
                <noscript><p>Ative o JavaScript para visualizar o gráfico ou consulte o link Ver medições.</p></noscript>
                <p class="small text-muted mt-2 mb-0">
                    Cada ponto representa uma medição. Passe o cursor no ponto para consultar seus detalhes.
                    @if ($chartData['evolucao']['pontos']->count() === 1)
                        Há apenas uma medição numérica no período selecionado.
                    @endif
                </p>
            @elseif ($chartData['evolucao']['totalPeriodo'] === 0)
                <div class="alert alert-info mb-0">Não há medições para este dado biomédico no período selecionado.</div>
            @else
                <div class="alert alert-info mb-0">Não há medições numéricas para representar neste período.</div>
            @endif
        @else
            <div class="alert alert-info mb-0">
                <a href="{{ route('dado_biomedico.create') }}">Cadastre um dado biomédico</a> para acompanhar os valores das medições.
            </div>
        @endif
    </div>
</div>

<div class="row gy-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                    <h5 class="card-title mb-0">Medições por dia</h5>
                    <a href="{{ route('medicao.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Nova medição
                    </a>
                </div>

                @if ($totalMedicoes > 0)
                    <p class="small text-muted mb-2">Quantidade diária por dado biomédico. Selecione uma legenda para exibir ou ocultar a linha.</p>
                    <div class="chart-container chart-container--daily">
                        <canvas id="chartMedicoesPorDia"></canvas>
                    </div>
                    <ul id="legendMedicoesPorDia" class="chart-legend" aria-label="Linhas do gráfico de medições por dia"></ul>
                @else
                    <div class="alert alert-info mb-0">
                        Registre medições para visualizar as quantidades por dia.
                    </div>
                @endif
            </div>
        </div>
    </div>

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

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Dados por categoria</h5>

                @if ($totalDadosBiomedicos > 0)
                    <div class="chart-container chart-container--category">
                        <canvas id="chartDadosPorCategoria"></canvas>
                    </div>
                    <ul id="legendDadosPorCategoria" class="chart-legend" aria-label="Categorias do gráfico"></ul>
                @else
                    <div class="alert alert-info mb-0">
                        Cadastre dados biomédicos para visualizar categorias.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Medições por dado biomédico</h5>

                @if ($totalMedicoes > 0)
                    <div class="chart-container" id="biomedical-chart-container">
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
<script src="https://cdn.jsdelivr.net/npm/luxon@3.5.0/build/global/luxon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.3.1/dist/chartjs-adapter-luxon.umd.min.js"></script>
<script>
    const chartData = {{ Illuminate\Support\Js::from($chartData) }};

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
            legend: { display: false },
            tooltip: {
                callbacks: {
                    title: (items) => wrapChartText(items[0]?.label ?? ''),
                    label: (item) => wrapChartText(`${item.dataset.label ?? item.label}: ${item.formattedValue}`)
                }
            }
        }
    };

    const compactScreen = window.matchMedia('(max-width: 575.98px)');
    const readableTimeAxis = {
        id: 'readableTimeAxis',
        beforeUpdate(chart) {
            chart.options.scales.x.ticks.maxTicksLimit = chart.width < 480 ? 4 : 8;
            chart.options.scales.x.ticks.maxRotation = 0;
            if (chart.canvas.id === 'chartMedicoesPorDia') {
                chart.options.interaction.mode = chart.width < 480 ? 'nearest' : 'index';
                chart.options.interaction.axis = chart.width < 480 ? 'xy' : 'x';
            }
        }
    };

    function wrapChartText(value, limit = 28) {
        const lines = [];
        let line = '';
        for (const word of String(value).split(/\s+/)) {
            if (line && line.length + word.length + 1 > limit) {
                lines.push(line);
                line = '';
            }
            if (word.length > limit) {
                if (line) lines.push(line);
                const chunks = word.match(new RegExp(`.{1,${limit}}`, 'gu'));
                lines.push(...chunks.slice(0, -1));
                line = chunks.at(-1);
            } else {
                line = line ? `${line} ${word}` : word;
            }
        }
        if (line) lines.push(line);
        return lines;
    }

    function renderLegend(chart, containerId, categories = false) {
        const container = document.getElementById(containerId);
        const items = categories ? chart.data.labels : chart.data.datasets.map((dataset) => dataset.label);
        container.replaceChildren();
        items.forEach((label, index) => {
            const li = document.createElement('li');
            const button = document.createElement('button');
            button.type = 'button';
            button.setAttribute('aria-pressed', 'true');
            const swatch = document.createElement('span');
            swatch.className = 'chart-legend-swatch';
            swatch.setAttribute('aria-hidden', 'true');
            swatch.style.backgroundColor = categories
                ? chart.data.datasets[0].backgroundColor[index]
                : chart.data.datasets[index].borderColor;
            const text = document.createElement('span');
            text.textContent = label;
            button.append(swatch, text);
            button.addEventListener('click', () => {
                if (categories) {
                    chart.toggleDataVisibility(index);
                } else {
                    chart.setDatasetVisibility(index, !chart.isDatasetVisible(index));
                }
                button.setAttribute('aria-pressed', String(categories ? chart.getDataVisibility(index) : chart.isDatasetVisible(index)));
                chart.update();
            });
            li.append(button);
            container.append(li);
        });
    }

    function hasChartData(values) {
        return Array.isArray(values) && values.some((value) => Number(value) > 0);
    }

    function renderEvolutionChart() {
        const canvas = document.getElementById('chartEvolucaoMedicoes');
        const data = chartData.evolucao;

        if (!canvas || data.pontos.length === 0) {
            return;
        }

        try {
            new Chart(canvas, {
                type: 'line',
                plugins: [readableTimeAxis],
                data: {
                    datasets: [{
                        label: data.nome,
                        data: data.pontos,
                        borderColor: '#1977cc',
                        backgroundColor: '#1977cc',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHitRadius: 12,
                        tension: 0,
                        fill: false
                    }]
                },
                options: {
                    ...baseOptions,
                    locale: 'pt-BR',
                    parsing: false,
                    interaction: { mode: 'nearest', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (items) => items[0]?.raw.data ?? '',
                                label: (item) => wrapChartText(`${item.raw.nome}: ${item.raw.valor}${data.unidade ? ` ${data.unidade}` : ''}`)
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            adapters: { date: { zone: data.fusoHorario, locale: 'pt-BR' } },
                            time: {
                                minUnit: 'minute',
                                displayFormats: {
                                    minute: 'dd/MM HH:mm',
                                    hour: 'dd/MM HH:mm',
                                    day: 'dd/MM/yyyy',
                                    week: 'dd/MM/yyyy',
                                    month: 'MM/yyyy',
                                    year: 'yyyy'
                                }
                            },
                            title: { display: true, text: 'Data e hora' },
                            ticks: { maxTicksLimit: 8 }
                        },
                        y: {
                            title: { display: true, text: data.unidade ? `Valor (${data.unidade})` : 'Valor' }
                        }
                    }
                }
            });
        } catch (error) {
            document.getElementById('evolucao-chart-container').hidden = true;
            document.getElementById('evolucao-chart-error').hidden = false;
        }
    }

    function renderLineChart() {
        const data = chartData.medicoesPorDia;

        if (!document.getElementById('chartMedicoesPorDia') || data.labels.length === 0 || data.datasets.length === 0) {
            return;
        }

        const chart = new Chart(document.getElementById('chartMedicoesPorDia'), {
            type: 'line',
            plugins: [readableTimeAxis],
            data: {
                labels: data.labels,
                datasets: data.datasets.map((dataset, index) => {
                    const color = palette[index] ?? `hsl(${(index * 137.508) % 360}, 65%, 42%)`;

                    return {
                        label: dataset.label,
                        data: dataset.data,
                        borderColor: color,
                        backgroundColor: color,
                        pointBackgroundColor: color,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointHitRadius: 12,
                        borderWidth: 2,
                        tension: 0,
                        fill: false
                    };
                })
            },
            options: {
                ...baseOptions,
                interaction: { mode: 'nearest', axis: 'xy', intersect: false },
                scales: {
                    x: {
                        title: { display: true, text: 'Data' },
                        ticks: {
                            callback(value) {
                                const label = this.getLabelForValue(value);
                                return this.chart.width < 480 ? label.slice(0, 5) : label;
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Quantidade de medições' },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        renderLegend(chart, 'legendMedicoesPorDia');
    }

    function renderCategoryChart() {
        const data = chartData.dadosPorCategoria;

        if (!document.getElementById('chartDadosPorCategoria') || !hasChartData(data.values)) {
            return;
        }

        const chart = new Chart(document.getElementById('chartDadosPorCategoria'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: data.labels.map((_, index) => palette[index] ?? `hsl(${(index * 137.508) % 360}, 65%, 42%)`)
                }]
            },
            options: {
                ...baseOptions,
                cutout: '62%'
            }
        });
        renderLegend(chart, 'legendDadosPorCategoria', true);
    }

    let biomedicalChart;
    function renderBiomedicalDataChart() {
        const data = chartData.medicoesPorDado;

        if (!document.getElementById('chartMedicoesPorDado') || !hasChartData(data.values)) {
            return;
        }

        biomedicalChart?.destroy();
        const horizontal = compactScreen.matches;
        document.getElementById('biomedical-chart-container').style.height = horizontal
            ? `${Math.max(320, data.labels.length * 64)}px`
            : '340px';
        biomedicalChart = new Chart(document.getElementById('chartMedicoesPorDado'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Medições',
                    data: data.values,
                    backgroundColor: data.labels.map((_, index) => palette[index] ?? `hsl(${(index * 137.508) % 360}, 65%, 42%)`),
                    borderRadius: 6
                }]
            },
            options: {
                ...baseOptions,
                indexAxis: horizontal ? 'y' : 'x',
                plugins: {
                    ...baseOptions.plugins,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    [horizontal ? 'x' : 'y']: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            maxTicksLimit: horizontal ? 5 : 8
                        }
                    },
                    [horizontal ? 'y' : 'x']: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            font: { size: 12 },
                            callback(value) {
                                return wrapChartText(this.getLabelForValue(value), horizontal ? 18 : 20);
                            }
                        }
                    }
                }
            }
        });
    }

    renderEvolutionChart();
    renderLineChart();
    renderCategoryChart();
    renderBiomedicalDataChart();
    compactScreen.addEventListener('change', renderBiomedicalDataChart);
</script>
@endsection
