<?php

namespace App\Exports;

use App\Models\Sales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Yajra\DataTables\Exports\DataTablesCollectionExport;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport extends DataTablesCollectionExport implements WithMapping
{
    public function headings(): array
    {
        return [
            'Number',
            'Inventory Name',
            'Quantity',
            'Price',
            'Created At',
        ];
    }
 
    public function map($row): array

    {
        return [
            $row['number'],
            $row['details']['inventory']['name'],
            $row['details']['qty'],
            $row['details']['price'],
            $row['created_at'],
        ];
    }
}
