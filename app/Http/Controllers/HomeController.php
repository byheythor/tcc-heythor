<?php

namespace App\Http\Controllers;

use App\Models\DadoBiomedico;
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

        return view('principal', compact('dadosBiomedicos'));
    }
}
