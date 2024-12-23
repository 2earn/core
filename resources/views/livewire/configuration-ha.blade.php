<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('ConfigurationHA') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                       id="ActionHistorysTable">
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('Name of setting') }}</th>
                        <th>{{ __('reponce') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="list form-check-all">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="HistoryActionModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="HistoryActionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="HistoryActionModalLabel">{{__('saveHA')}}</h5>
                    <button type="button" class="btn-close btn-close-ha" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('backand.Name') }}</label>
                                <input type="text" class="form-control" placeholder="name" name="ParameterName"
                                       wire:model="titleHA">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('backand.reponce') }}</label>
                                <select wire:model="reponceHA" class="form-control" name="reponce">
                                    <option value="0">{{ __('backand.sans reponce') }}</option>
                                    <option value="1">{{ __('backand.create reponce') }}</option>
                                    <option value="2">{{ __('backand.list reponce') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-12">
                                <label class="me-sm-2">{{ __('backand.list reponce') }}</label>
                                <input data-role="tagsinput" id="tags" name='tags' wire:model="list_reponceHA"
                                       class="form-control" autofocus>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" wire:click="saveHA()" class="btn btn-primary">{{__('Save changes')}}</button>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        function emitHA(idHa) {
            if (idHa) {
                window.Livewire.emit('initHaFunction', idHa);
            }
        }

        $(document).on('turbolinks:load', function () {
            $('#ActionHistorysTable').DataTable(
                {
                    retrieve: true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                if ($.fn.dataTable.isDataTable('#ActionHistorysTable')) {
                                    var that = $('#ActionHistorysTable').DataTable();
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
                    "ajax": "{{route('api_action_history',app()->getLocale())}}",
                    "columns": [
                        {data: 'title'},
                        {data: 'reponce'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    "language": {"url": urlLang},
                    "drawCallback": function (settings, json) {
                        $(".edit-ha-btn").each(function () {
                            $(this).on("click", function () {
                                emitHA($(this).attr('data-id'))
                            });
                        });
                    },
                }
            );

            window.addEventListener('closeModalHA', event => {
                $('.btn-close-ha').trigger('click');
                $('#ActionHistorysTable').DataTable().ajax.reload();
            });
        });
    </script>
</div>








