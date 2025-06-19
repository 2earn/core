<div class="container-fluid">
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
    <div class="row card">
        <div class="card-body row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Platforms')}}</label>
                        <div class="row">
                            @foreach($allPlatforms as $platform)
                                <div class="col-auto">
                                    <div class="form-check form-switch form-check-inline" dir="ltr">
                                        <label for="platform.{{__($platform->name)}}">{{__($platform->name)}}</label>
                                        <input type="checkbox" class="form-check-input" wire:model="selectedPlatforms"
                                               value="{{$platform->id}}"
                                               id="platform.{{__($platform->name)}}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Status')}}</label>
                        <select class="form-select form-select-sm  mb-3" multiple wire:model="selectedStatuses">
                            @foreach($allStatuses as $status)
                                <option value="{{$status->value}}">{{__($status->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="row m-1 card border border-muted">

                    <div class="card-body border-info">
                        <label>
                            {{__('Name')}}
                        </label>
                        <input class="form-select form-select-sm" type="text" multiple wire:model="keyword">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Type')}}</label>
                        <select class="form-select form-select-sm  mb-3" multiple wire:model="selectedTypes">
                            @foreach($allTypes as $type)
                                <option value="{{$type->value}}">{{__($type->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary refreshDeals float-end">{{__('Search Deals')}}</button>
        </div>
    </div>
    @if($choosenDeals->count())
        <div class="row">
            <div class="col-lg-12 card">
                <div class="card-body table-responsive">
                    <table id="dealTable"
                           class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                        <thead class="table-light">
                        <tr class="head2earn tabHeader2earn">
                            <th>{{__('Details')}}</th>
                            <th>{{__('id')}}</th>
                            <th>{{__('name')}}</th>
                            <th>{{__('Details')}}</th>
                            <th>{{__('Platform')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        @foreach($choosenDeals as $deal)
                            <tr>
                                <td>
                                    <i class="fa-solid fa-circle-question text-info fa-lg dtmdbtn"></i>
                                </td>
                                <td>{{$deal->id}}</td>
                                <td>{{$deal->name}}</td>
                                <td>
                                    <ul class="list-group list-group-horizontal-md">
                                        <li class="list-group-item">
                                                    <span class="text-info btn btn-soft-primary"
                                                          title="{{$deal->status}}">
                                                {{__(strtoupper(\Core\Enum\DealStatus::from($deal->status)->name))}}
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                                    <span class="text-info btn btn-soft-secondary"
                                                          title="{{$deal->type}}">
                                                {{__(strtoupper(\Core\Enum\DealTypeEnum::from($deal->type)->name))}}
                                            </span>
                                        </li>
                                        <li class="list-group-item">@if($deal->validated)
                                                <span class="btn btn-soft-success"> {{__('Validated')}}</span>
                                            @else
                                                <span class="btn btn-soft-warning"> {{__('Not validated')}}</span>
                                            @endif
                                        </li>
                                    </ul>

                                </td>
                                <td>
                                    {{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
                                    <br>
                                    @if(\App\Models\User::isSuperAdmin())
                                        <small class="mx-2">
                                            <a class="link-info"
                                               href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">{{__('See or update Translation')}}</a>
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        @if(isset($currentRouteName))
                                            @if($currentRouteName!='deals_show')
                                                @if(\App\Models\User::isSuperAdmin())
                                                    <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
                                                       class="btn btn-xs btn-outline-info btn2earnTable  m-1">{{__('Show')}}</a>
                                                @endif

                                                @if(\Core\Models\Platform::canCheckDeals(auth()->user()->id))
                                                    <a class="link-info" target="_blank"
                                                       href="{{route('sales_tracking',['locale'=>app()->getLocale(),'id'=>$deal->id])}}">
                                                        @if(\App\Models\User::isSuperAdmin())
                                                            {{ __('See details for Platform role') }}
                                                        @else
                                                            {{ __('See more deal details') }}
                                                        @endif
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                        <hr>
                                        @if(!$deal->validated)
                                            <a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'id' => $deal->id, 'idPlatform' => $deal->platform_id])}}"
                                               class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>
                                            @if($deal->status< \Core\Enum\DealStatus::Opened->value)
                                                <button class="btn btn-secondary updateDeal" data-status="0"
                                                        data-id="{{$deal->id}}" data-status-name="{{__('Validate')}}">
                                                    {{__('Validate')}}
                                                </button>
                                            @endif
                                        @endif
                                        @if($deal->validated)
                                            <a href="{{route('items_create_update',['locale'=>app()->getLocale(), 'dealId' => $deal->id])}}"
                                               class="btn btn-outline-success">{{__('Create Item')}}
                                            </a>
                                            @if($deal->status== \Core\Enum\DealStatus::New->value)
                                                <button class="btn btn-secondary updateDeal"
                                                        data-status="{{\Core\Enum\DealStatus::Opened->value}}"
                                                        data-id="{{$deal->id}}"
                                                        data-status-name="{{__(\Core\Enum\DealStatus::Opened->name)}}">
                                                    {{__('Open')}}
                                                </button>
                                            @endif
                                            @if($deal->validated)
                                                @if($deal->status== \Core\Enum\DealStatus::Opened->value)
                                                    <button class="btn btn-secondary updateDeal"
                                                            data-status="{{\Core\Enum\DealStatus::Closed->value}}"
                                                            data-id="{{$deal->id}}"
                                                            data-status-name="{{__(\Core\Enum\DealStatus::Closed->name)}}">
                                                        {{__('close')}}
                                                    </button>
                                                @endif
                                                @if($deal->status== \Core\Enum\DealStatus::Closed->value)
                                                    <button class="btn btn-secondary updateDeal"
                                                            data-status="{{\Core\Enum\DealStatus::Archived->value}}"
                                                            data-id="{{$deal->id}}"
                                                            data-status-name="{{__(\Core\Enum\DealStatus::Archived->name)}}">
                                                        {{__('Archive')}}
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a data-id="{{$deal->id}}" data-name="{{$deal->name }}"
                                               title="{{$deal->name }}"
                                               class="btn btn-xs btn-danger btn2earnTable deleteDeal m-1">{{__('Delete')}}</a>
                                        @endif
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
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
