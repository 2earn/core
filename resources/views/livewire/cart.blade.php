<div class="dropdown topbar-head-dropdown ms-1 header-item">
    <button wire:ignore.self
        type="button"
        class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary position-relative"
        id="page-header-cart-dropdown"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-haspopup="true"
        aria-expanded="false">
        <i class="bx bx-shopping-bag fs-22"></i>
        @if($cart->total_cart_quantity > 0)
            <span class="translate-middle badge rounded-pill bg-info fs-10 cart-item-badge number-notif">
                {{$cart->total_cart_quantity > 99 ? '99+' : $cart->total_cart_quantity}}
                <span class="visually-hidden">{{__('items in cart')}}</span>
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart shadow-lg"
         aria-labelledby="page-header-cart-dropdown"
         wire:ignore.self
         style="min-width: 420px;"
    >
        <div class="dropdown-head bg-info bg-gradient rounded-top">
            <div class="p-3">
                <div class="row align-items-center g-0" title="{{$date_rendered}}">
                    <div class="col">
                        <h6 class="m-0 fs-15 fw-semibold text-white">
                            <i class="bx bx-shopping-bag me-1"></i>{{__('My Cart')}}
                        </h6>
                    </div>
                    @if($cart->total_cart_quantity > 0)
                        <div class="col-auto">
                            <span class="badge bg-light text-info">
                                <span class="cart-item-badge">{{$cart->total_cart_quantity}}</span>
                                {{__('items')}}
                            </span>
                        </div>
                    @endif
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
                                <div class="p-3">
                                    <div class="text-center empty-cart py-4" id="empty-cart" style="display: none;">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-info-subtle text-info fs-1 rounded-circle">
                                                <i class="bx bx-cart-alt"></i>
                                            </div>
                                        </div>
                                        <h5 class="mb-1">{{__('Your Cart is Empty!')}}</h5>
                                        <p class="text-muted small mb-0">{{__('Add items to get started')}}</p>
                                    </div>
                                    @foreach($cart->cartItem()->get() as $item)
                                        <div class="border rounded mb-2 p-3 bg-light bg-opacity-50 position-relative">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    @if($item->item()->first()->photo_link)
                                                        <img alt="{{__('Item Image')}}"
                                                             src="{{$item->item()->first()->photo_link}}"
                                                             class="rounded avatar-md object-fit-cover"/>
                                                    @elseif($item->item()->first()->thumbnailsImage)
                                                        <img
                                                            src="{{ asset('uploads/' . $item->item()->first()->thumbnailsImage->url) }}"
                                                            alt="{{__('Item Image')}}"
                                                            class="rounded avatar-md object-fit-cover"
                                                        >
                                                    @else
                                                        <img
                                                            src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                                            alt="{{__('Item Image')}}"
                                                            class="rounded avatar-md object-fit-cover">
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="mb-1 fs-14 fw-semibold text-truncate">
                                                        {{$item->item()->first()->name}}
                                                    </h6>
                                                    @if($item->item()->first()->deal()->first())
                                                        <p class="text-muted mb-2 small">
                                                            <i class="bx bx-purchase-tag me-1"></i>{{$item->item()->first()->deal()->first()->name}}
                                                        </p>
                                                    @endif
                                                    <div class="d-flex flex-wrap gap-3 text-muted small">
                                                        <div>
                                                            <i class="bx bx-package me-1"></i>
                                                            {{__('Qty')}}: <span class="fw-medium text-dark cart-item-qty">{{$item->qty}}</span>
                                                        </div>
                                                        <div>
                                                            <i class="bx bx-dollar me-1"></i>
                                                            {{__('Price')}}: <span class="fw-medium text-dark">{{$item->unit_price}}</span>
                                                        </div>
                                                        <div>
                                                            <i class="bx bx-bus me-1"></i>
                                                            {{__('Shipping')}}: <span class="fw-medium text-dark cart-item-shipping">{{$item->shipping}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end d-flex flex-column align-items-end gap-2">
                                                    <button wire:click="removeCartItem({{$item->id}})"
                                                       id="removeCartItem{{$item->id}}"
                                                       class="btn btn-sm btn-ghost-danger btn-icon">
                                                        <i class="ri-close-line fs-16"></i>
                                                    </button>
                                                    <h5 class="m-0 fw-semibold text-info">
                                                        $<span class="cart-item-price">{{$item->total_amount + $item->shipping}}</span>
                                                    </h5>
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

        @if($cart->total_cart_quantity > 0)
            <div class="p-3 bg-light border-top" id="checkout-elem">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0 text-muted fw-semibold">{{__('Total')}}:</h5>
                    <h4 class="m-0 text-info fw-bold" id="cart-item-total">{{$cart->total_cart}} {{config('app.currency')}}</h4>
                </div>
                <a href="{{route('orders_summary',['locale'=> app()->getLocale()])}}"
                   class="btn btn-info text-white w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                    <i class="bx bx-check-circle fs-16"></i>
                    <span class="fw-semibold">{{__('Checkout')}}</span>
                </a>
            </div>
        @else
            <div class="p-4 text-center border-top bg-light">
                <p class="text-muted mb-0 small">{{__('Your cart is empty')}}</p>
            </div>
        @endif
    </div>
    <script type="module">

    </script>
</div>
