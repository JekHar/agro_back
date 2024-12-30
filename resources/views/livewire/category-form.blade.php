<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <!-- Nombre de la categoria -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="name">{{ __('crud.categories.fields.name') }}</label>
                    <input type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        wire:model="name"
                        placeholder="{{ __('crud.categories.fields.name') }}">
                    @error('name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="description">{{ __('crud.categories.fields.description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description"
                        wire:model="description"
                        rows="3"></textarea>
                    @error('description')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-fw fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                {{ $isEditing ? __('crud.categories.actions.edit') : __('crud.categories.add') }}
            </button>
        </div>
    </form>
</div>