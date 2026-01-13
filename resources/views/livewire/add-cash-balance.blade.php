<div class="container-fluid">
    @section('title')
        {{ __('Add Cash Balance') }}
    @endsection

    @component('components.breadcrumb')
        @slot('title')
            {{ __('Add Cash Balance') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('Add Cash Balance to User') }}</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-line me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="addCash">
                        <div class="row g-3">

                            <!-- User Search Section -->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="search" class="form-label">{{ __('Search User') }} <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input
                                            type="text"
                                            class="form-control @error('selectedUserId') is-invalid @enderror"
                                            id="search"
                                            wire:model.live.debounce.300ms="search"
                                            placeholder="{{ __('Search by ID, Name, Email or Phone') }}"
                                            autocomplete="off"
                                        >

                                        @if($selectedUserId)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-link position-absolute top-50 end-0 translate-middle-y"
                                                wire:click="clearSelection"
                                                style="text-decoration: none;"
                                            >
                                                <i class="ri-close-line"></i>
                                            </button>
                                        @endif

                                        <!-- Search Results Dropdown -->
                                        @if($showUsersList && count($users) > 0)
                                            <div class="list-group position-absolute w-100" style="z-index: 1000; max-height: 300px; overflow-y: auto;">
                                                @foreach($users as $user)
                                                    <button
                                                        type="button"
                                                        class="list-group-item list-group-item-action"
                                                        wire:click="selectUser({{ $user['idUser'] }}, '{{ getUserDisplayedName($user['idUser']) ?? $user['email'] }}')"
                                                    >
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">{{ getUserDisplayedName($user['idUser'])  ?? $user['email'] }}</h6>
                                                            <small class="text-muted">ID: {{ $user['idUser'] }}</small>
                                                        </div>
                                                        <small class="text-muted">{{ $user['email'] }}</small>
                                                        @if(!empty($user['mobile']))
                                                            <br><small class="text-muted"><i class="ri-phone-line"></i> {{ $user['mobile'] }}</small>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @error('selectedUserId')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Selected User Display -->
                                @if($selectedUserId)
                                    <div class="alert alert-info" role="alert">
                                        <i class="ri-user-line me-2"></i>
                                        <strong>{{ __('Selected User') }}:</strong> {{ $selectedUserName }} (ID: {{ $selectedUserId }})
                                    </div>
                                @endif
                            </div>

                            <!-- Amount Input -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            id="amount"
                                            wire:model.defer="amount"
                                            placeholder="{{ __('Enter amount') }}"
                                        >
                                        <span class="input-group-text">$</span>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description Input -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }} ({{ __('Optional') }})</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="description"
                                        wire:model.defer="description"
                                        placeholder="{{ __('Enter description') }}"
                                        maxlength="255"
                                    >
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                        wire:loading.attr="disabled"
                                        wire:target="addCash"
                                        {{ $selectedUserId ? '' : 'disabled' }}
                                    >
                                        <span wire:loading.remove wire:target="addCash">
                                            <i class="ri-add-line align-bottom me-1"></i>
                                            {{ __('Add Cash Balance') }}
                                        </span>
                                        <span wire:loading wire:target="addCash">
                                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                            {{ __('Processing...') }}
                                        </span>
                                    </button>
                                    @if(!$selectedUserId)
                                        <div class="text-muted small mt-2">
                                            <i class="ri-information-line"></i> {{ __('Please select a user first') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

            <!-- Instructions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Instructions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">{{ __('How to use') }}:</h6>
                        <ol class="mb-0">
                            <li>{{ __('Search for a user by their ID, name, email address, or phone number') }}</li>
                            <li>{{ __('Select the desired user from the search results') }}</li>
                            <li>{{ __('Enter the amount you want to add to their cash balance') }}</li>
                            <li>{{ __('Optionally, add a description for this transaction') }}</li>
                            <li>{{ __('Click "Add Cash Balance" to complete the transaction') }}</li>
                        </ol>
                    </div>
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="ri-information-line me-2"></i>
                        {{ __('The transaction will be recorded with operation type SI_CB (System Input - Cash Balance)') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush

