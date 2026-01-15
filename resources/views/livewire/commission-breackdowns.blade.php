@if(count($commissions))
    <div class="my-2">
        <h6 class="card-title my-2">
            <i class="ri-bar-chart-box-line text-primary me-2"></i>{{__('Commission break down')}}
        </h6>
        @foreach($commissions as $key => $commission)
            <div class="card mb-3">
                <div class="card-header ">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-dark fs-14">
                                        <i class="ri-hashtag me-1"></i>{{$key+1}}
                                    </span>
                            <span title="{{__('Commission brackdown Status')}}" class="badge fs-14
                                     @if($commission->type->value==\App\Enums\CommissionTypeEnum::IN->value)
                                     bg-primary
                                     @elseif($commission->type->value==\App\Enums\CommissionTypeEnum::OUT->value)
                                     bg-secondary
                                     @else
                                     bg-warning
                                     @endif
                                     ">
                                        <i class="ri-arrow-left-right-line me-1"></i>{{__(\App\Enums\CommissionTypeEnum::tryFrom($commission->type->value)->name)}}
                                    </span>
                            @if($commission->trigger)
                                <span class="text-danger fs-14" title="{{__('Order trigger')}}">
                                            <i class="ri-flashlight-line me-1"></i>{{$commission->trigger}}
                                        </span>
                            @endif
                        </div>
                        <small class="text-muted">
                            <i class="ri-time-line me-1"></i>{{$commission->created_at}}
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Turnover Section -->
                        <div class="col-lg-4">
                            <div class="card border h-100">
                                <div class="card-header bg-info-subtle">
                                    <h6 class="card-title mb-0 text-info">
                                        <i class="ri-exchange-line me-2"></i>{{__('Turnover')}}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($commission->type->value!==\App\Enums\CommissionTypeEnum::OUT->value)
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                                    <span class="text-muted">
                                                        <i class="ri-history-line me-1"></i>{{__('Old')}}
                                                    </span>
                                            <span class="text-info-subtle text-info fs-14">
                                                        {{formatSolde($commission->old_turnover)}} {{config('app.currency')}}
                                                    </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">
                                                        <i class="ri-refresh-line me-1"></i>{{__('New')}}
                                                    </span>
                                            <span class="text-info fs-14">
                                                        {{formatSolde($commission->new_turnover)}} {{config('app.currency')}}
                                                    </span>
                                        </div>
                                    @else
                                        <div class="alert alert-light material-shadow mb-0 text-center" role="alert">
                                            <i class="ri-information-line me-1"></i>{{__('No data')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Value & Commission -->
                        <div class="col-lg-4">
                            <div class="card border h-100">
                                <div class="card-header bg-success-subtle">
                                    <h6 class="card-title mb-0 text-success">
                                        <i class="ri-price-tag-3-line me-2"></i>{{__('Purchase value')}}
                                        & {{__('Commission')}}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                                <span class="text-muted">
                                                    <i class="ri-shopping-cart-line me-1"></i>{{__('Purchase value')}}
                                                </span>
                                        <span class="text-warning fs-14">
                                                    {{formatSolde($commission->purchase_value)}} {{config('app.currency')}}
                                                </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">
                                                    <i class="ri-money-dollar-circle-line me-1"></i>{{__('Commission')}}
                                                </span>
                                        <span class="text-success fs-14">
                                                    {{formatSolde($commission->commission_value)}} {{config('app.currency')}}
                                                </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">
                                                    <i class="ri-percent-line me-1"></i>{{__('Percentage')}}
                                                </span>
                                        <span class="text-success-subtle text-success">
                                                    {{formatSolde($commission->commission_percentage)}} %
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional & Camembert -->
                        <div class="col-lg-4">
                            <div class="card border h-100">
                                <div class="card-header bg-warning-subtle">
                                    <h6 class="card-title mb-0 text-warning">
                                        <i class="ri-pie-chart-line me-2"></i>{{__('Additional')}} & {{__('Camembert')}}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($commission->type->value!==\App\Enums\CommissionTypeEnum::OUT->value)
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                                    <span class="text-muted">
                                                        <i class="ri-add-circle-line me-1"></i>{{__('Additional')}}
                                                    </span>
                                            <span class="text-warning fs-14">
                                                        {{formatSolde($commission->additional_amount)}} {{config('app.currency')}}
                                                    </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">
                                                        <i class="ri-pie-chart-2-line me-1"></i>{{__('Camembert')}}
                                                    </span>
                                            <span class="text-success fs-14">
                                                        {{formatSolde($commission->camembert)}} {{config('app.currency')}}
                                                    </span>
                                        </div>
                                    @else
                                        <div class="alert alert-light material-shadow mb-0 text-center" role="alert">
                                            <i class="ri-information-line me-1"></i>{{__('No data')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Camembert Parts (Full Width) -->
                        @if($commission->type->value!==\App\Enums\CommissionTypeEnum::OUT->value)
                            <div class="col-12">
                                <div class="card border-success">
                                    <div class="card-header bg-success-subtle">
                                        <h6 class="card-title mb-0 text-success">
                                            <i class="ri-stack-line me-2"></i>{{__('Camembert parts')}}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <!-- Cash Company Profit -->
                                            <div class="col-md-6 col-lg-3">
                                                <div class="card border-primary h-100">
                                                    <div class="card-header bg-primary-subtle">
                                                        <small class="fw-bold text-primary"
                                                               title="{{__('Cash company profit')}}">
                                                            <i class="ri-building-line me-1"></i>{{__('Cash company profit')}}
                                                        </small>
                                                    </div>
                                                    <div
                                                        class="card-body d-flex align-items-center justify-content-center">
                                                                <span class="text-primary fs-14">
                                                                    {{$commission->cash_company_profit}} {{config('app.currency')}}
                                                                </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cash Jackpot -->
                                            <div class="col-md-6 col-lg-3">
                                                <div class="card border-warning h-100">
                                                    <div class="card-header bg-warning-subtle">
                                                        <small class="fw-bold text-warning"
                                                               title="{{__('Cash jackpot')}}">
                                                            <i class="ri-trophy-line me-1"></i>{{__('Cash jackpot')}}
                                                        </small>
                                                    </div>
                                                    <div
                                                        class="card-body d-flex align-items-center justify-content-center">
                                                                <span class="text-warning fs-14">
                                                                    {{$commission->cash_jackpot}} {{config('app.currency')}}
                                                                </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cash Tree -->
                                            <div class="col-md-6 col-lg-3">
                                                <div class="card border-success h-100">
                                                    <div class="card-header bg-success-subtle">
                                                        <small class="fw-bold text-success" title="{{__('Cash tree')}}">
                                                            <i class="ri-git-branch-line me-1"></i>{{__('Cash tree')}}
                                                        </small>
                                                    </div>
                                                    <div
                                                        class="card-body d-flex align-items-center justify-content-center">
                                                                <span class="text-success fs-14">
                                                                    {{$commission->cash_tree}} {{config('app.currency')}}
                                                                </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cash Cashback -->
                                            <div class="col-md-6 col-lg-3">
                                                <div class="card border-info h-100">
                                                    <div class="card-header bg-info-subtle">
                                                        <small class="fw-bold text-info"
                                                               title="{{__('Cash cashback')}}">
                                                            <i class="ri-refund-line me-1"></i>{{__('Cash cashback')}}
                                                        </small>
                                                    </div>
                                                    <div
                                                        class="card-body d-flex align-items-center justify-content-center">
                                                                <span class="text-info fs-14">
                                                                    {{formatSolde($commission->cash_cashback)}} {{config('app.currency')}}
                                                                </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
