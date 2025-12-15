
<div class="{{getContainerType()}}">
    @section('title')
        {{ __('SMS') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('SMS') }}
        @endslot
    @endcomponent

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Total SMS') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-message-2-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $totalSms }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Today') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-info fs-14 mb-0">
                                <i class="ri-calendar-todo-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $todaySms }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('This Week') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-warning fs-14 mb-0">
                                <i class="ri-calendar-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $weekSms }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('This Month') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-danger fs-14 mb-0">
                                <i class="ri-calendar-2-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $monthSms }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ri-filter-3-line align-middle me-1"></i> {{ __('Filters') }}
            </h5>
            <button wire:click="resetFilters" class="btn btn-sm btn-soft-danger">
                <i class="ri-refresh-line align-middle me-1"></i> {{ __('Reset Filters') }}
            </button>
        </div>
        <div class="card-body filter-card">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">{{ __('Date From') }}</label>
                    <input type="date" wire:model.live="date_from" class="form-control" id="date_from">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">{{ __('Date To') }}</label>
                    <input type="date" wire:model.live="date_to" class="form-control" id="date_to">
                </div>
                <div class="col-md-3">
                    <label for="destination_number" class="form-label">{{ __('Phone Number') }}</label>
                    <input type="text" wire:model.live.debounce.500ms="destination_number" class="form-control" id="destination_number"
                           placeholder="{{ __('Enter phone number') }}">
                </div>
                <div class="col-md-3">
                    <label for="message" class="form-label">{{ __('Message Content') }}</label>
                    <input type="text" wire:model.live.debounce.500ms="message" class="form-control" id="message"
                           placeholder="{{ __('Search in message') }}">
                </div>
                <div class="col-md-3">
                    <label for="user_id" class="form-label">{{ __('User ID') }}</label>
                    <input type="number" wire:model.live.debounce.500ms="user_id" class="form-control" id="user_id"
                           placeholder="{{ __('Enter user ID') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- SMS List Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-mail-line align-middle me-1"></i> {{ __('SMS List') }}
                    </h5>
                    <select wire:model.live="perPage" class="form-select form-select-sm per-page-width" >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="card-body">
                    <div wire:loading class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('Loading...') }}</span>
                        </div>
                    </div>

                    <div wire:loading.remove>
                        @if($smsList->count() > 0)
                            <div class="row g-3">
                                @foreach($smsList as $sms)
                                    <div class="col-12">
                                        <div class="card border shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1 fw-bold">
                                                            <i class="ri-message-2-line text-primary me-1"></i>
                                                            SMS #{{ $sms->id }}
                                                        </h6>
                                                        <small class="text-muted d-block">
                                                            @if($sms->user_name)
                                                                <i class="ri-user-line me-1"></i>{{ $sms->user_name }} (ID: {{ $sms->created_by ?? 'N/A' }})
                                                            @else
                                                                <span class="badge bg-secondary">System</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <button wire:click="viewSms({{ $sms->id }})" class="btn btn-sm btn-soft-primary">
                                                        <i class="ri-eye-line align-middle"></i> {{ __('View') }}
                                                    </button>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="ri-phone-line me-1"></i>{{ __('Phone Number') }}
                                                        </small>
                                                        <strong class="d-block">{{ $sms->destination_number ?? 'N/A' }}</strong>
                                                        <small class="text-muted">{{ __('From') }}: {{ $sms->source_number ?? 'N/A' }}</small>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="ri-calendar-line me-1"></i>{{ __('Date') }}
                                                        </small>
                                                        <strong class="d-block">{{ $sms->created_at->format('Y-m-d') }}</strong>
                                                        <small class="text-muted">{{ $sms->created_at->format('H:i:s') }}</small>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="ri-message-line me-1"></i>{{ __('Message') }}
                                                        </small>
                                                        <span class="d-block text-break">
                                                            @php
                                                                $message = $sms->message ?? '';
                                                            @endphp
                                                            @if(strlen($message) > 50)
                                                                {{ substr($message, 0, 50) }}...
                                                            @else
                                                                {{ $message }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="ri-information-line align-middle me-1"></i>
                                {{ __('No SMS found') }}
                            </div>
                        @endif

                        @if($smsList->hasPages())
                            <div class="mt-4">
                                <div class="d-flex justify-content-center">
                                    {{ $smsList->links() }}
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        {{ __('Showing') }} {{ $smsList->firstItem() ?? 0 }}
                                        {{ __('to') }} {{ $smsList->lastItem() ?? 0 }}
                                        {{ __('of') }} {{ $smsList->total() }} {{ __('entries') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SMS Detail Modal -->
    @if($showDetailModal && $selectedSms)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-soft-primary">
                        <h5 class="modal-title">
                            <i class="ri-message-2-line align-middle me-1"></i> {{ __('SMS Details') }}
                        </h5>
                        <button type="button" wire:click="closeModal" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('SMS ID') }}</label>
                                    <p class="form-control-static">{{ $selectedSms['sms']->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('Created At') }}</label>
                                    <p class="form-control-static">{{ $selectedSms['sms']->created_at }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('Destination Number') }}</label>
                                    <p class="form-control-static">{{ $selectedSms['sms']->destination_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('Source Number') }}</label>
                                    <p class="form-control-static">{{ $selectedSms['sms']->source_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if($selectedSms['user'])
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('User') }}</label>
                                        <p class="form-control-static">
                                            {{ $selectedSms['user']->enFirstName ?? '' }} {{ $selectedSms['user']->enLastName ?? '' }}
                                            (ID: {{ $selectedSms['user']->id ?? 'N/A' }})
                                        </p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('Message') }}</label>
                                    <div class="alert alert-info">
                                        {{ $selectedSms['sms']->message ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
