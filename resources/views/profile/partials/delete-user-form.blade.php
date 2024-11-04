<div class="block block-rounded h-100 mb-0">
    <div class="block-header block-header-default">
        <h3 class="block-title">{{ __('Delete Account') }}</h3>
    </div>
    <div class="block-content fs-sm text-muted pb-3">
        <div class="mb-3">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <div class="row mb-0">
            <div class="col-md-6">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deleteAccountModalLabel">
            {{ __('Are you sure you want to delete your account?') }}
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </div>
        <form id="deleteAccountForm" method="post" action="{{ route('profile.destroy') }}" class="p-2">
            @csrf
            @method('delete')

            <div>
                <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required>

                @error('password', 'userDeletion')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            {{ __('Cancel') }}
        </button>
        <button type="submit" class="btn btn-danger" form="deleteAccountForm">
            {{ __('Delete Account') }}
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
    @php $shouldOpenModal = $errors->userDeletion->isNotEmpty(); @endphp

    <script>
        let shouldOpenModal = {{ Js::from($shouldOpenModal) }};

        if (shouldOpenModal) {
            window.addEventListener('load', function() {
                let deleteAccountModal = new bootstrap.Modal('#deleteAccountModal');
                deleteAccountModal.toggle();
            });
        }
    </script>
@endPush
