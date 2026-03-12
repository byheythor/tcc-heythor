<?php

namespace App\Http\Controllers;

use App\Models\CategoriaMidia;
use App\Models\DadoBiomedico;
use App\Models\Midia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MidiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Midia::with(['dado_biomedico', 'categoria_midia'])->whereHas('dado_biomedico', function ($q) {
            $q->where('paciente_id', auth()->id());
        });

        if ($request->filled('q')) {
            $query->where('tipo', 'like', '%' . trim((string) $request->input('q')) . '%');
        }

        if ($request->filled('dado_biomedico_id')) {
            $query->where('dado_biomedico_id', $request->integer('dado_biomedico_id'));
        }

        $midias = $query->orderByDesc('id')->paginate(10)->withQueryString();
        $dadosBiomedicos = DadoBiomedico::where('paciente_id', auth()->id())->get();

        return view('midia.index', compact('midias', 'dadosBiomedicos'));
    }

    public function create()
    {
        $dadosBiomedicos = DadoBiomedico::where('paciente_id', auth()->id())->get();
        $categoriasMidia = CategoriaMidia::all();

        return view('midia.create', compact('dadosBiomedicos', 'categoriasMidia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anexo' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'tipo' => ['nullable', 'string', 'max:45'],
            'dado_biomedico_id' => ['required', 'integer', 'exists:dado_biomedico,id'],
            'categoria_midia_id' => ['required', 'integer', 'exists:categoria_midia,id'],
        ]);

        DadoBiomedico::where('id', $request->integer('dado_biomedico_id'))
            ->where('paciente_id', auth()->id())
            ->firstOrFail();

        $data = $request->only('tipo', 'dado_biomedico_id', 'categoria_midia_id');
        $data['anexo'] = $request->file('anexo')->store('midias', 'public');

        Midia::create($data);
        return redirect()->route('midia.index')->with('success', 'Mídia criada com sucesso.');
    }

    public function edit($id)
    {
        $midia = Midia::where('id', $id)
            ->whereHas('dado_biomedico', fn ($q) => $q->where('paciente_id', auth()->id()))
            ->firstOrFail();

        $dadosBiomedicos = DadoBiomedico::where('paciente_id', auth()->id())->get();
        $categoriasMidia = CategoriaMidia::all();

        return view('midia.edit', compact('midia', 'dadosBiomedicos', 'categoriasMidia'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'anexo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'tipo' => ['nullable', 'string', 'max:45'],
            'dado_biomedico_id' => ['required', 'integer', 'exists:dado_biomedico,id'],
            'categoria_midia_id' => ['required', 'integer', 'exists:categoria_midia,id'],
        ]);

        DadoBiomedico::where('id', $request->integer('dado_biomedico_id'))
            ->where('paciente_id', auth()->id())
            ->firstOrFail();

        $midia = Midia::where('id', $id)
            ->whereHas('dado_biomedico', fn ($q) => $q->where('paciente_id', auth()->id()))
            ->firstOrFail();

        $data = $request->only('tipo', 'dado_biomedico_id', 'categoria_midia_id');

        if ($request->hasFile('anexo')) {
            if ($midia->anexo) {
                Storage::disk('public')->delete($midia->anexo);
            }
            $data['anexo'] = $request->file('anexo')->store('midias', 'public');
        }

        $midia->update($data);
        return redirect()->route('midia.index')->with('success', 'Mídia atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $midia = Midia::where('id', $id)
            ->whereHas('dado_biomedico', fn ($q) => $q->where('paciente_id', auth()->id()))
            ->firstOrFail();

        if ($midia->anexo) {
            Storage::disk('public')->delete($midia->anexo);
        }

        $midia->delete();
        return redirect()->route('midia.index')->with('success', 'Mídia removida com sucesso.');
    }
}
