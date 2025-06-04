<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
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
                $roleName = $user->roles->first()?->name ?? 'N/A';
                if ($roleName != 'N/A') {
                    return __('crud.roles.types.' . $roleName);
                }
            })
            ->editColumn('created_at', fn ($item) => $item->created_at->format('d-m-Y H:i:s'))
            ->addColumn('action', 'pages.users.action')
            ->rawColumns(['image', 'action'])
            ->setRowClass(function () {
                return 'align-middle position-relative';
            })
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        if (auth()->user()->hasRole('Admin')) {
            return $model->newQuery()
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->leftjoin('merchants', 'users.merchant_id', '=', 'merchants.id')
                ->select('users.*', 'roles.name as role_name', 'merchants.business_name as merchant_name');
        }elseif(auth()->user()->hasRole('Tenant')){
            return $model->newQuery()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftjoin('merchants', 'users.merchant_id', '=', 'merchants.id')
            ->select('users.*', 'roles.name as role_name', 'merchants.business_name as merchant_name')
            ->where('users.merchant_id', auth()->user()->merchant_id);
    }}

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
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
            Column::make('name')->title('Nombre'),
            Column::make('email')->title('Correo Electrónico'),
            column::make('role')->title('Rol'),
            Column::make('created_at')->title('Fecha creación'),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
