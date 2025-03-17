<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Configuration Settings') }}
        @endslot
    @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="card" data-turbolinks="false" wire:ignore>
            <div class="card-body">
                <table
                       class="table table-bordered dt-responsive nowrap table-striped align-middle"
                       id="SettingsTable">
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('id') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Value') }}</th>
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
    <div wire:ignore.self class="modal fade" id="settingModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">{{__('Update param')}}</h5>
                    <button type="button" class="btn-close btn-close-setting " data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Name') }}</label>
                                <input type="text" class="form-control" disabled wire:model.live="parameterName"
                                       placeholder="{{ __('Name') }}">
                            </div>
                            @if(!is_null($IntegerValue))
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('IntegerValue') }}</label>
                                    <input type="number" class="form-control" wire:model.live="IntegerValue"
                                           placeholder="{{ __('IntegerValue') }}" name="IntegerValue">
                                </div>
                            @endif
                            @if(!is_null($StringValue))
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('StringValue') }}</label>
                                    <input type="text" class="form-control" wire:model.live="StringValue"
                                           placeholder="{{ __('StringValue') }}" name="StringValue">
                                </div>
                            @endif
                            @if(!is_null($DecimalValue))
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2"> {{ __('DecimalValue') }}</label>
                                    <input type="number" class="form-control" wire:model.live="DecimalValue"
                                           placeholder="{{ __('DecimalValue') }}" name="DecimalValue">
                                </div>
                            @endif
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Unit') }}</label>
                                <input maxlength="5" type="text" class="form-control" wire:model.live="Unit"
                                       placeholder="{{ __('Unit') }}" name="Unit">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('I/O') }}</label>
                                <select class="form-control" name="Automatically_calculated"
                                        wire:model.live="Automatically_calculated">
                                    <option value="0"> {{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-12">
                                <label class="me-sm-2">{{ __('description') }}</label>
                                <textarea maxlength="250" class="form-control" wire:model.live="Description"
                                          placeholder="{{ __('description') }}" name="Description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="saveSetting"
                            class="btn btn-primary">{{__('Save changes')}}</button>
                    <button type="button" class="btn btn-light btn-close-setting" data-bs-dismiss="modal">{{__('Close')}}</button>
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
                    "aLengthMenu": [[10, 25, 50], [10, 25, 50]],
                    "ajax": "{{route('api_settings',app()->getLocale())}}",
                    "columns": [
                        {"data": "idSETTINGS"},
                        {"data": "ParameterName"},
                        {"data": "value"},
                        {"data": "Unit"},
                        {"data": "Automatically_calculated"},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    "language": {"url": urlLang},
                    order: [[0, 'desc']],
                    "drawCallback": function (settings, json) {
                        $(".edit-setting-btn").each(function () {
                            $(this).on("click", function () {
                                emitSetting($(this).attr('data-id'))
                            });
                        });
                    },
                }
            );
            $(".btn-close-setting").click(function () {
                location.reload();
            });
        });
    </script>
</div>








