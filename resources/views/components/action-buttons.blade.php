<div class="btn-group action-buttons">
    <a href="{{ $editRoute }}" class="btn btn-sm btn-alt-secondary" title="{{ __('edit') }}">
        <i class="fa fa-fw fa-pencil-alt text-primary"></i>
    </a>
    <form action="{{ $deleteRoute }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-alt-secondary"
            onclick="return confirm('¿Está seguro que desea eliminar este registro?')"
            title="{{ __('delete') }}">
            <i class="fa fa-fw fa-times text-danger"></i>
        </button>
    </form>
</div>