<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Order simulation') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Order simulation') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <h3>{{__('Order simulation')}}</h3>
        </div>
        @if(!is_null($order->simulation_result)&&!$order->simulation_result)
            <div class="card-body">
                <div class="alert alert-warning material-shadow" t role="alert">
                    <strong> {{__('Simulation Error')}} | {{__("At")}}
                        :</strong> {{__($order->simulation_datetime)}}
                    <hr>
                    <strong>{{__("Details")}}:</strong> {{__($order->simulation_details)}}
                    <br>
                </div>
            </div>
        @endif
        <div class="card-body">
         @include('livewire.order-item', ['order' => $order])
        </div>
        @if($order->status->value ==\Core\Enum\OrderEnum::New->value)
            <div class="card-body">
                @if($order->orderDetails->count() > 0)
                    <div class="alert alert-info material-shadow" role="alert">
                        {{__('Order not ready for simulation')}}
                    </div>
                @endif
                @if($order->orderDetails->count() > 0)
                    <button wire:click="makeOrderReady()"
                            class="btn btn-soft-success mx-2 float-end">{{__('Make order not ready for simulation')}}</button>
                @else
                    <div class="alert alert-info material-shadow" role="alert">
                        {{__('Empty order')}}
                    </div>
                @endif

            </div>

        @else
            @if(!$this->validated)
                <div class="card-footer">
                    <button wire:click="validateOrder()"
                            class="btn btn-soft-success mx-2 float-end">{{__('Complete Order')}}</button>

                    <button wire:click="cancelOrder()"
                            class="btn btn-soft-warning mx-2 float-end">{{__('Cancel Order')}}</button>
                </div>
            @endif
        @endif
        @if(!$this->validated && !$this->simulation)
            <div class="card-footer">
                <a href="{{route('orders_simulation', ['locale' => app()->getLocale(), 'id' => $order->id])}}"
                   class="btn btn-soft-primary mx-2 float-end">{{__('Re-run Simulation ')}}</a>
            </div>
        @endif
    </div>
</div>
