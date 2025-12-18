<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Partner Payment Details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Partner Payment Details') }}
        @endslot
        @slot('li_1')
            <a href="{{route('partner_payment_index', app()->getLocale())}}">{{ __('Partner Payments') }}</a>
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    @if($payment)
        <div class="row">
            <!-- Payment Information Card -->
            <div class="col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">
                                <i class="ri-money-dollar-circle-line me-2"></i>
                                {{__('Payment')}} #{{$payment->id}}
                            </h5>
                            @if($payment->isValidated())
                                <span class="badge bg-success">
                                    <i class="ri-checkbox-circle-line align-middle"></i>
                                    {{__('Validated')}}
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="ri-time-line align-middle"></i>
                                    {{__('Pending Validation')}}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Amount Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="bg-light p-4 rounded text-center">
                                    <h6 class="text-muted mb-2">{{__('Payment Amount')}}</h6>
                                    <h2 class="mb-0 text-primary fw-bold">
                                        {{number_format($payment->amount, 2)}} {{__('USD')}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-semibold">{{__('Payment Method')}}</label>
                                <p class="mb-0">
                                    <span class="badge bg-info-subtle text-info fs-6">
                                        {{ucfirst(str_replace('_', ' ', $payment->method))}}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-semibold">{{__('Payment Date')}}</label>
                                <p class="mb-0">
                                    <i class="ri-calendar-line me-1"></i>
                                    {{$payment->payment_date?->format('F d, Y H:i') ?? __('N/A')}}
                                </p>
                            </div>
                            @if($payment->demand_id)
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">{{__('Demand ID')}}</label>
                                    <p class="mb-0">
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{$payment->demand_id}}
                                        </span>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <!-- User Information -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-semibold">
                                    <i class="ri-user-line me-1"></i>{{__('Payer (User)')}}
                                </label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-4">
                                            {{substr($payment->user->name ?? 'U', 0, 1)}}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{$payment->user->name ?? __('N/A')}}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{__('ID')}}: {{$payment->user_id}}
                                            @if($payment->user->email)
                                                <br>{{$payment->user->email}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted fw-semibold">
                                    <i class="ri-user-star-line me-1"></i>{{__('Receiver (Partner)')}}
                                </label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-soft-success text-success rounded-circle fs-4">
                                            {{substr($payment->partner->name ?? 'P', 0, 1)}}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{$payment->partner->name ?? __('N/A')}}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{__('ID')}}: {{$payment->partner_id}}
                                            @if($payment->partner->email)
                                                <br>{{$payment->partner->email}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($payment->isValidated())
                            <hr class="my-4">
                            <!-- Validation Information -->
                            <div class="alert alert-success mb-0">
                                <h6 class="alert-heading">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    {{__('Validation Information')}}
                                </h6>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted fw-semibold small">{{__('Validated By')}}</label>
                                        <p class="mb-0">
                                            {{$payment->validator->name ?? __('N/A')}}
                                            <br>
                                            <small class="text-muted">{{__('ID')}}: {{$payment->validated_by}}</small>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted fw-semibold small">{{__('Validated At')}}</label>
                                        <p class="mb-0">
                                            {{$payment->validated_at?->format('F d, Y H:i')}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <button wire:click="goToList" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i>
                                {{__('Back to List')}}
                            </button>
                            <div class="hstack gap-2">
                                @if(\App\Models\User::isSuperAdmin())
                                    @if(!$payment->isValidated())
                                        <button wire:click="goToEdit" class="btn btn-warning">
                                            <i class="ri-edit-line me-1"></i>
                                            {{__('Edit')}}
                                        </button>
                                        <button wire:click="confirmValidation" class="btn btn-success">
                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            {{__('Validate Payment')}}
                                        </button>
                                        <button wire:click="deletePayment"
                                                wire:confirm="{{__('Are you sure you want to delete this payment?')}}"
                                                class="btn btn-danger">
                                            <i class="ri-delete-bin-line me-1"></i>
                                            {{__('Delete')}}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit & Timeline Card -->
            <div class="col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-time-line me-2"></i>
                            {{__('Timeline & Audit')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-primary rounded-circle">
                                                <i class="ri-add-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{__('Created')}}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{$payment->created_at?->format('F d, Y H:i')}}
                                            @if($payment->created_by)
                                                <br>{{__('By')}}: {{__('User')}} #{{$payment->created_by}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </li>
                            @if($payment->updated_at && $payment->updated_at != $payment->created_at)
                                <li class="mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-warning rounded-circle">
                                                    <i class="ri-edit-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">{{__('Last Updated')}}</h6>
                                            <p class="text-muted mb-0 small">
                                                {{$payment->updated_at?->format('F d, Y H:i')}}
                                                @if($payment->updated_by)
                                                    <br>{{__('By')}}: {{__('User')}} #{{$payment->updated_by}}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if($payment->isValidated())
                                <li class="mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-success rounded-circle">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">{{__('Validated')}}</h6>
                                            <p class="text-muted mb-0 small">
                                                {{$payment->validated_at?->format('F d, Y H:i')}}
                                                <br>{{__('By')}}: {{$payment->validator->name ?? __('N/A')}}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Quick Stats -->
                @if($payment->demand)
                    <div class="card shadow-sm mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-list-line me-2"></i>
                                {{__('Related Demand')}}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">{{__('Demand ID')}}</label>
                                <p class="mb-0 fw-semibold">{{$payment->demand->numeroReq}}</p>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted small">{{__('Amount')}}</label>
                                <p class="mb-0 fw-semibold">{{number_format($payment->demand->amount, 2)}}</p>
                            </div>
                            <div class="mb-0">
                                <label class="text-muted small">{{__('Status')}}</label>
                                <p class="mb-0 fw-semibold">{{$payment->demand->status}}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Validation Modal -->
        @if($showValidateModal)
            <div class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ri-checkbox-circle-line me-2"></i>
                                {{__('Confirm Payment Validation')}}
                            </h5>
                            <button type="button" class="btn-close" wire:click="$set('showValidateModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                {{__('Are you sure you want to validate this payment? This action cannot be undone.')}}
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <strong>{{__('Amount')}}:</strong>
                                </div>
                                <div class="col-6">
                                    {{number_format($payment->amount, 2)}}
                                </div>
                                <div class="col-6">
                                    <strong>{{__('Partner')}}:</strong>
                                </div>
                                <div class="col-6">
                                    {{$payment->partner->name ?? __('N/A')}}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showValidateModal', false)">
                                {{__('Cancel')}}
                            </button>
                            <button type="button" class="btn btn-success" wire:click="validatePayment">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                {{__('Confirm Validation')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

