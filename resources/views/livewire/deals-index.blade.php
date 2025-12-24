@php
    $dateFormat = config('app.date_format');
@endphp
<div class="container">
    @section('title')
        {{ __('Deals') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Deals') }}
        @endslot
    @endcomponent

    @if(\App\Models\User::isSuperAdmin())
        <div class="row">
                <div class="col-12 card  deals-sub-menu">
                    <div class="card-body d-flex align-items-center justify-content-end">
                        <div class="d-flex gap-1">
                            <a href="{{route('deals_all_requests', ['locale' => app()->getLocale()])}}"
                               class="btn btn-outline-primary">
                                <i class="fas fa-list me-1"></i>{{__('All Requests')}}
                            </a>
                        </div>
                    </div>
                </div>
        </div>
    @endif

    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="text-info mb-0">
                    <i class="ri-filter-3-line me-2"></i>{{ __('Filters') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Platform Filter -->
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">
                            <i class="ri-layout-grid-line me-1"></i>{{ __('Platforms') }}
                        </label>
                        <select wire:model.live="selectedPlatforms" class="form-select form-select-sm" multiple>
                            @foreach($allPlatforms as $platform)
                                <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">
                            <i class="ri-checkbox-circle-line me-1"></i>{{ __('Status') }}
                        </label>
                        <select wire:model.live="selectedStatuses" class="form-select form-select-sm" multiple>
                            @foreach($allStatuses as $status)
                                <option value="{{ $status->value }}">{{ __($status->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">
                            <i class="ri-price-tag-3-line me-1"></i>{{ __('Type') }}
                        </label>
                        <select wire:model.live="selectedTypes" class="form-select form-select-sm" multiple>
                            @foreach($allTypes as $type)
                                <option value="{{ $type->value }}">{{ __($type->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search by Name -->
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">
                            <i class="ri-search-line me-1"></i>{{ __('Search') }}
                        </label>
                        <input type="text"
                               class="form-control form-control-sm"
                               wire:model.live="keyword"
                               placeholder="{{ __('Search by name...') }}">
                    </div>

                    <!-- Start Date From -->
                    <div class="col-md-6 col-lg-2">
                        <label class="form-label">
                            <i class="ri-calendar-line me-1"></i>{{ __('Start From') }}
                        </label>
                        <input type="date"
                               class="form-control form-control-sm"
                               wire:model.live="startDateFrom">
                    </div>

                    <!-- Start Date To -->
                    <div class="col-md-6 col-lg-2">
                        <label class="form-label">
                            <i class="ri-calendar-line me-1"></i>{{ __('Start To') }}
                        </label>
                        <input type="date"
                               class="form-control form-control-sm"
                               wire:model.live="startDateTo">
                    </div>

                    <!-- End Date From -->
                    <div class="col-md-6 col-lg-2">
                        <label class="form-label">
                            <i class="ri-calendar-check-line me-1"></i>{{ __('End From') }}
                        </label>
                        <input type="date"
                               class="form-control form-control-sm"
                               wire:model.live="endDateFrom">
                    </div>

                    <!-- End Date To -->
                    <div class="col-md-6 col-lg-2">
                        <label class="form-label">
                            <i class="ri-calendar-check-line me-1"></i>{{ __('End To') }}
                        </label>
                        <input type="date"
                               class="form-control form-control-sm"
                               wire:model.live="endDateTo">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary refreshDeals">
                        <i class="ri-search-line me-1"></i>{{ __('Search Deals') }}
                    </button>
                    <button wire:click="resetFilters" class="btn btn-outline-secondary">
                        <i class="ri-restart-line me-1"></i>{{ __('Reset Filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 card shadow-sm border-0">
            <div class="card-header text-muted d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-list-alt me-2"></i>
                    <h5 class="card-title mb-0 text-muted">{{__('Results')}}</h5>
                </div>
                @if($choosenDeals instanceof \Illuminate\Pagination\LengthAwarePaginator && $choosenDeals->total() > 0)
                    <span class="badge bg-white text-dark fs-6">
                        {{$choosenDeals->total()}} {{__('Deal(s)')}}
                    </span>
                @elseif($choosenDeals instanceof \Illuminate\Support\Collection && $choosenDeals->count() > 0)
                    <span class="badge bg-white text-dark fs-6">
                        {{$choosenDeals->count()}} {{__('Deal(s)')}}
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        @forelse($choosenDeals as $deal)
            <div class="col-12  card border shadow-none my-2">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                <i class="fa fa-handshake text-info fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                                <span class="badge bg-light text-dark border mb-1">
                                                    #{{$deal->id}}
                                                </span>
                                    <h5 class="fs-15 mb-1">{{$deal->name}}</h5>
                                    @if(isset($deal->platform))
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-desktop me-1"></i>
                                            <strong class="text-primary">
                                                {!! \App\Models\TranslaleModel::getTranslation($deal->platform,'name',$deal->platform->name) !!}
                                            </strong>
                                        </p>
                                    @endif
                                    @if(\App\Models\User::isSuperAdmin() && isset($deal->platform))
                                        <small>
                                            <a class="link-info text-decoration-none"
                                               href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">
                                                <i class="fas fa-language me-1"></i>{{__('Translation')}}
                                            </a>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commission Plan Block -->
                    @if($deal->commissionPlan)
                        <div class="alert alert-success border-success mb-3" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    @if($deal->commissionPlan->iconImage)
                                        <img src="{{ asset('uploads/' . $deal->commissionPlan->iconImage->url) }}"
                                             alt="{{ $deal->commissionPlan->name }}"
                                             class="rounded-circle"
                                             style="width: 50px; height: 50px; object-fit: cover;"
                                             onerror="this.src='{{ Vite::asset(\App\Models\PlanLabel::DEFAULT_IMAGE_TYPE_ICON) }}'">
                                    @else
                                        <img
                                            src="{{ Vite::asset(\App\Models\PlanLabel::DEFAULT_IMAGE_TYPE_ICON) }}"
                                            alt="{{ $deal->commissionPlan->name }}"
                                            class="rounded-circle"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <i class="fas fa-award me-2 text-success"></i>
                                        <strong>{{ $deal->commissionPlan->name }}</strong>
                                    </h6>
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="badge bg-success fs-6">
                                            {{ $deal->commissionPlan->getCommissionRange() }}
                                        </span>
                                        @if($deal->commissionPlan->description)
                                            <small class="text-muted">
                                                {{ Str::limit($deal->commissionPlan->description, 100) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row g-2 mb-3">
                        <div class="col-md-4 col-6">
                            <div class="p-2 bg-light rounded">
                                <p class="text-primary fs-12 mb-1">
                                    <i class="fas fa-circle-notch me-1"></i>{{__('Status')}}
                                </p>
                                <span class="badge bg-primary-subtle text-primary px-2 py-1">
                                                {{__(strtoupper(\Core\Enum\DealStatus::from($deal->status)->name))}}
                                            </span>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="p-2 bg-light rounded">
                                <p class="text-primary fs-12 mb-1">
                                    <i class="fas fa-tag me-1"></i>{{__('Type')}}
                                </p>
                                <span class="badge bg-info-subtle text-info px-2 py-1">
                                                {{__(\Core\Enum\DealTypeEnum::from($deal->type)->name)}}
                                            </span>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="p-2 bg-light rounded">
                                @if($deal->validated)
                                    <span class="badge bg-success-subtle text-success px-2 py-1">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{__('Validated')}}
                                                </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{__('Not validated')}}
                                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6 col-12">
                            <div class="p-2 bg-light rounded">
                                <p class="text-info fs-12 mb-2">
                                    <i class="fas fa-calendar-alt me-1"></i>{{__('Deal Period')}}
                                </p>
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        <small class="text-muted">{{__('Start Date')}}:</small>
                                        <span class="badge bg-info-subtle text-info px-2 py-1 ms-1">
                                            {{$deal->start_date ? \Carbon\Carbon::parse($deal->start_date)->format($dateFormat) : __('N/A')}}
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted">{{__('End Date')}}:</small>
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 ms-1">
                                            {{$deal->end_date ? \Carbon\Carbon::parse($deal->end_date)->format($dateFormat) : __('N/A')}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="p-2 bg-light rounded">
                                <p class="text-success fs-12 mb-2">
                                    <i class="fas fa-percent me-1"></i>{{__('Commission Range')}}
                                </p>
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        <small class="text-muted">{{__('Initial')}}:</small>
                                        <span class="badge bg-success-subtle text-success px-2 py-1 ms-1">
                                            {{number_format($deal->initial_commission, 2)}}%
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted">{{__('Final')}}:</small>
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 ms-1">
                                            {{number_format($deal->final_commission, 2)}}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Change Request Alert (Super Admin Only) -->
                    @if(\App\Models\User::isSuperAdmin() && $deal->pendingChangeRequest)
                        <div class="alert alert-success border-success mb-3" role="alert">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <i class="fas fa-file-edit me-2"></i>
                                    <strong>{{__('Pending Update Request')}}</strong>
                                    @if($deal->pendingChangeRequest->changes)
                                        <span class="ms-2 badge bg-success">
                                                        {{ count($deal->pendingChangeRequest->changes) }} {{__('field(s) changed')}}
                                                    </span>
                                    @endif
                                    @if($deal->pendingChangeRequest->requestedBy)
                                        <small class="d-block text-muted mt-1">
                                            <i class="fas fa-user me-1"></i>{{__('Requested by')}}
                                            : {{ getUserDisplayedNameFromId($deal->pendingChangeRequest->requestedBy->id)}}
                                        </small>
                                    @endif
                                </div>
                                <a href="{{route('deals_change_requests', ['locale' => app()->getLocale()])}}"
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-check-double me-1"></i>{{__('Review')}}
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-2 flex-wrap">
                        @if(isset($currentRouteName) && $currentRouteName!='deals_show')
                            @if(\App\Models\User::isSuperAdmin())
                                <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
                                   class="btn btn-sm btn-soft-info flex-fill">
                                    <i class="fas fa-eye me-1"></i>
                                    {{__('Show')}}
                                </a>
                            @endif

                            @if(\Core\Models\Platform::havePartnerSpecialRole(auth()->user()->id))
                                <a class="btn btn-sm btn-soft-secondary flex-fill"
                                   target="_blank"
                                   title="{{__('For User Role')}}"
                                   href="{{route('sales_tracking',['locale'=>app()->getLocale(),'id'=>$deal->id])}}">
                                    <i class="fas fa-chart-line me-1"></i>
                                    {{ __('Deals details') }}
                                </a>
                            @endif
                        @endif

                        @if(!$deal->validated)
                            <a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'id' => $deal->id, 'idPlatform' => $deal->platform_id])}}"
                               class="btn btn-sm btn-soft-primary flex-fill">
                                <i class="fas fa-edit me-1"></i>
                                {{__('Edit')}}
                            </a>
                            @if($deal->status< \Core\Enum\DealStatus::Opened->value)
                                <button class="btn btn-sm btn-soft-success updateDeal flex-fill"
                                        data-status="0"
                                        data-id="{{$deal->id}}"
                                        data-status-name="{{__('Validate')}}">
                                    <i class="fas fa-check me-1"></i>
                                    {{__('Validate')}}
                                </button>
                            @endif
                        @endif

                        @if(\App\Models\User::isSuperAdmin())
                            <button data-id="{{$deal->id}}"
                                    data-name="{{$deal->name}}"
                                    class="btn btn-sm btn-soft-danger deleteDeal flex-fill">
                                <i class="fas fa-trash me-1"></i>
                                {{__('Delete')}}
                            </button>
                        @endif

                        <a href="{{ route('deals_dashboard', ['locale' => app()->getLocale(), 'dealId' => $deal->id]) }}"
                           class="btn btn-sm btn-soft-success flex-fill">
                            <i class="ri-bar-chart-line me-1"></i>
                            {{__('Dashboard')}}
                        </a>
                    </div>
                    @if($deal->validated)
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            @if($deal->status== \Core\Enum\DealStatus::New->value)
                                <button class="btn btn-sm btn-soft-info updateDeal flex-fill"
                                        data-status="{{\Core\Enum\DealStatus::Opened->value}}"
                                        data-id="{{$deal->id}}"
                                        data-status-name="{{__(\Core\Enum\DealStatus::Opened->name)}}">
                                    <i class="fas fa-door-open me-1"></i>
                                    {{__('Open')}}
                                </button>
                            @endif
                            @if($deal->status== \Core\Enum\DealStatus::Opened->value)
                                <button class="btn btn-sm btn-soft-warning updateDeal flex-fill"
                                        data-status="{{\Core\Enum\DealStatus::Closed->value}}"
                                        data-id="{{$deal->id}}"
                                        data-status-name="{{__(\Core\Enum\DealStatus::Closed->name)}}">
                                    <i class="fas fa-times-circle me-1"></i>
                                    {{__('Close')}}
                                </button>
                            @endif
                            @if($deal->status== \Core\Enum\DealStatus::Closed->value)
                                <button class="btn btn-sm btn-soft-secondary updateDeal flex-fill"
                                        data-status="{{\Core\Enum\DealStatus::Archived->value}}"
                                        data-id="{{$deal->id}}"
                                        data-status-name="{{__(\Core\Enum\DealStatus::Archived->name)}}">
                                    <i class="fas fa-archive me-1"></i>
                                    {{__('Archive')}}
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{__('No deals')}}</h5>
                <p class="text-muted">{{__('Try adjusting your filters to see more results')}}</p>
            </div>
        @endforelse
    </div>
    @if($choosenDeals instanceof \Illuminate\Pagination\LengthAwarePaginator && $choosenDeals->hasPages())
        <div class="row">
            <div class="col-12 card">
                <div class="card-body">
                    {{ $choosenDeals->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    @endif
</div>

<script type="module">
    document.addEventListener("DOMContentLoaded", function () {
        $('body').on('click', '.refreshDeals', function (event) {
            window.Livewire.dispatch("refreshDeals", [$(event.target).attr('data-id')]);
        });

        $('body').on('click', '.deleteDeal', function (event) {
            Swal.fire({
                title: '{{__('Are you sure to delete this Deal')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                showCancelButton: true,
                confirmButtonText: "{{__('Delete')}}",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch("delete", [$(event.target).attr('data-id')]);
                }
            });
        });


        $('body').on('click', '.updateDeal', function (event) {
            var status = $(event.target).attr('data-status');
            var id = $(event.target).attr('data-id');
            var name = $(event.target).attr('data-status-name');
            var title = '{{__('Are you sure to')}} ' + name + ' ?';
            var confirmButtonText = name;
            Swal.fire({
                title: title,
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch("updateDeal", [id, status]);
                }
            });
        });
    });
</script>
</div>
