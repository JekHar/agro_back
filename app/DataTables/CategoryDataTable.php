<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn ($item) => $item->created_at->format('d-m-Y H:i:s'))
            ->addColumn('action', 'pages.categories.action')
            ->rawColumns(['image', 'action'])
            ->setRowClass(function () {
                return 'align-middle position-relative';
            })
            ->setRowId('id');
    }

    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('category-table')
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
            Column::make('name')->title('Nombre'),
            Column::make('description')->title('Descripción'),
            // Column::make('created_at')->title('Fecha creación'),
            // Column::make('updated_at')->title('Fecha modificación'),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Category_' . date('YmdHis');
    }
}
