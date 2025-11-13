<div class="card-body bg-light" id="users-stats">
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <article class="card border-0 shadow-lg h-100 overflow-hidden rounded-3">
                <div class="card-body p-4 bg-gradient position-relative" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.15) 100%);">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-title bg-primary text-white rounded-circle fs-1 shadow">
                                        <i class="ri-exchange-dollar-line" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-uppercase fw-bold text-primary mb-1 fs-6">
                                        {{__('Cash Balance')}}
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge rounded-pill bg-primary fs-7 px-3 py-2" title="{{ __('Admin') }}">
                                            <i class="ri-building-line align-bottom me-1" aria-hidden="true"></i>
                                            {{number_format($adminCash, 2)}}
                                        </span>
                                        <span class="badge rounded-pill bg-info fs-7 px-3 py-2" title="{{ __('Users') }}">
                                            <i class="ri-user-line align-bottom me-1" aria-hidden="true"></i>
                                            {{number_format($usersCashBalance, 2)}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-top border-primary border-opacity-25">
                        <div class="d-flex align-items-baseline">
                            <h2 class="mb-0 display-5 fw-bold text-primary me-2">
                                {{formatSolde($totalCashBalance)}}
                            </h2>
                            <span class="text-muted fs-5 fw-semibold">{{config('app.currency')}}</span>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-4 col-md-6">
            <article class="card border-0 shadow-lg h-100 overflow-hidden rounded-3">
                <div class="card-body p-4 bg-gradient position-relative" style="background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(25, 135, 84, 0.15) 100%);">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-title bg-success text-white rounded-circle fs-1 shadow">
                                        <i class="ri-shopping-cart-2-line" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-uppercase fw-bold text-success mb-0 fs-6">
                                        {{__('BFS')}}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-top border-success border-opacity-25">
                        <div class="d-flex align-items-baseline">
                            <h2 class="mb-0 display-5 fw-bold text-success me-2">
                                {{$bfsBalance}}
                            </h2>
                            <span class="text-muted fs-5 fw-semibold">{{config('app.currency')}}</span>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-4 col-md-6">
            <article class="card border-0 shadow-lg h-100 overflow-hidden rounded-3">
                <div class="card-body p-4 bg-gradient position-relative" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(255, 193, 7, 0.15) 100%);">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-title bg-warning text-white rounded-circle fs-1 shadow">
                                        <i class="ri-percent-line" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-uppercase fw-bold text-warning mb-0 fs-6">
                                        {{__('Discount Balance')}}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-top border-warning border-opacity-25">
                        <div class="d-flex align-items-baseline">
                            <h2 class="mb-0 display-5 fw-bold text-warning me-2">
                                {{$discountBalance}}
                            </h2>
                            <span class="text-muted fs-5 fw-semibold">{{config('app.currency')}}</span>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <article class="card border border-info border-opacity-25 shadow h-100 overflow-hidden rounded-3 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-3 fs-2">
                                <i class="ri-message-line" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-semibold text-info mb-1 fs-7 lh-sm">
                                {{__('sms balance')}}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-info border-opacity-10">
                        <h3 class="mb-0 fs-2 fw-bold text-dark">
                            {{$smsBalance}}
                        </h3>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-3 col-md-6">
            <article class="card border border-danger border-opacity-25 shadow h-100 overflow-hidden rounded-3 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-3 fs-2">
                                <i class="ri-stackshare-line" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-semibold text-danger mb-1 fs-7 lh-sm">
                                {{__('Shares Sold')}}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-danger border-opacity-10">
                        <div class="d-flex align-items-baseline">
                            <h3 class="mb-0 fs-2 fw-bold text-dark me-2">
                                {{$sharesSold}}
                            </h3>
                            <small class="text-muted fw-semibold">{{config('app.currency')}}</small>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-3 col-md-6">
            <article class="card border border-secondary border-opacity-25 shadow h-100 overflow-hidden rounded-3 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-secondary bg-opacity-10 text-secondary rounded-3 fs-2">
                                <i class="ri-swap-line" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-semibold text-secondary mb-1 fs-7 lh-sm">
                                {{__('Shares Revenue')}}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-secondary border-opacity-10">
                        <div class="d-flex align-items-baseline">
                            <h3 class="mb-0 fs-2 fw-bold text-dark me-2">
                                {{$sharesRevenue}}
                            </h3>
                            <small class="text-muted fw-semibold">{{config('app.currency')}}</small>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-3 col-md-6">
            <article class="card border border-dark border-opacity-25 shadow h-100 overflow-hidden rounded-3 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-dark bg-opacity-10 text-dark rounded-3 fs-2">
                                <i class="ri-exchange-funds-line" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-semibold text-dark mb-1 fs-7 lh-sm">
                                {{__('Cash Flow')}}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-dark border-opacity-10">
                        <div class="d-flex align-items-baseline">
                            <h3 class="mb-0 fs-2 fw-bold text-dark me-2">
                                {{$cashFlow}}
                            </h3>
                            <small class="text-muted fw-semibold">{{config('app.currency')}}</small>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

