@php
    $currency = config('app.currency');
@endphp
<div class="row">
    <div class="col-12 card btn-light">
        <div class="card-body row g-2"
             title="{{__('Soldes calculated at')}} : {{Carbon\Carbon::now()->toDateTimeString()}}">
            <div class="col-md-4 col-lg-4">
                <div class="solde-cash card card-animate shadow-sm hover-scale h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/nlmjynuq.json') }}" trigger="loop"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Cash balance') }}</h6>
                                    <a href="{{route('user_balance_cb' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-info">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div>
                                @php
                                    $cb_asd = floatval($cashBalance) - floatval($arraySoldeD[0]);
                                    $cb_class = $cb_asd >= 0 ? 'success' : 'danger';
                                    $cb_prefix = $cb_asd > 0 ? '+' : '';
                                    $cb_icon = $cb_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                                @endphp
                                <span class="badge bg-{{ $cb_class }} align-middle">
                                {{ $cb_prefix }}{{ formatSolde($cb_asd) }}
                                <i class="{{ $cb_icon }} align-middle ms-1"></i>
                            </span>
                            </div>
                        </div>

                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Cash balance') }}: {{ formatSolde($cashBalance) }}">
                                    @if(app()->getLocale()!="ar")
                                        <span class="me-1">{{$currency}}</span>
                                        <span class="counter-value"
                                              data-target="{{intval($cashBalance)}}">{{formatSolde($cashBalance,0)}}</span>
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($cashBalance) ? $decimalSeperator . getDecimals($cashBalance) : '' }}</small>
                                    @else
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($cashBalance) ? getDecimals($cashBalance) . $decimalSeperator : '' }}</small>
                                        <span class="counter-value">{{intval($cashBalance)}}</span>
                                        <span class="ms-1">{{$currency}}</span>
                                    @endif
                                </h3>
                            </div>
                            <div class="text-end text-muted small">{{ __('updated') }}
                                <br><small>{{ Carbon\Carbon::now()->format('H:i') }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4">
                <div class="solde-bfs card card-animate shadow-sm hover-scale h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon
                                        src="{{ URL::asset('build/icons/146-basket-trolley-shopping-card-gradient-edited.json') }}"
                                        trigger="loop" colors="primary:#464fed,secondary:#bc34b6"
                                        style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Balance for Shopping') }}</h6>
                                    <a href="{{route('user_balance_bfs' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-info">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div>
                                @php
                                    $bfs_asd = $balanceForSopping - floatval($arraySoldeD[1]);
                                    $bfs_class = $bfs_asd >= 0 ? 'success' : 'danger';
                                    $bfs_prefix = $bfs_asd > 0 ? '+' : '';
                                    $bfs_icon = $bfs_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                                @endphp
                                <span class="badge bg-{{ $bfs_class }} align-middle">
                                {{ $bfs_prefix }}{{ formatSolde($bfs_asd) }}
                                <i class="{{ $bfs_icon }} align-middle ms-1"></i>
                            </span>
                            </div>
                        </div>

                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Balance for Shopping') }}: {{ formatSolde($balanceForSopping) }}">
                                    @if(app()->getLocale()!="ar")
                                        <span class="me-1">{{$currency}}</span>
                                        <span class="counter-value"
                                              data-target="{{intval($balanceForSopping)}}">{{formatSolde($balanceForSopping,0)}}</span>
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($balanceForSopping) ? $decimalSeperator . getDecimals($balanceForSopping) : '' }}</small>
                                    @else
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($balanceForSopping) ? getDecimals($balanceForSopping) . $decimalSeperator : '' }}</small>
                                        <span class="counter-value">{{formatSolde($balanceForSopping,0)}}</span>
                                        <span class="ms-1">{{$currency}}</span>
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4">
                <div class="solde-discount card card-animate shadow-sm hover-scale h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/qrbokoyz.json') }}" trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Discounts Balance') }}</h6>
                                    <a href="{{route('user_balance_db' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-info">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div>
                                @php
                                    $db_asd = $discountBalance - floatval($arraySoldeD[2]);
                                    $db_class = $db_asd >= 0 ? 'success' : 'danger';
                                    $db_prefix = $db_asd > 0 ? '+' : '';
                                    $db_icon = $db_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                                @endphp
                                <span class="badge bg-{{ $db_class }} align-middle">
                                {{ $db_prefix }}{{ formatSolde($db_asd) }}
                                <i class="{{ $db_icon }} align-middle ms-1"></i>
                            </span>
                            </div>
                        </div>

                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Discounts Balance') }}: {{ formatSolde($discountBalance) }}">
                                    @if(app()->getLocale()!="ar")
                                        <span class="me-1">{{$currency}}</span>
                                        <span class="counter-value"
                                              data-target="{{intval($discountBalance)}}">{{intval($discountBalance)}}</span>
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($discountBalance) ? $decimalSeperator . getDecimals($discountBalance) : '' }}</small>
                                    @else
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($discountBalance) ? getDecimals($discountBalance) . $decimalSeperator : '' }}</small>
                                        <span class="counter-value">{{formatSolde($discountBalance,0)}}</span>
                                        <span class="ms-1">{{$currency}}</span>
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-sms card card-animate shadow-sm hover-scale h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon
                                        src="{{ URL::asset('build/icons/981-consultation-gradient-edited.json') }}"
                                        trigger="loop" colors="primary:#464fed,secondary:#bc34b6"
                                        style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('SMS Solde') }}</h6>
                                    <a href="{{route('user_balance_sms' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-info">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div class="text-success small">{{ $SMSBalance }}</div>
                        </div>
                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('SMS Solde') }}: {{ $SMSBalance }}">
                                    <span class="counter-value" data-target="{{$SMSBalance}}">{{$SMSBalance}}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-tree card card-animate shadow-sm hover-scale h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/1855-palmtree.json') }}"
                                               trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Tree Solde') }}</h6>
                                    <a href="{{route('user_balance_tree' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-info">{{ __('see_details') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Tree Solde') }}: {{ $treeBalance }}%">
                                    <span>{{ $treeBalance }}</span> %
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-chance card card-animate shadow-sm hover-scale h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/1471-dice-cube.json') }}"
                                               trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Chance Sold') }}</h6>
                                    <a href="{{route('user_balance_chance' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-info">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div class="text-success small">{{$chanceBalance}}</div>
                        </div>
                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Chance Sold') }}: {{$chanceBalance}}">
                                <span class="counter-value"
                                      data-target="{{$chanceBalance}}">{{$chanceBalance}}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-action card card-animate shadow-sm hover-scale h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/wired-gradient-751-share.json') }}"
                                               trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Actions (Shares)') }}</h6>
                                    <a href="{{route('user_balance_shares' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-info">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div class="text-success small">{{$actualActionValue['int']}}
                                .{{$actualActionValue['2Fraction']}}<small
                                    class="action_fraction">{{$actualActionValue['3_2Fraction']}}</small></div>
                        </div>
                        <div class="mt-auto">
                            <div class="row">
                                <div class="col-7">
                                    <h3 class="mb-1 fs-16 fw-bold ff-secondary col-6"
                                        aria-label="{{ __('Actions (Shares)') }}: {{ formatSolde($userSelledAction,0) }}">
                                <span class="counter-value"
                                      data-target="{{$userSelledAction}}">{{formatSolde($userSelledAction,0)}}</span>
                                        <small class="text-muted fs-10">({{$actionsValues}}) <span
                                                class="text-muted">{{$currency}}</span></small>
                                    </h3>
                                </div>
                                <div class="col-5">
                                           <span class="badge bg-light text-success ms-2"> <i
                                                   class="ri-arrow-up-line align-middle"></i> {{$userActualActionsProfit}} <span
                                                   class="text-muted">{{$currency}}</span></span>
                                </div>
                                <div class="col-12">
                                    <a href="{{route('business_hub_trading',app()->getLocale())}}"
                                       class="btn btn-sm float-end @if($flash) btn-flash @else btn-soft-secondary @endif">{{ __('Buy Shares') }}</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
