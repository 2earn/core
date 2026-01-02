<div class="container">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Coupon buy') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mb-3">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center">
                            <h4 title="{{$platform->id}}" class="card-title mb-0 me-3">
                                {{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
                            </h4>
                            @if(\App\Models\User::isSuperAdmin())
                                <span class="badge bg-primary-subtle text-primary">
                                    <a class="link-info text-decoration-none"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">
                                        <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4 text-end">
                        <a href="{{route('platform_index',['locale'=>app()->getLocale()])}}" class="d-inline-block">
                            @if ($platform?->logoImage)
                                <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                     alt="{{$platform->name}}"
                                     class="img-thumbnail" style="height: 90px; object-fit: contain;">
                            @else
                                <img src="{{Vite::asset(\App\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                     alt="{{$platform->name}}"
                                     class="img-thumbnail" style="height: 90px; object-fit: contain;">
                            @endif
                        </a>
                    </div>
                    @if(!$buyed)
                        <div class="col-lg-12">
                            <div class="alert alert-warning alert-border-left alert-dismissible fade show mb-3"
                                 role="alert">
                                <i class="ri-time-line me-2 align-middle fs-16"></i>
                                <strong>{{__('Notes')}}: </strong>
                                {{__('This simulation is available only for')}} <span
                                    class="badge bg-warning text-dark">{{$time}} {{__('minutes_')}}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="card border shadow-sm">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <label class="form-label fw-semibold mb-2">
                                            <i class="ri-money-dollar-circle-line me-1"></i>{{__('Enter Amount')}}
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" class="form-control"
                                                   placeholder="{{__('Enter amount')}}"
                                                   wire:model.live="displayedAmount"
                                                   autocomplete="off"
                                                   @if($buyed) disabled @endif>
                                            <span class="input-group-text fw-semibold">{{config('app.currency')}}</span>
                                            <button class="btn btn-primary px-4"
                                                    wire:click="simulateCoupon"
                                                    type="button"
                                                    @if($buyed) disabled @endif
                                                    id="button-simulate">
                                                <i class="ri-shopping-cart-line me-1"></i>{{__('Buy')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if($buyed)
                        <div class="col-lg-12 mb-3">
                            <div class="alert alert-success alert-border-left border-success fade show" role="alert">
                                <i class="ri-checkbox-circle-line me-2 align-middle fs-16"></i>
                                <strong>{{__('Coupoun Order secceded')}}</strong>
                            </div>
                        </div>
                    @endif
                    @if($lastValue)
                        @if(!$buyed && !$equal)
                            <div class="col-lg-12 mb-3">
                                <div title="{{__('Simulated At')}} : {{now()}}"
                                     class="alert alert-info alert-border-left alert-dismissible fade show"
                                     role="alert">
                                    <i class="ri-information-line me-2 align-middle fs-16"></i>
                                    <div class="d-inline-block">
                                        <strong>{{__('Alternative Purchase Options')}}</strong>
                                        <p class="mb-0 mt-1">
                                            {{__('Depending on coupon availability, you can choose to purchase for')}}
                                            @if($amount>0 && ($lastValue+$amount<=$maxAmount))
                                                <span
                                                    class="badge bg-info-subtle text-info">{{$amount}} {{config('app.currency')}}</span>
                                            @endif
                                            @if($amount>0 && ($lastValue+$amount<=$maxAmount))
                                                {{__('or')}}
                                            @endif
                                            <span
                                                class="badge bg-info-subtle text-info">{{$lastValue+$amount}} {{config('app.currency')}}</span>
                                            {{__('as a coupon with the exact requested value is not available')}}
                                        </p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3 text-end">
                                <button class="btn btn-warning"
                                        wire:click="CancelPurchase()">
                                    <i class="ri-close-line me-1"></i>{{__('Cancel the purchase')}}
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="row mt-3">
                    @if(!$buyed && $preSumulationResult && $amount>0   && ($lastValue+$amount<=$maxAmount))
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-success-subtle">
                                    <h5 class="card-title mb-0 text-success">
                                        <i class="ri-check-line me-1"></i>{{__('Option 1')}}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <button
                                        class="btn btn-success w-100 mb-3"
                                        wire:click="ConfirmPurchase(1)" type="button"
                                        id="button-buy">
                                        <i class="ri-check-double-line me-1"></i>{{__('Confirm the purchase')}} {{$amount}} {{config('app.currency')}}
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-nowrap mb-0">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('Pin')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                <th scope="col" class="text-end">{{__('value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($preSumulationResult['coupons'] as $key=> $coupon)
                                                <tr>
                                                    <td>
                                                        <span class="fw-semibold text-primary">#{{$key}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info-subtle text-info"
                                                            @if(\App\Models\User::isSuperAdmin())
                                                                title="{{$coupon->reserved_until}} - {{__(\App\Enums\CouponStatusEnum::tryFrom($coupon->status)->name)}}"
                                                            @endif
                                                        >
                                                        @if(!is_array($coupon))
                                                                {{$coupon->sn}}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary text-white">
                                                        @if(!is_array($coupon))
                                                                @if(!$buyed)
                                                                    {{substr_replace($coupon->pin, str_repeat('*', strlen($coupon->pin)), 0 )}}
                                                                @else
                                                                    {{$coupon->pin}}
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger-subtle text-danger">
                                                            {{__(\App\Enums\CouponStatusEnum::tryFrom($coupon->status)->name)}}
                                                        </span>
                                                        @if($coupon->status==\App\Enums\CouponStatusEnum::reserved->value)
                                                            <small
                                                                class="text-muted d-block mt-1">{{$coupon->reserved_until}}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if(!is_array($coupon))
                                                            <strong
                                                                class="text-dark">{{$coupon->value}} {{config('app.currency')}}</strong>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                            <tr>
                                                <td colspan="4" class="fw-semibold">{{__('Total')}}</td>
                                                <td class="text-end">
                                                    <span
                                                        class="badge bg-success fs-14">{{$amount}} {{config('app.currency')}}</span>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                    @if(!$buyed && $result  && $lastValue+$amount>0 && !$equal)
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-warning-subtle">
                                    <h5 class="card-title mb-0 text-warning">
                                        <i class="ri-alert-line me-1"></i>{{__('Option 2')}}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <button
                                        class="btn btn-success w-100 mb-3"
                                        wire:click="ConfirmPurchase(2)">
                                        <i class="ri-check-double-line me-1"></i>{{__('Confirm the purchase')}} {{$lastValue+$amount}} {{config('app.currency')}}
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-nowrap mb-0">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('Pin')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                <th scope="col" class="text-end">{{__('value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($result['coupons'] as $key=> $coupon)
                                                <tr>
                                                    <td>
                                                        <span class="fw-semibold text-primary">#{{$key}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info-subtle text-info"
                                                            @if(\App\Models\User::isSuperAdmin())
                                                                title="{{$coupon->reserved_until}} _ {{__(\App\Enums\CouponStatusEnum::tryFrom($coupon->status)->name)}}"
                                                            @endif
                                                        >
                                                        @if(!is_array($coupon))
                                                                {{$coupon->sn}}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary text-white">
                                                        @if(!is_array($coupon))
                                                                @if(!$buyed)
                                                                    {{substr_replace($coupon->pin, str_repeat('*', strlen($coupon->pin)), 0 )}}
                                                                @else
                                                                    {{$coupon->pin}}
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">
                                                            {{__(\App\Enums\CouponStatusEnum::tryFrom($coupon->status)->name)}}
                                                        </span>
                                                        @if($coupon->status==\App\Enums\CouponStatusEnum::reserved->value)
                                                            <small
                                                                class="text-muted d-block mt-1">{{$coupon->reserved_until}}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if(!is_array($coupon))
                                                            <strong
                                                                class="text-dark">{{$coupon->value}} {{config('app.currency')}}</strong>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            @if(!$equal)
                                                <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="4" class="fw-semibold">{{__('Total')}}</td>
                                                    <td class="text-end">
                                                        <span
                                                            class="badge bg-success fs-14">{{$lastValue+$amount}} {{config('app.currency')}}</span>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            @endif

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                        @if($buyed && !empty($coupons))
                            <div class="card shadow-sm">
                                <div class="card-header bg-success-subtle">
                                    <h5 class="card-title mb-0 text-success">
                                        <i class="ri-shopping-bag-line me-1"></i>{{__('Purchased Coupons')}}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-nowrap mb-0">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('Pin')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                <th scope="col" class="text-end">{{__('value')}}</th>
                                                @if($buyed)
                                                    <th scope="col" class="text-end">{{__('Action')}}</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($coupons as $key=> $coupon)
                                                <tr>
                                                    <td>
                                                        <span class="fw-semibold text-primary">#{{$key}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info-subtle text-info"
                                                            @if(\App\Models\User::isSuperAdmin())
                                                                title="{{$coupon->reserved_until}} _ {{__(\App\Enums\CouponStatusEnum::tryFrom($coupon->status)->name)}}"
                                                            @endif
                                                        >
                                                        @if(!is_array($coupon))
                                                                {{$coupon->sn}}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success">
                                                        @if(!is_array($coupon))
                                                                @if(!$buyed)
                                                                    {{substr_replace($coupon->pin, str_repeat('*', strlen($coupon->pin)), 0 )}}
                                                                @else
                                                                    {{$coupon->pin}}
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">
                                                            {{__(\App\Enums\CouponStatusEnum::tryFrom($coupon->status)->name)}}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        @if(!is_array($coupon))
                                                            <strong
                                                                class="text-dark">{{$coupon->value}} {{config('app.currency')}}</strong>
                                                        @endif
                                                    </td>
                                                    @if($buyed)
                                                        <td class="text-end">
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <button
                                                                    class="btn btn-outline-primary"
                                                                    @if(!$coupon->consumed)
                                                                        onclick="copyClipboard('{{$coupon->pin}}')"
                                                                    @endif
                                                                    @if($coupon->consumed)
                                                                        disabled
                                                                    @endif
                                                                    type="button">
                                                                    <i class="ri-file-copy-line"></i> {{__('Copier')}}
                                                                </button>
                                                                @if(!$coupon->consumed)
                                                                    <button
                                                                        class="btn btn-outline-success"
                                                                        wire:click="consumeCoupon({{$coupon->id}})"
                                                                        type="button">
                                                                        <i class="ri-check-line"></i> {{__('Consume')}}
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-sm mt-3">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="ri-file-list-3-line me-1"></i>{{__('Order Summary')}}
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>{{__('Order Total')}}</strong>
                                            <span
                                                class="badge bg-secondary fs-14">{{$order->deal_amount_before_discount}} {{config('app.currency')}}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>{{__('Discount')}}</strong>
                                            <span
                                                class="badge bg-danger fs-14">-{{$order->total_final_discount}} {{config('app.currency')}}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>{{__('Amount after discount')}}</strong>
                                            <span
                                                class="badge bg-primary fs-14">{{$order->amount_after_discount}} {{config('app.currency')}}</span>
                                        </li>
                                        <li class="list-group-item d-flex logoTopBFSLabel justify-content-between align-items-center">
                                            <strong>{{__('Paid with BFSs')}}</strong>
                                            <h5 class="mb-0 text-primary">{{$order->amount_after_discount-$order->paid_cash}} {{config('app.currency')}}</h5>
                                        </li>
                                        <li class="list-group-item d-flex logoTopCashLabel justify-content-between align-items-center bg-success-subtle">
                                            <strong class="text-success">{{__('Paid with Cash')}}</strong>
                                            <h5 class="mb-0 text-success">{{$order->paid_cash}} {{config('app.currency')}}</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @if($buyed)
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{$linkOrder}}" class="btn btn-primary w-100">
                                        <i class="ri-file-list-line me-1"></i>{{__('Check the order')}}
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col-lg-12">
                        @if($simulated && $lastValue+$amount==0)
                            <div class="alert alert-warning alert-border-left border-warning fade show" role="alert">
                                <i class="ri-error-warning-line me-2 align-middle fs-16"></i>
                                <strong>{{__('No available coupons combination')}}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function copyClipboard(text) {
            window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
        }
    </script>
</div>
