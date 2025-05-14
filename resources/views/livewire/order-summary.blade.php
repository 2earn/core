<div>
    @section('title')
        {{ __('Order Summary') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Order Summary') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        @if($cart->total_cart>0)
            <div class="card-header">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">{{__('Order Summary')}}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="table-light text-muted">
                        <tr>
                            <th style="width: 90px;" scope="col">{{__('Product')}}</th>
                            <th scope="col">{{__('Product Info')}}</th>
                            <th scope="col" class="text-end">{{__('Price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cart->cartItem()->get() as $item)
                            <tr>
                                <td>
                                    @if($item->item()->first()->photo_link)
                                        <img alt="{{__('Item Image')}}"
                                             src="{{$item->item()->first()->photo_link}}"
                                             class="img-fluid d-block"/>
                                    @elseif($item->item()->first()->thumbnailsImage)
                                        <img
                                            src="{{ asset('uploads/' . $item->item()->first()->thumbnailsImage->url) }}"
                                            alt="{{__('Item Image')}}"
                                            class="img-fluid d-block"
                                        >
                                    @else
                                        <img src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                             alt="{{__('Item Image')}}"
                                             class="img-fluid d-block">
                                    @endif
                                </td>
                                <td>
                                    <h5 class="fs-14">{{$item->item()->first()->name}}
                                        @if($item->item()->first()->deal()->first())
                                            <span class="text-muted mb-0">[{{$item->item()->first()->deal()->first()->name}}]</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-0"
                                       title="{{__('Unit price')}}">{{config('app.currency')}} {{$item->unit_price}}
                                        x {{$item->qty }}</p>
                                    <p class="text-muted mb-0">
                                        {{__('Shipping price')}}:
                                        {{config('app.currency')}} {{$item->shipping}}
                                    </p>
                                </td>
                                <td class="text-end" title="{{__('Total')}}">
                                    <h5 class="fs-14 mb-0">   {{config('app.currency')}} {{$item->total_amount + $item->shipping}}</h5>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">
                                <span class="text-muted">  {{__('Shipping Charge')}} :</span>
                            </td>
                            <td class="text-end" title="{{__('Shipping')}}">
                                <h5 class="fs-14 mb-0">   {{config('app.currency')}} {{$cart->shipping}}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="text-muted">  {{__('Estimated Tax')}}:</span>
                            </td>
                            <td class="text-end">
                                <h5 class="fs-14 mb-0">
                                    {{config('app.currency')}} 0
                                </h5>
                            </td>
                        </tr>
                        <tr class="table-active">
                            <th colspan="2">
                                 <span class="text-muted">
                                     {{__('Total')}}   {{config('app.currency')}} </span>
                            </th>
                            <td class="text-end">
                            <span class="fw-semibold">
                                <span class="text-muted">
                                    {{config('app.currency')}} {{$cart->total_cart}}
                                </span>
                            </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @if($cart->total_cart>0)
            <div class="card-footer">
                <button wire:click="validateCart()"
                        class="btn btn-soft-success mx-2 float-end">{{__('Validate Cart')}}</button>
                <button wire:click="clearCart()"
                        class="btn btn-soft-warning mx-2 float-end">{{__('Clear Cart')}}</button>
            </div>
        @endif
        @if(!empty($orders))
            <div class="card-body">
                @foreach($orders as $order)
                    <div class="col-sm-12 col-lg-12">
                        @include('livewire.order-item', ['order' => $order,'currentRouteName' => $currentRouteName])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @if($cart->total_cart==0 && empty($orders))
        <div class="alert border-0 alert-warning material-shadow" role="alert">
            <strong> {{__('Empty Cart')}} </strong>
            <hr>
            {{__('Add items to the cart to see the summary')}}
        </div>
    @endif
</div>
