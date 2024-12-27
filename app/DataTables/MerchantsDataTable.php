<?php

namespace App\DataTables;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MerchantsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
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
   if (request()->routeIs('merchants.clients.*')) {
       return $model->newQuery()
           ->select('merchants.*')
           ->leftJoin('lots', 'merchants.id', '=', 'lots.merchant_id')
           //->leftJoin('orders', 'merchants.id', '=', 'orders.merchant_id')
           ->where('merchant_type', 'Client')
           //->selectRaw('COUNT(DISTINCT lots.id) as lots_count')
           //->selectRaw('COUNT(DISTINCT lots.id) as lots_count, MAX(orders.created_at) as last_service')
           //->groupBy('merchants.id');
           ;
   }

   if (request()->routeIs('merchants.tenants.*')) {
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
            ->orderBy(1)
            ->parameters([
                'dom' => 'Bfrtip',
                'drawCallback' => 'function() { initDeleteConfirmation() }',
                
            ])
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {

        if (request()->routeIs('merchants.tenants.*')) {
            return [
                Column::make('id')->title('#'),
                Column::make('business_name')->name('business_name'),
                Column::make('trade_name')->name('trade name'),
                Column::make('fiscal_number')->name('fiscal number'),
                Column::make('email')->name('email'),
                Column::make('phone')->name('phone'),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
                    ->addClass('text-center'),
            ];
        }
        if (request()->routeIs('merchants.clients.*')) {
            return [
                Column::make('id')->title('#'),
                Column::make('business_name')->name('business_name'),
                Column::make('trade_name')->name('trade name'),
                Column::make('fiscal_number')->name('fiscal number'),
                Column::make('main_activity')->name('main activity'),
                Column::make('email')->name('email'),
                Column::make('phone')->name('phone'),
                //Column::Make('lots')->name('lots'),
                //Column::Make('last_service')->name('last service'),
                Column::computed('action')
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
