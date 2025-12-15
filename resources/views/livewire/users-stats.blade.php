@php
    $currency = config('app.currency');
@endphp
<div class="card-body" id="users-stats">
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4 bg-primary bg-opacity-10">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-lg bg-primary bg-gradient rounded-3 d-flex align-items-center justify-content-center">
                                <i class="ri-exchange-dollar-line fs-1 text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-uppercase fw-semibold text-primary mb-2 fs-6 letter-spacing-1">
                                {{__('Cash Balance')}}
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary bg-opacity-75 fs-7 px-3 py-2 fw-semibold"
                                      title="{{ __('Admin') }}">
                                    <i class="ri-building-line align-bottom me-1" aria-hidden="true"></i>
                                    {{number_format($adminCash, 2)}}
                                </span>
                                <span class="badge bg-info fs-7 px-3 py-2 fw-semibold" title="{{ __('Users') }}">
                                    <i class="ri-user-line align-bottom me-1" aria-hidden="true"></i>
                                    {{number_format($usersCashBalance, 2)}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top border-primary border-opacity-25">
                        <div class="d-flex align-items-end justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">{{__('Total Balance')}}</p>
                                <h2 class="mb-0 fw-bold text-primary ">
                                    {{formatSolde($totalCashBalance,3)}}
                                </h2>
                            </div>
                            <span class="text-muted fs-4 fw-semibold mb-2">{{$currency}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4 bg-success bg-opacity-10">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-lg bg-success bg-gradient rounded-3 d-flex align-items-center justify-content-center">
                                <i class="ri-shopping-cart-2-line fs-1 text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-uppercase fw-semibold text-success mb-1 fs-6 letter-spacing-1">
                                {{__('BFS')}}
                            </h5>
                            <p class="text-muted mb-0 small">{{__('Business Flow System')}}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top border-success border-opacity-25">
                        <div class="d-flex align-items-end justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">{{__('Current Balance')}}</p>
                                <h2 class="mb-0 fw-bold text-success ">
                                    {{formatSolde($bfsBalance,3)}}
                                </h2>
                            </div>
                            <span class="text-muted fs-4 fw-semibold mb-2">{{config('app.currency')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4 bg-warning bg-opacity-10">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-lg bg-warning bg-gradient rounded-3 d-flex align-items-center justify-content-center">
                                <i class="ri-percent-line fs-1 text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-uppercase fw-semibold text-warning mb-1 fs-6 letter-spacing-1">
                                {{__('Discount Balance')}}
                            </h5>
                            <p class="text-muted mb-0 small">{{__('Available Discounts')}}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top border-warning border-opacity-25">
                        <div class="d-flex align-items-end justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">{{__('Total Amount')}}</p>
                                <h2 class="mb-0 fw-bold text-warning ">
                                    {{formatSolde($discountBalance,3)}}
                                </h2>
                            </div>
                            <span class="text-muted fs-4 fw-semibold mb-2">{{config('app.currency')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-md bg-info bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center">
                                <i class="ri-message-line fs-3 text-info" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold text-info mb-1 fs-7">
                                {{__('SMS Balance')}}
                            </p>
                            <h4 class="mb-0 fw-bold text-dark">
                                {{$smsBalance}}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info  py-2">
                    <small class="text-info fw-medium">
                        <i class="ri-information-line me-1"></i>
                        {{__('Available SMS Credits')}}
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-md bg-danger bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center">
                                <i class="ri-stackshare-line fs-3 text-danger" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold text-danger mb-1 fs-7">
                                {{__('Shares Sold')}}
                            </p>
                            <div class="d-flex align-items-baseline">
                                <h4 class="mb-0 fw-bold text-dark me-2">
                                    {{formatSolde($sharesSold,3)}}
                                </h4>
                                <small class="text-muted fw-medium">{{config('app.currency')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-danger  py-2">
                    <small class="text-danger fw-medium">
                        <i class="ri-arrow-right-up-line me-1"></i>
                        {{__('Total Shares Transactions')}}
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-md bg-secondary bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center">
                                <i class="ri-swap-line fs-3 text-secondary" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold text-secondary mb-1 fs-7">
                                {{__('Shares Revenue')}}
                            </p>
                            <div class="d-flex align-items-baseline">
                                <h4 class="mb-0 fw-bold text-dark me-2">
                                    {{formatSolde($sharesRevenue,3)}}
                                </h4>
                                <small class="text-muted fw-medium">{{config('app.currency')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary  py-2">
                    <small class="text-secondary fw-medium">
                        <i class="ri-funds-line me-1"></i>
                        {{__('Generated Revenue')}}
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-md bg-dark bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center">
                                <i class="ri-exchange-funds-line fs-3 text-dark" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold text-dark mb-1 fs-7">
                                {{__('Cash Flow')}}
                            </p>
                            <div class="d-flex align-items-baseline">
                                <h4 class="mb-0 fw-bold text-dark me-2">
                                    {{formatSolde($cashFlow,3)}}
                                </h4>
                                <small class="text-muted fw-medium">{{$currency}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-dark  py-2">
                    <small class="text-dark fw-medium">
                        <i class="ri-line-chart-line me-1"></i>
                        {{__('Transaction Flow')}}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

