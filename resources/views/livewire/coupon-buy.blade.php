<div class="container-fluid">
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
                                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                                @else
                                    <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                                @endif
                            </div>
                        </a>
                    </div>
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
                                                           aria-label="Recipient's username"
                                                           aria-describedby="button-addon2"
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
                                                @if($buyed)
                                                    <p>
                                                        <a href="{{$linkOrder}}"
                                                           class="link-secondary float-end">{{__('Go to the order')}}</a>
                                                    </p>
                                                @endif
                                                @if($lastValue)
                                                    @if(!$buyed)
                                                        <div class="col-lg-12">
                                                            <div title="{{__('Simulated At')}} : {{now()}}"
                                                                 class="alert alert-info alert-dismissible fade show material-shadow"
                                                                 role="alert">

                                                                {{__('Depending on coupon availability, you can choose to purchase for')}}
                                                                {{$amount}}
                                                                @if(!$equal)
                                                                    {{__('or')}}
                                                                    {{$lastValue+$amount}}
                                                                @endif
                                                                {{__('as a coupon with the exact requested value is not available')}}
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="alert"
                                                                        aria-label="Close"></button>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <button button
                                                                    class="btn btn-outline-success material-shadow-none"
                                                                    wire:click="BuyCoupon" type="button"
                                                                    id="button-buy">{{__('Confirm the purchase')}} {{$amount}} {{config('app.currency')}}
                                                            </button>
                                                            @if(!$equal)
                                                                <button button
                                                                        class="btn btn-outline-success material-shadow-none"
                                                                        wire:click="ConfirmPurchase()">{{__('Confirm the purchase')}} {{$lastValue+$amount}} {{config('app.currency')}}</button>
                                                            @endif

                                                            <button button
                                                                    class="btn btn-outline-warning material-shadow-none float-end"
                                                                    wire:click="CancelPurchase()">{{__('Cancel the purchase')}} </button>
                                                        </div>
                                                    @endif
                                                @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        @if(!empty($coupons))
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('ID')}}</th>
                                                <th scope="col">{{__('Serial number')}}</th>
                                                <th scope="col">{{__('Pin')}}</th>
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
                                    class="badge bg-success-subtle text-success fs-14 my-1 fw-normal">
                                    @if(!is_array($coupon))
                                        {{$coupon->sn}}
                                    @endif
                                </span>
                                                    </td>
                                                    <td>
                                <span
                                    class="badge bg-success-subtle text-success fs-14 my-1 fw-normal">
                                    @if(!is_array($coupon))
                                        {{substr_replace($coupon->pin, str_repeat('*', strlen($coupon->pin)), 0 )}}
                                    @endif
                                </span>
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
                                                                class="btn btn-outline-info waves-effect waves-light"
                                                                @if($coupon->consumed)
                                                                    disabled
                                                                @endif
                                                                type="submit">{{__('Voir')}}</button>
                                                            <button
                                                                class="btn btn-outline-primary waves-effect waves-light"
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
                        @else
                            @if($simulated)
                                <div class="alert alert-warning material-shadow" role="alert">
                                    {{__('No available coupons combination')}}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

