<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('ConfigurationHA Settings') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="tab-content text-muted">
                    <div class="tab-pane active show" id="setting" role="tabpanel">
                        <div wire:ignore class="card-body">
                            <div id="customerList">
                                <div class="row ">
                                    <div class="col-sm-auto">

                                    </div>
                                    <div class="table-responsive table-card mt-3 mb-1">
                                        <table
                                            class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                            id="SettingsTable" style="width: 100%">
                                            <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('IntegerValue') }}</th>
                                                <th>{{ __('StringValue') }}</th>
                                                <th>{{ __('DecimalValue') }}</th>
                                                <th>{{ __('Unit') }}</th>
                                                <th>{{ __('AutoCalculated') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="list form-check-all">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="settingModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">{{__('Add setting')}}</h5>
                    <button type="button" class="btn-close btn-close-setting " data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Name') }}</label>
                                <input type="text" class="form-control" wire:model="parameterName"
                                       placeholder="{{ __('Name') }}">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('IntegerValue') }}</label>
                                <input type="number" class="form-control" wire:model="IntegerValue"
                                       placeholder="{{ __('IntegerValue') }}" name="IntegerValue">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('StringValue') }}</label>
                                <input type="text" class="form-control" wire:model="StringValue"
                                       placeholder="{{ __('StringValue') }}" name="StringValue">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2"> {{ __('DecimalValue') }}</label>
                                <input type="number" class="form-control" wire:model="DecimalValue"
                                       placeholder="{{ __('DecimalValue') }}" name="DecimalValue">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Unit') }}</label>
                                <input maxlength="5" type="text" class="form-control" wire:model="Unit"
                                       placeholder="{{ __('Unit') }}" name="Unit">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('I/O') }}</label>
                                <select class="form-control" name="Automatically_calculated"
                                        wire:model="Automatically_calculated">
                                    <option value="0"> {{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-12">
                                <label class="me-sm-2">{{ __('description') }}</label>
                                <textarea maxlength="250" class="form-control" wire:model="Description"
                                          placeholder="{{ __('description') }}" name="Description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" wire:click="saveSetting"
                            class="btn btn-primary">{{__('Save changes')}}</button>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        function emitSetting(idSetting) {
            if (idSetting) {
                window.Livewire.emit('initSettingFunction', idSetting);
            }
        }

        $(document).on('turbolinks:load', function () {
            $('#SettingsTable').DataTable(
                {
                    retrieve: true,
                    "colReorder": false,
                    "orderCellsTop": false,
                    "fixedHeader": true,
                    search: {return: true},
                    "processing": true,
                    "aLengthMenu": [[5, 30, 50], [5, 30, 50]],
                    "ajax": "{{route('API_settings',app()->getLocale())}}",
                    "columns": [
                        {"data": "ParameterName"},
                        {"data": "IntegerValue"},
                        {"data": "StringValue"},
                        {"data": "DecimalValue"},
                        {"data": "Unit"},
                        {"data": "Automatically_calculated"},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    "language": {"url": urlLang},
                    "drawCallback": function (settings, json) {
                        $(".edit-setting-btn").each(function () {
                            $(this).on("click", function () {
                                emitSetting($(this).attr('data-id'))
                            });
                        });
                    },
                }
            );

            window.addEventListener('closeModal', event => {
                $('.btn-close-setting').trigger('click');
                $('#SettingsTable').DataTable().ajax.reload();
            });
        });
    </script>
</div>








