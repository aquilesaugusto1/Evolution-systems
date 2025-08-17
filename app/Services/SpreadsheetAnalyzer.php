<?php

namespace App\Services;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetAnalyzer
{
    /**
     * @return array{0: array<int, mixed>, 1: int}
     */
    public function analyze(string $filePath, string $sheetName): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getSheetByName($sheetName);

        if (! $worksheet) {
            return [[], -1];
        }

        /** @var array<int, array<string, mixed>> $sheetData */
        $sheetData = $worksheet->toArray(null, true, true, true);
        $rows = new Collection($sheetData);

        return $this->findHeaderRow($rows);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return array{0: array<int, mixed>, 1: int}
     */
    private function findHeaderRow(Collection $rows): array
    {
        $bestGuessIndex = -1;
        $maxStringCount = -1;

        foreach ($rows->take(20) as $index => $row) {
            $filteredRow = (new Collection($row))->filter(fn ($cell) => $cell !== null && $cell !== '');
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
            /** @var array<string, mixed> $headerRow */
            $headerRow = $rows->get($bestGuessIndex, []);
            $headerValues = array_values((new Collection($headerRow))->filter()->all());
            $zeroBasedIndex = $bestGuessIndex - 1;

            return [$headerValues, $zeroBasedIndex];
        }

        return [[], -1];
    }
}