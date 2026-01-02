<div class="container">
    @section('title')
        {{ __('Partner Request Details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('requests_partner', app()->getLocale()) }}">
                {{ __('Partner Requests') }}
            </a>
        @endslot
        @slot('title')
            {{ __('Partner Request Details') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">{{ __('Request Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>{{ __('Status') }}:</strong></label>
                        @if($partnerRequest->status == \App\Enums\BePartnerRequestStatus::InProgress->value)
                            <span class="badge bg-warning">{{ __('In Progress') }}</span>
                        @elseif($partnerRequest->status == \App\Enums\BePartnerRequestStatus::Validated->value)
                            <span class="badge bg-success">{{ __('Validated') }}</span>
                        @elseif($partnerRequest->status == \App\Enums\BePartnerRequestStatus::Validated2earn->value)
                            <span class="badge bg-info">{{ __('Validated 2earn') }}</span>
                        @elseif($partnerRequest->status == \App\Enums\BePartnerRequestStatus::Rejected->value)
                            <span class="badge bg-danger">{{ __('Rejected') }}</span>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>{{ __('Applicant Name') }}:</strong></label>
                            <p>{{ $partnerRequest->user?->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>{{ __('Email') }}:</strong></label>
                            <p>
                                <a href="mailto:{{ $partnerRequest->user?->email }}">
                                    {{ $partnerRequest->user?->email }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>{{ __('Company Name') }}:</strong></label>
                        <p>{{ $partnerRequest->company_name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>{{ __('Business Sector') }}:</strong></label>
                        <p>{{ $partnerRequest->businessSector?->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>{{ __('Platform URL') }}:</strong></label>
                        <p>
                            <a href="{{ $partnerRequest->platform_url }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-external-link-alt"></i> {{ $partnerRequest->platform_url }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>{{ __('Platform Description') }}:</strong></label>
                        <div class="border p-3 rounded bg-light">
                            <p>{{ $partnerRequest->platform_description }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>{{ __('Reason for Partnership Request') }}:</strong></label>
                        <div class="border p-3 rounded bg-light">
                            <p>{{ $partnerRequest->partnership_reason }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>{{ __('Request Date') }}:</strong></label>
                            <p>
                                @if($partnerRequest->request_date)
                                    @if(is_object($partnerRequest->request_date))
                                        {{ $partnerRequest->request_date->format('Y-m-d H:i') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($partnerRequest->request_date)->format('Y-m-d H:i') }}
                                    @endif
                                @else
                                    N/A
                                @endif

                            </p>
                        </div>
                        @if($partnerRequest->examination_date)
                            <div class="col-md-6">
                                <label class="form-label"><strong>{{ __('Examination Date') }}:</strong></label>
                                <p>
                                    @if($partnerRequest->examination_date)
                                        @if(is_object($partnerRequest->examination_date))
                                            {{ $partnerRequest->examination_date->format('Y-m-d H:i') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($partnerRequest->examination_date)->format('Y-m-d H:i') }}
                                        @endif
                                    @else
                                        N/A
                                    @endif


                                </p>
                            </div>
                        @endif
                    </div>

                    @if($partnerRequest->examiner)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>{{ __('Examiner') }}:</strong></label>
                                <p>{{ $partnerRequest->examiner?->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($partnerRequest->status == \App\Enums\BePartnerRequestStatus::Rejected->value && !is_null($partnerRequest->note))
                        <div class="mb-3">
                            <label class="form-label"><strong>{{ __('Rejection Reason') }}:</strong></label>
                            <div class="alert alert-danger">
                                <p>{{ $partnerRequest->note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($partnerRequest->status == \App\Enums\BePartnerRequestStatus::InProgress->value)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">{{ __('Actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <button type="button"
                                        class="btn btn-success w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#validateModal">
                                    <i class="fas fa-check"></i> {{ __('Validate Request') }}
                                </button>
                            </div>

                            <div class="col-md-6 mb-3">
                                <button type="button"
                                        class="btn btn-danger w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> {{ __('Reject Request') }}
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('requests_partner', app()->getLocale()) }}"
                                   class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-muted">{{ __('This request has already been reviewed') }}</p>
                        <a href="{{ route('requests_partner', app()->getLocale()) }}"
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="validateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Validate Partnership Request') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to validate this request?') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-success" wire:click="validatePartnerRequest()"
                            data-bs-dismiss="modal">
                        <i class="fas fa-check"></i> {{ __('Confirm Validation') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Reject Partnership Request') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="rejectPartnerRequest">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejectionNote" class="form-label">
                                {{ __('Reason for Rejection') }} <span class="text-danger">*</span>
                            </label>
                            <textarea id="rejectionNote"
                                      class="form-control @error('rejectionNote') is-invalid @enderror"
                                      wire:model.defer="rejectionNote"
                                      rows="4"
                                      placeholder="{{ __('Explain why this request is being rejected...') }}"
                                      required></textarea>
                            @error('rejectionNote')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> {{ __('Confirm Rejection') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

