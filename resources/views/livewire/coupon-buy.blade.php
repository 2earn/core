<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Coupon buy e') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
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
                                                <input type="number" class="form-control"
                                                       wire:model="amount" aria-label="Recipient's username"
                                                       aria-describedby="button-addon2">
                                                <button class="btn btn-outline-success material-shadow-none"
                                                        wire:click="simulateCoupon" type="button"
                                                        id="button-simulate">{{__('Simulate')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    @if(!empty($coupons))

                        <div class="card">
                            <div class="card-body">
                                <table class="table table-responsive table-card">
                                    <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col">{{__('ID')}}</th>
                                        <th scope="col">{{__('Serial number')}}</th>
                                        <th scope="col">{{__('value')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coupons as $key=> $coupon)
                                        <tr>
                                            <td>
                                                <span class="fw-medium link-primary">#{{$key}}</span>
                                            </td>
                                            <td>
                                <span
                                    class="badge bg-success-subtle text-success">{{substr_replace($coupon->sn, str_repeat('*', strlen($coupon->sn) - 3), 0, -3)}}
                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success">{{$coupon->value}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-outline-success material-shadow-none float-end"
                                        wire:click="BuyCoupon" type="button"
                                        id="button-buy">{{__('Buy this simulation')}}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
