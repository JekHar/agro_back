<div class="btn-group">
    @can('clients.orders.edit')
    <a href="{{ route(request()->routeIs('clients.orders.*') ? 'clients.orders.edit' : 'tenants.orders.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.orders.actions.create') }}"> <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    @endcan
    @can('clients.orders.destroy')
    <x-delete-action
        :route="route('tenants.orders.destroy', $model->id)"
        :id="$model->id"
        :title="$model->amount ?? 'no tene titulo'"
        :model="$model::class" />
    @endcan
</div>
