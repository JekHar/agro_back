<div class="btn-group">
    @can('clients.merchants.edit')
    <a href="{{ route(request()->routeIs('clients.merchants.*') ? 'clients.merchants.edit' : 'tenants.merchants.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.merchants.actions.create') }}"> <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    @endcan
    @can('clients.merchants.destroy')
    <x-delete-action
        :route="route('tenants.merchants.destroy', $model->id)"
        :id="$model->id"
        :title="$model->amount ?? 'no tene titulo'"
        :model="$model::class" />
    @endcan
</div>