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
                                    <td>{{$deal->detail}}</td>
                                    <td>{{$deal->platform_id}}</td>
                                    <td>{{$deal->action}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    @endif
    <script type="module">
        function updateDatatable() {
            $('#dealTable').DataTable({
                "responsive": true,

                "language": {"url": urlLang},
            });
        }

        window.addEventListener('confirmOPTVerifMail', event => {
            updateDatatable();
        });
        document.addEventListener("DOMContentLoaded", function () {

            if (!$.fn.dataTable.isDataTable('#dealTable')) {
                updateDatatable();
            }

            $('body').on('click', '.refreshDeals', function (event) {

                window.Livewire.dispatch("refreshDeals", [$(event.target).attr('data-id')]);

                $('#dealTable').DataTable().ajax.reload();
                console.log('dealTable')
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
