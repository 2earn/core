<div>
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
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row card">
        <div class="card-header border-info">
            <div class="row p-2">
                <div class="col-4">
                    <input wire:model.live="search" type="text" id="simple-search"
                           class="form-control"
                           placeholder="{{__('Search Deal')}}">
                </div>
                <div class="col-8">
                    @foreach($platforms as $platform)
                        <a href="{{$platform->link}}">
                            <span class="text-muted fs-16 mx-2">
                                {{__($platform->name)}}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
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
                                    <th>{{__('description')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Platform')}}</th>
                                    <th>{{__('Created by')}}</th>
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
        function openDeal() {
            console.log('End');
        }
    </script>
    <script type="module">
        $(document).on('turbolinks:load', function () {
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
                        {data: 'description'},
                        {data: 'status'},
                        {data: 'platform_id'},
                        {data: 'created_by'},
                        {data: 'action'},
                    ],
                    "language": {"url": urlLang},
                });
            }


            $('body').on('click', '.deleteDeal', function (event) {
                Swal.fire({
                    title: '{{__('Are you sure to delete this Deal')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    denyButtonText: `Rollback`
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.emit("delete", $(event.target).attr('data-id'));
                    }
                });
            });


            $('body').on('click', '.changeStatus', function (event) {
                var status = $(event.target).attr('data-status');
                var id = $(event.target).attr('data-id');
                var name = $(event.target).attr('data-status-name');
                var title = '{{__('Are you sure to')}} ' + name + ' ?';
                var confirmButtonText = name;

                Swal.fire({
                    title: title,
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                    denyButtonText: `Rollback`
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.emit("changeStatus", id, status);
                    }
                });
            });


        });
    </script>
</div>
