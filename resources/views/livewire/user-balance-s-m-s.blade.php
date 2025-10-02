<div class="container-fluid">
    <div>
        @section('title')
            {{ __('sms balance') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('sms balance') }}
            @endslot
        @endcomponent
        <div class="row card">
            <div class="card-body">
                <div class="table-responsive">
                    <table
                        class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                        id="userBalanceSMS_table" style="width: 100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{__('Ranks')}}</th>
                            <th>{{__('reference')}}</th>
                            <th>{{ __('Created at') }}</th>
                            <th>{{ __('Operation Designation') }}</th>
                            <th>{{ __('Value') }}</th>
                            <th>{{ __('Current balance') }}</th>
                            <th>{{ __('Complementary information') }}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            $('#userBalanceSMS_table').DataTable(
                {
                    "ordering": false,
                    retrieve: true,
                    "colReorder": false,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "order": [[1, 'asc']],
                    "processing": true,
                    "serverSide": false,
                    "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                    search: {return: true},
                    autoWidth: false,
                    bAutoWidth: false,

                    "ajax": {
                        url: "{{route('api_user_sms',['locale'=> app()->getLocale()])}}",
                        type: "GET",
                        headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                        error: function (xhr, error, thrown) {
                            loadDatatableModalError('userBalanceSMS_table')
                        }
                    },
                    "columns": [
                        {data: 'ranks'},
                        {data: 'reference'},
                        {data: 'created_at'},
                        {data: 'operation'},
                        {data: 'value', className: classAl},
                        {data: 'current_balance', className: classAl},
                        {data: 'complementary_information'},
                    ],
                    "columnDefs":
                        [
                            {
                                "targets": [5],
                                render: function (data, type, row) {
                                    return '<span class="badge bg-danger con fs-14">' + data + '</span>';
                                }
                            },
                            {
                                "targets": [3],
                                render: function (data, type, row) {
                                    return data;
                                }
                            }],
                    "language": {"url": urlLang}
                }
            );
        });
    </script>
</div>
</div>









