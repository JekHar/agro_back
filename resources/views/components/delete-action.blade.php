<button type="button"
        class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled delete-confirm"
        data-bs-toggle="tooltip"
        aria-label="Delete"
        data-title="{{ $title ?? 'this item' }}"
        data-id-selector="delete-{{ $model }}-{{ $id }}"
        data-bs-original-title="{{ __('crud.items.actions.delete') }}">
    <i class="fa fa-fw fa-times text-danger"></i>
    <form action="{{ $route }}" method="POST" class="d-none delete-form" id="delete-{{ $model }}-{{ $id }}">
        @csrf
        @method('DELETE')
    </form>
</button>
