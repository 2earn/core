<div class="dropdown topbar-head-dropdown ms-1 header-item">
    <button
        type="button"
        class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary"
        id="page-header-cart-dropdown"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
        <i class="bx bx-shopping-bag fs-22"></i>
        <span
            class="position-absolute topbar-badge cart-item-badge fs-10 translate-middle badge rounded-pill bg-info">{{$cart->total_cart_quantity}}</span>
    </button>
    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart"
         aria-labelledby="page-header-cart-dropdown"
         style="position: absolute; inset: 0 0 auto auto; margin: 0; transform: translate(0px, 58px);"
         data-popper-placement="bottom-end"
    >
        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
            <div class="row align-items-center" title="{{$date_rendered}}">
                <div class="col">
                    <h6 class="m-0 fs-16 fw-semibold">{{__('My Cart')}} :</h6>
                </div>
                <div class="col-auto">
                    <span class="badge bg-warning-subtle text-warning fs-13">
                        <span class="cart-item-badge">{{$cart->total_cart_quantity}}</span>
                        {{__('items')}}
                    </span>
                </div>
            </div>
        </div>
        <div data-simplebar="init" style="max-height: 300px;" class="simplebar-scrollable-y">
            <div class="simplebar-wrapper" style="margin: 0;">
                <div class="simplebar-height-auto-observer-wrapper">
                    <div class="simplebar-height-auto-observer">
                    </div>
                </div>
                <div class="simplebar-mask">
                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                        <div class="simplebar-content-wrapper" tabindex="0" role="region"
                             aria-label="scrollable content" style="height: auto; overflow: hidden scroll;">
                            <div class="simplebar-content" style="padding: 0px;">
                                <div class="p-2">
                                    <div class="text-center empty-cart" id="empty-cart" style="display: none;">
                                        <div class="avatar-md mx-auto my-3">
                                            <div class="avatar-title bg-info-subtle text-info fs-36 rounded-circle">
                                                <i class="bx bx-cart"></i>
                                            </div>
                                        </div>
                                        <h5 class="mb-3">{{__('Your Cart is Empty!')}}</h5>
                                    </div>
                                    @foreach($cart->cartItem()->get() as $item)
                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                @if($item->item()->first()->photo_link)
                                                    <img alt="{{__('Item Image')}}"
                                                         src="{{$item->item()->first()->photo_link}}"
                                                         class="me-3 rounded-circle avatar-sm p-2 bg-light"/>
                                                @elseif($item->item()->first()->thumbnailsImage)
                                                    <img
                                                        src="{{ asset('uploads/' . $item->item()->first()->thumbnailsImage->url) }}"
                                                        alt="{{__('Item Image')}}"
                                                        class="me-3 rounded-circle avatar-sm p-2 bg-light"
                                                    >
                                                @else
                                                    <img
                                                        src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                                        alt="{{__('Item Image')}}"
                                                        class="me-3 rounded-circle avatar-sm p-2 bg-light">
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mt-0 mb-1 fs-14">
                                                        <strong>{{$item->item()->first()->name}}</strong>
                                                        @if($item->item()->first()->deal()->first())
                                                            <span class="text-muted mb-0"> [{{$item->item()->first()->deal()->first()->name}}]</span>
                                                        @endif
                                                    </h6>
                                                    <p class="mb-0 fs-12 text-muted">
                                                        {{__('Quantity')}}:
                                                        <span class="cart-item-qty">{{$item->qty }}</span> *
                                                        <span>{{$item->unit_price}}</span>
                                                    </p>
                                                    <p class="mb-0 fs-12 text-muted">
                                                        {{__('Shipping')}}:
                                                        <span class="cart-item-shipping">{{$item->shipping }}</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">{{$item->total_amount + $item->shipping}}</span>
                                                    </h5>
                                                </div>
                                                <div class="ps-2">
                                                    <a wire:click="removeCartItem({{$item->id}})"
                                                       id="removeCartItem{{$item->id}}"
                                                       class="btn btn-icon btn-sm btn-ghost-secondary">
                                                        <i class="ri-close-fill fs-16"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simplebar-placeholder" style="width: 420px; height: 336px;"></div>
            </div>
            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
            </div>
            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                <div class="simplebar-scrollbar"
                     style="height: 267px; display: block; transform: translate3d(0px, 0px, 0px);">
                </div>
            </div>
        </div>
        <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border" id="checkout-elem"
             style="display: block;">
            <div class="d-flex justify-content-between align-items-center pb-3">
                <h5 class="m-0 text-muted">{{__('Total')}}:</h5>
                <div class="px-2">
                    <h5 class="m-0" id="cart-item-total">{{$cart->total_cart}} {{config('app.currency')}}</h5>
                </div>
            </div>
            <a href="{{route('orders_summary',['locale'=> app()->getLocale()])}}"
               class="btn btn-success text-center w-100">
                {{__('Checkout')}}
            </a>
        </div>
    </div>
    <script type="module">

    </script>
</div>
