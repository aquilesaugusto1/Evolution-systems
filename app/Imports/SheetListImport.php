<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SheetListImport implements SkipsUnknownSheets, WithMultipleSheets
{
    /**
     * @var array<int, string>
     */
    private array $sheetNames = [];

    /**
     * @return array{}
     */
    public function sheets(): array
    {
        return [];
    }

    /**
     * @param mixed $sheetName
     */
    public function onUnknownSheet($sheetName): void
    {
        if (is_string($sheetName)) {
            $this->sheetNames[] = $sheetName;
        }
    }

    /**
     * @return array<int, string>
     */
    public function getSheetNames(): array
    {
        return $this->sheetNames;
    }
}
