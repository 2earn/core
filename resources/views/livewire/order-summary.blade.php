<div>
    @section('title')
        {{ __('Order Summary') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Order Summary') }}
        @endslot
    @endcomponent
    <div class="card">
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
                                    <img src="{{ asset('uploads/' . $item->item()->first()->thumbnailsImage->url) }}"
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
                                <p class="text-muted mb-0">{{config('app.currency')}} {{$item->unit_price}}
                                    x {{$item->qty }}</p>
                                <p class="text-muted mb-0">{{config('app.currency')}} {{$item->shipping}}</p>
                            </td>
                            <td class="text-end">{{config('app.currency')}} {{$item->total_amount + $item->shipping}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">{{__('Shipping Charge')}} :</td>
                        <td class="text-end">{{config('app.currency')}} {{$cart->shipping}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">{{__('Estimated Tax')}}:</td>
                        <td class="text-end">{{config('app.currency')}} 0</td>
                    </tr>
                    <tr class="table-active">
                        <th colspan="2">{{__('Total')}}   {{config('app.currency')}} :</th>
                        <td class="text-end">
                            <span class="fw-semibold">
                                {{config('app.currency')}} {{$cart->total_cart}}
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button wire:click="validateCart()" class="btn btn-soft-success mx-2 float-end">{{__('Validate Cart')}}</button>
            <button wire:click="clearCart()" class="btn btn-soft-warning mx-2 float-end">{{__('Clear Cart')}}</button>
        </div>
    </div>
</div>
