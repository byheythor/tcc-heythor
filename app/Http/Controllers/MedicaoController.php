<?php

namespace App\Http\Controllers;

use App\Models\DadoBiomedico;
use App\Models\Medicao;
use Illuminate\Http\Request;

class MedicaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicao::with('dado_biomedico')->whereHas('dado_biomedico', function ($q) {
            $q->where('paciente_id', auth()->id());
        });

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('nome', 'like', '%' . $term . '%')
                    ->orWhere('valor', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('dado_biomedico_id')) {
            $query->where('dado_biomedico_id', $request->integer('dado_biomedico_id'));
        }

        $medicoes = $query->orderByDesc('data_hora')->paginate(10)->withQueryString();
        $dadosBiomedicos = DadoBiomedico::where('paciente_id', auth()->id())->get();

        return view('medicao.index', compact('medicoes', 'dadosBiomedicos'));
    }

    public function create()
    {
        $dadosBiomedicos = DadoBiomedico::where('paciente_id', auth()->id())->get();
        return view('medicao.create', compact('dadosBiomedicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor' => ['required', 'string', 'max:45'],
            'dado_biomedico_id' => ['required', 'integer', 'exists:dado_biomedico,id'],
            'data_hora' => ['required', 'date'],
        ]);

        DadoBiomedico::where('id', $request->integer('dado_biomedico_id'))
            ->where('paciente_id', auth()->id())
            ->firstOrFail();

        Medicao::create($request->only('nome', 'valor', 'dado_biomedico_id', 'data_hora'));

        return redirect()->route('medicao.index')->with('success', 'Medição criada com sucesso.');
    }

    public function edit($id)
    {
        $medicao = Medicao::where('id', $id)
            ->whereHas('dado_biomedico', fn ($q) => $q->where('paciente_id', auth()->id()))
            ->firstOrFail();

        $dadosBiomedicos = DadoBiomedico::where('paciente_id', auth()->id())->get();
        return view('medicao.edit', compact('medicao', 'dadosBiomedicos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor' => ['required', 'string', 'max:45'],
            'dado_biomedico_id' => ['required', 'integer', 'exists:dado_biomedico,id'],
            'data_hora' => ['required', 'date'],
        ]);

        DadoBiomedico::where('id', $request->integer('dado_biomedico_id'))
            ->where('paciente_id', auth()->id())
            ->firstOrFail();

        $medicao = Medicao::where('id', $id)
            ->whereHas('dado_biomedico', fn ($q) => $q->where('paciente_id', auth()->id()))
            ->firstOrFail();

        $medicao->update($request->only('nome', 'valor', 'dado_biomedico_id', 'data_hora'));

        return redirect()->route('medicao.index')->with('success', 'Medição atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $medicao = Medicao::where('id', $id)
            ->whereHas('dado_biomedico', fn ($q) => $q->where('paciente_id', auth()->id()))
            ->firstOrFail();

        $medicao->delete();
        return redirect()->route('medicao.index')->with('success', 'Medição removida com sucesso.');
    }
}
