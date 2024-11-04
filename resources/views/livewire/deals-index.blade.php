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
    <div class="row card">
        <div class="card-header border-info">
            <div class="row p-2">
                <div class="col-6">
                    <input wire:model.live="search" type="text" id="simple-search"
                           class="form-control"
                           placeholder="{{__('Search Deal')}}">
                </div>
                <div class="col-6">
                    <a href="{{route('deals_create_update', ['locale'=> request()->route("locale")] )}}"
                       class="btn btn-soft-secondary material-shadow-none mb-2 float-end">
                        {{__('Create Deal')}}
                    </a>
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
                                <tr class="head2earn  tabHeader2earn">
                                    <th>{{__('Details')}}</th>
                                    <th>{{__('reference')}}</th>
                                    <th>{{__('source')}}</th>
                                    <th>{{__('receiver')}}</th>
                                    <th>{{__('Actions')}}</th>
                                    <th>{{__('date')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('reponce')}}</th>
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
                        {data: 'reference'},
                        {data: 'send'},
                        {data: 'receiver'},
                        {data: 'action'},
                        {data: 'date'},
                        {data: 'type'},
                        {data: 'responce'},
                    ],
                    "language": {"url": urlLang},
                });
            }
        });
    </script>
</div>
