<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Coupon history') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="card">
            <div class="card-header border-0">
                <div class="row g-3 align-items-center">
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label mb-1 fw-semibold">{{ __('Item per page') }}</label>
                        <select wire:model.live="pageCount" class="form-select"
                                aria-label="Default select example">
                            <option @if($pageCount=="10") selected @endif value="10">10</option>
                            <option @if($pageCount=="25") selected @endif value="25">25</option>
                            <option @if($pageCount=="50") selected @endif value="50">50</option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-9">
                        <label class="form-label mb-1 fw-semibold">{{ __('Search') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ri-search-line"></i></span>
                            <input wire:model.live="search" type="search"
                                   class="form-control"
                                   placeholder="{{ __('Search by pin, sn, value or platform') }}..." aria-label="Search"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div wire:loading.delay class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{__('Loading...')}}</span>
                    </div>
                </div>

                <div wire:loading.remove.delay>
                    @forelse($coupons as $coupon)
                        <div class="card border shadow-none mb-3">
                            <div class="card-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="text-primary fs-14 mb-2">
                                                <i class="ri-barcode-line align-middle me-1"></i>
                                                {{__('Coupon Details')}}
                                            </h6>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12">{{__('Pin')}}:</span>
                                                <span class="fw-medium ms-2 fs-13">
                                                    @if(!$coupon->consumed)
                                                        {{substr_replace($coupon->pin, str_repeat('*', strlen($coupon->pin)), 0 )}}
                                                    @else
                                                        {{$coupon->pin}}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12">{{__('sn')}}:</span>
                                                <span class="fw-medium ms-2 fs-13">{{$coupon->sn}}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted fs-12">{{__('Platform')}}:</span>
                                                <span class="fw-medium ms-2 fs-13">
                                                    @if($coupon->platform)
                                                        {{$coupon->platform->id}} - {{$coupon->platform->name}}
                                                    @else
                                                        **
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="text-primary fs-14 mb-2">
                                                <i class="ri-information-line align-middle me-1"></i>
                                                {{__('Status & Value')}}
                                            </h6>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12">{{__('Value')}}:</span>
                                                <span class="badge bg-success ms-2 fs-13">{{$coupon->value}} {{config('app.currency')}}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted fs-12">{{__('Consumed')}}:</span>
                                                @if($coupon->consumed)
                                                    <span class="badge bg-success ms-2 fs-11">{{__('yes')}}</span>
                                                @else
                                                    <span class="badge bg-danger ms-2 fs-11">{{__('no')}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($coupon->attachment_date || $coupon->purchase_date || $coupon->consumption_date)
                                    <div class="p-3 bg-light rounded mb-3">
                                        <h6 class="text-primary fs-14 mb-3">
                                            <i class="ri-calendar-line align-middle me-1"></i>
                                            {{__('Dates')}}
                                        </h6>
                                        <div class="row g-2">
                                            @if($coupon->attachment_date)
                                                <div class="col-md-4">
                                                    <div class="p-2 border rounded">
                                                        <p class="text-muted fs-11 mb-1">
                                                            <i class="ri-attachment-line align-middle me-1"></i>
                                                            {{__('Attachement')}}
                                                        </p>
                                                        <p class="text-dark fs-12 mb-0 fw-medium">
                                                            {{$coupon->attachment_date}}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($coupon->purchase_date)
                                                <div class="col-md-4">
                                                    <div class="p-2 border rounded">
                                                        <p class="text-muted fs-11 mb-1">
                                                            <i class="ri-shopping-cart-line align-middle me-1"></i>
                                                            {{__('Purchase')}}
                                                        </p>
                                                        <p class="text-dark fs-12 mb-0 fw-medium">
                                                            {{$coupon->purchase_date}}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($coupon->consumption_date)
                                                <div class="col-md-4">
                                                    <div class="p-2 border rounded">
                                                        <p class="text-muted fs-11 mb-1">
                                                            <i class="ri-check-line align-middle me-1"></i>
                                                            {{__('Consumption')}}
                                                        </p>
                                                        <p class="text-dark fs-12 mb-0 fw-medium">
                                                            {{$coupon->consumption_date}}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex gap-2 flex-wrap">
                                    @if(!$coupon->consumed)
                                        <button onclick="confirmConsumeCoupon({{$coupon->id}}, '{{$coupon->pin}}')"
                                                class="btn btn-sm btn-warning flex-fill">
                                            <i class="ri-check-double-line align-bottom me-1"></i>
                                            {{__('Consume')}}
                                        </button>
                                        <button onclick="confirmCopyCoupon('{{$coupon->sn}}')"
                                                class="btn btn-sm btn-soft-warning flex-fill">
                                            <i class="ri-file-copy-line align-bottom me-1"></i>
                                            {{__('Copy')}}
                                        </button>
                                    @else
                                        <div class="flex-fill text-center p-2">
                                            <span class="text-muted fs-13">
                                                <i class="ri-check-line align-middle me-1"></i>
                                                {{__('Consumed')}}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="ri-information-line fs-18 align-middle me-2"></i>
                                {{__('No records')}}
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card-body pt-0">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

    <script>
        function confirmConsumeCoupon(couponId, couponPin) {
            Swal.fire({
                title: '{{__('Are you sure to mark this Coupon as consumed')}}? <h5 class="float-end">' + couponPin + ' </h5>',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "{{__('Consume')}}",
                denyButtonText: `Rollback`
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch("markAsConsumed", [couponId]);
                }
            });
        }

        function confirmCopyCoupon(couponSn) {
            Swal.fire({
                title: '{{__('Copy this Coupon')}}' + '<h5 class="float-end">' + couponSn + ' </h5>',
                html: '{{__('Give me your password')}}',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT',180000) }}',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                confirmButtonText: '{{trans('ok')}}',
                input: 'text',
                inputAttributes: {autocapitalize: 'off'},
            }).then((resultat) => {
                if (resultat.isConfirmed) {
                    window.Livewire.dispatch('verifPassword', [resultat.value, couponSn]);
                }
            }).catch((error) => {
                console.error('SweetAlert Error:', error);
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            window.addEventListener('showPin', event => {
                Swal.fire({
                    title: event.detail[0].title,
                    html: event.detail[0].html,
                    icon: 'success',
                    allowOutsideClick: false,
                    confirmButtonText: "{{__('ok')}}",
                    backdrop: `
                        rgba(0,0,0,0.7)
                        left top
                        no-repeat
                      `
                }).catch((error) => {
                    console.error('SweetAlert Error:', error);
                });
            });

            window.addEventListener('cancelPin', event => {
                Swal.fire({
                    title: event.detail[0].title,
                    text: event.detail[0].text,
                    icon: 'error',
                    allowOutsideClick: false,
                    confirmButtonText: "{{__('Back to coupons history')}}",
                    backdrop: `
                        rgba(0,0,0,0.7)
                        left top
                        no-repeat
                      `
                }).catch((error) => {
                    console.error('SweetAlert Error:', error);
                });
            });
        });
    </script>
</div>
