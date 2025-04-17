<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('client_name', function ($order) {
                return $order->client_name ?? 'N/A';
            })
            ->editColumn('tenant_name', function ($order) {
                return $order->tenant_name ?? 'N/A';
            })
            ->editColumn('service_name', function ($order) {
                return $order->service_name ?? 'N/A';
            })
            ->editColumn('pilot_name', function ($order) {
                return $order->pilot_name ?? 'N/A';
            })
            ->editColumn('total_amount', function ($order) {
                return '$' . number_format($order->total_amount, 2);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y H:i:s');
            })
            ->editColumn('status', function ($row) {
                $statusClass = [
                    'pending' => 'warning',
                    'in_progress' => 'primary',
                    'completed' => 'success',
                    'canceled' => 'danger',
                ][$row->status] ?? 'secondary';

                $statusLabel = [
                    'pending' => 'Pendiente',
                    'in_progress' => 'En Progreso',
                    'completed' => 'Completada',
                    'canceled' => 'Cancelada',
                ][$row->status] ?? 'Desconocido';

                return '<span class="badge bg-'.$statusClass.'">'.$statusLabel.'</span>';
            })
            ->filterColumn('client_name', function ($query, $keyword) {
                $query->where('client_merchants.business_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('tenant_name', function ($query, $keyword) {
                $query->where('tenant_merchants.business_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('service_name', function ($query, $keyword) {
                $query->where('services.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('pilot_name', function ($query, $keyword) {
                $query->where('pilots.name', 'like', "%{$keyword}%");
            })
            ->addColumn('action', 'pages.products.orders-actions')
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->leftJoin('merchants as client_merchants', 'orders.client_id', '=', 'client_merchants.id')
            ->leftJoin('merchants as tenant_merchants', 'orders.tenant_id', '=', 'tenant_merchants.id')
            ->leftJoin('services', 'orders.service_id', '=', 'services.id')
            ->leftJoin('users as pilots', 'orders.pilot_id', '=', 'pilots.id')
            ->leftJoin('users as ground_support', 'orders.ground_support_id', '=', 'ground_support.id')
            ->select(
                'orders.*',
                'client_merchants.business_name as client_name',
                'tenant_merchants.business_name as tenant_name',
                'services.name as service_name',
                'pilots.name as pilot_name',
                'ground_support.name as ground_support_name'
            );

        if (auth()->user()->hasRole('Admin')) {
            return $query;
        } elseif (auth()->user()->hasRole('Tenant')) {
            return $query->where('orders.tenant_id', auth()->user()->merchant_id);
        } elseif (auth()->user()->hasRole('Client')) {
            return $query->where('orders.client_id', auth()->user()->merchant_id);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('order-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0, 'desc')
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'language' => ['url' => asset('js/plugins/datatables/Spanish.json')],
                        'drawCallback' => 'function() { initDeleteConfirmation() }',
                    ])
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
            Column::make('id')->title('#'),
            Column::make('order_number')->title('Número de Orden'),
            Column::make('client_name')->title('Cliente'),
            Column::make('tenant_name')->title('Proveedor'),
            Column::make('service_name')->title('Servicio'),
            Column::make('total_hectares')->title('Total Hectáreas'),
            Column::make('total_amount')->title('Monto Total'),
            Column::make('status')->title('Estado'),
            Column::computed('action')->title('Acciones')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
