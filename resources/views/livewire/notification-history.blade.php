<div>
    @section('title')
        {{ __('history') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Notification history') }}
        @endslot
    @endcomponent
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="HistoryNotificationTable"
                               class="table table-striped table-bordered"
                               style="width:100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
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
        <script type="module">
            $(document).on('turbolinks:load', function () {
                $('#HistoryNotificationTable').DataTable({
                    retrieve: true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                var that = $('#HistoryNotificationTable').DataTable();
                                $('input', this.footer()).on('keydown', function (ev) {
                                    if (ev.keyCode == 13) {
                                        that.search(this.value).draw();
                                    }
                                });
                            });
                    },
                    "processing": true,
                    search: {return: true},
                    "ajax": "{{route('api_history_notification',app()->getLocale())}}",
                    "columns": [
                        {data: 'reference'},
                        {data: 'send'},
                        {data: 'receiver'},
                        {data: 'action'},
                        {data: 'date'},
                        {data: 'type'},
                        {data: 'responce'},
                    ],
                    "language": {"url": urlLang}
                });
            });
        </script>
</div>
