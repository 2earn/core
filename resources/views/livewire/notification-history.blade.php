<div class="{{getContainerType()}}">
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
        <div class="row card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <label for="notifRefSearch" class="me-2 small text-muted mb-0">{{ __('reference') }}</label>
                                    <input id="notifRefSearch" type="search" class="form-control form-control-sm" placeholder="{{ __('Search reference and press Enter') }}" style="min-width:220px;" />
                                </div>
                                <div>
                                    <button id="HistoryNotificationRefresh" class="btn btn-sm btn-outline-secondary">
                                        <i class="ri-refresh-line"></i> {{ __('Refresh') }}
                                    </button>
                                </div>
                            </div>

                            <table id="HistoryNotificationTable"
                                   class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap" aria-describedby="notification-history-caption">
                                <caption id="notification-history-caption" class="visually-hidden">{{ __('Notification history table') }}</caption>
                                <thead class="table-light">
                                <tr class="head2earn tabHeader2earn">
                                    <th>{{__('Details')}}</th>
                                    <th>{{__('reference')}}</th>
                                    <th>{{__('source')}}</th>
                                    <th>{{__('receiver')}}</th>
                                    <th>{{__('Actions')}}</th>
                                    <th>{{__('date')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('response')}}</th>
                                </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('Details')}}"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('reference')}}"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('source')}}"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('receiver')}}"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('Actions')}}"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('date')}}"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('Type')}}"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="{{__('response')}}"></th>
                                    </tr>
                                </tfoot>
                                <tbody class="body2earn">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="module">
            document.addEventListener("DOMContentLoaded", function () {

                if (!$.fn.dataTable.isDataTable('#HistoryNotificationTable')) {
                    // initialize the DataTable and keep a reference
                    var table = $('#HistoryNotificationTable').DataTable({
                        responsive: true,
                        colReorder: true,
                        orderCellsTop: true,
                        fixedHeader: true,
                        processing: true,
                        search: {return: true},
                        ajax: {
                            url: "{{route('api_history_notification',['locale'=> app()->getLocale()])}}",
                            type: "GET",
                            headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                            error: function () {
                                // show a friendly error modal when the ajax fails
                                loadDatatableModalError('HistoryNotificationTable');
                            }
                        },
                        columns: [
                            datatableControlBtn,
                            {data: 'reference'},
                            {data: 'send'},
                            {data: 'receiver'},
                            {data: 'action'},
                            {data: 'date'},
                            {data: 'type'},
                            {data: 'responce'},
                        ],
                        language: {url: urlLang},
                        initComplete: function () {
                            var api = this.api();
                            // attach enter-to-search handler for each footer input (column-specific)
                            api.columns().every(function () {
                                var column = this;
                                $('input', column.footer()).on('keydown', function (ev) {
                                    if (ev.key === 'Enter') {
                                        column.search(this.value).draw();
                                    }
                                });
                            });

                            // also wire the top short reference search box to search across all columns on Enter
                            $('#notifRefSearch').on('keydown', function (ev) {
                                if (ev.key === 'Enter') {
                                    api.search(this.value).draw();
                                }
                            });
                        }
                    });

                    // refresh button to reload ajax data
                    $('#HistoryNotificationRefresh').on('click', function () {
                        table.ajax.reload(null, false);
                    });
                }
            });
        </script>
    </div>
</div>
