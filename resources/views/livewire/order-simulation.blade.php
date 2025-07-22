<div class="container-fluid">
    @section('title')
        {{ __('Order simulation') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Order simulation') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <h3>{{__('Order simulation')}}</h3>
        </div>
        <div class="card-body">
            @include('livewire.order-item', ['order' => $order])
        </div>
        @if(!$this->validated)
            <div class="card-footer">
                <button wire:click="validateOrder()"
                        class="btn btn-soft-success mx-2 float-end">{{__('Validate Order')}}</button>
            </div>
        @endif
    </div>
</div>
