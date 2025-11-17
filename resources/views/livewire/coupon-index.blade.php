<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Coupon') }}
    @endsection

    @component('components.breadcrumb')
        @slot('title')
            {{ __('Coupon') }}
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
                        <select wire:model.live="pageCount" class="form-select">
                            <option @if($pageCount=="10") selected @endif value="10">10</option>
                            <option @if($pageCount=="25") selected @endif value="25">25</option>
                            <option @if($pageCount=="50") selected @endif value="50">50</option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-5">
                        <label class="form-label mb-1 fw-semibold">{{ __('Search') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ri-search-line"></i></span>
                            <input wire:model.live="search" type="search"
                                   class="form-control"
                                   placeholder="{{ __('Search by pin, sn, value or platform') }}..." />
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-4 text-end">
                        <label class="form-label mb-1 d-block">&nbsp;</label>
                        <button wire:click="deleteSelected"
                                class="btn btn-soft-danger"
                                @if(empty($selectedIds)) disabled @endif>
                            <i class="ri-delete-bin-line align-middle me-1"></i>
                            {{__('Delete')}}
                            @if(!empty($selectedIds))
                                ({{ count($selectedIds) }})
                            @endif
                        </button>
                        <a href="{{route('coupon_create',['locale'=>app()->getLocale()])}}"
                           class="btn btn-soft-info">
                            <i class="ri-add-line align-middle me-1"></i>
                            {{__('Add Coupons list')}}
                        </a>
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
                    @if($coupons->isNotEmpty())
                        <div class="mb-3 d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       wire:model.live="selectAll"
                                       id="selectAllCheckbox">
                                <label class="form-check-label text-muted" for="selectAllCheckbox">
                                    {{ __('Select all on this page') }}
                                </label>
                            </div>
                        </div>
                    @endif

                    @forelse($coupons as $coupon)
                        <div class="card border shadow-none mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox"
                                               value="{{$coupon->id}}"
                                               wire:model.live="selectedIds"
                                               id="coupon_{{$coupon->id}}">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="row g-3">
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
                                                    <div class="mb-2">
                                                        <span class="text-muted fs-12">{{__('Consumed')}}:</span>
                                                        @if($coupon->consumed)
                                                            <span class="badge bg-success ms-2 fs-11">{{__('yes')}}</span>
                                                        @else
                                                            <span class="badge bg-danger ms-2 fs-11">{{__('no')}}</span>
                                                        @endif
                                                    </div>
                                                    @if($coupon->user)
                                                        <div>
                                                            <span class="text-muted fs-12">{{__('User')}}:</span>
                                                            <span class="fw-medium ms-2 fs-13">{{$coupon->user->name}}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($coupon->attachment_date || $coupon->purchase_date || $coupon->consumption_date)
                                            <div class="p-3 bg-light rounded mt-3">
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

                                        <div class="d-flex gap-2 flex-wrap mt-3">
                                            <button onclick="confirmDeleteCoupon({{$coupon->id}}, '{{$coupon->pin}}')"
                                                    class="btn btn-sm btn-danger flex-fill">
                                                <span wire:loading wire:target="delete('{{$coupon->id}}')">
                                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                                </span>
                                                <span wire:loading.remove wire:target="delete('{{$coupon->id}}')">
                                                    <i class="ri-delete-bin-line align-bottom me-1"></i>
                                                    {{__('Delete')}}
                                                </span>
                                            </button>
                                        </div>
                                    </div>
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
        function confirmDeleteCoupon(couponId, couponPin) {
            Swal.fire({
                title: '{{__('Are you sure to delete this Coupon')}}? <h5 class="float-end">' + couponPin + ' </h5>',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "{{__('Delete')}}",
                denyButtonText: `{{__('Rollback')}}`
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch("delete", [couponId]);
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            window.addEventListener('coupon-deleted', event => {
                // Optional: Add any post-delete actions here
            });
        });
    </script>
</div>
