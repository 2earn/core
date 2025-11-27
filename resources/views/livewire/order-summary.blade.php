<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Cart Summary') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            <i class="ri-shopping-cart-line me-2"></i>{{ __('Cart Summary') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-12 card shadow-sm">
            @if($cart->total_cart>0)
                <div class="card-header bg-light">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">
                                <i class="ri-shopping-bag-line text-primary me-2"></i>{{__('Cart Summary')}}
                            </h5>
                        </div>
                        <div class="flex-shrink-0">
                        <span class="badge bg-primary-subtle text-primary fs-12">
                            {{$cart->cartItem()->count()}} {{__('Items')}}
                        </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 100px;" scope="col" class="ps-4">{{__('Product')}}</th>
                                <th scope="col">{{__('Product Info')}}</th>
                                <th scope="col" class="text-end pe-4">{{__('Price')}}</th>
                            </tr>
                            </thead>
                            <tbody class="border-top">
                            @foreach($cart->cartItem()->get() as $item)
                                <tr class="border-bottom">
                                    <td class="ps-4">
                                        <div class="avatar-lg bg-light rounded">
                                            @if($item->item()->first()->photo_link)
                                                <img alt="{{__('Item Image')}}"
                                                     src="{{$item->item()->first()->photo_link}}"
                                                     class="img-fluid rounded"/>
                                            @elseif($item->item()->first()->thumbnailsImage)
                                                <img
                                                    src="{{ asset('uploads/' . $item->item()->first()->thumbnailsImage->url) }}"
                                                    alt="{{__('Item Image')}}"
                                                    class="img-fluid rounded"
                                                >
                                            @else
                                                <img src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                                     alt="{{__('Item Image')}}"
                                                     class="img-fluid rounded">
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <h5 class="fs-15 fw-semibold mb-2">{{$item->item()->first()->name}}</h5>
                                        @if($item->item()->first()->deal()->first())
                                            <span class="badge bg-success-subtle text-success mb-2">
                                            <i class="ri-price-tag-3-line me-1"></i>{{$item->item()->first()->deal()->first()->name}}
                                        </span>
                                        @endif
                                        <div class="text-muted small">
                                        <span class="d-inline-block me-3" title="{{__('Unit price')}}">
                                            <i class="ri-price-tag-line text-primary me-1"></i>
                                            {{config('app.currency')}} {{$item->unit_price}} x {{$item->qty }}
                                        </span>
                                            <span class="d-inline-block" title="{{__('Shipping price')}}">
                                            <i class="ri-truck-line text-info me-1"></i>
                                            {{__('Shipping')}}: {{config('app.currency')}} {{$item->shipping}}
                                        </span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4 py-3" title="{{__('Total')}}">
                                        <h5 class="fs-15 fw-bold text-primary mb-0">
                                            {{config('app.currency')}} {{$item->total_amount + $item->shipping}}
                                        </h5>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="border-bottom">
                                <td colspan="2" class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-truck-line text-info fs-18 me-2"></i>
                                        <span class="fw-medium">{{__('Shipping Charge')}}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <h6 class="fs-15 mb-0 text-muted">{{config('app.currency')}} {{$cart->shipping}}</h6>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td colspan="2" class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-file-list-3-line text-warning fs-18 me-2"></i>
                                        <span class="fw-medium">{{__('Estimated Tax')}}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <h6 class="fs-15 mb-0 text-muted">{{config('app.currency')}} 0</h6>
                                </td>
                            </tr>
                            <tr class="table-active border-top border-2">
                                <td colspan="2" class="ps-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-shopping-cart-2-line text-success fs-18 me-2"></i>
                                        <span class="fw-bold fs-16">{{__('Total')}}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 py-4">
                                    <h4 class="fw-bold text-success mb-0">
                                        {{config('app.currency')}} {{$cart->total_cart}}
                                    </h4>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @if($cart->total_cart>0)
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-end gap-2">
                        <button wire:click="clearCart()"
                                class="btn btn-warning">
                            <i class="ri-delete-bin-line me-1"></i>{{__('Clear Cart')}}
                        </button>
                        <button wire:click="createAndSimulateOrder()"
                                class="btn btn-success">
                            <i class="ri-play-circle-line me-1"></i>{{__('Create Order & Simulate it')}}
                        </button>
                    </div>
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
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="ri-shopping-cart-line display-1 text-muted opacity-50"></i>
                    </div>
                    <h4 class="mb-3">{{__('Empty Cart')}}</h4>
                    <p class="text-muted fs-15 mb-0">{{__('Add items to the cart to see the summary')}}</p>
                </div>
            </div>
        @endif
    </div>
</div>
