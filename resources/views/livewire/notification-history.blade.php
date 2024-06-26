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
    @section('content')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header ">
                    </div>
                    <div class="card-body table-responsive">
                        <table id="HistoryNotificationTable"
                               class="table nowrap dt-responsive align-middle table-hover table-bordered"
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
        <script>
            window.addEventListener('load', () => {
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
                        "ajax": "{{route('API_HistoryNotification',app()->getLocale())}}",
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
            });
        </script>
</div>
