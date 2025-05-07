<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="name"><span class="text-danger">*</span>
                        {{ __('crud.users.fields.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        wire:model="name" placeholder="{{ __('crud.users.fields.name') }}">
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="email"><span class="text-danger">*</span>
                        {{ __('crud.users.fields.email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        wire:model="email" placeholder="{{ __('crud.users.fields.email') }}">
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="role"><span class="text-danger">*</span>
                        {{ __('crud.users.fields.role') }}</label>
                    <select class="form-control @error('role') is-invalid @enderror" id="role" wire:model="role">
                        <option value="">{{ __('crud.users.select_role') }}</option>
                        @foreach ($roles as $id => $roleName)
                            <option value="{{ $id }}">{{ __('crud.roles.types.' . $roleName) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    @if (auth()->user()->hasRole('Admin'))
                        <label class="form-label" for="merchant_id">
                            <span class="text-danger">*</span> {{ __('crud.users.fields.merchant') }}
                        </label>
                        <select class="form-control @error('merchant_id') is-invalid @enderror" id="merchant_id"
                            wire:model="merchant_id">
                            <option value="">{{ __('crud.users.select_merchant') }}</option>
                            @foreach ($merchants as $id => $businessName)
                                <option value="{{ $id }}">{{ $businessName }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('merchant_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-4 position-relative">
                    <label class="form-label" for="password">{{ __('crud.users.fields.password') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        wire:model="password">
                    <span class="password-toggle-icon position-absolute"
                        style="right: 10px; top: 38px; cursor: pointer;">
                        <i class="fa fa-eye-slash toggle-password" data-target="password"></i>
                    </span>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-4 position-relative">
                    <label class="form-label"
                        for="password_confirmation">{{ __('crud.users.fields.password_confirmation') }}</label>
                    <input type="password" class="form-control" id="password_confirmation"
                        wire:model="password_confirmation">
                    <span class="password-toggle-icon position-absolute"
                        style="right: 10px; top: 38px; cursor: pointer;">
                        <i class="fa fa-eye-slash toggle-password" data-target="password_confirmation"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
                <i class="fa fa-fw fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                {{ $isEditing ? __('crud.users.actions.edit') : __('crud.users.add') }}
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        console.log(toggleButtons);

        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                console.log(targetId, passwordInput);


                if (passwordInput) {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                }
            });
        });
    });
</script>
