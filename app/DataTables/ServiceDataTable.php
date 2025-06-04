<?php

namespace App\DataTables;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ServiceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
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
            ->editColumn('updated_at', fn ($item) => $item->updated_at->format('d-m-Y H:i:s'))
            ->editColumn('created_at', fn ($item) => $item->created_at->format('d-m-Y H:i:s'))
            ->addColumn('action', 'pages.services.action')
            ->rawColumns(['image', 'action'])
            ->setRowClass(function () {
                return 'align-middle position-relative';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Service $model): QueryBuilder
    {

        
        if(auth()->user()->hasRole('Admin')){
            return $model->newQuery()
            ->join('merchants', 'services.merchant_id', '=', 'merchants.id')
            ->select('services.*', 'merchants.business_name as  merchant_name');
        }elseif(auth()->user()->hasRole('Tenant')){
            return $model->newQuery()
            ->join('merchants', 'services.merchant_id', '=', 'merchants.id')
            ->select('services.*', 'merchants.business_name as  merchant_name')
            ->whereColumn('services.merchant_id', 'merchants.id')
            ->where('merchants.id', auth()->user()->merchant_id);
    }}

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->parameters([
                'dom' => 'Bfrtip',
                'language' => ['url' => asset('js/plugins/datatables/Spanish.json')],
                'drawCallback' => 'function() { initDeleteConfirmation() }',

            ])
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel')->attr(['class' => 'btn btn-secondary rounded-pill me-1']),
                Button::make('csv')->attr(['class' => 'btn btn-secondary rounded-pill me-1']),
                Button::make('pdf')->attr(['class' => 'btn btn-secondary rounded-pill me-1']),
                Button::make('print')->attr(['class' => 'btn btn-secondary rounded-pill me-1']),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Servicio'),
            Column::make('price_per_hectare')->title('Precio/Ha'),
            Column::computed('action')->title('Acciones')
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
        return 'Service_' . date('YmdHis');
    }
}
