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
                <div class="row m-1 card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{__('Platforms')}}</h4>
                    </div>
                    <div class="card-body border-info">
                        <div class="row">
                            @foreach($platforms as $platform)
                                <div class="col-auto">
                                    <div class="form-check form-switch form-check-inline" dir="ltr">
                                        <label for="platform.{{__($platform->name)}}">{{__($platform->name)}}</label>
                                        <input type="checkbox" class="form-check-input"
                                               id="platform.{{__($platform->name)}}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="row m-1 card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{__('Status')}}</h4>
                    </div>
                    <div class="card-body border-info">
                        <select class="form-select form-select-sm  mb-3" aria-label=".form-select-sm example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="row m-1 card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{__('Type')}}</h4>
                    </div>
                    <div class="card-body border-info">
                        <select class="form-select form-select-sm  mb-3" aria-label=".form-select-sm example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" type="submit">{{__('Search Deals')}}</button>
        </div>
    </div>
    <div class="row card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {

            if (!$.fn.dataTable.isDataTable('#dealTable')) {
                $('#dealTable').DataTable({
                    "responsive": true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                var that = $('#dealTable').DataTable();
                                $('input', this.footer()).on('keydown', function (ev) {
                                    if (ev.keyCode == 13) {
                                        that.search(this.value).draw();
                                    }
                                });
                            });
                    },
                    "processing": true,
                    search: {return: true},
                    "ajax": "{{route('api_deal',app()->getLocale())}}",
                    "columns": [
                        datatableControlBtn,
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'details'},
                        {data: 'platform_id'},
                        {data: 'action'},
                    ],
                    "language": {"url": urlLang},
                });
            }


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
