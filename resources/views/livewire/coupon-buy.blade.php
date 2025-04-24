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
                                <h3 class="card-title mb-0 flex-grow-1 float-end text-success">{{$platform->name}}</h3>
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
                                                    <button class="btn btn-outline-primary material-shadow-none"
                                                            wire:click="simulateCoupon" type="button"
                                                            id="button-simulate">{{__('Simulate')}}
                                                    </button>
                                                </div>
                                            </div>
                                            @if($lastValue && !$equal)
                                                <div class="col-lg-12">
                                                    <button button class="btn btn-outline-success material-shadow-none"
                                                            wire:click="addLastValue()">{{__('add')}} {{$lastValue}}  {{config('app.currency')}}</button>
                                                </div>
                                            @endif
                                            @if($simulated)
                                                <div class="col-lg-12">
                                                    <div title="{{__('Simulated At')}} : {{now()}}"
                                                         class="alert alert-success alert-dismissible fade show material-shadow"
                                                         role="alert">

                                                        {{__('Depending on coupon availability, you can choose to purchase for')}}
                                                        @if($lastValue)
                                                            {{$lastValue+$amount}} {{__('or')}}
                                                            ,
                                                        @endif
                                                        {{$amount}} {{__('as a coupon with the exact requested value is not available')}}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                                aria-label="Close"></button>
                                                    </div>
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
                                                <th scope="col">{{__('Pin')}}</th>
                                                <th scope="col">{{__('value')}}</th>
                                                <th scope="col">{{__('Action')}}</th>
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
                                        {{$coupon->sn}}
                                    @endif
                                </span>
                                                    </td>
                                                    <td>
                                <span
                                    class="badge bg-success-subtle text-success fs-14 my-1 fw-normal">
                                    @if(!is_array($coupon))
                                        {{substr_replace($coupon->pin, str_repeat('*', strlen($coupon->pin)), 0 )}}
                                    @endif
                                </span>
                                                    </td>
                                                    <td>
                                                <span class="text-muted fs-16 my-1">
                                                                                @if(!is_array($coupon))
                                                        <strong>       {{$coupon->value}}  {{config('app.currency')}}</strong>
                                                    @endif
      </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-outline-info waves-effect waves-light"
                                                                type="submit">{{__('Voir')}}</button>
                                                        <button class="btn btn-outline-primary waves-effect waves-light"
                                                                type="submit">{{__('Copier')}}</button>
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
                        @else
                            @if($simulated)
                                <div class="alert alert-warning material-shadow" role="alert">
                                    {{__('No available coupons combination')}}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
