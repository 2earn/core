<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Coupon injector runner') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Coupon injector runner') }}
        @endslot
    @endcomponent  <div class="row">
        <div class="col-12 mb-3">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                                    <i class="ri-coupon-3-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fw-semibold text-dark">{{__('Coupon runner')}}</h5>
                            <p class="text-muted small mb-0">{{__('Enter your pin code to redeem coupon')}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="pinCode" class="form-label fw-semibold">
                                <i class="ri-lock-password-line me-1 text-primary"></i>{{__('Pin code')}}
                            </label>
                            <div class="input-group input-group">
                                <span class="input-group-text bg-primary-subtle border-primary">
                                    <i class="ri-key-2-line text-primary"></i>
                                </span>
                                <input type="text"
                                       wire:model.live="pin"
                                       id="pinCode"
                                       class="form-control form-control border-primary"
                                       placeholder="{{__('Enter your pin code')}}">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button"
                                    wire:click="runCoupon()"
                                    class="btn btn-primary btn w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="ri-play-circle-line fs-18"></i>
                                <span>{{__('Run coupon')}}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            @livewire('coupon-injector-user-index')
        </div>
    </div>
</div>
