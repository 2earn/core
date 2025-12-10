@php
    $currency = config('app.currency');
@endphp
<div class="row">
    <div class="col-12 card btn-light">
        <div class="card-header row">
            <div class="row m-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-2">{{__('Users balances Recaps')}} <small
                            class="text-muted  float-end">{{__('Soldes calculated at')}}
                            : {{Carbon\Carbon::now()->toDateTimeString()}}</small></h2>

                </div>
            </div>
        </div>
        <div class="card-body row g-2">
            <div class="col-md-4 col-lg-4">
                <div class="solde-cash card card-body card-animate shadow-sm hover-scale">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <lord-icon src="{{ URL::asset('build/icons/nlmjynuq.json') }}" trigger="loop"
                                           style="width:40px;height:40px"></lord-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h5 class="card-title mb-1">{{ __('Cash balance') }}</h5>
                            @php
                                $cb_asd = floatval($cashBalance) - floatval($arraySoldeD[0]);
                                $cb_class = $cb_asd >= 0 ? 'success' : 'danger';
                                $cb_prefix = $cb_asd > 0 ? '+' : '';
                                $cb_icon = $cb_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                            @endphp
                            <p class="text-muted mb-0">
                                <span class="badge bg-{{ $cb_class }} align-middle">
                                    {{ $cb_prefix }}{{ formatSolde($cb_asd) }}
                                    <i class="{{ $cb_icon }} align-middle ms-1"></i>
                                </span>
                            </p>
                        </div>
                    </div>
                    <h6 class="mb-1">
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
                    </h6>
                    <a href="{{route('user_balance_cb' , app()->getLocale() )}}"
                       class="btn btn-outline-primary btn-sm">{{ __('see_details') }}</a>
                </div>
            </div>

            <div class="col-md-4 col-lg-4">
                <div class="solde-bfs card card-body card-animate shadow-sm hover-scale">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <lord-icon
                                    src="{{ URL::asset('build/icons/146-basket-trolley-shopping-card-gradient-edited.json') }}"
                                    trigger="loop" colors="primary:#464fed,secondary:#bc34b6"
                                    style="width:40px;height:40px"></lord-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h5 class="card-title mb-1">{{ __('Balance for Shopping') }}</h5>
                            @php
                                $bfs_asd = $balanceForSopping - floatval($arraySoldeD[1]);
                                $bfs_class = $bfs_asd >= 0 ? 'success' : 'danger';
                                $bfs_prefix = $bfs_asd > 0 ? '+' : '';
                                $bfs_icon = $bfs_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                            @endphp
                            <p class="text-muted mb-0">
                                <span class="badge bg-{{ $bfs_class }} align-middle">
                                    {{ $bfs_prefix }}{{ formatSolde($bfs_asd) }}
                                    <i class="{{ $bfs_icon }} align-middle ms-1"></i>
                                </span>
                            </p>
                        </div>
                    </div>
                    <h6 class="mb-1">
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
                    </h6>
                    <a href="{{route('user_balance_bfs' , app()->getLocale() )}}"
                       class="btn btn-outline-primary btn-sm">{{ __('see_details') }}</a>
                </div>
            </div>

            <div class="col-md-4 col-lg-4">
                <div class="solde-discount card card-body card-animate shadow-sm hover-scale">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <lord-icon src="{{ URL::asset('build/icons/qrbokoyz.json') }}" trigger="loop"
                                           colors="primary:#464fed,secondary:#bc34b6"
                                           style="width:40px;height:40px"></lord-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h5 class="card-title mb-1">{{ __('Discounts Balance') }}</h5>
                            @php
                                $db_asd = $discountBalance - floatval($arraySoldeD[2]);
                                $db_class = $db_asd >= 0 ? 'success' : 'danger';
                                $db_prefix = $db_asd > 0 ? '+' : '';
                                $db_icon = $db_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                            @endphp
                            <p class="text-muted mb-0">
                                <span class="badge bg-{{ $db_class }} align-middle">
                                    {{ $db_prefix }}{{ formatSolde($db_asd) }}
                                    <i class="{{ $db_icon }} align-middle ms-1"></i>
                                </span>
                            </p>
                        </div>
                    </div>
                    <h6 class="mb-1">
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
                    </h6>
                    <a href="{{route('user_balance_db' , app()->getLocale() )}}"
                       class="btn btn-outline-primary btn-sm">{{ __('see_details') }}</a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-sms card card-body card-animate shadow-sm hover-scale">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <lord-icon
                                    src="{{ URL::asset('build/icons/981-consultation-gradient-edited.json') }}"
                                    trigger="loop" colors="primary:#464fed,secondary:#bc34b6"
                                    style="width:40px;height:40px"></lord-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h5 class="card-title mb-1">{{ __('SMS Solde') }}</h5>
                            <p class="text-muted mb-0">
                                <span class="badge bg-light text-success">{{ $SMSBalance }}</span>
                            </p>
                        </div>
                    </div>
                    <h6 class="mb-1">
                        <span class="counter-value" data-target="{{$SMSBalance}}">{{$SMSBalance}}</span>
                    </h6>
                    <a href="{{route('user_balance_sms' , app()->getLocale() )}}"
                       class="btn btn-outline-primary btn-sm">{{ __('see_details') }}</a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-tree card card-body card-animate shadow-sm hover-scale">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <lord-icon src="{{ URL::asset('build/icons/1855-palmtree.json') }}"
                                           trigger="loop"
                                           colors="primary:#464fed,secondary:#bc34b6"
                                           style="width:40px;height:40px"></lord-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h5 class="card-title mb-1">{{ __('Tree Solde') }}</h5>
                            <p class="text-muted mb-0">{{ __('Tree Balance') }}</p>
                        </div>
                    </div>
                    <h6 class="mb-1">
                        <span>{{ $treeBalance }}</span> %
                    </h6>
                    <a href="{{route('user_balance_tree' , app()->getLocale() )}}"
                       class="btn btn-outline-primary btn-sm">{{ __('see_details') }}</a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-chance card card-body card-animate shadow-sm hover-scale">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <lord-icon src="{{ URL::asset('build/icons/1471-dice-cube.json') }}"
                                           trigger="loop"
                                           colors="primary:#464fed,secondary:#bc34b6"
                                           style="width:40px;height:40px"></lord-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h5 class="card-title mb-1">{{ __('Chance Sold') }}</h5>
                            <p class="text-muted mb-0">
                                <span class="badge bg-light text-success">{{$chanceBalance}}</span>
                            </p>
                        </div>
                    </div>
                    <h6 class="mb-1">
                        <span class="counter-value" data-target="{{$chanceBalance}}">{{$chanceBalance}}</span>
                    </h6>
                    <a href="{{route('user_balance_chance' , app()->getLocale() )}}"
                       class="btn btn-outline-primary btn-sm">{{ __('see_details') }}</a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="solde-action card card-body card-animate shadow-sm hover-scale">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <lord-icon src="{{ URL::asset('build/icons/wired-gradient-751-share.json') }}"
                                           trigger="loop"
                                           colors="primary:#464fed,secondary:#bc34b6"
                                           style="width:40px;height:40px"></lord-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h5 class="card-title mb-1">{{ __('Actions (Shares)') }}</h5>
                            <p class="text-muted mb-0">
                                <span class="badge bg-light text-success">
                                    {{$actualActionValue['int']}}.{{$actualActionValue['2Fraction']}}<small
                                        class="action_fraction">{{$actualActionValue['3_2Fraction']}}</small>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class=" mb-2">
                        <span class="counter-value"
                              data-target="{{$userSelledAction}}">{{formatSolde($userSelledAction,0)}}</span>
                            <small class="text-muted fs-10">({{$actionsValues}}) <span
                                    class="text-muted">{{$currency}}</span></small>
                        </h6>
                        <p class="card-text text-muted float-end  mb-2">
                        <span class="badge bg-light text-success ms-2">
                            <i class="ri-arrow-up-line align-middle"></i> {{$userActualActionsProfit}} <span
                                class="text-muted">{{$currency}}</span>
                        </span>
                        </p>
                    </div>
                    <a href="{{route('business_hub_trading',app()->getLocale())}}"
                       class="btn btn-outline-primary btn-sm @if($flash) btn-flash @endif">{{ __('Buy Shares') }}</a>
                </div>
            </div>
        </div>
        <div class="card-footer text-center text-muted small">
            {{ __('updated') }}: {{ Carbon\Carbon::now()->format('H:i') }}
        </div>
    </div>
</div>
