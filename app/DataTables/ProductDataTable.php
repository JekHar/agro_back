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
                ->whereColumn('products.merchant_id', 'merchants.id')
                ->where('merchants.merchant_id', auth()->user()->merchant_id);
        }
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
            Column::make('name')->title('Nombre'),
            Column::make('category_name')->title('Categoría'),
            Column::make('merchant_name')->title('Cliente'),
            Column::make('concentration')->title('Concent'),
            Column::make('dosage_per_hectare')->title('Dosis/ha'),
            Column::make('application_volume_per_hectare')->title('Volumen/ha'),
            Column::make('stock')->title('Stock'),
            // Column::make('created_at')->title('Fecha creación'),
            // Column::make('updated_at')->title('Fecha modificación'),
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
        return 'Product_' . date('YmdHis');
    }
}
