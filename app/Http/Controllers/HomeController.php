<?php

namespace App\Http\Controllers;

use App\Models\DadoBiomedico;
use App\Models\Medicao;
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
    public function index()
    {
        $pacienteId = auth()->id();

        $dadosBiomedicos = DadoBiomedico::with('categoria')
            ->withCount('medicoes')
            ->where('paciente_id', $pacienteId)
            ->orderBy('nome')
            ->get();

        $medicoes = Medicao::with('dado_biomedico')
            ->whereHas('dado_biomedico', fn ($query) => $query->where('paciente_id', $pacienteId))
            ->orderBy('data_hora')
            ->get();

        $medicoesPorDia = $medicoes
            ->groupBy(fn (Medicao $medicao) => $medicao->data_hora->format('d/m/Y'))
            ->map(fn ($grupo) => $grupo->count());

        $dadosBiomedicosComMedicoes = $dadosBiomedicos
            ->filter(fn (DadoBiomedico $dadoBiomedico) => $dadoBiomedico->medicoes_count > 0)
            ->values();

        $dadosPorCategoria = $dadosBiomedicos
            ->groupBy(fn (DadoBiomedico $dadoBiomedico) => $dadoBiomedico->categoria->nome ?? 'Sem categoria')
            ->map(fn ($grupo) => $grupo->count());

        $chartData = [
            'medicoesPorDia' => [
                'labels' => $medicoesPorDia->keys()->values(),
                'values' => $medicoesPorDia->values(),
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
