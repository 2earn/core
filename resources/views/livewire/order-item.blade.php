<div class="{{getContainerType()}}">
    @php
        $currency = config('app.currency');
    @endphp
    @if($currentRouteName=="orders_detail")
        @section('title')
            {{ __('Order details') }} : {{__('Order id')}} : {{$order->id}}
        @endsection
        @component('components.breadcrumb')
            @slot('title')
                <a href="{{route('orders_previous',['locale'=> app()->getLocale()])}}" class="text-white mx-1"><i
                        class="ri-shopping-cart-fill"></i> </a>{{ __('Order details') }} : {{__('Order id')}}
                : {{$order->id}}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
    @endif
    <div @class(['row','card' => $currentRouteName=="orders_detail"])>
        <div class="col-12 my-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <i class="ri-file-list-line text-primary fs-18 me-2"></i>
                    <span class="badge bg-primary-subtle text-primary fs-14 fw-semibold">#{{$order->id}}</span>
                </div>

                <span class="badge bg-info-subtle text-info">
                    <i class="ri-user-line me-1"></i>{{getUserDisplayedName($order->user()->first()->idUser)}}
                </span>

                @if($order->total_order_quantity && $currentRouteName=="orders_detail")
                    <span class="badge bg-primary-subtle text-primary">
                        <i class="ri-shopping-bag-line me-1"></i>{{__('Total order quantity')}}: {{$order->total_order_quantity}}
                    </span>
                @endif

                <span class="badge bg-secondary-subtle text-secondary">
                    <i class="ri-checkbox-circle-line me-1"></i>{{__($order->status->name)}}
                </span>

                @if($order->OrderDetails()->first()?->item()->first()?->platform()->first()?->name)
                    <span class="badge bg-warning-subtle text-warning" title="{{__('Platform')}}">
                        <i class="ri-computer-line me-1"></i>{{__($order->OrderDetails()->first()?->item()->first()?->platform()->first()?->name)}}
                    </span>
                @endif

                @if($order->status->value == \Core\Enum\OrderEnum::Dispatched->value && $currentRouteName=="orders_detail")
                    <button type="button" class="btn btn-success btn-sm ms-auto"
                            onclick="window.print()" title="{{__('Print')}}">
                        <i class="ri-printer-line me-1"></i>{{ __('Print') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div @class(['row','card' => $currentRouteName=="orders_detail"])>
        <div class="col-12 my-3">

            <h6 class="card-title mb-3">
                <i class="ri-file-text-line text-primary me-2"></i>{{__('Order details summary')}}
            </h6>
            @if($order->OrderDetails()->count()>0)
                <div class="row">
                    @if(\App\Models\User::isSuperAdmin())
                        @if($order->note && $currentRouteName=="orders_detail")
                            <div class="col-md-12 mb-3">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning-subtle">
                                        <h6 class="card-title mb-0">
                                            <i class="ri-alert-line text-warning me-2"></i>{{__('Order details')}}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning mb-0">
                                            <p class="mb-2">
                                                <i class="ri-lock-line me-1"></i>
                                                <small
                                                    class="text-muted">{{__('This is only for admin / not translatable note')}}</small>
                                            </p>
                                            <strong><i class="ri-sticky-note-line me-1"></i>{{__('Note')}}:
                                            </strong>
                                            <p class="mb-0 mt-2">{{$order->note}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if( str_contains($order->note, 'Coupons buy from'))
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="ri-coupon-line me-2"></i>
                                        {{__('This order contains coupons')}}
                                    </span>
                                <a class="btn btn-info btn-sm"
                                   href="{{route('coupon_history',['locale'=>app()->getLocale()])}}">
                                    <i class="ri-history-line me-1"></i>{{ __('Go to Coupons History') }}
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        @if($order->orderDetails()->count())
                            <div class="mt-3">
                                @foreach($order->orderDetails()->get() as $key => $orderDetail)
                                    <div
                                        class="card border-0 shadow-sm mb-3 @if($orderDetail->item()->first()->deal()->exists()) border-start border-success border-4 @endif">
                                        <div class="card-body p-4">
                                            <!-- Order Item Number Badge -->
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                                <span
                                                                    class="fw-bold text-primary fs-5">{{$key + 1}}</span>
                                                    </div>
                                                    @if($orderDetail->item()->first()->deal()->exists())
                                                        <span
                                                            class="badge bg-success-subtle text-success px-3 py-2">
                                                                <i class="ri-gift-line me-1"></i>{{__('Deal Item')}}
                                                            </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Item Details Section -->
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <div class="card bg-light border-0 h-100">
                                                        <div class="card-body">
                                                            <h6 class="text-primary mb-3">
                                                                <i class="ri-information-line me-2"></i>{{__('Item Information')}}
                                                            </h6>
                                                            <div class="d-flex flex-column gap-2">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                        <span class="text-muted">
                                                                            <i class="ri-price-tag-line text-primary me-2"></i>{{__('REF')}} - {{__('Name')}}
                                                                        </span>
                                                                    <span
                                                                        class="badge bg-primary-subtle text-primary">
                                                                            @if(\App\Models\User::isSuperAdmin() && $currentRouteName!="orders_detail")
                                                                            <a href="{{route('items_detail',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->id])}}"
                                                                               class="text-primary text-decoration-none">
                                                                                    #{{$orderDetail->item()->first()->ref}} - {{$orderDetail->item()->first()->name}}
                                                                                </a>
                                                                        @else
                                                                            #{{$orderDetail->item()->first()->ref}}
                                                                            - {{$orderDetail->item()->first()->name}}
                                                                        @endif
                                                                        </span>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                        <span class="text-muted">
                                                                            <i class="ri-money-dollar-circle-line text-success me-2"></i>{{__('Unit Price')}}
                                                                        </span>
                                                                    <span
                                                                        class="badge bg-success-subtle text-success fs-6">
                                                                            {{$orderDetail->unit_price}} {{$currency}}
                                                                        </span>
                                                                </div>
                                                                @if($orderDetail->item()->first()?->platform()->exists())
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                            <span class="text-muted">
                                                                                <i class="ri-computer-line text-info me-2"></i>{{__('Platform')}}
                                                                            </span>
                                                                        <span
                                                                            class="badge bg-info-subtle text-info">
                                                                                {{__($orderDetail->item()->first()?->platform()->first()?->name)}}
                                                                            </span>
                                                                    </div>
                                                                @endif
                                                                @if($orderDetail->item()->first()->deal()->exists())
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded">
                                                                            <span class="text-muted">
                                                                                <i class="ri-gift-line text-success me-2"></i>{{__('Deal')}}
                                                                            </span>
                                                                        @if(\App\Models\User::isSuperAdmin())
                                                                            <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}"
                                                                               class="badge bg-success text-white text-decoration-none">
                                                                                <i class="ri-fire-fill me-1"></i>{{$orderDetail->item()->first()->deal()->first()->id}}
                                                                                - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                            </a>
                                                                        @else
                                                                            <span
                                                                                class="badge bg-success text-white">
                                                                                    <i class="ri-fire-fill me-1"></i>{{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                </span>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card bg-light border-0 h-100">
                                                        <div class="card-body">
                                                            <h6 class="text-primary mb-3">
                                                                <i class="ri-shopping-cart-line me-2"></i>{{__('Order Summary')}}
                                                            </h6>
                                                            <div class="d-flex flex-column gap-2">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                        <span class="text-muted">
                                                                            <i class="ri-hashtag me-2"></i>{{__('Quantity')}}
                                                                        </span>
                                                                    <span class="badge bg-soft-secondary fs-6">
                                                                            {{$orderDetail->qty}}
                                                                        </span>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                        <span class="text-muted">
                                                                            <i class="ri-calculator-line me-2"></i>{{__('Calculation')}}
                                                                        </span>
                                                                    <span class="text-dark">
                                                                            {{$orderDetail->qty}} × {{$orderDetail->unit_price}} {{$currency}}
                                                                        </span>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center p-3 bg-primary bg-opacity-10 rounded border border-primary border-opacity-25">
                                                                        <span class="fw-bold text-primary">
                                                                            <i class="ri-price-tag-3-line me-2"></i>{{__('Total Amount')}}
                                                                        </span>
                                                                    <span
                                                                        class="badge bg-primary fs-5 px-3 py-2">
                                                                            {{$orderDetail->total_amount}} {{$currency}}
                                                                        </span>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                        <span class="text-muted">
                                                                            <i class="ri-truck-line me-2"></i>{{__('Shipping')}}
                                                                        </span>
                                                                    @if($orderDetail->shipping)
                                                                        <span
                                                                            class="badge bg-warning-subtle text-warning">
                                                                                {{formatSolde($orderDetail->shipping,3)}} {{$currency}}
                                                                            </span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-secondary-subtle text-secondary">
                                                                                <i class="ri-close-circle-line me-1"></i>{{__('No shipping')}}
                                                                            </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
                                                @if($orderDetail->item->deal()->exists())
                                                    <div class="border-top pt-3 mt-2">
                                                        <h6 class="text-success mb-3">
                                                            <i class="ri-discount-percent-line me-2"></i>{{__('Discount Breakdown')}}
                                                        </h6>
                                                        <div class="row g-3">
                                                            <div class="col-md-3">
                                                                <div
                                                                    class="card border-0">
                                                                    <div class="card-body p-3">
                                                                        <div class="text-center mb-2">
                                                                            <i class="ri-user-star-line text-primary fs-4"></i>
                                                                        </div>
                                                                        <h6 class="text-center text-primary small mb-3">{{__('Partner Discount')}}</h6>
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <small
                                                                                    class="text-muted">{{__('Percentage')}}</small>
                                                                                <span
                                                                                    class="badge bg-primary-subtle text-primary">
                                                                                        {{$orderDetail->partner_discount_percentage}}%
                                                                                    </span>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <small
                                                                                    class="text-muted">{{__('Discount')}}</small>
                                                                                <span
                                                                                    class="text-danger fw-semibold">
                                                                                        -{{formatSolde($orderDetail->partner_discount)}}
                                                                                    </span>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                                <small
                                                                                    class="text-muted">{{__('After')}}</small>
                                                                                <span
                                                                                    class="badge bg-success-subtle text-success">
                                                                                        {{formatSolde($orderDetail->amount_after_partner_discount)}}
                                                                                    </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- 2earn Discount -->
                                                            <div class="col-md-3">
                                                                <div
                                                                    class="card border-0 bg-info bg-opacity-10">
                                                                    <div class="card-body p-3">
                                                                        <div class="text-center mb-2">
                                                                            <i class="ri-gift-2-line text-info fs-4"></i>
                                                                        </div>
                                                                        <h6 class="text-center text-info small mb-3">{{__('2earn Discount')}}</h6>
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <small
                                                                                    class="text-muted">{{__('Percentage')}}</small>
                                                                                <span
                                                                                    class="badge bg-info-subtle text-info">
                                                                                        {{$orderDetail->earn_discount_percentage}}%
                                                                                    </span>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <small
                                                                                    class="text-muted">{{__('Discount')}}</small>
                                                                                <span
                                                                                    class="text-danger fw-semibold">
                                                                                        -{{formatSolde($orderDetail->earn_discount)}}
                                                                                    </span>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                                <small
                                                                                    class="text-muted">{{__('After')}}</small>
                                                                                <span
                                                                                    class="badge bg-success-subtle text-success">
                                                                                        {{formatSolde($orderDetail->amount_after_earn_discount)}}
                                                                                    </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div
                                                                    class="card border-0  bg-opacity-10">
                                                                    <div class="card-body p-3">
                                                                        <div class="text-center mb-2">
                                                                            <i class="ri-fire-line text-warning fs-4"></i>
                                                                        </div>
                                                                        <h6 class="text-center text-warning small mb-3">{{__('Deal Discount')}}</h6>
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <small
                                                                                    class="text-muted">{{__('Percentage')}}</small>
                                                                                <span
                                                                                    class="badge text-warning">
                                                                                        {{$orderDetail->deal_discount_percentage}}%
                                                                                    </span>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <small
                                                                                    class="text-muted">{{__('Discount')}}</small>
                                                                                <span
                                                                                    class="text-danger fw-semibold">
                                                                                        -{{formatSolde($orderDetail->deal_discount)}}
                                                                                    </span>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                                                                <small
                                                                                    class="text-muted">{{__('After')}}</small>
                                                                                <span
                                                                                    class="badge bg-success-subtle text-success">
                                                                                        {{formatSolde($orderDetail->amount_after_deal_discount)}}
                                                                                    </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div
                                                                    class="card border-0 bg-success bg-opacity-10 h-100">
                                                                    <div
                                                                        class="card-body p-3 d-flex flex-column justify-content-center align-items-center">
                                                                        <div class="text-center mb-2">
                                                                            <i class="ri-checkbox-circle-line text-success fs-4"></i>
                                                                        </div>
                                                                        <h6 class="text-center text-success mb-3">{{__('Total Discount')}}</h6>
                                                                        <span
                                                                            class="badge bg-success fs-4 px-4 py-3">
                                                                                <i class="ri-discount-percent-line me-2"></i>{{formatSolde($orderDetail->total_discount,3)}} {{$currency}}
                                                                            </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-light border mb-0 mt-3">
                                                        <i class="ri-information-line me-2"></i>{{__('No deal in this order details')}}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-info d-flex align-items-center mb-0">
                    <i class="ri-inbox-line fs-20 me-2"></i>
                    <span>{{__('Empty order')}}</span>
                </div>
            @endif

        </div>
        <div class="col-12 my-3">
            @if(\App\Models\User::isSuperAdmin())
                @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value && $commissions->isNotEmpty() &&(isset($discount) ||isset($bfss)||isset($cash)))
                    @include('livewire.order-deals', ['orderDeals' => $orderDeals])
                @endif
            @endif
        </div>

        @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value  && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
            <div class="col-12 mt-2">
                <h6 class="card-title mb-0">
                    <i class="ri-file-chart-line text-primary me-2"></i>{{__('Order simulation summary')}}
                </h6>
                <div class=" row">
                    <div class="col-md-4">
                        <div class="card mt-2">
                            <div class="card-header bg-primary-subtle">
                                <h6 class="card-title mb-0 text-primary">
                                    <i class="ri-calculator-line me-2"></i>{{__('Order totals')}}
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @if($order->deal_amount_before_discount)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-money-dollar-circle-line text-primary me-2"></i>
                                                        <strong>{{__('Amount before discount')}}</strong>
                                                    </span>
                                            <span class="badge bg-light text-dark border">
                                                        {{formatSolde($order->deal_amount_before_discount)}} {{$currency}}
                                                    </span>
                                        </li>
                                    @endif
                                    @if($order->out_of_deal_amount)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-money-dollar-circle-line text-warning me-2"></i>
                                                        <strong>{{__('Out of deal amount')}}</strong>
                                                    </span>
                                            <span class="badge bg-light text-dark border">
                                                        {{formatSolde($order->out_of_deal_amount)}} {{$currency}}
                                                    </span>
                                        </li>
                                    @endif
                                    @if($order->out_of_deal_amount && $order->deal_amount_before_discount)
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                                    <span>
                                                        <i class="ri-calculator-line text-success me-2"></i>
                                                        <strong>{{__('Total')}}</strong>
                                                    </span>
                                            <span class="badge bg-success fs-14">
                                                        {{formatSolde($order->out_of_deal_amount +$order->deal_amount_before_discount)}} {{$currency}}
                                                    </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    @if($order->total_final_discount)
                        <div class="col-md-4">
                            <div class="card mt-2 ">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 text-warning">
                                        <i class="ri-discount-percent-line me-2"></i>{{__('Order discounts')}}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @if($order->total_final_discount)
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                title="{{$order->total_final_discount_percentage}} {{config('app.percentage')}}">
                                                        <span>
                                                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                                                            <strong>{{__('Total final discount')}}</strong>
                                                        </span>
                                                <span class="badge bg-success-subtle text-success">
                                                            {{formatSolde($order->total_final_discount)}} {{$currency}}
                                                        </span>
                                            </li>
                                        @endif
                                        @if($order->total_lost_discount)
                                            <li class="list-group-item"
                                                title="{{$order->total_lost_discount_percentage}} {{config('app.percentage')}}">
                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-2">
                                                            <span>
                                                                <i class="ri-error-warning-line text-danger me-2"></i>
                                                                <strong>{{__('Total lost discount')}}</strong>
                                                            </span>
                                                    <span class="badge bg-danger-subtle text-danger">
                                                                {{formatSolde($order->total_lost_discount)}} {{$currency}}
                                                            </span>
                                                </div>
                                                <div class="alert alert-warning mb-0 small">
                                                    <i class="ri-information-line me-1"></i>
                                                    {{__('You can top up your discount with')}} {{formatSolde($order->total_lost_discount)}} {{$currency}}
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($order->amount_after_discount)
                        <div class="col-md-4">
                            <div class="card mt-2 border shadow-sm">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 text-success">
                                        <i class="ri-wallet-3-line me-2"></i>{{__('Order mains amounts')}}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-price-tag-3-line text-success me-2"></i>
                                                        <strong>{{__('Amount after discount')}}</strong>
                                                    </span>
                                            <span class="badge bg-success fs-14">
                                                        {{formatSolde($order->amount_after_discount)}} {{$currency}}
                                                    </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                                    <span>
                                                        <i class="ri-arrow-up-circle-line text-info me-2"></i>
                                                        <strong>{{__('Gain from BFSs soldes')}}</strong>
                                                    </span>
                                            <span class="badge bg-info-subtle text-info fs-14">
                                                        {{formatSolde($order->amount_after_discount-$order->paid_cash)}} {{$currency}}
                                                    </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-money-dollar-circle-line text-danger me-2"></i>
                                                        <strong>{{__('Paid cash')}}</strong>
                                                    </span>
                                            <span class="badge bg-danger fs-14">
                                                        {{formatSolde($order->paid_cash)}} {{$currency}}
                                                    </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-12 my-3">
                @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value &&(!is_null($discount) ||!is_null($bfss)||!is_null($cash)))
                    <div class="col-md-12 my-2">
                        <div class=" my-2 border-0 shadow-sm">
                            <h6>
                                <i class="ri-wallet-line text-primary me-2"></i>{{__('Balances Operations')}}
                            </h6>
                            <div class="mt-2">
                                <ul class="list-group list-group-flush">
                                    @if(isset($discount))
                                        <li class="list-group-item logoTopDBLabel">
                                            <h5 class="mb-3">
                                                <i class="ri-discount-percent-line text-primary me-2"></i>{{__('Discount')}}
                                            </h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">{{__('Reference')}}</th>
                                                        <th scope="col" class="text-end">{{__('Value')}}</th>
                                                        <th scope="col"
                                                            class="text-end">{{__('Current balance')}}</th>
                                                        <th scope="col">{{__('Description')}}</th>
                                                        <th scope="col">{{__('Created at')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                                <span
                                                                    class="badge bg-primary-subtle text-primary">{{$discount->reference}}</span>
                                                        </td>
                                                        <td class="logoTopDBLabel text-end">
                                                            <h6 class="mb-0 text-danger">
                                                                -{{formatSolde($discount->value)}} {{$currency}}</h6>
                                                        </td>
                                                        <td class="logoTopDBLabel text-end">
                                                            <h6 class="mb-0">{{formatSolde($discount->current_balance)}} {{$currency}}</h6>
                                                        </td>
                                                        <td>
                                                            <p class="mb-1 small">{{$discount->description}}</p>
                                                            <p class="mb-1 text-muted small">
                                                                {{__('Discount')}} {{$discount->current_balance+$discount->value}}
                                                                - {{$discount->value}}
                                                                = {{$discount->current_balance}}
                                                            </p>
                                                            <div class="mt-2">
                                                                {!! \App\Services\Balances\Balances::generateDescription($discount) !!}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <small
                                                                class="text-muted">{{$discount->created_at}}</small>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    @endif
                                    @if(isset($bfss) && $bfss->isNotEmpty())
                                        <li class="list-group-item logoTopBFSLabel">
                                            <h5 class="mb-3">
                                                <i class="ri-shopping-bag-line text-info me-2"></i>{{__('BFS (Balances for Shopping)')}}
                                            </h5>
                                            <div class="table-responsive">
                                                <table class="table align-middle mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">{{__('Reference')}}</th>
                                                        <th scope="col" class="text-end">{{__('Value')}}</th>
                                                        <th scope="col"
                                                            class="text-end">{{__('Current balance')}}</th>
                                                        <th scope="col">{{__('Description')}}</th>
                                                        <th scope="col"
                                                            class="text-end">{{__('Percentage')}}</th>
                                                        <th scope="col">{{__('Created at')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($bfss as $bfs)
                                                        <tr>
                                                            <td>
                                                                    <span
                                                                        class="badge bg-info-subtle text-info">{{$bfs->reference}}</span>
                                                            </td>
                                                            <td class="logoTopBFSLabel text-end">
                                                                <h6 class="mb-0 text-danger">
                                                                    -{{formatSolde($bfs->value)}} {{$currency}}</h6>
                                                            </td>
                                                            <td class="logoTopBFSLabel text-end">
                                                                <h6 class="mb-0">{{formatSolde($bfs->current_balance)}} {{$currency}}</h6>
                                                            </td>
                                                            <td>
                                                                <p class="mb-1 small">{{$bfs->description}}</p>
                                                                <p class="mb-1 text-muted small">
                                                                    {{__('BFS')}} {{$bfs->current_balance+$bfs->value}}
                                                                    - {{$bfs->value}}
                                                                    = {{$bfs->current_balance}}
                                                                </p>
                                                                <div class="mt-2">
                                                                    {!! \App\Services\Balances\Balances::generateDescription($bfs) !!}
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                    <span class="badge bg-warning-subtle text-warning">
                                                                        {{$bfs->percentage}} {{config('app.percentage')}}
                                                                    </span>
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="text-muted">{{$bfs->created_at}}</small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    @endif
                                    @if(isset($cash))
                                        <li class="list-group-item logoTopCashLabel">
                                            <h5 class="mb-3">
                                                <i class="ri-wallet-3-line text-success me-2"></i>{{__('Cash')}}
                                            </h5>
                                            <div class="table-responsive">
                                                <table class="table align-middle mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">{{__('Reference')}}</th>
                                                        <th scope="col" class="text-end">{{__('Value')}}</th>
                                                        <th scope="col"
                                                            class="text-end">{{__('Current balance')}}</th>
                                                        <th scope="col">{{__('Description')}}</th>
                                                        <th scope="col">{{__('Created at')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                                <span
                                                                    class="badge bg-success-subtle text-success">{{$cash->reference}}</span>
                                                        </td>
                                                        <td class="logoTopCashLabel text-end">
                                                            <h6 class="mb-0 text-danger">
                                                                -{{formatSolde($cash->value)}} {{$currency}}</h6>
                                                        </td>
                                                        <td class="logoTopCashLabel text-end">
                                                            <h6 class="mb-0">{{formatSolde($cash->current_balance)}} {{$currency}}</h6>
                                                        </td>
                                                        <td>
                                                            <p class="mb-1 small">{{$cash->description}}</p>
                                                            <p class="mb-1 text-muted small">
                                                                {{__('CASH')}} {{$cash->current_balance+$cash->value}}
                                                                - {{$cash->value}}
                                                                = {{$cash->current_balance}}
                                                            </p>
                                                            <div class="mt-2">
                                                                {!! \App\Services\Balances\Balances::generateDescription($cash) !!}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <small
                                                                class="text-muted">{{$cash->created_at}}</small>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-12 my-3">
                @if(\App\Models\User::isSuperAdmin())
                    @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value && $commissions->isNotEmpty() &&(isset($discount) ||isset($bfss)||isset($cash)))
                        @include('livewire.commission-breackdowns', ['commissions' => $commissions])
                    @endif
                @endif
            </div>
        @endif
        <div class="col-12 my-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="badge bg-info-subtle text-info fs-13">
                        <i class="ri-refresh-line me-1"></i>{{__('Updated at')}} : {{$order->updated_at}}
                    </span>
                @if($currentRouteName=="orders_detail")
                    <span class="badge bg-info-subtle text-info fs-13">
                            <i class="ri-time-line me-1"></i>{{__('Created at')}} : {{$order->created_at}}
                        </span>
                @endif
                @if($currentRouteName=="orders_index" || $currentRouteName=="orders_previous"|| $currentRouteName=="orders_summary" )
                    <a href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$order->id])}}"
                       class="btn btn-sm btn-outline-primary ms-auto">
                        <i class="ri-eye-line me-1"></i>{{__('More details')}}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

