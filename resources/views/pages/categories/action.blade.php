<div class="btn-group">
    @can('categories.edit')
    <a href="{{ route('categories.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.categories.actions.edit') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    @endcan
    @can('categories.destroy')
    <x-delete-action
        :route="route('categories.destroy', $model->id)"
        :id="$model->id"
        :title="$model->name"
        :model="$model::class" />
    @endcan
</div>