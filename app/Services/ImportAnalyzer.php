<?php

namespace App\Services;

use App\Imports\SheetContentImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ImportAnalyzer
{
    public function analyze(string $filePath, string $sheetName): array
    {
        $sheetContentImport = new SheetContentImport($sheetName);
        Excel::import($sheetContentImport, $filePath);
        $rows = $sheetContentImport->getRows();

        return $this->findHeaderRow($rows);
    }

    private function findHeaderRow(Collection $rows): array
    {
        $bestGuessIndex = -1;
        $maxStringCount = -1;

        foreach ($rows->take(20) as $index => $row) {
            $filteredRow = $row->filter(fn ($cell) => $cell !== null);
            if ($filteredRow->isEmpty()) {
                continue;
            }

            $stringCount = $filteredRow->filter(fn ($cell) => is_string($cell) && ! is_numeric($cell))->count();

            if ($stringCount >= $maxStringCount && $stringCount > $filteredRow->count() * 0.5) {
                $maxStringCount = $stringCount;
                $bestGuessIndex = $index;
            }
        }

        if ($bestGuessIndex !== -1) {
            return [$rows[$bestGuessIndex]->filter()->toArray(), $bestGuessIndex];
        }

        return [[], -1];
    }
}
