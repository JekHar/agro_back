<div class="btn-group">
    @can('orders.edit')
        <a href="{{ route('orders.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.items.actions.create') }}">
            <i class="fa fa-fw fa-pencil-alt text-primary"></i>
        </a>
    @endcan
{{--    @can('orders.destroy')--}}
{{--        <x-delete-action--}}
{{--            :route="route('orders.destroy', $model->id)"--}}
{{--            :id="$model->id"--}}
{{--            :title="$model->name"--}}
{{--            :model="$model::class" />--}}
{{--    @endcan--}}
</div>
