<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Orders') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Orders') }}
        @endslot
    @endcomponent

    @if($pendingOrdersCount > 0)
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">
                                    <i class="ri-file-list-3-line text-info me-2"></i>{{__('Pending Orders Review')}}
                                </h5>
                                <p class="text-muted mb-0">
                                    {{__('You have')}} <strong>{{ $pendingOrdersCount }}</strong>
                                    {{ $pendingOrdersCount > 1 ? __('orders') : __('order') }} {{__('ready for review and simulation')}}
                                </p>
                            </div>
                            <button wire:click="goToOrdersReview" class="btn btn-info">
                                <i class="ri-eye-line me-1"></i>{{__('Review Orders')}}
                                <span class="badge bg-white text-info ms-2">{{ $pendingOrdersCount }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
            @include('layouts.flash-messages')
    </div>
    @if(\App\Models\User::isSuperAdmin())
        <div class="row d-none">
            <div class="col-12 card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12 col-md-3 col-lg-6">
                                <a class="btn btn-soft-info material-shadow-none mt-1 float-end"
                                   wire:click="simulateOrderCreation">
                                    {{__('Simulate Order creation')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        @forelse($orders as $order)
            <div class="col-12 card">
                @include('livewire.order-item', ['order' => $order])
            </div>
        @empty
            <p class="text-muted">{{__('No orders')}}</p>
        @endforelse
        {{ $orders->links() }}
    </div>
</div>
