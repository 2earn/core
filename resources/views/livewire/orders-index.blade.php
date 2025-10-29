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
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-header border-info">
                <div class="row">
                    <div class="float-end col-sm-12 col-md-6 col-lg-6">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control float-end"
                                       placeholder="{{__('Search Orders')}}">
                            </div>
                        </form>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-12 col-md-3 d-none col-lg-6">
                            <a class="btn btn-soft-info material-shadow-none mt-1 float-end"
                               wire:click="simulateOrderCreation">
                                {{__('Simulate Order creation')}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body row">
                @forelse($orders as $order)
                    <div class="col-sm-12 col-lg-12">
                        @include('livewire.order-item', ['order' => $order])
                    </div>
                @empty
                    <p class="text-muted">{{__('No orders')}}</p>
                @endforelse
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
