<div>
    <form wire:submit.prevent="save">
        <div class="row push">
            <!-- Merchant -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="merchant_id">
                        <span class="text-danger">*</span> {{ __('crud.aircrafts.fields.merchant') }}
                    </label>
                    <select class="form-select @error('merchant_id') is-invalid @enderror" id="merchant_id" wire:model="merchant_id">
                        <option value="">{{ __('crud.aircrafts.select_merchant') }}</option>
                        @foreach ($merchants as $id => $businessName)
                            <option value="{{ $id }}">{{ $businessName }}</option>
                        @endforeach
                    </select>
                    @error('merchant_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Brand -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="brand">
                        <span class="text-danger">*</span> {{ __('crud.aircrafts.fields.brand') }}
                    </label>
                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" wire:model="brand">
                    @error('brand')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Model -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="models">
                        <span class="text-danger">*</span> {{ __('crud.aircrafts.fields.model') }}
                    </label>
                    <input type="text" class="form-control @error('models') is-invalid @enderror" id="models" wire:model="models">
                    @error('models')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Working Width -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="working_width"> <span class="text-danger">*</span>
                        {{ __('crud.aircrafts.fields.working_width') }}
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control @error('working_width') is-invalid @enderror" id="working_width" wire:model="working_width" step="0.01">
                        <span class="input-group-text">m</span>
                    </div>
                    @error('working_width')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Manufacturing Year -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="manufacturing_year">
                        {{ __('crud.aircrafts.fields.manufacturing_year') }}
                    </label>
                    <input type="number" class="form-control @error('manufacturing_year') is-invalid @enderror" id="manufacturing_year" wire:model="manufacturing_year">
                    @error('manufacturing_year')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Acquisition Date -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="acquisition_date"> <span class="text-danger">*</span>
                        {{ __('crud.aircrafts.fields.acquisition_date') }}
                    </label>
                    <input type="date" class="js-flatpickr form-control @error('acquisition_date') is-invalid @enderror" id="acquisition_date" wire:model="acquisition_date" data-date-format="Y-m-d">
                    @error('acquisition_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-fw fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                {{ $isEditing ? __('crud.aircrafts.actions.edit') : __('crud.aircrafts.add') }}
            </button>
        </div>
    </form>
</div>