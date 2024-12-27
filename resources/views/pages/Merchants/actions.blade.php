<div class="btn-group">
    <a href="{{ route('merchants.tenants.merchants.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.merchants.actions.create') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    <x-delete-action
        :route="route('merchants.tenants.merchants.destroy', $model->id)"
        :id="$model->id"
        :title="$model->amount ?? 'no tene titulo'"
        :model="$model::class" />
</div>
