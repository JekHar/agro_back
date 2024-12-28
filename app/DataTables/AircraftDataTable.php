<?php

namespace App\DataTables;

use App\Models\Aircraft;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AircraftDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.p
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('merchant_name', function ($row) {
                return $row->merchant->business_name ?? 'N/A';
                })
            ->filterColumn('merchant_name', function ($query, $keyword) {
                $query->where('merchants.business_name', 'like', "%{$keyword}%");
            })
            ->editColumn('acquisition_date', fn ($row) => Carbon::parse($row->acquisition_date)->format('d-m-Y'))            
            ->editColumn('updated_at', fn ($row) => $row->updated_at->format('d-m-Y H:i:s'))
            ->editColumn('created_at', fn ($row) => $row->created_at->format('d-m-Y H:i:s'))
            ->addColumn('action', 'pages.aircraft.action')
            ->rawColumns(['image', 'action'])
            ->setRowClass(function () {
                return 'align-middle position-relative';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Aircraft $model): QueryBuilder
    {
        return $model->newQuery()
            ->join('merchants', 'aircrafts.merchant_id', '=', 'merchants.id')
            ->select('aircrafts.*', 'merchants.business_name as  merchant_name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('aircroft-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1, 'desc')
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'drawCallback' => 'function() { initDeleteConfirmation() }',

                    ])
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        // Button::make('reset'),
                        // Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('merchant_name')->title('Nombre del Cliente'),
            Column::make('brand')->title('Marca'),
            Column::make('models')->title('Modelo'),
            Column::make('manufacturing_year')->title('Año de Fabricación'),
            Column::computed('acquisition_date')->title('Fecha de Adquisición'),
            Column::make('working_width')->title('Ancho de Trabajo'),
            Column::make('created_at'),
            Column::make('updated_at'),
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
        return 'Aircroft_' . date('YmdHis');
    }
}
