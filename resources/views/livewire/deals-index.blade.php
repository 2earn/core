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
        @include('layouts.flash-messages')
    </div>
    <div class="row card">
        <div class="card-header border-info">
            <div class="row">
                @foreach($platforms as $platform)
                    <div class="col-auto">
                        <h3 class="fs-14 my-1"><a href="{{$platform->link}}">
                                {{__($platform->name)}}
                            </a>
                        </h3>
                    </div>

                @endforeach
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
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Validated')}}</th>
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
                        {data: 'status'},
                        {data: 'validated'},
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
                        window.Livewire.emit("delete", $(event.target).attr('data-id'));
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
                        window.Livewire.emit("updateDeal", id, status);
                    }
                });
            });


        });
    </script>
</div>
