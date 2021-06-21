<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SearchExport implements FromArray, WithHeadings
{
    protected $search;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Porcentaje',
            'Departamento',
            'Localidad',
            'Municipio',
            'Años activo',
            'Tipo persona',
            'Tipo cargo'
        ];
    }

    public function array(): array
    {
        return $this->search;
    }
}
