<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('category_id', function ($product) {
                return $product->category_name;
            })
            ->filterColumn('category_name', function ($query, $keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('merchant_name', function ($query, $keyword) {
                $query->where('merchants.business_name', 'like', "%{$keyword}%");
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y H:i:s');
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('d-m-Y H:i:s');
            })
            ->addColumn('action', 'pages.products.action')
            ->addColumn('inventory_liters', function ($product) {
                return $product->inventory_liters;
            })
            ->addColumn('inventory_cans', function ($product) {
                return $product->inventory_cans;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        if (auth()->user()->hasRole('Admin')) {
            return $model->newQuery()
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('merchants', 'products.merchant_id', '=', 'merchants.id')
                ->select('products.*', 'categories.name as category_name', 'merchants.business_name as merchant_name');
        }elseif(auth()->user()->hasRole('Tenant')){
            return $model->newQuery()
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('merchants', 'products.merchant_id', '=', 'merchants.id')
                ->select('products.*', 'categories.name as category_name', 'merchants.business_name as merchant_name')
                ->where('merchants.id', auth()->user()->merchant_id);
        }

        // Default return for other roles
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
                    //->dom('Bfrtip')
            ->orderBy(0, 'asc')
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
            Column::make('id'),
            Column::make('name')->title('Name'),
            Column::make('category_name')->title('Category'),
            Column::make('merchant_name')->title('Client'),
            Column::make('commercial_brand')->title('Commercial Brand'),
            Column::make('dosage_per_hectare')->title('Dosage/ha'),
            Column::make('liters_per_can')->title('Liters per Can'),
            Column::computed('inventory_liters')->title('Inventory LITERS'),
            Column::computed('inventory_cans')->title('Inventory CANS'),
            // Column::make('stock')->title('Stock'),
            // Column::make('created_at')->title('Created Date'),
            // Column::make('updated_at')->title('Updated Date'),
            Column::computed('action')->title('Actions')
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
        return 'Product_' . date('YmdHis');
    }
}
