<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Coupon buy') }}
            @endslot
        @endcomponent
        <div class="row">
            <div class="col-lg-12">
                @include('layouts.flash-messages')
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 title="{{$platform->id}}" class="card-title">
                            {{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}

                        </h4>
                        @if(\App\Models\User::isSuperAdmin())
                            <small class="mx-2">
                                <a class="link-info"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">{{__('See or update Translation')}}</a>
                            </small>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <a href="{{route('platform_index',['locale'=>app()->getLocale()])}}">
                            <div class="flex-shrink-0">
                                @if ($platform?->logoImage)
                                    <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                         class="img-fluid d-block" style="height: 90px">
                                @else
                                    <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                         class="img-fluid d-block" style="height: 90px">
                                @endif
                            </div>
                        </a>
                    </div>
                    @if(!$buyed)
                        <div class="col-lg-12">
                            <div class="card-body">
                                <h4 class="card-title mb-2">{{__('Notes')}}</h4>
                                <p class="card-text">{{__('This simulation is available only for')}} {{$time}} {{__('minutes_')}} </p>

                            </div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <div>
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                           wire:model.live="displayedAmount"
                                                           autocomplete="off"
                                                           @if($buyed)
                                                               disabled
                                                        @endif
                                                    >
                                                    <span class="input-group-text"> {{config('app.currency')}}</span>
                                                    <button class="btn btn-outline-primary material-shadow-none"
                                                            wire:click="simulateCoupon" type="button"
                                                            @if($buyed)
                                                                disabled
                                                            @endif
                                                            id="button-simulate">{{__('Buy')}}

                                                    </button>
                                                </div>
                                            </div>
                                            @if($simulated)
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if($buyed)
                        <div class="col-lg-12 mb-2">
                            <div class="alert alert-success material-shadow" role="alert">
                                <strong> {{__('Coupoun Order secceded')}}</strong>
                            </div>
                        </div>
                    @endif
                    @if($lastValue)
                        @if(!$buyed && !$equal)
                            <div class="col-lg-12 mb-2">
                                <div title="{{__('Simulated At')}} : {{now()}}"
                                     class="alert alert-info alert-dismissible fade show material-shadow"
                                     role="alert">

                                    {{__('Depending on coupon availability, you can choose to purchase for')}}
                                    @if($amount>0 && ($lastValue+$amount<=$maxAmount))
                                        {{$amount}} {{config('app.currency')}}
                                    @endif
                                    @if($amount>0 && ($lastValue+$amount<=$maxAmount))
                                        {{__('or')}}
                                    @endif
                                    {{$lastValue+$amount}} {{config('app.currency')}}
                                    {{__('as a coupon with the exact requested value is not available')}}
                                    <button type="button" class="btn-close"
                                            data-bs-dismiss="alert"
                                            aria-label="Close">
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button button
                                        class="btn btn-outline-warning material-shadow-none float-end"
                                        wire:click="CancelPurchase()">{{__('Cancel the purchase')}} </button>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="row mt-2">
                    @if(!$buyed && $preSumulationResult && $amount>0   && ($lastValue+$amount<=$maxAmount))
                        <div class="col-lg-6">
                            <div class="card card-light">
                                <div class="card-header">
                                    <button
                                        class="btn btn-success material-shadow-none"
                                        wire:click="ConfirmPurchase(1)" type="button"
                                        id="button-buy">{{__('Confirm the purchase')}} {{$amount}} {{config('app.currency')}}
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table-card m-1">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('Pin')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                <th scope="col">{{__('value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($preSumulationResult['coupons'] as $key=> $coupon)
                                                <tr>
                                                    <td>
                                                        <span class="fw-medium link-primary">#{{$key}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info-subtle text-info fs-14 my-1 fw-normal"
                                                            @if(\App\Models\User::isSuperAdmin())
                                                                title="{{$coupon->reserved_until}} - {{__(\Core\Enum\CouponStatusEnum::tryFrom($coupon->status)->name)}}"
                                                            @endif
                                                        >
                                                        @if(!is_array($coupon))
                                                                {{$coupon->sn}}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info-subtle text-info fs-14 my-1 fw-normal">
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
                                                        <h5 class="fs-14 my-1 fw-normal"><span
                                                                class="badge bg-danger-subtle text-danger">{{__(\Core\Enum\CouponStatusEnum::tryFrom($coupon->status)->name)}}</span>
                                                        </h5>
                                                        @if($coupon->status==\Core\Enum\CouponStatusEnum::reserved->value)
                                                            <span class="text-muted">{{$coupon->reserved_until}}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-16 my-1">
                                                                                        @if(!is_array($coupon))
                                                                <strong>       {{$coupon->value}}  {{config('app.currency')}}</strong>
                                                            @endif
                                            </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                            <tr>
                                                <td colspan="4">
                                                    <strong>{{__('Total')}}</strong>
                                                </td>
                                                <td>
                                                <span
                                                    class="badge bg-success-subtle fs-14 text-success">{{$amount}} {{config('app.currency')}}</span>
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
                        <div class="col-lg-6">
                            <div class="card card-light">
                                <div class="card-header">
                                    <button
                                        class="btn btn-success material-shadow-none"
                                        wire:click="ConfirmPurchase(2)">{{__('Confirm the purchase')}} {{$lastValue+$amount}} {{config('app.currency')}}</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table-card m-1">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('Pin')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                <th scope="col">{{__('value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($result['coupons'] as $key=> $coupon)
                                                <tr>
                                                    <td>
                                                        <span class="fw-medium link-primary">#{{$key}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="text-muted fs-14 my-1 fw-normal"
                                                            @if(\App\Models\User::isSuperAdmin())
                                                                title="{{$coupon->reserved_until}} _ {{__(\Core\Enum\CouponStatusEnum::tryFrom($coupon->status)->name)}}"
                                                            @endif
                                                        >
                                                        @if(!is_array($coupon))
                                                                {{$coupon->sn}}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-success-subtle text-info fs-14 my-1 fw-normal">
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
                                                        <h5 class="fs-14 my-1 fw-normal"><span
                                                                class="badge bg-info-subtle text-info">{{__(\Core\Enum\CouponStatusEnum::tryFrom($coupon->status)->name)}}</span>
                                                        </h5>
                                                        @if($coupon->status==\Core\Enum\CouponStatusEnum::reserved->value)
                                                            <span class="text-muted">{{$coupon->reserved_until}}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                                <span class="text-muted fs-16 my-1">
                                                                                                @if(!is_array($coupon))
                                                                        <strong>       {{$coupon->value}}  {{config('app.currency')}}</strong>
                                                                    @endif
                                                    </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            @if(!$equal)
                                                <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3">
                                                        <strong>{{__('Total')}}</strong>
                                                    </td>
                                                    <td>
                    <span
                        class="badge bg-success-subtle fs-14 text-success"> {{$lastValue+$amount}} {{config('app.currency')}}</span>
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
                            <div class="card card-light">
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('Pin')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                <th scope="col">{{__('value')}}</th>
                                                @if($buyed)
                                                    <th scope="col">{{__('Action')}}</th>
                                                @endif
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
                                                        class="text-muted fs-14 my-1 fw-normal"
                                                        @if(\App\Models\User::isSuperAdmin())
                                                            title="{{$coupon->reserved_until}} _ {{__(\Core\Enum\CouponStatusEnum::tryFrom($coupon->status)->name)}}"
                                                        @endif
                                                    >
                                                    @if(!is_array($coupon))
                                                            {{$coupon->sn}}
                                                        @endif
                                                    </span>
                                                    </td>
                                                    <td>
                                                    <span
                                                        class="badge bg-success-subtle text-success fs-14 my-1 fw-normal">
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
                                                        <h5 class="fs-14 my-1 fw-normal"><span
                                                                class="badge bg-info-subtle text-info">{{__(\Core\Enum\CouponStatusEnum::tryFrom($coupon->status)->name)}}</span>
                                                        </h5>
                                                    </td>
                                                    <td>
            <span class="text-muted fs-16 my-1">
                                            @if(!is_array($coupon))
                    <strong>       {{$coupon->value}}  {{config('app.currency')}}</strong>
                @endif
</span>
                                                    </td>
                                                    @if($buyed)
                                                        <td>
                                                            <button
                                                                class="btn btn-outline-primary waves-effect waves-light"
                                                                @if(!$coupon->consumed)
                                                                    onclick="copyClipboard('{{$coupon->pin}}')"
                                                                @endif

                                                                @if($coupon->consumed)
                                                                    disabled
                                                                @endif
                                                                type="submit">{{__('Copier')}}</button>
                                                            @if(!$coupon->consumed)
                                                                <button
                                                                    class="btn btn-outline-primary waves-effect waves-light"
                                                                    wire:click="consumeCoupon({{$coupon->id}})"
                                                                    type="submit">{{__('Consume')}}
                                                                </button>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-light mt-2">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>  {{__('Order Total')}}</strong> <span
                                                class="badge bg-dark">{{$order->deal_amount_before_discount}}  {{config('app.currency')}}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>   {{__('Discount')}}</strong><span
                                                class="badge bg-dark">{{$order->total_final_discount}}  {{config('app.currency')}}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>   {{__('Amount after discount')}}</strong> <span
                                                class="badge bg-primary">  {{$order->amount_after_discount}}    {{config('app.currency')}}</span>
                                        </li>
                                        <li class="list-group-item d-flex logoTopBFSLabel justify-content-between align-items-center">
                                            <strong>   {{__('Paid with BFSs')}}</strong>
                                            <h5>   {{$order->amount_after_discount-$order->paid_cash}}  {{config('app.currency')}}</h5>
                                        </li>
                                        <li class="list-group-item d-flex logoTopCashLabel  justify-content-between align-items-center">
                                            <strong> {{__('Paid with Cash')}} </strong>
                                            <h5>  {{$order->paid_cash}}  {{config('app.currency')}}</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @if($buyed)
                                <div class="card-body">
                                    <a href="{{$linkOrder}}"
                                       class="link-secondary float-end">{{__('Check the order')}}</a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col-lg-12">
                        @if($simulated && $lastValue+$amount==0)
                            <div class="alert alert-warning material-shadow" role="alert">
                                {{__('No available coupons combination')}}
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
