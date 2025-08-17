<?php

namespace App\Http\Controllers;

use App\Imports\AlocacoesImport;
use App\Imports\SheetListImport;
use App\Services\SpreadsheetAnalyzer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function create(): View
    {
        return view('imports.create');
    }

    public function selectSheet(Request $request): View|RedirectResponse
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls|max:10240']);
        $path = $request->file('file')->store('imports');

        if ($path === false) {
            return redirect()->route('imports.create')->with('error', 'Não foi possível armazenar o arquivo enviado.');
        }

        $sheetListImport = new SheetListImport();
        Excel::import($sheetListImport, $path);
        $sheetNames = $sheetListImport->getSheetNames();

        $request->session()->put('import_file_path', $path);

        return view('imports.select_sheet', compact('sheetNames'));
    }

    public function showMapping(Request $request, SpreadsheetAnalyzer $analyzer): View|RedirectResponse
    {
        $request->validate(['sheet_name' => 'required|string']);
        $path = $request->session()->get('import_file_path', '');
        $sheetName = $request->string('sheet_name')->toString();

        if (! is_string($path) || ! file_exists(storage_path('app/'.$path))) {
            return redirect()->route('imports.create')->with('error', 'Arquivo de importação expirou. Por favor, envie novamente.');
        }

        [$headings, $headerRowIndex] = $analyzer->analyze(storage_path('app/'.$path), $sheetName);

        if (empty($headings)) {
            return redirect()->route('imports.create')->with('error', "Não foram encontrados cabeçalhos na planilha '{$sheetName}'. Verifique se a planilha tem dados e tente novamente.");
        }

        $request->session()->put('import_sheet_name', $sheetName);
        $request->session()->put('import_header_row_index', $headerRowIndex);

        $dbColumns = [
            'consultor_id' => 'Nome do Consultor',
            'projeto_id' => 'Nome do Projeto',
            'data_inicio' => 'Data de Início',
            'data_fim' => 'Data de Fim',
            'tipo_alocacao' => 'Tipo de Alocação',
        ];

        return view('imports.mapping', compact('headings', 'dbColumns'));
    }

    public function processMapping(Request $request): RedirectResponse
    {
        $request->validate(['mappings' => 'required|array']);
        $path = $request->session()->pull('import_file_path', '');
        $sheetName = $request->session()->pull('import_sheet_name', '');
        $headerRowIndex = $request->session()->pull('import_header_row_index');
        $mappings = (array) $request->input('mappings');

        if (! is_string($path) || ! is_string($sheetName) || ! is_numeric($headerRowIndex)) {
            return redirect()->route('imports.create')->with('error', 'Sessão de importação expirou. Por favor, comece novamente.');
        }

        try {
            $headerRowNumber = (int) $headerRowIndex + 1;
            Excel::import(new AlocacoesImport($mappings, $sheetName, $headerRowNumber), $path);

            return redirect()->route('agendas.index')->with('success', 'Alocações importadas com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('imports.create')->with('error', 'Ocorreu um erro na importação: '.$e->getMessage());
        }
    }
}