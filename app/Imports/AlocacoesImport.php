<?php

namespace App\Imports;

use App\Models\Agenda;
use App\Models\Consultor;
use App\Models\Projeto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AlocacoesImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    private $mappings;

    private $sheetName;

    private $headerRow;

    public function __construct(array $mappings, string $sheetName, int $headerRow = 1)
    {
        $this->mappings = $mappings;
        $this->sheetName = $sheetName;
        $this->headerRow = $headerRow;
    }

    public function sheets(): array
    {
        return [$this->sheetName => $this];
    }

    public function headingRow(): int
    {
        return $this->headerRow;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $rowData = $row->toArray();
            if (empty(array_filter($rowData))) {
                continue;
            }

            $mappedData = [];
            foreach ($this->mappings as $dbField => $fileHeader) {
                $headerKey = strtolower(str_replace(' ', '_', (string) $fileHeader));
                if ($fileHeader && isset($rowData[$headerKey])) {
                    $mappedData[$dbField] = $rowData[$headerKey];
                }
            }

            if (empty($mappedData['consultor_id']) || empty($mappedData['projeto_id']) || empty($mappedData['data_inicio'])) {
                continue;
            }

            $this->processRow($mappedData);
        }
    }

    private function processRow(array $data)
    {
        $validator = Validator::make($data, [
            'consultor_id' => 'exists:consultores,nome',
            'data_inicio' => 'required|numeric',
        ], [
            'consultor_id.exists' => "O consultor '{$data['consultor_id']}' não foi encontrado.",
            'data_inicio.numeric' => "A data de início '{$data['data_inicio']}' não é uma data válida do Excel.",
        ]);

        if ($validator->fails()) {
            throw new \Exception(implode(', ', $validator->errors()->all()));
        }

        $consultor = Consultor::where('nome', $data['consultor_id'])->first();
        $projeto = Projeto::firstOrCreate(
            ['nome' => $data['projeto_id']],
            ['empresa_parceira_id' => $consultor->empresa_parceira_id ?? 1]
        );

        Agenda::create([
            'consultor_id' => $consultor->id,
            'projeto_id' => $projeto->id,
            'data_inicio' => Date::excelToDateTimeObject($data['data_inicio']),
            'data_fim' => isset($data['data_fim']) && is_numeric($data['data_fim']) ? Date::excelToDateTimeObject($data['data_fim']) : null,
            'tipo_alocacao' => $data['tipo_alocacao'] ?? 'Normal',
        ]);
    }
}
