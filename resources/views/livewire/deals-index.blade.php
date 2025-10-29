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
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-check-circle me-2"></i>{{__('Status')}}
                            </label>
                            <select class="form-select shadow-sm" multiple wire:model="selectedStatuses" size="4">
                                @foreach($allStatuses as $status)
                                    <option value="{{$status->value}}">{{__($status->name)}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{__('Hold Ctrl to select multiple')}}</small>
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
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-tag me-2"></i>{{__('Type')}}
                            </label>
                            <select class="form-select shadow-sm" multiple wire:model="selectedTypes" size="4">
                                @foreach($allTypes as $type)
                                    <option value="{{$type->value}}">{{__($type->name)}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{__('Hold Ctrl to select multiple')}}</small>
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
                <div class="card-body table-responsive p-0">
                    @if($choosenDeals->count())
                        <table id="dealTable"
                               class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">
                                    <i class="fas fa-info-circle text-primary"></i>
                                </th>
                                <th style="width: 80px;">{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Details')}}</th>
                                <th>{{__('Platform')}}</th>
                                <th class="text-center" style="width: 250px;">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($choosenDeals as $deal)
                                <tr>
                                    <td class="text-center">
                                        <i class="fa-solid fa-circle-question text-info fa-lg dtmdbtn"
                                           style="cursor: pointer;"
                                           title="{{__('More information')}}"></i>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            #{{$deal->id}}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{$deal->name}}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-primary-subtle text-primary px-3 py-2"
                                                  title="{{__('Status')}}: {{$deal->status}}">
                                                <i class="fas fa-circle-notch me-1"></i>
                                                {{__(strtoupper(\Core\Enum\DealStatus::from($deal->status)->name))}}
                                            </span>
                                            <span class="badge bg-info-subtle text-info px-3 py-2"
                                                  title="{{__('Type')}}: {{$deal->type}}">
                                                <i class="fas fa-tag me-1"></i>
                                                {{__(strtoupper(\Core\Enum\DealTypeEnum::from($deal->type)->name))}}
                                            </span>
                                            @if($deal->validated)
                                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{__('Validated')}}
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{__('Not validated')}}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-primary">
                                                {!! \App\Models\TranslaleModel::getTranslation($deal->platform,'name',$deal->platform->name) !!}
                                            </strong>
                                            @if(\App\Models\User::isSuperAdmin())
                                                <br>
                                                <small>
                                                    <a class="link-info text-decoration-none"
                                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">
                                                        <i class="fas fa-language me-1"></i>{{__('Translation')}}
                                                    </a>
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            @if(isset($currentRouteName))
                                                @if($currentRouteName!='deals_show')
                                                    @if(\App\Models\User::isSuperAdmin())
                                                        <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
                                                           class="btn btn-sm btn-outline-info"
                                                           title="{{__('Show')}}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if(\Core\Models\Platform::canCheckDeals(auth()->user()->id))
                                                        <a class="btn btn-sm btn-outline-secondary"
                                                           target="_blank"
                                                           href="{{route('sales_tracking',['locale'=>app()->getLocale(),'id'=>$deal->id])}}"
                                                           title="@if(\App\Models\User::isSuperAdmin()){{ __('See details for Platform role') }}@else{{ __('See more deal details') }}@endif">
                                                            <i class="fas fa-chart-line"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif

                                            @if(!$deal->validated)
                                                <a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'id' => $deal->id, 'idPlatform' => $deal->platform_id])}}"
                                                   class="btn btn-sm btn-primary"
                                                   title="{{__('Edit')}}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($deal->status< \Core\Enum\DealStatus::Opened->value)
                                                    <button class="btn btn-sm btn-success updateDeal"
                                                            data-status="0"
                                                            data-id="{{$deal->id}}"
                                                            data-status-name="{{__('Validate')}}"
                                                            title="{{__('Validate')}}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            @endif

                                            @if($deal->validated)

                                                @if($deal->status== \Core\Enum\DealStatus::New->value)
                                                    <button class="btn btn-sm btn-info updateDeal"
                                                            data-status="{{\Core\Enum\DealStatus::Opened->value}}"
                                                            data-id="{{$deal->id}}"
                                                            data-status-name="{{__(\Core\Enum\DealStatus::Opened->name)}}"
                                                            title="{{__('Open')}}">
                                                        <i class="fas fa-door-open"></i>
                                                    </button>
                                                @endif
                                                @if($deal->status== \Core\Enum\DealStatus::Opened->value)
                                                    <button class="btn btn-sm btn-warning updateDeal"
                                                            data-status="{{\Core\Enum\DealStatus::Closed->value}}"
                                                            data-id="{{$deal->id}}"
                                                            data-status-name="{{__(\Core\Enum\DealStatus::Closed->name)}}"
                                                            title="{{__('Close')}}">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                @endif
                                                @if($deal->status== \Core\Enum\DealStatus::Closed->value)
                                                    <button class="btn btn-sm btn-secondary updateDeal"
                                                            data-status="{{\Core\Enum\DealStatus::Archived->value}}"
                                                            data-id="{{$deal->id}}"
                                                            data-status-name="{{__(\Core\Enum\DealStatus::Archived->name)}}"
                                                            title="{{__('Archive')}}">
                                                        <i class="fas fa-archive"></i>
                                                    </button>
                                                @endif
                                            @endif

                                            @if(\App\Models\User::isSuperAdmin())
                                                <button data-id="{{$deal->id}}"
                                                        data-name="{{$deal->name}}"
                                                        title="{{__('Delete')}}"
                                                        class="btn btn-sm btn-danger deleteDeal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{__('No deals')}}</h5>
                            <p class="text-muted">{{__('Try adjusting your filters to see more results')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        window.addEventListener('updateDealsDatatable', event => {
            var table = $('#dealTable').DataTable();
            table.destroy();
            $('#dealTable').DataTable({
                "paging": true,
                "responsive": true,
                "language": {"url": urlLang},
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            if (!$.fn.dataTable.isDataTable('#dealTable')) {
                $('#dealTable').DataTable({
                    "paging": true,
                    "responsive": true,
                    "language": {"url": urlLang},
                });
            }

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
