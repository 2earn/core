<div class="container">
    @section('title')
        {{ __('Order Dashboard') }}
    @endsection

    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Order Dashboard') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row mb-2">
        <div class="col-12 card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-filter-3-line me-2"></i>{{ __('Filters') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Start Date -->
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">{{ __('Start Date') }}</label>
                        <input type="date"
                               id="startDate"
                               wire:model.live="startDate"
                               class="form-control">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">{{ __('End Date') }}</label>
                        <input type="date"
                               id="endDate"
                               wire:model.live="endDate"
                               class="form-control">
                    </div>

                    <!-- Deal Filter -->
                    <div class="col-md-3">
                        <label for="dealId" class="form-label">{{ __('Deal') }}</label>
                        <select id="dealId"
                                wire:model.live="dealId"
                                class="form-select">
                            <option value="">{{ __('All Deals') }}</option>
                            @foreach($deals as $deal)
                                <option value="{{ $deal->id }}"> {{ $deal->id }} - {{ $deal->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Filter -->
                    <div class="col-md-3">
                        <label for="productId" class="form-label">{{ __('Product') }}</label>
                        <select id="productId"
                                wire:model.live="productId"
                                class="form-select"
                                @if(!$dealId) disabled @endif>
                            <option value="">{{ __('All Products') }}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->ref }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="mt-3">
                    <button wire:click="resetFilters"
                            class="btn btn-secondary">
                        <i class="ri-refresh-line me-1"></i>{{ __('Reset Filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($loading)
        <div class="row">
            <div class="col-12 card">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                    <p class="mt-2 text-muted">{{ __('Loading statistics...') }}</p>
                </div>
            </div>
        </div>
    @else
        @if(isset($statistics['summary']))
            <div class="row mb-2">
                <div class="col-xl col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Total Orders') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-shopping-cart-line align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                        {{ number_format($statistics['summary']['total_orders']) }}
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-shopping-bag text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="col-xl col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Total Revenue') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                        {{ number_format($statistics['summary']['total_revenue'], 2) }}
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="bx bx-dollar-circle text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Paid Card -->
                <div class="col-xl col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Total Paid') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                        {{ number_format($statistics['summary']['total_paid'], 2) }}
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="bx bx-wallet text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Sold Card -->
                <div class="col-xl col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Items Sold') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                        {{ number_format($statistics['summary']['total_items_sold']) }}
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="bx bx-package text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Order Value Card -->
                <div class="col-xl col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Avg Order Value') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-muted fs-14 mb-0">
                                        <i class="ri-arrow-right-line align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                        {{ number_format($statistics['summary']['average_order_value'], 2) }}
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                        <i class="bx bx-trending-up text-secondary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($statistics['orders_by_deal']) && count($statistics['orders_by_deal']) > 0)
            <div class="row mb-4">
                <div class="col-12 card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">
                            <i class="ri-file-list-3-line me-2"></i>{{ __('Orders by Deal') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('Deal Name') }}</th>
                                    <th scope="col" class="text-center">{{ __('Orders') }}</th>
                                    <th scope="col" class="text-end">{{ __('Revenue') }}</th>
                                    <th scope="col" class="text-center">{{ __('Items Sold') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($statistics['orders_by_deal'] as $deal)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div
                                                            class="avatar-title bg-primary-subtle text-primary rounded-circle fs-13">
                                                            <i class="ri-file-text-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fs-14 mb-0">{{ $deal->deal_name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        {{ number_format($deal->orders_count) }}
                                                    </span>
                                        </td>
                                        <td class="text-end">
                                                    <span class="text-success fw-semibold">
                                                        {{ number_format($deal->total_revenue, 2) }}
                                                    </span>
                                        </td>
                                        <td class="text-center">
                                                    <span class="badge bg-info-subtle text-info">
                                                        {{ number_format($deal->items_sold) }}
                                                    </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($statistics['top_products']) && count($statistics['top_products']) > 0)
            <div class="row mb-4">
                <div class="col-12 card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">
                            <i class="ri-star-line me-2"></i>{{ __('Top Products') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('Product') }}</th>
                                    <th scope="col">{{ __('Ref') }}</th>
                                    <th scope="col" class="text-center">{{ __('Quantity Sold') }}</th>
                                    <th scope="col" class="text-end">{{ __('Revenue') }}</th>
                                    <th scope="col" class="text-center">{{ __('Orders') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($statistics['top_products'] as $index => $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div
                                                            class="avatar-title bg-warning-subtle text-warning rounded-circle fs-13">
                                                            {{ $index + 1 }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fs-14 mb-0">{{ $product->product_name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        {{ $product->product_ref }}
                                                    </span>
                                        </td>
                                        <td class="text-center">
                                                    <span class="badge bg-primary-subtle text-primary fs-12">
                                                        {{ number_format($product->quantity_sold) }}
                                                    </span>
                                        </td>
                                        <td class="text-end">
                                                    <span class="text-success fw-semibold">
                                                        {{ number_format($product->total_revenue, 2) }}
                                                    </span>
                                        </td>
                                        <td class="text-center">
                                                    <span class="badge bg-info-subtle text-info">
                                                        {{ number_format($product->orders_count) }}
                                                    </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Orders List -->
        @if(isset($statistics['orders_list']) && count($statistics['orders_list']) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                <i class="ri-list-check-2 me-2"></i>{{ __('Recent Orders') }}
                            </h4>
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ count($statistics['orders_list']) }} {{ __('orders') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-nowrap align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">{{ __('Order ID') }}</th>
                                            <th scope="col">{{ __('Date') }}</th>
                                            <th scope="col">{{ __('Customer') }}</th>
                                            <th scope="col" class="text-end">{{ __('Revenue') }}</th>
                                            <th scope="col" class="text-end">{{ __('Paid') }}</th>
                                            <th scope="col" class="text-center">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($statistics['orders_list'] as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('orders_detail', ['locale' => app()->getLocale(), 'id' => $order->id]) }}"
                                                       class="fw-medium link-primary">
                                                        #{{ $order->id }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="ri-calendar-line me-1"></i>
                                                        {{ \Carbon\Carbon::parse($order->payment_datetime)->format('M d, Y') }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($order->payment_datetime)->format('h:i A') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <div class="avatar-xs">
                                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                    {{ substr($order->user->name ?? 'U', 0, 1) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="fs-14 mb-0">{{ $order->user->name ?? __('Unknown') }}</h6>
                                                            <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="text-success fw-semibold">
                                                        {{ number_format($order->total_order, 2) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="text-info fw-semibold">
                                                        {{ number_format($order->paid_cash, 2) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = match($order->status) {
                                                            1 => 'bg-warning-subtle text-warning',
                                                            2 => 'bg-success-subtle text-success',
                                                            3 => 'bg-danger-subtle text-danger',
                                                            default => 'bg-secondary-subtle text-secondary'
                                                        };
                                                        $statusText = match($order->status) {
                                                            1 => __('Ready'),
                                                            2 => __('Completed'),
                                                            3 => __('Cancelled'),
                                                            default => __('Unknown')
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end fw-semibold">{{ __('Total') }}:</td>
                                            <td class="text-end">
                                                <span class="text-success fw-bold fs-15">
                                                    {{ number_format($statistics['orders_list']->sum('total_order'), 2) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-info fw-bold fs-15">
                                                    {{ number_format($statistics['orders_list']->sum('paid_cash'), 2) }}
                                                </span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if(isset($statistics['summary']) && $statistics['summary']['total_orders'] == 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                        <div class="avatar-xl mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                                <i class="ri-inbox-line"></i>
                            </div>
                        </div>
                        <h5 class="fs-16 mt-4">{{ __('No orders found') }}</h5>
                        <p class="text-muted mb-4">{{ __('Try adjusting your filters to see results') }}</p>
                        <button wire:click="resetFilters" class="btn btn-primary">
                            <i class="ri-refresh-line me-1"></i>{{ __('Reset Filters') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

