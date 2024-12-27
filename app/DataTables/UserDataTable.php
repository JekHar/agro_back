<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('merchant_name', function ($user) {
                return $user->merchant->business_name ?? 'N/A';
            })
            ->filterColumn('merchant_name', function ($query, $keyword) {
                $query->where('merchants.business_name', 'like', "%{$keyword}%");
            })
            
            ->addColumn('role', function ($user) {
                return $user->roles->first()?->name ?? 'N/A';
            })
            ->editColumn('updated_at', fn($item) => $item->updated_at->format('Y-m-d H:i:s'))
            ->editColumn('created_at', fn($item) => $item->created_at->format('Y-m-d H:i:s'))
            ->addColumn('action', 'pages.users.action')
            ->rawColumns(['image', 'action'])
            ->setRowClass(function () {
                return 'align-middle position-relative';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('roles')
            ->with('merchant')
            ->join('merchants', 'users.merchant_id', '=', 'merchants.id') 
            ->select('users.*', 'merchants.business_name as merchant_name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('user-table')
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
            Column::make('name'),
            Column::make('email'),
            column::make('role'),
            Column::make('merchant_name'),
            // column::make('merchant_id'),
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
        return 'User_' . date('YmdHis');
    }
}
