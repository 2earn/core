<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Coupon injector runner') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Coupon injector runner') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xl-12">
            @include('layouts.flash-messages')
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center border-0 d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{__('Coupon runner')}}</h4>
                </div>
                <div class="card-body p-0">
                    <div class="p-3">
                        <div class="input-group mb-0">
                            <label class="input-group-text">{{__('Pin code')}}</label>
                            <input type="text" wire:model.live="pin" class="form-control"
                                   placeholder="{{__('Pin code')}}">
                        </div>
                        <div class="pt-2">
                            <button type="button" wire:click="runCoupon()"
                                    class="btn btn-primary w-100">{{__('Run coupon')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            @livewire('coupon-injector-user-index')
        </div>
    </div>
</div>
