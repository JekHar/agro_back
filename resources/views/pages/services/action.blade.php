<div class="btn-group">
    @can('services.edit')
    <a href="{{ route('services.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.items.actions.create') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    @endcan

    @can('services.destroy')
    <x-delete-action
        :route="route('services.destroy', $model->id)"
        :id="$model->id"
        :title="$model->name"
        :model="$model::class" /> 
    @endcan
</div>
