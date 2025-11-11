@if($orderDeals->count())
    <div class="col-md-12">
        <div class="card mt-2 border-0 shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h6 class="card-title mb-0">
                    <i class="ri-bar-chart-line text-primary me-2"></i>{{__('Order deals Turnovers')}}
                </h6>
            </div>
            <div class="card-body">
                @foreach($orderDeals as $key => $orderDealsItem)
                    <div class="card mb-3 border-success shadow-sm">
                        <div class="card-header bg-success-subtle">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary fs-14">
                                        <i class="ri-hashtag me-1"></i>{{$orderDealsItem->id}}
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="ri-gift-line me-1"></i>{{__('Deal')}}
                                    </span>
                                </div>
                                <div>
                                    @if(\App\Models\User::isSuperAdmin())
                                        <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDealsItem->deals()->first()->id])}}" class="badge bg-info text-white text-decoration-none">
                                            {{$orderDealsItem->deals()->first()->id}} - {{$orderDealsItem->deals()->first()->name}}
                                        </a>
                                    @else
                                        <span class="badge bg-info">
                                            {{$orderDealsItem->deals()->first()->id}} - {{$orderDealsItem->deals()->first()->name}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Total Amount -->
                                <div class="col-md-4">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="card-title mb-0">
                                                <i class="ri-money-dollar-circle-line text-warning me-2"></i>{{__('Total amount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <span class="text-warning fs-16">
                                                {{$orderDealsItem->total_amount}} {{config('app.currency')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner Discount -->
                                <div class="col-md-4">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="card-title mb-0">
                                                <i class="ri-team-line text-success me-2"></i>{{__('Partner discount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">{{__('Discount')}}</span>
                                                <span class="text-success fs-14">
                                                    {{$orderDealsItem->partner_discount}} {{config('app.currency')}}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">{{__('After discount')}}</span>
                                                <span class="text-primary fs-14">
                                                    {{$orderDealsItem->amount_after_partner_discount}} {{config('app.currency')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2earn Discount -->
                                <div class="col-md-4">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="card-title mb-0">
                                                <i class="ri-percent-line text-success me-2"></i>{{__('Earn discount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">{{__('Discount')}}</span>
                                                <span class="text-success fs-14">
                                                    {{$orderDealsItem->earn_discount}} {{config('app.currency')}}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">{{__('After discount')}}</span>
                                                <span class="text-primary fs-14">
                                                    {{$orderDealsItem->amount_after_earn_discount}} {{config('app.currency')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Deal Discount -->
                                <div class="col-md-4">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="card-title mb-0">
                                                <i class="ri-gift-line text-success me-2"></i>{{__('Deal discount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">{{__('Discount')}}</span>
                                                <span class="text-success fs-14">
                                                    {{$orderDealsItem->deal_discount}} {{config('app.currency')}}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">{{__('After discount')}}</span>
                                                <span class="text-primary fs-14">
                                                    {{$orderDealsItem->amount_after_deal_discount}} {{config('app.currency')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Discount -->
                                <div class="col-md-4">
                                    <div class="card border-info h-100">
                                        <div class="card-header bg-info-subtle">
                                            <h6 class="card-title mb-0 text-info">
                                                <i class="ri-discount-percent-line me-2"></i>{{__('Total discount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <span class="text-info fs-16">
                                                {{$orderDealsItem->total_discount}} {{config('app.currency')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Final Discount -->
                                <div class="col-md-4">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success-subtle">
                                            <h6 class="card-title mb-0 text-success">
                                                <i class="ri-checkbox-circle-line me-2"></i>{{__('Final discount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">{{__('Amount')}}</span>
                                                <span class="text-success fs-14">
                                                    {{$orderDealsItem->final_discount}} {{config('app.currency')}}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">{{__('Percentage')}}</span>
                                                <span class="text-success-subtle text-success">
                                                    {{$orderDealsItem->final_discount_percentage * 100}} {{config('app.percentage')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lost Discount -->
                                <div class="col-md-6">
                                    <div class="card border-warning h-100">
                                        <div class="card-header bg-warning-subtle">
                                            <h6 class="card-title mb-0 text-warning">
                                                <i class="ri-error-warning-line me-2"></i>{{__('Lost discount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <span class="text-warning fs-16">
                                                {{$orderDealsItem->lost_discount}} {{config('app.currency')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Final Amount -->
                                <div class="col-md-6">
                                    <div class="card border-danger h-100">
                                        <div class="card-header bg-danger-subtle">
                                            <h6 class="card-title mb-0 text-danger">
                                                <i class="ri-wallet-3-line me-2"></i>{{__('Final amount')}}
                                            </h6>
                                        </div>
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <span class="text-danger fs-16">
                                                {{$orderDealsItem->final_amount}} {{config('app.currency')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
