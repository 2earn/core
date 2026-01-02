<div class="col-12 card">
    <div class="card-header align-items-center border-0 d-flex">
        <h4 class="card-title mb-0 flex-grow-1">{{__('Coupon runner table')}}</h4>
    </div>
    <div class="card-body">
        <div class="card-header border-info">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <form class="items-center">
                        <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                        <div class="w-full">
                            <input wire:model.live="search" type="text" id="simple-search"
                                   class="form-control"
                                   placeholder="{{__('Search coupons')}}">
                        </div>
                    </form>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 text-end">
                    @if(count($selectedCoupons) > 0)
                        <button wire:click="$dispatch('confirmDeleteSelected')"
                                class="btn btn-danger">
                            <i class="ri-delete-bin-line align-bottom me-1"></i>
                            {{__('Delete Selected')}} ({{ count($selectedCoupons) }})
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body row">
            <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    {{ __('Showing') }} {{ $coupons->count() }} / {{ $coupons->total() }} {{ __('coupons') }}
                </div>
                <div class="form-check">
                    <input wire:model.live="selectAll" class="form-check-input" type="checkbox" id="selectAllCheck">
                    <label class="form-check-label" for="selectAllCheck">
                        {{__('Select All')}}
                    </label>
                </div>
            </div>

            @if($coupons->count())
                <div class="col-12">
                    @foreach($coupons as $coupon)
                        <div class="card mb-3 @if($coupon->consumed) border-success @endif">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <input wire:model.live="selectedCoupons" type="checkbox"
                                               value="{{ $coupon->id }}"
                                               class="form-check-input" @if($coupon->consumed) disabled @endif>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-info fs-14">
                                                {{__(\App\Enums\BalanceEnum::tryFrom($coupon->category)->name)}}
                                            </span>
                                            @if($coupon->category==\App\Enums\BalanceEnum::BFS->value)
                                                <span class="badge bg-vertical-gradient fs-12">
                                                    {{$coupon->type}}
                                                </span>
                                            @endif
                                            @if($coupon->consumed)
                                                <span class="badge bg-success">{{__('Consumed')}}</span>
                                            @else
                                                <span class="badge bg-secondary">{{__('Available')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <small class="text-muted">{{__('Pin')}}</small>
                                        <div class="fw-bold">{{ $coupon->pin }}</div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <small class="text-muted">{{__('Serial Number')}}</small>
                                        <div class="fw-bold">{{ $coupon->sn }}</div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <small class="text-muted">{{__('Value')}}</small>
                                        <div class="fw-bold text-success">{{ number_format($coupon->value, 2) }}</div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <small class="text-muted">{{__('Status')}}</small>
                                        <div>
                                            @if($coupon->consumed)
                                                <span class="badge bg-success">{{__('Consumed')}}</span>
                                            @else
                                                <span class="badge bg-warning">{{__('Not Consumed')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">{{__('Attachment Date')}}</small>
                                        <div>{{ $coupon->attachment_date ? \Carbon\Carbon::parse($coupon->attachment_date)->format(config('app.date_format')) : '-' }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">{{__('Consumption Date')}}</small>
                                        <div>{{ $coupon->consumption_date ? \Carbon\Carbon::parse($coupon->consumption_date)->format(config('app.date_format')) : '-' }}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="small text-muted">
                                        {{__('Created at')}}
                                        : {{ $coupon->created_at->format(config('app.date_format')) }}
                                    </div>
                                    <div>
                                        @if(!$coupon->consumed)
                                            <button
                                                wire:click="$dispatch('confirmDelete', { id: {{ $coupon->id }}, pin: '{{ $coupon->pin }}' })"
                                                class="btn btn-sm btn-soft-danger"
                                                title="{{__('Delete Coupon')}}">
                                                <i class="ri-delete-bin-line align-bottom me-1"></i>{{__('Delete')}}
                                            </button>
                                        @else
                                            <span
                                                class="text-muted small">{{__('Cannot delete consumed coupon')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-12 mt-3">{{ $coupons->links() }}</div>
            @else
                <div class="col-12 py-5 text-center">
                    <h5 class="text-muted">{{ __('No coupons found') }}</h5>
                    <p class="text-muted">{{ __('There are no coupons matching your search criteria.') }}</p>
                    @if($search)
                        <button wire:click="$set('search', '')" class="btn btn-sm btn-outline-primary">
                            {{__('Clear search')}}
                        </button>
                    @else
                        <p class="text-muted">{{ __('You do not have any coupons yet.') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            window.addEventListener('confirmDelete', event => {
                Swal.fire({
                    title: '{{__('Are you sure to delete this coupon')}}?',
                    text: '{{__('Pin')}}: ' + event.detail.pin,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{__('Delete')}}',
                    cancelButtonText: '{{__('Cancel')}}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch("deleteCoupon", [event.detail.id]);
                    }
                });
            });

            window.addEventListener('confirmDeleteSelected', event => {
                Swal.fire({
                    title: '{{__('Are you sure to delete selected coupons')}}?',
                    text: '{{__('Only unconsumed coupons will be deleted')}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{__('Delete')}}',
                    cancelButtonText: '{{__('Cancel')}}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch("deleteSelected");
                    }
                });
            });
        });
    </script>
</div>
