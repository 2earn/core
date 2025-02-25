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
                                <div class="avatar-md bg-light rounded p-1">
                                    <img src="assets/images/products/img-8.png" alt="" class="img-fluid d-block">
                                </div>
                            </td>
                            <td>
                                <h5 class="fs-14"><a href="apps-ecommerce-product-details.html" class="text-body">{{$item->item()->first()->name}}</a></h5>
                                <p class="text-muted mb-0">{{config('app.currency')}} {{$item->unit_price}} x {{$item->qty }}</p>
                                <p class="text-muted mb-0">{{config('app.currency')}} {{$item->shipping}}</p>
                            </td>
                            <td class="text-end">{{config('app.currency')}} {{$item->total_amount + $item->shipping}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="fw-semibold" colspan="2">{{__('Sub Total')}} :</td>
                        <td class="fw-semibold text-end">{{config('app.currency')}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">{{__('Shipping Charge')}} :</td>
                        <td class="text-end">{{config('app.currency')}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">{{__('Estimated Tax')}}:</td>
                        <td class="text-end">{{config('app.currency')}}</td>
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
    </div>
</div>
