<div class="{{getContainerType()}}">

    @section('title')
        {{ __('Review orders') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Review orders') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    @if($orders->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ri-file-list-line display-4 text-muted mb-3"></i>
                        <h5 class="mb-3">{{__('No orders found')}}</h5>
                        <p class="text-muted">{{__('There are no orders to review')}}</p>
                        <button wire:click="goToOrdersList" class="btn btn-primary">
                            <i class="ri-arrow-left-line me-1"></i>{{__('Go to Orders List')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-2">
                                    <i class="ri-shopping-cart-2-line text-primary me-2"></i>
                                    {{__('Orders Summary')}}
                                </h5>
                                <p class="text-muted mb-0">
                                    {{__('You have created')}} <strong>{{ $orders->count() }}</strong>
                                    {{ $orders->count() > 1 ? __('orders') : __('order') }} {{__('from different platforms')}}
                                </p>
                            </div>
                            <div>
                                <button wire:click="simulateAllOrders"
                                        class="btn btn-success me-2"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="simulateAllOrders">
                                            <i class="ri-play-circle-line me-1"></i>{{__('Simulate All Orders')}}
                                        </span>
                                    <span wire:loading wire:target="simulateAllOrders">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"
                                                  aria-hidden="true"></span>
                                            {{__('Processing...')}}
                                        </span>
                                </button>
                                <button wire:click="goToOrdersList" class="btn btn-outline-primary">
                                    <i class="ri-list-check me-1"></i>{{__('View All Orders')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($orders as $order)
                <div class="col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="ri-file-list-3-line text-primary me-1"></i>
                                    {{__('Order')}} #{{ $order->id }}
                                </h5>
                                @php
                                    $statusClass = match($order->status) {
                                        \Core\Enum\OrderEnum::Ready => 'bg-info-subtle text-info',
                                        \Core\Enum\OrderEnum::Paid => 'bg-success-subtle text-success',
                                        \Core\Enum\OrderEnum::Failed => 'bg-danger-subtle text-danger',
                                        \Core\Enum\OrderEnum::Simulated => 'bg-warning-subtle text-warning',
                                        \Core\Enum\OrderEnum::New => 'bg-primary-subtle text-primary',
                                        \Core\Enum\OrderEnum::Dispatched => 'bg-success-subtle text-success',
                                        default => 'bg-secondary-subtle text-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                        {{ $order->status->name }}
                                    </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Platform Info -->
                            @if($order->platform)
                                <div class="mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-store-2-line text-info fs-4 me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">{{__('Platform')}}</small>
                                            <strong>{{ $order->platform->name }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Order Items -->
                            <div class="mb-3">
                                <h6 class="mb-2">{{__('Items')}} ({{ $order->orderDetails->count() }})</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tbody>
                                        @foreach($order->orderDetails as $detail)
                                            <tr>
                                                <td class="py-2">
                                                    <div class="d-flex align-items-start">
                                                        <i class="ri-checkbox-circle-line text-success me-2 mt-1"></i>
                                                        <div>
                                                            <div class="fw-medium">{{ $detail->item->name }}</div>
                                                            @if($detail->item->deal)
                                                                <small class="text-muted">
                                                                    <i class="ri-price-tag-3-line me-1"></i>{{ $detail->item->deal->name }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end py-2">
                                                    <div class="fw-medium">x{{ $detail->qty }}</div>
                                                    <small
                                                        class="text-muted">{{config('app.currency')}} {{ number_format($detail->unit_price, 2) }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($order->total_order)
                                <!-- Order Total -->
                                <div class="border-top pt-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">{{__('Total')}}</span>
                                        <h5 class="mb-0 text-primary">
                                            {{config('app.currency')}} {{ number_format($order->total_order ?? 0, 2) }}
                                        </h5>
                                    </div>
                                </div>
                            @endif
                            <!-- Order Note -->
                            @if($order->note)
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="ri-sticky-note-line me-1"></i>{{ $order->note }}
                                    </small>
                                </div>
                            @endif

                            <!-- Action Button / Links -->
                            @if($order->status == \Core\Enum\OrderEnum::Ready)
                                <button wire:click="simulateOrder({{ $order->id }})"
                                        class="btn btn-success w-100"
                                        wire:loading.attr="disabled"
                                        wire:target="simulateOrder({{ $order->id }})">
                                        <span wire:loading.remove wire:target="simulateOrder({{ $order->id }})">
                                            <i class="ri-play-circle-line me-1"></i>{{__('Simulate This Order')}}
                                        </span>
                                    <span wire:loading wire:target="simulateOrder({{ $order->id }})">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"
                                                  aria-hidden="true"></span>
                                            {{__('Processing...')}}
                                        </span>
                                </button>
                            @elseif($order->status == \Core\Enum\OrderEnum::Simulated)
                                <a href="{{ route('orders_simulation', ['locale' => app()->getLocale(), 'id' => $order->id]) }}"
                                   class="btn btn-primary w-100">
                                    <i class="ri-eye-line me-1"></i>{{__('View Simulation Results')}}
                                </a>
                            @elseif($order->status == \Core\Enum\OrderEnum::Failed)
                                <div class="alert alert-danger mb-0">
                                    <i class="ri-close-circle-line me-1"></i>
                                    {{ __('Order simulation failed') }}
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="ri-information-line me-1"></i>
                                    {{ __('Order status') }}: {{ $order->status->name }}
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="ri-time-line me-1"></i>
                                {{__('Created')}}: {{ $order->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

