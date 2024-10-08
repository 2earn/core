<div>
    @section('title')
        {{ __('Trading') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Trading') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xxl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 id="exampleModalgridLabel">{{ __('Buy Shares') }}
                        @if($flash)
                            <div class="flash-background float-end">{{__('V I P')}}</div>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="modal-content">

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
                                    <div class="row mt-2 ml-1 @if($flash) alert-flash @else alert  @endif alert-light">
                                        <h5 class="ml-3">
                                            <span class="form-label">{{ __('Buy For') }}:</span>
                                        </h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item list-group-item-light">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="inlineRadioOptions"
                                                           checked
                                                           id="inlineRadio1" value="me">
                                                    <label class="form-check-label"
                                                           for="inlineRadio1">{{ __('me') }}</label>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-light">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="inlineRadioOptions"
                                                           id="inlineRadio2" value="other" disabled>
                                                    <label class="form-check-label"
                                                           for="inlineRadio2">{{ __('other') }}</label>
                                                </div>
                                            </li>

                                        </ul>
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
                                                    <input class="form-check-input" type="radio" name="bfs-for"
                                                           id="bfs-for-1"
                                                           value="me">
                                                    <label for="bfs-for-1"
                                                           class="form-check-label">{{ __('me') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="bfs-for"
                                                           id="bfs-for-2"
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
                                                    <h5 id="flash-timer1" class="mb-0 text-black"></h5>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-6  @if($flash) ribbon-box right overflow-hidden @endif ">
                                            <label for="ammount" class="col-form-label">{{ __('Amount_pay') }}
                                                ({{$currency}}
                                                )</label>
                                            <div class="input-group mb-3">

                                                <input aria-describedby="simulateAmmount" type="number"
                                                       max="{{$cashBalance}}"
                                                       wire:keyup.debounce="simulateAmmount()" wire:model="ammount"
                                                       id="ammount"
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
                                            <label for="action" class="col-form-label">
                                                {{ __('Number of shares') }}
                                            </label>
                                            <div class="input-group mb-3">
                                                <input aria-describedby="simulateAction" type="number"
                                                       max="{{$maxActions}}"
                                                       wire:keyup.debounce="simulateAction()" wire:model="action"
                                                       id="action"
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
                                            <input type="text" inputmode="numeric" pattern="[-+]?[0-9]*[.,]?[0-9]+"
                                                   disabled
                                                   class="@if($flash) form-control-flash @else form-control  @endif"
                                                   id="profit"
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
                                                <button type="button" id="buy-action-submit"
                                                        wire:loading.attr="disabled"
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
        </div>
    </div>
</div>
