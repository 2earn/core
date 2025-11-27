@php
    $currency = config('app.currency');
    $percentage = config('app.percentage');
@endphp
<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Items details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Items details') }}
        @endslot
    @endcomponent
    <div class="row">
            @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card shadow-sm border-0 mb-4">
            <div class="card-header py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div
                        class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 48px; height: 48px;">
                        <i class="ri-shopping-bag-line text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-1 fw-bold text-dark">
                            {{$item->name}}
                        </h4>
                        <small class="text-muted">
                            <i class="ri-barcode-line me-1"></i>
                            <span class="badge bg-primary-subtle text-primary">ID: {{$item->id}}</span>
                        </small>
                    </div>
                </div>
                <div>
                    <div class="text-end">
                        <small class="text-muted d-block mb-1">{{__('Total ordered quantity')}}</small>
                        <span class="badge bg-info-subtle text-info fs-5 px-3 py-2">
                                <i class="ri-shopping-cart-line me-1"></i>{{$sumOfItemIds}}
                            </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-4 text-center mb-3 mb-md-0">
                        <div class="position-relative">
                            <div class="border border-2 border-primary border-opacity-25 rounded-3 p-3 bg-light">
                                @if($item->photo_link)
                                    <img src="{{$item->photo_link}}"
                                         class="img-fluid rounded-3 shadow w-100"
                                         alt="{{$item->name}}"
                                         style="max-width: 300px; object-fit: cover;">
                                @elseif($item->thumbnailsImage)
                                    <img src="{{ asset('uploads/' . $item->thumbnailsImage->url) }}"
                                         alt="{{$item->name}}"
                                         class="img-fluid rounded-3 shadow w-100"
                                         style="max-width: 300px; object-fit: cover;">
                                @else
                                    <img src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                         class="img-fluid rounded-3 shadow w-100"
                                         alt="{{$item->name}}"
                                         style="max-width: 300px; object-fit: cover;">
                                @endif
                            </div>
                        </div>
                        <div class="mt-3">
                                <span class="badge bg-secondary-subtle text-secondary fs-6 px-4 py-2">
                                    <i class="ri-hashtag me-1"></i>{{$item->ref}}
                                </span>
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-8">
                        <div class="list-group list-group-flush">
                            <div
                                class="list-group-item rounded-top">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-price-tag-3-line text-success fs-5"></i>
                                    <h6 class="mb-0 fw-semibold text-dark">{{__('Price')}}</h6>
                                </div>
                                <span class="badge fs-5 px-4 py-2 shadow-sm">
                                        <i class="ri-money-dollar-circle-line me-1"></i>{{$item->price}} {{$currency}}
                                    </span>
                            </div>

                            <div
                                class="list-group-item px-3 d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-discount-percent-line text-warning fs-5"></i>
                                    <h6 class="mb-0 fw-semibold text-dark">{{__('Discount')}}</h6>
                                </div>
                                <span class="badge bg-warning text-dark fs-5 px-4 py-2 shadow-sm">
                                        <i class="ri-percent-line me-1"></i>{{$item->discount}} {{$percentage}}
                                    </span>
                            </div>

                            <div
                                class="list-group-item px-3 d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-gift-line text-info fs-5"></i>
                                    <h6 class="mb-0 fw-semibold text-dark">{{__('Discount 2earn')}}</h6>
                                </div>
                                <span class="badge bg-info fs-5 px-4 py-2 shadow-sm">
                                        <i class="ri-percent-line me-1"></i>{{$item->discount_2earn}} {{$percentage}}
                                    </span>
                            </div>

                            @if ($item->stock)
                                <div
                                    class="list-group-item px-3 d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-stack-line text-dark fs-5"></i>
                                        <h6 class="mb-0 fw-semibold text-dark">{{__('Stock')}}</h6>
                                    </div>
                                    <span class="badge bg-dark fs-5 px-4 py-2 shadow-sm">
                                            <i class="ri-archive-line me-1"></i>{{$item->stock}}
                                        </span>
                                </div>
                            @endif

                            <div
                                class="list-group-item px-3 d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-fire-line text-danger fs-5"></i>
                                    <h6 class="mb-0 fw-semibold text-dark">{{__('Deal')}}</h6>
                                </div>
                                <div class="text-end">
                                    @if ($item->deal()->exists())
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$item->deal->id])}}"
                                               class="text-decoration-none">
                                                    <span
                                                        class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 shadow-sm">
                                                        <i class="ri-fire-fill me-1"></i>{{$item->deal->id}} - {{$item->deal->name}}
                                                    </span>
                                            </a>
                                        @else
                                            <span
                                                class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 shadow-sm">
                                                    <i class="ri-fire-fill me-1"></i>{{$item->deal->id}} - {{$item->deal->name}}
                                                </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary fs-6 px-3 py-2">
                                                <i class="ri-close-circle-line me-1"></i>{{__('No deal')}}
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="list-group-item px-3 d-flex justify-content-between align-items-center py-3 rounded-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-store-2-line text-primary fs-5"></i>
                                    <h6 class="mb-0 fw-semibold text-dark">{{__('Platform')}}</h6>
                                </div>
                                <div class="text-end">
                                    @if ($item->platform()->exists())
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a href="{{route('platform_show',['locale'=>app()->getLocale(),'id'=>$item->platform->id])}}"
                                               class="text-decoration-none">
                                                    <span
                                                        class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 shadow-sm">
                                                        <i class="ri-store-3-fill me-1"></i>{{$item->platform->id}} - {{$item->platform->name}}
                                                    </span>
                                            </a>
                                        @else
                                            <span
                                                class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 shadow-sm">
                                                    <i class="ri-store-3-fill me-1"></i>{{$item->platform->id}} - {{$item->platform->name}}
                                                </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary fs-6 px-3 py-2">
                                                <i class="ri-close-circle-line me-1"></i>{{__('No Platform')}}
                                            </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
