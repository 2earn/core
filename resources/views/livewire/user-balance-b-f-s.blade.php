<div class="container-fluid">
    <div>
        @section('title')
            {{ __('Balance For Shopping') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('Balance For Shopping') }}
            @endslot
        @endcomponent
        <div class="row card">
            @if(!empty($bfss))
                <div class="card-header">
                    <h5 class="card-title mb-0">{{__('BFSs description values')}} </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($bfss as $bfs)
                            <div class="col-lg-3 col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-light text-primary rounded-circle fs-3 material-shadow">
                                                            <i class="ri-money-dollar-circle-fill align-middle"></i>
                                                        </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h3 class="text-uppercase text-muted float-end mb-1">{{__('BFS_')}} {{$bfs['type']}}</h3>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="float-end mb-0">{{config('app.currency')}} <span
                                                    >{{formatSolde(floatval($bfs['value']),2)}}</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="card-header">
                <h5 class="card-title mb-0">{{__('BFSs description title')}} </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="justify-content-sm-end">
                            <div class="search-box ms-2">
                                <p>{{ __('bfs description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-4">
                    <select class="select2-hidden-accessible bfs_operation_multiple" name="states[]"
                            id="select2bfs" multiple="multiple">
                    </select>
                </div>
                <div class="table-responsive">
                    <table
                        class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                        id="ub_table_bfs" style="width: 100%">
                        <thead class="table-light">
                        <tr class=" tabHeader2earn">
                            <th>{{ __('Operation order') }}</th>
                            <th>{{ __('ref')}}</th>
                            <th>{{ __('date')}}</th>
                            <th>{{ __('Operation Designation')}}</th>
                            <th>{{ __('description')}}</th>
                            <th>{{ __('Percentage')}}</th>
                            <th>{{ __('Value')}}</th>
                            <th>{{ __('Balance')}}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                    $('#page-title-box').addClass('page-title-box-bfs');
                }
            );
            document.addEventListener("DOMContentLoaded", function () {
                var select2_array = [];
                $('#ub_table_bfs').DataTable(
                    {
                        retrieve: true,
                        "colReorder": true,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        "order": [[2, 'desc']],
                        initComplete: function () {
                            this.api()
                                .columns()
                                .every(function () {
                                    if ($.fn.dataTable.isDataTable('#ub_table_bfs')) {
                                        var that = $('#ub_table_bfs').DataTable();
                                    }
                                    $('input', this.footer()).on('keydown', function (ev) {
                                        if (ev.keyCode == 13) {
                                            that.search(this.value).draw();
                                        }
                                    });
                                });
                        },
                        "processing": true,
                        search: {return: true},
                        "ajax": "{{route('api_user_bfs_purchase',app()->getLocale())}}",
                        "columns": [
                            {data: 'ranks'},
                            {data: 'reference'},
                            {data: 'created_at'},
                            {data: 'operation'},
                            {data: 'description'},
                            {data: 'percentage'},
                            {data: 'value', className: classAl},
                            {data: 'current_balance', className: classAl},
                        ],
                        "columnDefs":
                            [
                                {
                                    "targets": [6],
                                    render: function (data, type, row) {
                                        if (data.indexOf('+') == -1)
                                            return '<span class="badge bg-danger con fs-14">' + data + '</span>';
                                        else
                                            return '<span class="badge bg-success con fs-14">' + data + '</span>';
                                    }
                                },
                                {
                                    "targets": [3],
                                    render: function (data, type, row) {
                                        if (select2_array.indexOf(data) == -1) {
                                            select2_array.push(data);
                                            $('.bfs_operation_multiple').append(('<option value="' + data + '">' + data + '</option>'));
                                        }
                                        return data;
                                    }
                                }],
                        "language": {"url": urlLang}
                    });
            });
        </script>
    </div>
</div>
