<?php

namespace App\DataTables;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MerchantsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'pages.merchants.actions')
            ->setRowId('id')
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Merchant $model)
    {

        if (request()->routeIs('clients.merchants.*')) {
            if (auth()->user()->hasRole('Admin')){
            return $model->newQuery()
                ->select('merchants.*')
                ->leftJoin('lots', 'merchants.id', '=', 'lots.merchant_id')
                //->leftJoin('orders', 'merchants.id', '=', 'orders.merchant_id')
                ->where('merchant_type', 'Client');
            //->selectRaw('COUNT(DISTINCT lots.id) as lots_count')
            //->selectRaw('COUNT(DISTINCT lots.id) as lots_count, MAX(orders.created_at) as last_service')
            //->groupBy('merchants.id');
            } elseif(auth()->user()->hasRole('Tenant')){
                return $model->newQuery()
                    ->select('merchants.*')
                    //->leftJoin('lots', 'merchants.id', '=', 'lots.merchant_id')
                    ->where('merchant_type', 'Client')
                    ->where('merchants.merchant_id', auth()->user()->merchant_id);
                    //->selectRaw('COUNT(DISTINCT lots.id) as lots_count');
            }
        }
        if (request()->routeIs('tenants.merchants.*')) {
            return $model->newQuery()->where('merchant_type', 'Tenant');
        }

        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('merchants-table')
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

        if (request()->routeIs('tenants.*')) {
            return [
                Column::make('id')->title('#'),
                Column::make('trade_name')->title('Nombre de Fantasia'),
                Column::make('fiscal_number')->title('CUIT'),
                Column::make('email')->title('Email'),
                Column::make('phone')->title('Telefono'),
                Column::computed('action')->title('Acciones')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
                    ->addClass('text-center'),
            ];
        }
        if (request()->routeIs('clients.*')) {
            return [
                Column::make('id')->title('#'),
                Column::make('trade_name')->title('Nombre de Fantasia'),
                Column::make('fiscal_number')->title('CUIT'),
                Column::make('main_activity')->title('Actividad Principal'),
                Column::make('email')->title('Email'),
                Column::make('phone')->title('Telefono'),
                //Column::Make('lots')->name('lots'),
                //Column::Make('last_service')->name('last service'),
                Column::computed('action')->title('Acciones')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
                    ->addClass('text-center'),
            ];
        }
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Merchants_' . date('YmdHis');
    }
}
