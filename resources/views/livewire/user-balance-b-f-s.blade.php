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
                    <h5 class="card-title mb-0">{{__('BFSs description values')}} @if($type)
                            <span class="text-success">({{$type}})</span>
                        @endif</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @if($type!='ALL')
                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('BFS_')}}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="text-muted fs-14 mb-0">
                                                    <i class="ri-money-dollar-circle-line fs-13 align-middle"></i> {{__('ALL')}}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{config('app.currency')}}{{formatSolde(floatval($this->totalBfs),2)}}</h4>
                                                <a href="{{route('user_balance_bfs' , ['locale'=>app()->getLocale()] )}}"
                                                   class="text-decoration-underline">{{__('More details')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @foreach($bfss as $bfs)
                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('BFS_')}}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="
                                                    @if($type==$bfs['type'])
                                                text-success
                                                 @else
                                                text-muted
                                                 @endif
                                                 fs-14 mb-0">
                                                    <i class="ri-money-dollar-circle-line fs-13 align-middle"></i> {{$bfs['type']}}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{config('app.currency')}}{{formatSolde(floatval($bfs['value']),2)}}</h4>
                                                <a href="{{route('user_balance_bfs' , ['locale'=>app()->getLocale(),'type'=>$bfs['type']] )}}"
                                                   class="text-decoration-underline">{{__('More details')}}</a>
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
                        "ajax": "{{route('api_user_bfs_purchase',['locale'=>app()->getLocale(),'type'=>$type])}}",
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
