<div class="btn-group">
    <a href="{{ route('users.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.users.actions.edit') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    <x-delete-action
        :route="route('users.destroy', $model->id)"
        :id="$model->id"
        :title="$model->name"
        :model="$model::class" />
</div>