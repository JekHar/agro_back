@can('aircraft.edit')
<div class="btn-group">
    <a href="{{ route('aircrafts.edit', $id) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{ __('crud.items.actions.create') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>

    <x-delete-action
        :route="route('aircrafts.destroy', $model->id)"
        :id="$model->id"
        :title="$model->brand"   
        :model="$model::class" />  
</div>
@endcan
