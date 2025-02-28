<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Coupon buy e') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{__('Simulate amount of coupons')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="live-preview">
                                <div>
                                    <div class="row g-3">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="button-addon2">
                                                <button class="btn btn-outline-success material-shadow-none"
                                                        type="button" id="button-addon2">{{__('Simulate')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
