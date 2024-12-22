<?php

namespace App\DataTables;

use App\Exports\SalesExport;
use App\Models\Sale;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalesDataTable extends DataTable
{   
    protected string $exportClass = SalesExport::class;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'sales.action')
            ->addColumn('created_at', function($row){
                return Carbon::parse($row->created_at);
            })
            ->addColumn('price', function($row){
                return intval($row->details?->price);
            })
            ->addColumn('inventory_name', function($row){
                return $row->details?->inventory->name;
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                  <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="'.route('sales.edit', ['id' => $row->id]).'">Edit</a></li>
                    <li>
                        <form method="POST" action="'.route('sales.delete', ['id' => $row->id]).'" attr-datatable-submit="delete">
                        <input type="hidden" name="_token" value="'.csrf_token().'" />
                        <input type="hidden" name="_method" value="delete">
                            <button class="dropdown-item" type="submit">Delete</a>
                        </form>
                    </li>
                  </ul>
                </div>';
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Sales $model): QueryBuilder
    {   
        $model = Sales::query()->with(['details', 'details.inventory']);

        if (!Auth::user()->hasRole(['super-admin'])) {
            $model->where('user_id', '=', Auth::user()->id);
        }

        return $model;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('sales-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ])
                    ->addTableClass('my-3');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->addClass("text-start"),
            Column::make('number')->title("Number")->addClass("text-start"),
            Column::make('date')->title("Date")->addClass("text-start"),
            Column::computed('inventory_name')->title("Inventory")->addClass("text-start")->orderable(true),
            Column::make('details.qty')->title("Quantity")->addClass("text-start"),
            Column::computed('price')->title("Price")->addClass("text-start")->sortable(true),
            Column::computed('created_at')->addClass("text-start")->orderable(true),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Sales_' . date('YmdHis');
    }
}
