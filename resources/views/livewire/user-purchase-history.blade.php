<div>
    @section('title')
        {{ __('history') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('history') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title mb-0 flex-grow-1">{{ __('history') }}</h6>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="userPurchase_table"
                                   class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                                   style="width:100%">
                                <thead class="table-light">
                                <tr class="head2earn  tabHeader2earn">
                                    <th>{{__('date')}}</th>
                                    <th>{{__('ref')}}</th>
                                    <th>{{__('Item')}}</th>
                                    <th>{{__('Quantity')}}</th>
                                    <th>{{__('Amout')}}</th>
                                    <th>{{__('invitation to purchase')}}</th>
                                    <th>{{__('Visit')}}</th>
                                    <th>{{__('Proactive BFS')}}</th>
                                    <th>{{__('Proactive CB')}}</th>
                                    <th>{{__('Cash back BFS')}}</th>
                                    <th>{{__('Cash back CB')}}</th>
                                    <th>{{__('Economy')}}</th>
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
            $('#userPurchase_table').DataTable({
                "ordering": false,
                retrieve: true,
                "colReorder": false,

                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            if ($.fn.dataTable.isDataTable('#countries_table')) {

                                var that = $('#userPurchase_table').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                },
                "order": [[0, 'desc']],
                "processing": true,
                "serverSide": true,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {return: true},
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('api_user_purchase', app()->getLocale() )}}",
                "columns": [
                    {data: 'DateAchat'},
                    {data: 'ReferenceAchat'},
                    {data: 'item_title'},
                    {data: 'nbrAchat'},
                    {data: 'Amout'},
                    {data: 'invitationPurshase'},
                    {data: 'visit'},
                    {data: 'PRC_BFS'},
                    {data: 'PRC_CB'},
                    {data: 'CashBack_BFS'},
                    {data: 'CashBack_CB'},
                    {data: 'Economy'}
                ],
                "language": {"url": urlLang}

            });
            $('#userPurchase_table').DataTable({
                "ordering": false,
                retrieve: true,
                "colReorder": false,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            if ($.fn.dataTable.isDataTable('#countries_table')) {
                                var that = $('#userPurchase_table').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "order": [[0, 'desc']],
                "processing": true,
                "serverSide": true,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {return: true},
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('api_user_purchase', app()->getLocale() )}}",
                "columns": [
                    {data: 'DateAchat'},
                    {data: 'ReferenceAchat'},
                    {data: 'item_title'},
                    {data: 'nbrAchat'},
                    {data: 'Amout'},
                    {data: 'invitationPurshase'},
                    {data: 'visit'},
                    {data: 'PRC_BFS'},
                    {data: 'PRC_CB'},
                    {data: 'CashBack_BFS'},
                    {data: 'CashBack_CB'},
                    {data: 'Economy'}
                ],
                "language": {"url": urlLang}
            });
        });
    </script>
</div>
