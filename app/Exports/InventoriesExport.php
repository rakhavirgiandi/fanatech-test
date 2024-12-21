<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class InventoriesExport extends DataTablesCollectionExport implements WithMapping
{
    public function headings(): array
    {
        return [
            'Name',
            'Price',
            'Stock',
        ];
    }
 
    public function map($row): array

    {
        return [
            $row['name'],
            $row['price'],
            $row['stock'],
        ];
    }
}
