<?php

namespace App\DataTables;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LotDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'pages.lots.action')
            ->addColumn('merchant_name', function ($row) {
                return $row->merchant->business_name;
            })
            ->filterColumn('merchant_name', function ($query, $keyword) {
                $query->where('merchants.business_name', 'like', "%{$keyword}%");
            })
            ->editColumn('updated_at', fn($item) => $item->updated_at->format('d-m-Y H:i:s'))
            ->editColumn('created_at', fn($item) => $item->created_at->format('d-m-Y H:i:s'))
            ->setRowClass(function () {
                return 'align-middle position-relative';
            })
            ->setRowId('id');
    }

    public function query(Lot $model): QueryBuilder
    {
        $merchant_id = session('merchant_id');

        $query = $model->newQuery()
            ->join('merchants', 'lots.merchant_id', '=', 'merchants.id')
            ->select('lots.*', 'merchants.business_name as merchant_name');

       
        if ($merchant_id) {
            $query->where('lots.merchant_id', $merchant_id);
        }

        if (auth()->user()->hasRole('Admin')) {
            $query->where('merchants.merchant_type', 'client');
        } elseif (auth()->user()->hasRole('Tenant')) {
            $query->where('merchants.merchant_type', 'client')
                ->where('merchants.merchant_id', auth()->user()->merchant_id);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('lot-table')
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

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('number')->title(__('crud.lots.fields.number')),
            Column::make('hectares')->title(__('crud.lots.fields.hectares')),
            Column::make('merchant_name')->title(__('crud.lots.fields.merchant')),
            Column::make('created_at')->title('Fecha creación'),
            Column::make('updated_at')->title('Fecha modificación'),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),

        ];
    }

    protected function filename(): string
    {
        return 'Lot_' . date('YmdHis');
    }
}
