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
        return view('home');
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
