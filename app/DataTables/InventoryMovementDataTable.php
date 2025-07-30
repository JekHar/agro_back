<?php

namespace App\DataTables;

use App\Models\InventoryMovement;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InventoryMovementDataTable extends DataTable
{
    protected $productId;

    public function __construct($productId = null)
    {
        $this->productId = $productId;
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('order_number', function (InventoryMovement $movement) {
                return $movement->order->order_number ?? 'N/A';
            })
            ->addColumn('client_name', function (InventoryMovement $movement) {
                return $movement->order->client->business_name ?? 'N/A';
            })
            ->addColumn('product_name', function (InventoryMovement $movement) {
                return $movement->product->name ?? 'N/A';
            })
            ->addColumn('client_provides_product', function (InventoryMovement $movement) {
                return $movement->client_provides_product
                    ? '<span class="badge bg-success">SÍ</span>'
                    : '<span class="badge bg-secondary">NO</span>';
            })
            ->addColumn('difference_badge', function (InventoryMovement $movement) {
                if ($movement->difference_type === 'exact') {
                    return '<span class="badge bg-info">Cantidad exacta</span>';
                }

                $badgeClass = $movement->difference_type === 'surplus' ? 'bg-success' : 'bg-danger';
                $action = $movement->difference_type === 'surplus' ? 'Sobra' : 'Falta';

                return "<span class=\"badge {$badgeClass}\">{$action} " . number_format($movement->difference_quantity, 2) . " L</span>";
            })
            ->addColumn('added_to_inventory', function (InventoryMovement $movement) {
                return $movement->add_surplus_to_inventory
                    ? '<span class="badge bg-primary">SÍ</span>'
                    : '<span class="badge bg-light text-dark">NO</span>';
            })
            ->editColumn('created_at', function (InventoryMovement $movement) {
                return $movement->created_at->format('d/m/Y H:i');
            })
            ->rawColumns(['client_provides_product', 'difference_badge', 'added_to_inventory'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(InventoryMovement $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['order.client', 'product'])
            ->orderByDesc('created_at');

        if ($this->productId) {
            $query->where('product_id', $this->productId);
        }

        return $query;
    }

    /**
     * Optional method to configure the DataTable.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('inventory-movements-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(6, 'desc') // Order by created_at desc
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ])
            ->responsive(true)
            ->autoWidth(false)
            ->parameters([
                'language' => [
                    'url' => asset('js/Spanish.json'),
                ],
                'pageLength' => 25,
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('order_number')
                ->title('Orden')
                ->width(120),
            Column::make('client_name')
                ->title('Cliente')
                ->width(150),
        ];

        // Only show product column if not filtering by specific product
        if (!$this->productId) {
            $columns[] = Column::make('product_name')
                ->title('Producto')
                ->width(150);
        }

        $columns = array_merge($columns, [
            Column::make('client_provides_product')
                ->title('Cliente Proporciona')
                ->width(120)
                ->className('text-center'),
            Column::make('client_provided_quantity')
                ->title('Cantidad Cliente (L)')
                ->width(120)
                ->className('text-end'),
            Column::make('required_quantity')
                ->title('Cantidad Requerida (L)')
                ->width(120)
                ->className('text-end'),
            Column::make('difference_badge')
                ->title('Diferencia')
                ->width(120)
                ->className('text-center'),
            Column::make('added_to_inventory')
                ->title('Agregado a Inventario')
                ->width(130)
                ->className('text-center'),
            Column::make('notes')
                ->title('Notas')
                ->width(200),
            Column::make('created_at')
                ->title('Fecha')
                ->width(120)
                ->className('text-center'),
        ]);

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'InventoryMovements_' . date('YmdHis');
    }
}
