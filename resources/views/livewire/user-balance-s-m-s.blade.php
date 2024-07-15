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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered"
                           id="userBalanceSMS_table" style="width: 100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{__('numero')}}</th>
                            <th>{{ __('ref') }}</th>
                            <th>{{ __('date') }}</th>
                            <th>{{ __('Operation Designation') }}</th>
                            <th>{{ __('description') }}</th>
                            <th>{{__('Prix')}}</th>
                            <th>{{ __('Value') }}</th>
                            <th>{{ __('Balance') }}</th>
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
        $(document).on('turbolinks:load', function () {
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
                    "ajax": "<?php echo e(route('API_UserBalances', ['locale' => app()->getLocale(), 'idAmounts' => 'SMS-Balance'])); ?>",
                    "columns": [
                        {data: 'ranks', "width": "1%"},
                        {data: 'Ref'},
                        {data: 'Date'},
                        {data: 'Designation'},
                        {data: 'Description'},
                        {data: 'PrixUnitaire'},
                        {data: 'value'},
                        {data: 'balance'},
                    ],
                    "columnDefs":
                        [
                            {
                                "targets": [6],
                                render: function (data, type, row) {
                                    if (data.indexOf('+') == -1)
                                        return '<span class="badge bg-danger">' + data + '</span>';
                                    else
                                        return '<span class="badge bg-success">' + data + '</span>';

                                }
                            }],
                    "language": {"url": urlLang}
                }
            );
        });
    </script>
</div>









