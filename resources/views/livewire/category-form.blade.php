<div>
    <form wire:submit.prevent="save">
        <div class="row">

            <div class="col-md-4">
                <div class="mb-4">
                    <label class="form-label" for="name"><span class="text-danger">*</span>  {{ __('crud.categories.fields.name') }}</label>
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

            <div class="col-md-4">
                <div class="mb-4">
                    <label class="form-label" for="category_id">{{ __('crud.categories.fields.parent_category') }}</label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                        id="category_id"
                        wire:model="category_id">
                        <option value="">{{ __('crud.categories.select_parent') }}</option>
                        @foreach($categories as $id => $categoryName)
                            @if(!$isEditing || $id != $categoryId)
                                <option value="{{ $id }}">{{ $categoryName }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>


        <div class="row">
            <div class="col-md-4">
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
            <button type="submit" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
                <i class="fa fa-fw fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                {{ $isEditing ? __('crud.categories.actions.edit') : __('crud.categories.add') }}
            </button>
        </div>
    </form>
</div>