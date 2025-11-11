<div class="{{getContainerType()}}">
    @if($currentRouteName=="orders_detail")
        @section('title')
            {{ __('Order details') }} : {{__('Order id')}} : {{$order->id}}
        @endsection
        @component('components.breadcrumb')
            @slot('title')
                <a href="{{route('orders_previous',['locale'=> app()->getLocale()])}}" class="text-white mx-1"><i
                        class="ri-shopping-cart-fill"></i> </a>   {{ __('Order details') }} : {{__('Order id')}}
                : {{$order->id}}
            @endslot
        @endcomponent
        <div class="row">
            <div class="col-12">
                @include('layouts.flash-messages')
            </div>
        </div>
    @endif
    <div class="card shadow-sm border">
        <div class="card-header bg-light border-bottom">
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
        <div class="card-body">
            <div class="card card-border-dark shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="ri-file-text-line text-primary me-2"></i>{{__('Order details summary')}}
                    </h6>
                </div>
                @if($order->OrderDetails()->count()>0)
                    <div class="card-body row">
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
                                <div class="mt-2">
                                    @foreach($order->orderDetails()->get() as $key => $orderDetail)
                                        <div class="card mb-3 shadow-sm @if($orderDetail->item()->first()->deal()->exists()) border-success @else border @endif">
                                            <div class="card-header @if($orderDetail->item()->first()->deal()->exists()) bg-success-subtle @else bg-light @endif">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="badge bg-primary fs-14">
                                                        <i class="ri-hashtag me-1"></i>{{$key + 1}}
                                                    </span>
                                                    @if($orderDetail->item()->first()->deal()->exists())
                                                        <span class="badge bg-success">
                                                            <i class="ri-gift-line me-1"></i>{{__('Deal')}}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <!-- Order Details Column -->
                                                    <div class="col-lg-6">
                                                        <div class="card border h-100">
                                                            <div class="card-header bg-light">
                                                                <h6 class="card-title mb-0">
                                                                    <i class="ri-file-list-line text-primary me-2"></i>{{__('Order details')}}
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                @if($currentRouteName=="orders_detail")
                                                                    <ul class="list-group list-group-flush">
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <strong><i class="ri-price-tag-line text-primary me-1"></i>{{__('REF')}} - {{__('Name')}}</strong>
                                                                            <span class="badge bg-primary-subtle text-primary">#{{$orderDetail->item()->first()->ref}} - {{$orderDetail->item()->first()->name}}</span>
                                                                        </li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <strong><i class="ri-money-dollar-circle-line text-success me-1"></i>{{__('Price')}}</strong>
                                                                            <span class="badge bg-success-subtle text-success">{{$orderDetail->unit_price}} {{config('app.currency')}}</span>
                                                                        </li>
                                                                        @if($orderDetail->item()->first()?->platform()->exists())
                                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <strong><i class="ri-computer-line text-info me-1"></i>{{__('Platform')}}</strong>
                                                                                <span class="badge bg-info-subtle text-info">{{__($orderDetail->item()->first()?->platform()->first()?->name)}}</span>
                                                                            </li>
                                                                        @endif
                                                                        @if($orderDetail->item()->first()->deal()->exists())
                                                                            <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                                                                <strong><i class="ri-gift-line text-success me-1"></i>{{__('Deal')}}</strong>
                                                                                @if(\App\Models\User::isSuperAdmin())
                                                                                    <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}" class="badge bg-success text-white text-decoration-none">
                                                                                        {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                    </a>
                                                                                @else
                                                                                    <span class="badge bg-success-subtle text-success">
                                                                                        {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                    </span>
                                                                                @endif
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                @else
                                                                    <div class="mb-3">
                                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                                            <strong class="text-muted"><i class="ri-shopping-bag-line me-1"></i>{{__('Item')}}:</strong>
                                                                            <span class="badge bg-info-subtle text-info">
                                                                                @if(\App\Models\User::isSuperAdmin())
                                                                                    <a href="{{route('items_detail',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->id])}}" class="text-info text-decoration-none">
                                                                                        {{$orderDetail->item()->first()->ref}} - {{$orderDetail->item()->first()->name}}
                                                                                    </a>
                                                                                @else
                                                                                    {{$orderDetail->item()->first()->ref}} - {{$orderDetail->item()->first()->name}}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                        @if($orderDetail->item()->first()->deal()->exists())
                                                                            <div class="d-flex justify-content-between align-items-start">
                                                                                <strong class="text-muted"><i class="ri-gift-line me-1"></i>{{__('Deal')}}:</strong>
                                                                                <span class="badge bg-success-subtle text-success">
                                                                                    @if(\App\Models\User::isSuperAdmin())
                                                                                        <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}" class="text-success text-decoration-none">
                                                                                            {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                        </a>
                                                                                    @else
                                                                                        {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Prices & Shipping Column -->
                                                    <div class="col-lg-6">
                                                        <div class="card border h-100">
                                                            <div class="card-header bg-light">
                                                                <h6 class="card-title mb-0">
                                                                    <i class="ri-money-dollar-circle-line text-success me-2"></i>{{__('Prices')}} & {{__('Shipping')}}
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong><i class="ri-calculator-line text-primary me-1"></i>{{__('Calculation')}}</strong>
                                                                        <span class="badge bg-light text-dark border">
                                                                            {{$orderDetail->qty}} × {{$orderDetail->unit_price}} {{config('app.currency')}}
                                                                        </span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong><i class="ri-price-tag-3-line text-success me-1"></i>{{__('Total Amount')}}</strong>
                                                                        <span class="badge bg-primary fs-14">
                                                                            {{$orderDetail->total_amount}} {{config('app.currency')}}
                                                                        </span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong><i class="ri-truck-line text-warning me-1"></i>{{__('Shipping')}}</strong>
                                                                        @if($orderDetail->shipping)
                                                                            <span class="badge bg-warning-subtle text-warning">
                                                                                {{$orderDetail->shipping}} {{config('app.currency')}}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">
                                                                                <i class="ri-close-circle-line me-1"></i>{{__('No shipping')}}
                                                                            </span>
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Discount Details (if applicable) -->
                                                    @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
                                                        @if($orderDetail->item->deal()->exists())
                                                            <div class="col-12">
                                                                <div class="card border-warning">
                                                                    <div class="card-header bg-warning-subtle">
                                                                        <h6 class="card-title mb-0">
                                                                            <i class="ri-discount-percent-line text-warning me-2"></i>{{__('Discount Breakdown')}}
                                                                        </h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row g-3">
                                                                            <!-- Partner Discount -->
                                                                            <div class="col-md-3">
                                                                                <div class="card border">
                                                                                    <div class="card-header bg-light">
                                                                                        <small class="fw-bold text-muted">{{__('Partner Discount')}}</small>
                                                                                    </div>
                                                                                    <div class="card-body p-2">
                                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                            <i class="ri-percent-line text-primary"></i>
                                                                                            <span class="badge bg-primary-subtle text-primary">
                                                                                                {{$orderDetail->partner_discount_percentage}}%
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                            <i class="ri-arrow-down-line text-danger"></i>
                                                                                            <span class="text-danger fw-semibold">
                                                                                                -{{$orderDetail->partner_discount}}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                                            <i class="ri-money-dollar-circle-line text-success"></i>
                                                                                            <span class="badge bg-success-subtle text-success">
                                                                                                {{$orderDetail->amount_after_partner_discount}}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- 2earn Discount -->
                                                                            <div class="col-md-3">
                                                                                <div class="card border">
                                                                                    <div class="card-header bg-light">
                                                                                        <small class="fw-bold text-muted">{{__('2earn Discount')}}</small>
                                                                                    </div>
                                                                                    <div class="card-body p-2">
                                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                            <i class="ri-percent-line text-primary"></i>
                                                                                            <span class="badge bg-primary-subtle text-primary">
                                                                                                {{$orderDetail->earn_discount_percentage}}%
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                            <i class="ri-arrow-down-line text-danger"></i>
                                                                                            <span class="text-danger fw-semibold">
                                                                                                -{{$orderDetail->earn_discount}}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                                            <i class="ri-money-dollar-circle-line text-success"></i>
                                                                                            <span class="badge bg-success-subtle text-success">
                                                                                                {{$orderDetail->amount_after_earn_discount}}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Deal Discount -->
                                                                            <div class="col-md-3">
                                                                                <div class="card border">
                                                                                    <div class="card-header bg-light">
                                                                                        <small class="fw-bold text-muted">{{__('Deal Discount')}}</small>
                                                                                    </div>
                                                                                    <div class="card-body p-2">
                                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                            <i class="ri-percent-line text-primary"></i>
                                                                                            <span class="badge bg-primary-subtle text-primary">
                                                                                                {{$orderDetail->deal_discount_percentage}}%
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                            <i class="ri-arrow-down-line text-danger"></i>
                                                                                            <span class="text-danger fw-semibold">
                                                                                                -{{$orderDetail->deal_discount}}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                                            <i class="ri-money-dollar-circle-line text-success"></i>
                                                                                            <span class="badge bg-success-subtle text-success">
                                                                                                {{$orderDetail->amount_after_deal_discount}}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Total Discount -->
                                                                            <div class="col-md-3">
                                                                                <div class="card border-success">
                                                                                    <div class="card-header bg-success-subtle">
                                                                                        <small class="fw-bold text-success">{{__('Total discount')}}</small>
                                                                                    </div>
                                                                                    <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                                                                        <span class="badge bg-success fs-14">
                                                                                            <i class="ri-discount-percent-line me-1"></i>{{$orderDetail->total_discount}} {{config('app.currency')}}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-12">
                                                                <div class="alert alert-light mb-0">
                                                                    <i class="ri-information-line me-1"></i>{{__('No deal in this order details')}}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card-body">
                        <div class="alert alert-info d-flex align-items-center mb-0">
                            <i class="ri-inbox-line fs-20 me-2"></i>
                            <span>{{__('Empty order')}}</span>
                        </div>
                    </div>
                @endif
            </div>

            @if(\App\Models\User::isSuperAdmin())
                @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value && $commissions->isNotEmpty() &&(isset($discount) ||isset($bfss)||isset($cash)))
                    @include('livewire.order-deals', ['orderDeals' => $orderDeals])
                @endif
            @endif
            @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value  && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
                <div class="col-md-12">
                    <div class="card mt-2 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="ri-file-chart-line text-primary me-2"></i>{{__('Order simulation summary')}}
                            </h6>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-4">
                                <div class="card mt-2 border shadow-sm">
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
                                                        {{$order->deal_amount_before_discount}} {{config('app.currency')}}
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
                                                        {{$order->out_of_deal_amount}} {{config('app.currency')}}
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
                                                        {{$order->out_of_deal_amount +$order->deal_amount_before_discount}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if($order->total_final_discount)
                                <div class="col-md-4">
                                    <div class="card mt-2 border shadow-sm">
                                        <div class="card-header bg-warning-subtle">
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
                                                            {{$order->total_final_discount}} {{config('app.currency')}}
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
                                                                {{$order->total_lost_discount}} {{config('app.currency')}}
                                                            </span>
                                                        </div>
                                                        <div class="alert alert-warning mb-0 small">
                                                            <i class="ri-information-line me-1"></i>
                                                            {{__('You can top up your discount with')}} {{$order->total_lost_discount}} {{config('app.currency')}}
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
                                        <div class="card-header bg-success-subtle">
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
                                                        {{$order->amount_after_discount}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                                    <span>
                                                        <i class="ri-arrow-up-circle-line text-info me-2"></i>
                                                        <strong>{{__('Gain from BFSs soldes')}}</strong>
                                                    </span>
                                                    <span class="badge bg-info-subtle text-info fs-14">
                                                        {{$order->amount_after_discount-$order->paid_cash}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-money-dollar-circle-line text-danger me-2"></i>
                                                        <strong>{{__('Paid cash')}}</strong>
                                                    </span>
                                                    <span class="badge bg-danger fs-14">
                                                        {{$order->paid_cash}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value &&(!is_null($discount) ||!is_null($bfss)||!is_null($cash)))
                        <div class="col-md-12">
                            <div class="card mt-2 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">
                                        <i class="ri-wallet-line text-primary me-2"></i>{{__('Balances Operations')}}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @if(isset($discount))
                                            <li class="list-group-item logoTopDBLabel">
                                                <div class="card border-primary shadow-sm">
                                                    <div class="card-header bg-primary-subtle">
                                                        <h5 class="card-title mb-0">
                                                            <i class="ri-discount-percent-line text-primary me-2"></i>{{__('Discount')}}
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <!-- Reference -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border h-100">
                                                                    <div class="card-header bg-light">
                                                                        <small class="fw-bold text-muted">{{__('Reference')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center">
                                                                        <span class="badge bg-primary-subtle text-primary fs-14">
                                                                            {{$discount->reference}}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Value -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border-danger h-100">
                                                                    <div class="card-header bg-danger-subtle">
                                                                        <small class="fw-bold text-danger">{{__('Value')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center logoTopDBLabel">
                                                                        <h6 class="mb-0 text-danger">
                                                                            <i class="ri-arrow-down-line me-1"></i>-{{$discount->value}} {{config('app.currency')}}
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Current Balance -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border-success h-100">
                                                                    <div class="card-header bg-success-subtle">
                                                                        <small class="fw-bold text-success">{{__('Current balance')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center logoTopDBLabel">
                                                                        <h6 class="mb-0 text-success">
                                                                            <i class="ri-wallet-line me-1"></i>{{$discount->current_balance}} {{config('app.currency')}}
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Created At -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border h-100">
                                                                    <div class="card-header bg-light">
                                                                        <small class="fw-bold text-muted">{{__('Created at')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center">
                                                                        <small class="text-muted">
                                                                            <i class="ri-time-line me-1"></i>{{$discount->created_at}}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Description (Full Width) -->
                                                            <div class="col-12">
                                                                <div class="card border-info">
                                                                    <div class="card-header bg-info-subtle">
                                                                        <small class="fw-bold text-info">
                                                                            <i class="ri-information-line me-1"></i>{{__('Description')}}
                                                                        </small>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p class="mb-2">{{$discount->description}}</p>
                                                                        <div class="alert alert-light mb-2">
                                                                            <small class="text-muted">
                                                                                <i class="ri-calculator-line me-1"></i>
                                                                                {{__('Discount')}} {{$discount->current_balance+$discount->value}}
                                                                                - {{$discount->value}}
                                                                                = {{$discount->current_balance}}
                                                                            </small>
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            {!! \App\Services\Balances\Balances::generateDescription($discount) !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                        @if(isset($bfss) && $bfss->isNotEmpty())
                                            <li class="list-group-item logoTopBFSLabel">
                                                <div class="card border-info shadow-sm">
                                                    <div class="card-header bg-info-subtle">
                                                        <h5 class="card-title mb-0">
                                                            <i class="ri-shopping-bag-line text-info me-2"></i>{{__('BFS (Balances for Shopping)')}}
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach($bfss as $bfs)
                                                            <div class="card border mb-3">
                                                                <div class="card-body">
                                                                    <div class="row g-3">
                                                                        <!-- Reference -->
                                                                        <div class="col-md-6 col-lg-2">
                                                                            <div class="card border h-100">
                                                                                <div class="card-header bg-light">
                                                                                    <small class="fw-bold text-muted">{{__('Reference')}}</small>
                                                                                </div>
                                                                                <div class="card-body d-flex align-items-center justify-content-center">
                                                                                    <span class="badge bg-info-subtle text-info fs-14">
                                                                                        {{$bfs->reference}}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Value -->
                                                                        <div class="col-md-6 col-lg-2">
                                                                            <div class="card border-danger h-100">
                                                                                <div class="card-header bg-danger-subtle">
                                                                                    <small class="fw-bold text-danger">{{__('Value')}}</small>
                                                                                </div>
                                                                                <div class="card-body d-flex align-items-center justify-content-center logoTopBFSLabel">
                                                                                    <h6 class="mb-0 text-danger">
                                                                                        <i class="ri-arrow-down-line me-1"></i>-{{$bfs->value}} {{config('app.currency')}}
                                                                                    </h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Current Balance -->
                                                                        <div class="col-md-6 col-lg-2">
                                                                            <div class="card border-success h-100">
                                                                                <div class="card-header bg-success-subtle">
                                                                                    <small class="fw-bold text-success">{{__('Current balance')}}</small>
                                                                                </div>
                                                                                <div class="card-body d-flex align-items-center justify-content-center logoTopBFSLabel">
                                                                                    <h6 class="mb-0 text-success">
                                                                                        <i class="ri-wallet-line me-1"></i>{{$bfs->current_balance}} {{config('app.currency')}}
                                                                                    </h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Percentage -->
                                                                        <div class="col-md-6 col-lg-2">
                                                                            <div class="card border-warning h-100">
                                                                                <div class="card-header bg-warning-subtle">
                                                                                    <small class="fw-bold text-warning">{{__('Percentage')}}</small>
                                                                                </div>
                                                                                <div class="card-body d-flex align-items-center justify-content-center">
                                                                                    <span class="badge bg-warning-subtle text-warning fs-14">
                                                                                        <i class="ri-percent-line me-1"></i>{{$bfs->percentage}} {{config('app.percentage')}}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Created At -->
                                                                        <div class="col-md-6 col-lg-4">
                                                                            <div class="card border h-100">
                                                                                <div class="card-header bg-light">
                                                                                    <small class="fw-bold text-muted">{{__('Created at')}}</small>
                                                                                </div>
                                                                                <div class="card-body d-flex align-items-center justify-content-center">
                                                                                    <small class="text-muted">
                                                                                        <i class="ri-time-line me-1"></i>{{$bfs->created_at}}
                                                                                    </small>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Description (Full Width) -->
                                                                        <div class="col-12">
                                                                            <div class="card border-info">
                                                                                <div class="card-header bg-info-subtle">
                                                                                    <small class="fw-bold text-info">
                                                                                        <i class="ri-information-line me-1"></i>{{__('Description')}}
                                                                                    </small>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <p class="mb-2">{{$bfs->description}}</p>
                                                                                    <div class="alert alert-light mb-2">
                                                                                        <small class="text-muted">
                                                                                            <i class="ri-calculator-line me-1"></i>
                                                                                            {{__('BFS')}} {{$bfs->current_balance+$bfs->value}}
                                                                                            - {{$bfs->value}}
                                                                                            = {{$bfs->current_balance}}
                                                                                        </small>
                                                                                    </div>
                                                                                    <div class="mt-2">
                                                                                        {!! \App\Services\Balances\Balances::generateDescription($bfs) !!}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                        @if(isset($cash))
                                            <li class="list-group-item logoTopCashLabel">
                                                <div class="card border-success shadow-sm">
                                                    <div class="card-header bg-success-subtle">
                                                        <h5 class="card-title mb-0">
                                                            <i class="ri-wallet-3-line text-success me-2"></i>{{__('Cash')}}
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <!-- Reference -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border h-100">
                                                                    <div class="card-header bg-light">
                                                                        <small class="fw-bold text-muted">{{__('Reference')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center">
                                                                        <span class="badge bg-success-subtle text-success fs-14">
                                                                            {{$cash->reference}}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Value -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border-danger h-100">
                                                                    <div class="card-header bg-danger-subtle">
                                                                        <small class="fw-bold text-danger">{{__('Value')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center logoTopCashLabel">
                                                                        <h6 class="mb-0 text-danger">
                                                                            <i class="ri-arrow-down-line me-1"></i>-{{$cash->value}} {{config('app.currency')}}
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Current Balance -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border-success h-100">
                                                                    <div class="card-header bg-success-subtle">
                                                                        <small class="fw-bold text-success">{{__('Current balance')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center logoTopCashLabel">
                                                                        <h6 class="mb-0 text-success">
                                                                            <i class="ri-wallet-line me-1"></i>{{$cash->current_balance}} {{config('app.currency')}}
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Created At -->
                                                            <div class="col-md-6 col-lg-3">
                                                                <div class="card border h-100">
                                                                    <div class="card-header bg-light">
                                                                        <small class="fw-bold text-muted">{{__('Created at')}}</small>
                                                                    </div>
                                                                    <div class="card-body d-flex align-items-center justify-content-center">
                                                                        <small class="text-muted">
                                                                            <i class="ri-time-line me-1"></i>{{$cash->created_at}}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Description (Full Width) -->
                                                            <div class="col-12">
                                                                <div class="card border-info">
                                                                    <div class="card-header bg-info-subtle">
                                                                        <small class="fw-bold text-info">
                                                                            <i class="ri-information-line me-1"></i>{{__('Description')}}
                                                                        </small>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p class="mb-2">{{$cash->description}}</p>
                                                                        <div class="alert alert-light mb-2">
                                                                            <small class="text-muted">
                                                                                <i class="ri-calculator-line me-1"></i>
                                                                                {{__('CASH')}} {{$cash->current_balance+$cash->value}}
                                                                                - {{$cash->value}}
                                                                                = {{$cash->current_balance}}
                                                                            </small>
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            {!! \App\Services\Balances\Balances::generateDescription($cash) !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(\App\Models\User::isSuperAdmin())
                        @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value && $commissions->isNotEmpty() &&(isset($discount) ||isset($bfss)||isset($cash)))
                            @include('livewire.commission-breackdowns', ['commissions' => $commissions])
                        @endif
                    @endif
                </div>
            @endif

            <div class="card-footer bg-light">
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
                           class="btn btn-sm btn-primary ms-auto">
                            <i class="ri-eye-line me-1"></i>{{__('More details')}}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
