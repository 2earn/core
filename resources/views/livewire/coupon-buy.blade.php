<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Coupon buy') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{__('Simulate amount of coupons')}}</h4>
                                <h4 class="card-title mb-0 flex-grow-1 float-end text-success">{{$platform->name}}</h4>
                            </div>
                            <div class="card-body">
                                <div class="live-preview">
                                    <div>
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                           wire:model.live="amount" aria-label="Recipient's username"
                                                           aria-describedby="button-addon2">
                                                    <span class="input-group-text"> {{config('app.currency')}}</span>
                                                    <button class="btn btn-outline-success material-shadow-none"
                                                            wire:click="simulateCoupon" type="button"
                                                            id="button-simulate">{{__('Simulate')}}
                                                    </button>
                                                </div>
                                            </div>
                                            @if($lastValue && !$equal)
                                                <div class="col-lg-12">
                                                    <button button class="btn btn-outline-success material-shadow-none"
                                                            wire:click="addLastValue()">{{__('add')}} {{$lastValue->value}}  {{config('app.currency')}}</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        @if(!empty($coupons))
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($coupons as $key=> $coupon)
                                                <tr>
                                                    <td>
                                                        <span class="fw-medium link-primary">#{{$key}}</span>
                                                    </td>
                                                    <td>
                                <span
                                    class="badge bg-success-subtle text-success fs-14 my-1 fw-normal">
                                    @if(!is_array($coupon))
                                        {{substr_replace($coupon->sn, str_repeat('*', strlen($coupon->sn) - 3), 0, -3)}}
                                    @endif
                                </span>
                                                    </td>
                                                    <td>
                                                <span class="text-muted fs-14 my-1">
                                                                                @if(!is_array($coupon))
                                                        {{$coupon->value}}  {{config('app.currency')}}
                                                    @endif
      </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-outline-success material-shadow-none float-end"
                                            wire:click="BuyCoupon" type="button"
                                            id="button-buy">{{__('Buy this simulation')}}
                                    </button>
                                </div>
                            </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
