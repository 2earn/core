<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Partner Payments') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Partner Payments') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row mb-2">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Total Payments')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-money-dollar-circle-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                {{number_format($stats['total_payments'])}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Pending Payments')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-warning fs-14 mb-0">
                                <i class="ri-time-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                {{number_format($stats['pending_payments'])}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Validated Payments')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-checkbox-circle-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                {{number_format($stats['validated_payments'])}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Rejected Payments')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-danger fs-14 mb-0">
                                <i class="ri-close-circle-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                {{number_format($stats['rejected_payments'])}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Total Amount (Validated)')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-info fs-14 mb-0">
                                <i class="ri-funds-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                {{number_format($stats['total_amount'], 2)}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 card shadow-sm">
            <div class="card-body p-0">
                <div class="card-header py-4">
                    <div class="row align-items-center g-3">
                        <!-- Search -->
                        <div class="col-sm-12 col-md-4">
                            <form class="items-center">
                                <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                                <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="ri-search-line text-muted"></i>
                                        </span>
                                    <input wire:model.live="search" type="text" id="simple-search"
                                           class="form-control border-start-0 ps-0"
                                           placeholder="{{__('Search payments...')}}">
                                </div>
                            </form>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-sm-6 col-md-2">
                            <select wire:model.live="statusFilter" class="form-select">
                                <option value="all">{{__('All Status')}}</option>
                                <option value="pending">{{__('Pending')}}</option>
                                <option value="validated">{{__('Validated')}}</option>
                                <option value="rejected">{{__('Rejected')}}</option>
                            </select>
                        </div>

                        <!-- Method Filter -->
                        <div class="col-sm-6 col-md-2">
                            <select wire:model.live="methodFilter" class="form-select">
                                <option value="">{{__('All Methods')}}</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{$method}}">{{ucfirst(str_replace('_', ' ', $method))}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reset Filters -->
                        <div class="col-sm-6 col-md-2">
                            <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                                <i class="ri-refresh-line align-middle me-1"></i>
                                {{__('Reset')}}
                            </button>
                        </div>

                        <!-- Create Button -->
                        @if(\Core\Models\Platform::havePartnerSpecialRole(auth()->user()->id))
                            <div class="col-sm-6 col-md-2 text-end">
                                <a href="{{route('partner_payment_manage', app()->getLocale())}}"
                                   class="btn btn-info waves-effect waves-light w-100">
                                    <i class="ri-add-line align-middle me-1"></i>
                                    {{__('Create')}}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <div class="row g-3">
                        @forelse($payments as $payment)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <!-- Header -->
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="badge bg-primary-subtle text-primary fs-6">#{{$payment->id}}</span>
                                            </div>
                                            <div>
                                                @if($payment->isValidated())
                                                    <span class="badge bg-success">
                                        <i class="ri-checkbox-circle-line align-middle"></i>
                                        {{__('Validated')}}
                                    </span>
                                                @elseif($payment->isRejected())
                                                    <span class="badge bg-danger">
                                        <i class="ri-close-circle-line align-middle"></i>
                                        {{__('Rejected')}}
                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                        <i class="ri-time-line align-middle"></i>
                                        {{__('Pending')}}
                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Amount -->
                                        <div class="mb-3">
                                            <h5 class="mb-1 text-primary">{{number_format($payment->amount, 2)}} {{__('USD')}}</h5>
                                            <small class="text-muted">
                                                <span class="badge bg-info-subtle text-info">
                                                    {{ucfirst(str_replace('_', ' ', $payment->method))}}
                                                </span>
                                            </small>
                                        </div>

                                        <!-- User Info -->
                                        <div class="mb-3 pb-3 border-bottom">
                                            <small class="d-block text-muted mb-2">{{__('Payer')}}</small>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-13">
                                                        {{substr($payment->user->name ?? 'U', 0, 1)}}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 small">{{$payment->user->name ?? 'N/A'}}</h6>
                                                    <small class="text-muted">ID: {{$payment->user_id}}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Partner Info -->
                                        <div class="mb-3 pb-3 border-bottom">
                                            <small class="d-block text-muted mb-2">{{__('Receiver')}}</small>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-soft-success text-success rounded-circle fs-13">
                                                        {{substr($payment->partner->name ?? 'P', 0, 1)}}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 small">{{$payment->partner->name ?? 'N/A'}}</h6>
                                                    <small class="text-muted">ID: {{$payment->partner_id}}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dates -->
                                        <div class="mb-3">
                                            <small class="d-block text-muted mb-2">{{__('Payment Date')}}</small>
                                            <small>{{$payment->payment_date?->format('Y-m-d H:i') ?? 'N/A'}}</small>
                                            @if($payment->isValidated())
                                                <br>
                                                <small class="text-muted">{{__('Validated')}} {{$payment->validated_at?->format('Y-m-d')}}</small>
                                            @elseif($payment->isRejected())
                                                <br>
                                                <small class="text-muted">{{__('Rejected')}} {{$payment->rejected_at?->format('Y-m-d')}}</small>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="card-footer bg-light border-top">
                                        <div class="hstack gap-2">
                                            <a href="{{route('partner_payment_detail', ['locale' => app()->getLocale(), 'id' => $payment->id])}}"
                                               class="btn btn-sm btn-info flex-grow-1" title="{{__('View Details')}}">
                                                <i class="ri-eye-line me-1"></i>
                                                {{__('View')}}
                                            </a>
                                            @if(\Core\Models\Platform::havePartnerSpecialRole(auth()->user()->id) && !$payment->isValidated() && !$payment->isRejected())
                                                <a href="{{route('partner_payment_manage', ['locale' => app()->getLocale(), 'id' => $payment->id])}}"
                                                   class="btn btn-sm btn-warning flex-grow-1" title="{{__('Edit')}}">
                                                    <i class="ri-edit-line me-1"></i>
                                                    {{__('Edit')}}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body py-5 text-center">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-1 d-block mb-3"></i>
                                            <p class="mb-0">{{__('No partner payments found')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{$payments->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

