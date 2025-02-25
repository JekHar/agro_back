<div class="btn-group">
    @can('lots.edit')
    <a href="{{ route('lots.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.lots.actions.edit') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    @endcan
    @can('lots.destroy')
    <x-delete-action
        :route="route('lots.destroy', $model->id)"
        :id="$model->id"
        :title="$model->number"
        :model="$model::class" />
    @endcan
</div>