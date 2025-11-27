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
