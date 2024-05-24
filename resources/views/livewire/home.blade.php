<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Home') }}
        @endslot
    @endcomponent
    <link type="text/css" rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <div class="row">
        @include('layouts.flash-messages')
    </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card vip-background">
                    <div class="card-body">
                        <div class="row col-12" role="alert">
                            <p>{{__('A mode for a')}} <span
                                    class="col-auto flash-red">{{$flashTimes}}</span> {{__('times bonus over')}}
                                <span
                                    class="col-auto flash-red">{{$flashPeriod}} {{__('hours')}}</span> {{__('with a minimum of')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($flashMinShares,0)}} {{__('Shares')}}</span>. {{__('il vous reste')}} <span
                                    class="col-auto flash-red">{{formatSolde($vip->solde,0)}}{{__('Shares')}}</span>
                                {{__('à conssommer. avec lachat de')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($vip->actions,0)}}</span>
                                {{__('actions, le prix de laction atteindra')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($vip->cout,2)}}{{$currency}}</span> {{__('et les benefices instentannés seront')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($vip->benefices,2)}}{{$currency}}</span></p>
                        </div>
                        <div class="row col-12">
                            <div class="discount-time text-center">
                                <h5 id="flash-timer" class="mb-0 flash-red"></h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 solde-cash">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0">{{ __('Cash Balance') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                @php
                                    $cb_asd =$cashBalance - $arraySoldeD[0];
                                @endphp
                                <p class="@if($cb_asd > 0) text-success @elseif($cb_asd < 0) text-danger @endif"
                                   style="max-height: 5px">@if ($cb_asd > 0)
                                        +
                                    @endif
                                    {{formatSolde($cb_asd)}}
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </p>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary">
                                @if(app()->getLocale()!="ar")
                                    {{$currency}}
                                    <span class="counter-value"
                                          data-target="{{intval($cashBalance)}}">{{formatSolde($cashBalance,0)}}</span>
                                    <small class="text-muted fs-13">
                                        @if(getDecimals($cashBalance))
                                            {{$decimalSeperator}}
                                            {{getDecimals($cashBalance)}}
                                        @endif
                                    </small>
                                @else
                                    <small class="text-muted fs-13">
                                        @if(getDecimals($cashBalance))
                                            {{getDecimals($cashBalance)}}
                                            {{$decimalSeperator}}
                                        @endif
                                    </small>
                                    <span class="counter-value"
                                          data-target="{{intval($cashBalance)}}">{{intval($cashBalance)}}</span>
                                    {{$currency}}
                                @endif
                            </h3>
                            <a href="{{route('user_balance_cb' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="{{ URL::asset('assets/icons/298-coins-gradient-edited.json') }}" trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6 solde-bfs">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0">{{ __('Balance For Shopping') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                @php
                                    $bfs_asd = $balanceForSopping - $arraySoldeD[1];
                                @endphp
                                <p class="@if ($bfs_asd > 0) text-success @elseif ($bfs_asd < 0) text-danger @endif"
                                   style="max-height: 5px">
                                    @if ($bfs_asd > 0)
                                        +
                                    @endif
                                    {{formatSolde($bfs_asd)}}
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </p>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary">
                                @if(app()->getLocale()!="ar")
                                    {{$currency}}
                                    <span class="counter-value"
                                          data-target="{{intval($balanceForSopping)}}">{{formatSolde($balanceForSopping,0)}}</span>
                                    <small class="text-muted fs-13">
                                        @if(getDecimals($balanceForSopping))
                                            {{$decimalSeperator}}
                                            {{getDecimals($balanceForSopping)}}
                                        @endif
                                    </small>
                                @else
                                    <small class="text-muted fs-13">
                                        @if(getDecimals($balanceForSopping))
                                            {{getDecimals($balanceForSopping)}}
                                            {{$decimalSeperator}}
                                        @endif
                                    </small>
                                    <span class="counter-value"
                                          data-target="{{intval($balanceForSopping)}}">{{formatSolde($balanceForSopping,0)}}</span>
                                    {{$currency}}
                                @endif
                            </h3>
                            <a href="{{route('user_balance_bfs' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="{{ URL::asset('assets/icons/146-basket-trolley-shopping-card-gradient-edited.json') }}"
                                trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6 solde-discount">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate  mb-0">{{ __('Discounts Balance') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                @php
                                    $db_asd = $discountBalance - $arraySoldeD[2];
                                @endphp
                                <p class="@if ( $db_asd > 0) text-success @elseif( $db_asd < 0) text-danger @endif"
                                   style="max-height: 5px">
                                    @if ($db_asd > 0)
                                        +
                                    @endif
                                    {{ formatSolde($db_asd)}}
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </p>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="mb-4 fs-22 fw-semibold ff-secondary">
                                @if(app()->getLocale()!="ar")
                                    {{$currency}}
                                    <span class="counter-value"
                                          data-target="{{intval($discountBalance)}}">{{intval($discountBalance)}}</span>
                                    <small class="text-muted fs-13">
                                        @if(getDecimals($discountBalance))
                                            {{$decimalSeperator}}
                                            {{getDecimals($discountBalance)}}
                                        @endif
                                    </small>
                                @else
                                    <small class="text-muted fs-13">
                                        @if(getDecimals($discountBalance))
                                            {{getDecimals($discountBalance)}}
                                            {{$decimalSeperator}}
                                        @endif
                                    </small>
                                    <span class="counter-value"
                                          data-target="{{intval($discountBalance)}}">{{formatSolde($discountBalance,0)}}</span>
                                    {{$currency}}
                                @endif
                            </h4>
                            <a href="{{route('user_balance_db' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="https://cdn.lordicon.com/qrbokoyz.json"
                                trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6"
                                style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6 solde-sms">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{ __('SMS Solde') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                {{$SMSBalance}}
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{$SMSBalance}}">{{$SMSBalance}}</span>
                            </h4>
                            <a href="{{route('user_balance_sms' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon src="{{ URL::asset('assets/icons/981-consultation-gradient-edited.json') }}"
                                       trigger="loop"
                                       colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 solde-actions">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <a href="{{route('sharessolde' , app()->getLocale() )}} "
                               class="text-decoration-underline"><p
                                    class="text-uppercase fw-medium text-muted text-truncate   mb-0">{{ __('Actions (Shares)') }}</p>
                            </a>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                {{$actualActionValue['int']}}.{{$actualActionValue['2Fraction']}}<small
                                    class="action_fraction">{{$actualActionValue['3_2Fraction']}}</small>
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary">
                                <span class="counter-value"
                                      data-target="{{$userSelledAction}}">{{formatSolde($userSelledAction,0)}}</span>
                                <small class="text-muted fs-13">
                                    ({{$actionsValues}}{{$currency}})
                                </small></h3>
                            <button data-bs-target="#buy-action" data-bs-toggle="modal"
                                    class="btn btn-sm @if($flash) btn-flash @else btn-secondary  @endif">{{ __('Buy Shares') }}</button>
                            <span class="badge bg-light text-success  ms-2 mb-0"><i
                                    class="ri-arrow-up-line align-middle"></i>
                                {{$userActualActionsProfit }} {{$currency}}
                            </span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="{{ URL::asset('assets/icons/wired-gradient-751-share.json') }}" trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="card-title" style="text-align: center">{{ __('we_are_present_in') }} </h4>
    <div class="col-12" style="padding-right: 0;padding-left: 0;">
        <div class="card" style="height: 500px;">
            <div class="card-body">
                <div id="any4"></div>
            </div>
        </div>
    </div>
    <div class="col-12" style="padding-right: 0;padding-left: 0;">
        <div class="card" style="height: 500px;">
            <div class="card-body">
                <div id="any5"></div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="buy-action" tabindex="-1" aria-labelledby="exampleModalgridLabel"
         aria-modal="true">
        <div class="modal-dialog" id="buy-share">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Buy Shares') }}@if($flash)
                            <div class="flash-background">{{__('V I P')}}</div>
                        @endif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($flash)
                        <div class="row pink col-12" role="alert">
                            <p>{{__('A mode for a')}} <span
                                    class="pinkbold col-auto">{{$flashTimes}}</span> {{__('times bonus over')}}
                                <span
                                    class="pinkbold col-auto">{{$flashPeriod}} {{__('hours')}}</span> {{__('with a minimum of')}}
                                <span
                                    class="pinkbold col-auto">{{$flashMinShares}} {{__('Shares')}}</span></p>
                        </div>
                    @endif
                    <div class="row @if($flash) alert-flash @else alert  @endif alert-info" role="alert">
                        <strong>{{ __('Notice') }}: </strong>{{ __('buy_shares_notice') }}
                    </div>
                    <a href="{{route('user_balance_cb',app()->getLocale())}}"
                       class="@if($cashBalance < $ammount) logoTopCashDanger  @else logoTopCash  @endif">
                        <div class="row d-flex mt-1">
                            <div class="col-4 avatar-xs flex-shrink-1 ">
                                <span class="avatar-title bg-soft-info custom rounded fs-3">
                                    <i class="bx bx-dollar-circle text-info"></i>
                                </span>
                            </div>
                            <div class="col-8 text-primary text-uppercase fs-16 pt-1 ms-5">
                                <h5 class="@if($cashBalance < $ammount) logoTopCashDanger  @else logoTopCashLabel  @endif">  {{ __('Cash Balance') }}
                                    : {{__('DPC')}}{{$soldeBuyShares->soldeCB}}</h5>
                            </div>
                        </div>
                    </a>
                    <div class="row d-flex">
                        <form class="needs-validation" novalidate>
                            <div class="row mt-2  @if($flash) alert-flash @else alert  @endif alert-primary">
                                <div class="col-3">
                                    <span class="form-label">{{ __('Buy For') }}:</span>
                                </div>
                                <div class="col-4">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" checked
                                               id="inlineRadio1" value="me">
                                        <label class="form-check-label" for="inlineRadio1">{{ __('me') }}</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                               id="inlineRadio2" value="other" disabled>
                                        <label class="form-check-label" for="inlineRadio2">{{ __('other') }}</label>
                                    </div>
                                </div>
                                <div class="col-6 d-none" id="contact-select">
                                    <div>
                                        <label for="phone" class="form-label">{{ __('Mobile_Number') }}</label>
                                        <input type="tel"
                                               class="@if($flash) form-control-flash @else form-control  @endif"
                                               name="mobile" id="phone" required>
                                    </div>
                                </div>
                                <div class="col-6 d-none" id="bfs-select">
                                    <span class="form-label mb-3">{{ __('BFS bonuses  for') }} </span>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bfs-for" id="bfs-for-1"
                                                   value="me">
                                            <label for="bfs-for-1" class="form-check-label">{{ __('me') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bfs-for" id="bfs-for-2"
                                                   value="other">
                                            <label for="bfs-for-2"
                                                   class="form-check-label">{{ __('The chosen user') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="simulator" class="row mt-3 mb-3">
                                @if($flash)
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="discount-time text-center">
                                            <h5 id="flash-timer" class="mb-0 text-black"></h5>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-6  @if($flash) ribbon-box right overflow-hidden @endif ">
                                    <label for="ammount" class="col-form-label">{{ __('Amount_pay') }}({{$currency}}
                                        ) </label>
                                    <div class="input-group mb-3">

                                        <input aria-describedby="simulateAmmount" type="number" max="{{$cashBalance}}"
                                               wire:keyup.debounce="simulateAmmount()" wire:model="ammount" id="ammount"
                                               class="form-control @if($flash) flash @endif">
                                        <div class="input-group-append">
                                            <button wire:click="simulateAmmount()"
                                                    class="btn @if($flash) btn-outline-flash @else btn-outline-primary  @endif">
                                                <div wire:loading wire:target="simulateAmmount">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                    <span class="sr-only">{{__('Loading')}}...</span>
                                                </div>
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-6 @if($flash) ribbon-box right overflow-hidden @endif ">
                                    <label for="action" class="col-form-label">{{ __('Actions') }}({{$currency}}
                                        ) </label>
                                    <div class="input-group mb-3">
                                        <input aria-describedby="simulateAction" type="number" max="{{$maxActions}}"
                                               wire:keyup.debounce="simulateAction()" wire:model="action" id="action"
                                               class="form-control @if($flash) flash @endif">
                                        <div class="input-group-append">
                                            <button wire:click="simulateAction()"
                                                    class="btn @if($flash) btn-outline-flash @else btn-outline-primary  @endif">
                                                <div wire:loading wire:target="simulateAction">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                    <span class="sr-only">{{__('Loading')}}...</span>
                                                </div>
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="number-of-gifted-action" class="col-form-label">
                                        {{ __('Gifted Shares') }}
                                    </label>
                                    <input type="number" disabled
                                           class="@if($flash) form-control-flash @else form-control  @endif"
                                           wire:model.live="gift"
                                           id="number-of-gifted-action"
                                           value="0000">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="profit" class="col-form-label">{{ __('Profit') }}
                                        ({{$currency}}) </label>
                                    <input type="text" inputmode="numeric" pattern="[-+]?[0-9]*[.,]?[0-9]+" disabled
                                           class="@if($flash) form-control-flash @else form-control  @endif" id="profit"
                                           value="0000"
                                           wire:model.live="profit">
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <div class="hstack gap-2 justify-content-end">
                                        @if($flash)
                                            <button type="button" class="btn btn-outline-gold">
                                                {{__('Flash gift')}}
                                                <span class="flash-background">{{$flashGift}}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-gold">
                                                {{__('Flash gain')}}
                                                <span class="flash-background">{{$flashGain}}$</span>
                                            </button>
                                        @endif
                                        @if(!$flash)
                                            <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                        @endif
                                        <button type="button" id="buy-action-submit" wire:loading.attr="disabled"
                                                wire:target="simulate"
                                                class="btn @if($flash) btn-flash @else btn-primary  @endif swal2-styled d-inline-flex">
                                            {{ __('Submit') }}
                                            <div
                                                class="spinner-border spinner-border-sm mx-2 mt-1 buy-action-submit-spinner"
                                                role="status"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if($flash)
            <script>
                var setEndDate6 = "{{$flashDate}}";

                function startCountDownDate(dateVal) {
                    var countDownDate = new Date(dateVal).getTime();
                    return countDownDate;
                }

                function countDownTimer(start, targetDOM) {
                    var now = new Date().getTime();
                    var distance = start - now;

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor(
                        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                    );
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    days = days < 10 ? "0" + days : days;
                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    document.querySelector("#" + targetDOM).textContent =
                        "- " + days + " {{__('days')}} : " + hours + " {{__('hours')}} : " + minutes + " {{__('minutes')}} : " + seconds + "  {{__('seconds')}}";

                    if (distance < 0) {
                        document.querySelector("#" + targetDOM).textContent = "00 : 00 : 00 : 00";
                    }
                }

                var flashTimer = startCountDownDate(setEndDate6);
                if (document.getElementById("flash-timer"))
                    setInterval(function () {
                        countDownTimer(flashTimer, "flash-timer");
                    }, 1000);
            </script>
        @endif
        <script>

            $(document).on('ready ', function () {
                    const input = document.querySelector("#phone");
                    const iti = window.intlTelInput(input, {
                        initialCountry: "auto",
                        useFullscreenPopup: false,
                        utilsScript: "{{asset('assets/js/utils.js')}}"
                    });
                    $('[name="inlineRadioOptions"]').on('change', function () {
                        if ($('#inlineRadio2').is(':checked')) {
                            $('#contact-select').removeClass('d-none');
                            $('#bfs-select').removeClass('d-none');
                        } else {
                            $('#contact-select').addClass('d-none');
                            $('#bfs-select').addClass('d-none');
                        }
                    });
                    $(document).on("click", "#buy-action-submit", function () {
                        this.disabled = true;
                        $('.buy-action-submit-spinner').show();
                        let ammount = parseInt($('#ammount').val());
                        let phone = $('#phone').val();
                        let me_or_other = $("input[name='inlineRadioOptions']:checked").val();
                        let bfs_for = $("input[name='bfs-for']:checked").val();
                        let country_code = iti.getSelectedCountryData().iso2;
                        $.ajax({
                            url: "{{ route('buyAction') }}",
                            type: "POST",
                            data: {
                                me_or_other: me_or_other,
                                bfs_for: bfs_for,
                                phone: phone,
                                country_code: country_code,
                                ammount: ammount,
                                vip: {{$flashTimes}},
                                flashMinShares: {{$flashMinShares}},
                                flash: "{{$flash}}",
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function (data) {
                                let backgroundColor = "#27a706"
                                if (data.error) {
                                    backgroundColor = "#ba0404";
                                    Swal.fire({
                                        icon: "error",
                                        title: "Validation Failed",
                                        html: response.error.join('<br>')
                                    });
                                }
                                $('#buy-action').modal('hide');
                                Toastify({
                                    text: data.message,
                                    gravity: "top",
                                    duration: 4000,
                                    className: "info",
                                    position: "center",
                                    backgroundColor: backgroundColor
                                }).showToast();
                                $('.buy-action-submit-spinner').hide();
                                location.reload();
                            },
                            error: function (data) {
                                var responseData = JSON.parse(data.responseText);
                                Swal.fire({icon: 'error', title: 'Oops...', text: responseData.error[0]});
                                $('.buy-action-submit-spinner').hide();
                            }
                        });
                        setTimeout(() => {
                            this.disabled = false;
                            $('.buy-action-submit-spinner').hide();
                        }, 2000);

                    })
                }
            );

            var series;
            anychart.onDocumentReady(function () {
                anychart.data.loadJsonFile(
                    "{{route('API_stat_countries',app()->getLocale())}}",
                    function (data) {
                        var map = anychart.map();
                        map.geoData('anychart.maps.world');
                        map.padding(0);
                        var dataSet = anychart.data.set(data);
                        var densityData = dataSet.mapAs({id: 'apha2', value: 'COUNT_USERS'});
                        series = map.choropleth(densityData);
                        series.labels(false);
                        series.hovered().fill('#f48fb1').stroke(anychart.color.darken('#f48fb1'));
                        series.tooltip(false);
                        var scale = anychart.scales.ordinalColor([
                            {less: 2},
                            {from: 2, to: 5},
                            {from: 5, to: 10},
                            {from: 10, to: 15},
                            {from: 15, to: 30},
                            {from: 30, to: 50},
                            {from: 50, to: 100},
                            {from: 100, to: 500},
                            {greater: 500}
                        ]);
                        scale.colors(['#81d4fa', '#4fc3f7', '#29b6f6', '#039be5', '#0288d1', '#0277bd', '#01579b', '#014377', '#000000']);
                        series.colorScale(scale);
                        var zoomController = anychart.ui.zoom();
                        zoomController.render(map);
                        map.container('any4');
                        map.draw();
                        var mapping = dataSet.mapAs({x: "name", value: "COUNT_USERS", category: "continant"});
                        var colors = anychart.scales.ordinalColor().colors(['#26959f', '#f18126', '#3b8ad8', '#60727b', '#e24b26']);
                        var chart = anychart.tagCloud();
                        chart.data(mapping).colorScale(colors).angles([-90, 0, 90,]);
                        chart.tooltip(false);
                        var colorRange = chart.colorRange();
                        colorRange.enabled(true).colorLineSize(15);
                        var normalFillFunction = chart.normal().fill();
                        var hoveredFillFunction = chart.hovered().fill();
                        chart.listen('pointsHover', function (e) {
                            if (e.actualTarget === colorRange) {
                                if (e.points.length) {
                                    chart.normal({
                                        fill: 'black 0.1'
                                    });
                                    chart.hovered({
                                        fill: chart.colorScale().valueToColor(e.point.get('category'))
                                    });
                                } else {
                                    chart.normal({fill: normalFillFunction});
                                    chart.hovered({fill: hoveredFillFunction});
                                }
                            }
                        });
                        chart.container('any5');
                        chart.draw();
                    }
                );
            });
        </script>
    @endpush
</div>
