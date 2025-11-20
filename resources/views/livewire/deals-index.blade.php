<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Deals') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Deals') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header  text-muted d-flex align-items-center">
                    <i class="fas fa-filter me-2"></i>
                    <h5 class="card-title mb-0 text-muted">{{__('Filters')}}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted mb-3">
                            <i class="fas fa-desktop me-2"></i>{{__('Platforms')}}
                        </label>
                        <div class="bg-light p-3 rounded border">
                            <div class="row g-3">
                                @foreach($allPlatforms as $platform)
                                    <div class="col-auto">
                                        <div class="form-check form-switch" dir="ltr">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   wire:model="selectedPlatforms"
                                                   value="{{$platform->id}}"
                                                   id="platform.{{$platform->id}}"
                                                   style="cursor: pointer;">
                                            <label class="form-check-label"
                                                   for="platform.{{$platform->id}}"
                                                   style="cursor: pointer;">
                                                {{__($platform->name)}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <label class="form-label fw-bold text-muted mb-3">
                                <i class="fas fa-check-circle me-2"></i>{{__('Status')}}
                            </label>
                            <div class="bg-light p-3 rounded border">
                                <div class="row g-3">
                                    @foreach($allStatuses as $status)
                                        <div class="col-auto">
                                            <div class="form-check form-switch" dir="ltr">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       wire:model="selectedStatuses"
                                                       value="{{$status->value}}"
                                                       id="status.{{$status->value}}"
                                                       style="cursor: pointer;">
                                                <label class="form-check-label"
                                                       for="status.{{$status->value}}"
                                                       style="cursor: pointer;">
                                                    {{__($status->name)}}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-search me-2"></i>{{__('Name')}}
                            </label>
                            <input class="form-control shadow-sm"
                                   type="text"
                                   wire:model="keyword"
                                   placeholder="{{__('Search by name...')}}">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <label class="form-label fw-bold text-muted mb-3">
                                <i class="fas fa-tag me-2"></i>{{__('Type')}}
                            </label>
                            <div class="bg-light p-3 rounded border">
                                <div class="row g-3">
                                    @foreach($allTypes as $type)
                                        <div class="col-auto">
                                            <div class="form-check form-switch" dir="ltr">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       wire:model="selectedTypes"
                                                       value="{{$type->value}}"
                                                       id="type.{{$type->value}}"
                                                       style="cursor: pointer;">
                                                <label class="form-check-label"
                                                       for="type.{{$type->value}}"
                                                       style="cursor: pointer;">
                                                    {{__($type->name)}}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        {{__('Apply filters to refine your search')}}
                    </small>
                    <button class="btn btn-primary refreshDeals shadow-sm">
                        <i class="fas fa-search me-2"></i>{{__('Search Deals')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header text-muted d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-list-alt me-2"></i>
                        <h5 class="card-title mb-0 text-muted">{{__('Results')}}</h5>
                    </div>
                    @if($choosenDeals->count())
                        <span class="badge bg-white text-dark fs-6">
                            {{$choosenDeals->count()}} {{__('Deal(s)')}}
                        </span>
                    @endif
                </div>
                <div class="card-body pt-0">
                    @forelse($choosenDeals as $deal)
                        <div class="card border shadow-none my-2">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-circle-question text-info fa-lg"></i>
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
                                    <div class="col-md-6 col-6">
                                        <div class="p-2 bg-light rounded">
                                            <p class="text-success fs-12 mb-1">
                                                <i class="fas fa-percent me-1"></i>{{__('Initial Commission')}}
                                            </p>
                                            <span class="badge bg-success-subtle text-success px-2 py-1">
                                                {{number_format($deal->initial_commission, 2)}}%
                                            </span>
                                            @if($deal->commissionFormula)
                                                <small class="d-block text-muted mt-1">
                                                    <i class="fas fa-calculator me-1"></i>{{$deal->commissionFormula->name}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <div class="p-2 bg-light rounded">
                                            <p class="text-warning fs-12 mb-1">
                                                <i class="fas fa-percent me-1"></i>{{__('Final Commission')}}
                                            </p>
                                            <span class="badge bg-warning-subtle text-warning px-2 py-1">
                                                {{number_format($deal->final_commission, 2)}}%
                                            </span>
                                            @if($deal->commissionFormula)
                                                <small class="d-block text-muted mt-1">
                                                    <i class="fas fa-chart-line me-1"></i>{{__('Formula Applied')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 flex-wrap">
                                    @if(isset($currentRouteName) && $currentRouteName!='deals_show')
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
                                               class="btn btn-sm btn-soft-info flex-fill">
                                                <i class="fas fa-eye me-1"></i>
                                                {{__('Show')}}
                                            </a>
                                        @endif

                                        @if(\Core\Models\Platform::canCheckDeals(auth()->user()->id))
                                            <a class="btn btn-sm btn-soft-secondary flex-fill"
                                               target="_blank"
                                               href="{{route('sales_tracking',['locale'=>app()->getLocale(),'id'=>$deal->id])}}">
                                                <i class="fas fa-chart-line me-1"></i>
                                                @if(\App\Models\User::isSuperAdmin())
                                                    {{ __('Platform Details') }}
                                                @else
                                                    {{ __('Details') }}
                                                @endif
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
            </div>
        </div>
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
