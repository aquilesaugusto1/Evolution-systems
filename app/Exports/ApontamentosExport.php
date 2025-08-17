<?php

namespace App\Exports;

use App\Models\Apontamento;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * @implements WithMapping<Apontamento>
 */
class ApontamentosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @var Collection<int, Apontamento>
     */
    protected Collection $apontamentos;

    /**
     * @var array<string, mixed>
     */
    protected array $filtros;

    /**
     * @param Collection<int, Apontamento> $apontamentos
     * @param array<string, mixed> $filtros
     */
    public function __construct(Collection $apontamentos, array $filtros)
    {
        $this->apontamentos = $apontamentos;
        $this->filtros = $filtros;
    }

    /**
     * @return Collection<int, Apontamento>
     */
    public function collection(): Collection
    {
        return $this->apontamentos;
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Data',
            'Consultor',
            'Cliente',
            'Contrato ID',
            'Descrição',
            'Horas Gastas',
            'Status',
        ];
    }

    /**
     * @param Apontamento $apontamento
     * @return array<int, mixed>
     */
    public function map($apontamento): array
    {
        return [
            \Carbon\Carbon::parse($apontamento->data_apontamento)->format('d/m/Y'),
            $apontamento->consultor?->nome,
            $apontamento->contrato?->empresaParceira?->nome_empresa,
            $apontamento->contrato?->id,
            $apontamento->descricao,
            number_format($apontamento->horas_gastas, 2, ',', '.'),
            $apontamento->status,
        ];
    }
}
