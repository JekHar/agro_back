@can('services.edit')
<div class="btn-group">
    <a href="{{ route('services.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.items.actions.create') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    <x-delete-action
        :route="route('services.destroy', $model->id)"
        :id="$model->id"
        :title="$model->name"
        :model="$model::class" /> 
</div>
@endcan
