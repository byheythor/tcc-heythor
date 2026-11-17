<?php

namespace App\Http\Controllers;

use App\Models\DadoBiomedico;
use App\Models\Medicao;
use App\Support\MeasurementValue;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'dado_biomedico_id' => ['nullable', 'integer', 'min:1'],
            'data_inicio' => ['nullable', 'date_format:Y-m-d'],
            'data_fim' => array_filter([
                'nullable', 'date_format:Y-m-d',
                $request->filled('data_inicio') ? 'after_or_equal:data_inicio' : null,
            ]),
        ], [
            'dado_biomedico_id.integer' => 'Selecione um dado biomédico válido.',
            'dado_biomedico_id.min' => 'Selecione um dado biomédico válido.',
            'data_inicio.date_format' => 'Informe uma data inicial válida.',
            'data_fim.date_format' => 'Informe uma data final válida.',
            'data_fim.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
        ]);

        $pacienteId = auth()->id();

        $dadosBiomedicos = DadoBiomedico::with('categoria')
            ->withCount('medicoes')
            ->where('paciente_id', $pacienteId)
            ->orderBy('nome')
            ->get();

        if ($request->filled('dado_biomedico_id')) {
            $dadoSelecionado = $dadosBiomedicos->firstWhere('id', $request->integer('dado_biomedico_id'));
            abort_unless($dadoSelecionado, 404);
        } else {
            $dadoSelecionado = $dadosBiomedicos->first(fn ($dado) => $dado->medicoes_count > 0)
                ?? $dadosBiomedicos->first();
        }

        $medicoes = Medicao::with('dado_biomedico')
            ->whereHas('dado_biomedico', fn ($query) => $query->where('paciente_id', $pacienteId))
            ->orderBy('data_hora')
            ->orderBy('id')
            ->get();

        $medicoesDoPeriodo = $medicoes->filter(function (Medicao $medicao) use ($dadoSelecionado, $filtros) {
            $data = $medicao->data_hora->format('Y-m-d');

            return $dadoSelecionado && (int) $medicao->dado_biomedico_id === (int) $dadoSelecionado->id
                && (empty($filtros['data_inicio']) || $data >= $filtros['data_inicio'])
                && (empty($filtros['data_fim']) || $data <= $filtros['data_fim']);
        });

        $pontos = $medicoesDoPeriodo->map(fn (Medicao $medicao) => [
            'x' => $medicao->data_hora->getTimestamp() * 1000,
            'y' => MeasurementValue::toNumber($medicao->valor),
            'nome' => $medicao->nome,
            'valor' => $medicao->valor,
            'data' => $medicao->data_hora->format('d/m/Y H:i:s'),
        ])->filter(fn ($ponto) => $ponto['y'] !== null)->values();

        $medicoesPorDia = $medicoes
            ->groupBy(fn (Medicao $medicao) => $medicao->data_hora->format('d/m/Y'))
            ->map(fn ($grupo) => $grupo->countBy('dado_biomedico_id'));

        $seriesPorDia = $dadosBiomedicos->map(fn (DadoBiomedico $dado) => [
            'id' => $dado->id,
            'label' => $dado->nome,
            'data' => $medicoesPorDia->map(fn ($contagens) => $contagens->get($dado->id, 0))->values(),
        ])->values();

        $dadosBiomedicosComMedicoes = $dadosBiomedicos
            ->filter(fn (DadoBiomedico $dadoBiomedico) => $dadoBiomedico->medicoes_count > 0)
            ->values();

        $dadosPorCategoria = $dadosBiomedicos
            ->groupBy(fn (DadoBiomedico $dadoBiomedico) => $dadoBiomedico->categoria->nome ?? 'Sem categoria')
            ->map(fn ($grupo) => $grupo->count());

        $chartData = [
            'evolucao' => [
                'nome' => $dadoSelecionado?->nome,
                'unidade' => $dadoSelecionado?->unidade_medida,
                'fusoHorario' => config('app.timezone'),
                'pontos' => $pontos,
                'totalPeriodo' => $medicoesDoPeriodo->count(),
                'naoNumericas' => $medicoesDoPeriodo->count() - $pontos->count(),
            ],
            'medicoesPorDia' => [
                'labels' => $medicoesPorDia->keys()->values(),
                'datasets' => $seriesPorDia,
            ],
            'medicoesPorDado' => [
                'labels' => $dadosBiomedicosComMedicoes->pluck('nome'),
                'values' => $dadosBiomedicosComMedicoes->pluck('medicoes_count'),
            ],
            'dadosPorCategoria' => [
                'labels' => $dadosPorCategoria->keys()->values(),
                'values' => $dadosPorCategoria->values(),
            ],
        ];

        return view('home', [
            'chartData' => $chartData,
            'dadosBiomedicos' => $dadosBiomedicos,
            'dadoSelecionado' => $dadoSelecionado,
            'filtros' => $filtros,
            'totalDadosBiomedicos' => $dadosBiomedicos->count(),
            'totalMedicoes' => $medicoes->count(),
            'ultimaMedicao' => $medicoes->last(),
        ]);
    }

    public function principal()
    {
        $dadosBiomedicos = DadoBiomedico::where('paciente_id', auth()->id())->get();
        $medicoesRecentes = Medicao::with('dado_biomedico')
            ->whereHas('dado_biomedico', fn ($query) => $query->where('paciente_id', auth()->id()))
            ->orderByDesc('data_hora')
            ->paginate(8)
            ->withQueryString();

        return view('principal', compact('dadosBiomedicos', 'medicoesRecentes'));
    }
}
