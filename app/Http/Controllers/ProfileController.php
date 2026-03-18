<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $cpf = preg_replace('/\D+/', '', (string) $request->input('cpf', ''));
        $celular = preg_replace('/\D+/', '', (string) $request->input('celular', ''));

        $request->merge([
            'cpf' => $cpf !== '' ? $cpf : null,
            'celular' => $celular !== '' ? $celular : null,
        ]);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:45', Rule::unique('users', 'email')->ignore($user->id)],
            'cpf' => ['nullable', 'digits:11', Rule::unique('users', 'cpf')->ignore($user->id)],
            'celular' => ['nullable', 'digits_between:10,11'],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (blank($validated['cpf'] ?? null)) {
            $validated['cpf'] = null;
        }

        if (blank($validated['celular'] ?? null)) {
            $validated['celular'] = null;
        }

        if (blank($validated['data_nascimento'] ?? null)) {
            $validated['data_nascimento'] = null;
        }

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso.');
    }
}
