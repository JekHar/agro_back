<form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($user))
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label" for="name">{{ __('crud.users.fields.name') }}</label>
                <input type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name ?? '') }}"
                    placeholder="{{ __('crud.users.fields.name') }}">
                @error('name')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label" for="email">{{ __('crud.users.fields.email') }}</label>
                <input type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email ?? '') }}"
                    placeholder="{{ __('crud.users.fields.email') }}">
                @error('email')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">{{ __('crud.users.fields.password') }}</label>
                <input type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    placeholder="{{ __('crud.users.fields.password') }}">
                @error('password')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label" for="password_confirmation">{{ __('crud.users.fields.password_confirmation') }}</label>
                <input type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="{{ __('crud.users.fields.password_confirmation') }}">
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-fw fa-{{ isset($user) ? 'save' : 'plus' }} me-1"></i>
            {{ isset($user) ? __('crud.users.actions.edit') : __('crud.users.add') }}
        </button>
    </div>
</form>
@push('after_body')
    @vite('resources/js/plugins/filepond.js')
@endpush