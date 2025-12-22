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
                        @if(\App\Models\User::isSuperAdmin())
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
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Method')}}</th>
                            <th>{{__('User')}}</th>
                            <th>{{__('Partner')}}</th>
                            <th>{{__('Payment Date')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">#{{$payment->id}}</span>
                                </td>
                                <td>
                                    <strong>{{number_format($payment->amount, 2)}}</strong>
                                </td>
                                <td>
                                        <span class="badge bg-info-subtle text-info">
                                            {{ucfirst(str_replace('_', ' ', $payment->method))}}
                                        </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <div
                                                    class="avatar-title bg-soft-primary text-primary rounded-circle fs-13">
                                                    {{substr($payment->user->name ?? 'U', 0, 1)}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{$payment->user->name ?? 'N/A'}}</h6>
                                            <small class="text-muted">ID: {{$payment->user_id}}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <div
                                                    class="avatar-title bg-soft-success text-success rounded-circle fs-13">
                                                    {{substr($payment->partner->name ?? 'P', 0, 1)}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{$payment->partner->name ?? 'N/A'}}</h6>
                                            <small class="text-muted">ID: {{$payment->partner_id}}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small>{{$payment->payment_date?->format('Y-m-d H:i') ?? 'N/A'}}</small>
                                </td>
                                <td>
                                    @if($payment->isValidated())
                                        <span class="badge bg-success">
                                                <i class="ri-checkbox-circle-line align-middle"></i>
                                                {{__('Validated')}}
                                            </span>
                                        <br>
                                        <small class="text-muted">{{$payment->validated_at?->format('Y-m-d')}}</small>
                                    @else
                                        <span class="badge bg-warning">
                                                <i class="ri-time-line align-middle"></i>
                                                {{__('Pending')}}
                                            </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="hstack gap-2">
                                        <a href="{{route('partner_payment_detail', ['locale' => app()->getLocale(), 'id' => $payment->id])}}"
                                           class="btn btn-sm btn-info" title="{{__('View Details')}}">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if(\App\Models\User::isSuperAdmin() && !$payment->isValidated())
                                            <a href="{{route('partner_payment_manage', ['locale' => app()->getLocale(), 'id' => $payment->id])}}"
                                               class="btn btn-sm btn-warning" title="{{__('Edit')}}">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line fs-1"></i>
                                        <p class="mt-2">{{__('No partner payments found')}}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{$payments->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

