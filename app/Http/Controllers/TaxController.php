<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $impostos = Tax::all();
        return view('taxes.index', compact('impostos'));
    }

    public function create()
    {
        return view('taxes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:federal,estadual,municipal',
            'aliquota' => 'required|numeric|min:0',
            'tipo_aliquota' => 'required|in:percentual,fixa',
        ]);

        Tax::create($request->all());

        return redirect()->route('impostos.index')
                         ->with('success', 'Imposto cadastrado com sucesso.');
    }

    public function show(Tax $imposto)
    {
        // Para um CRUD simples, a view show pode não ser necessária.
        // Redirecionando para a edição por padrão.
        return redirect()->route('impostos.edit', $imposto);
    }

    public function edit(Tax $imposto)
    {
        return view('taxes.edit', compact('imposto'));
    }

    public function update(Request $request, Tax $imposto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:federal,estadual,municipal',
            'aliquota' => 'required|numeric|min:0',
            'tipo_aliquota' => 'required|in:percentual,fixa',
        ]);

        $imposto->update($request->all());

        return redirect()->route('impostos.index')
                         ->with('success', 'Imposto atualizado com sucesso.');
    }

    public function destroy(Tax $imposto)
    {
        $imposto->delete();

        return redirect()->route('impostos.index')
                         ->with('success', 'Imposto excluído com sucesso.');
    }
}