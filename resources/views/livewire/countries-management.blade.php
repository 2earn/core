<div class="container">
    <div>
        @section('title')
            {{ __('Countries management') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('Countries management') }}
            @endslot
        @endcomponent
        <div class="row card">
            <div class="card-body">
                <div class="table-responsive ">
                    <table
                        class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                        id="countries_table"
                        style="width: 100%">
                        <thead>
                        <tr class="head2earn">
                            <th>{{ __('idCountry') }}</th>
                            <th>{{ __('CountryName') }}</th>
                            <th>{{ __('PhoneCode') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="editCountriesModal" tabindex="" role="dialog"
             aria-labelledby="editCountriesModal">
            <div class=" modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id=" ">{{ __('Edit country') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="save" id="basic-formdd" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('CountryName') }}</label>
                                    <input type="text" wire:model="name" class="form-control" name="name"
                                           disabled>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('Phone Code') }}</label>
                                    <input type="text" class="form-control" wire:model="phonecode"
                                           name="phonecode" disabled>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('ISO') }}</label>
                                    <input type="text" wire:model="ISO" class="form-control" name="iso" disabled>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('Language') }}</label>
                                    <select class="form-control" id="langueCountrie" name=" "
                                            wire:model="langue">
                                        @foreach($allLanguage as $language)
                                            <option value="{{$language->name}}">{{$language->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-center" style="margin-top: 20px;">
                                    <button type="submit" class="btn btn-success ps-5 pe-5">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .card-header:first-child {
                border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
            }

            ::placeholder {
                color: #cbd5e0;
                opacity: 1;
                font-size: 12px;
            }

            input {
                border: 1px solid #cbd5e0;
            }
        </style>
        <script type="module">
            function getEditCountrie(id) {
                window.Livewire.dispatch('initCountrie', [id]);
            }

            $("#editCountriesModal").on('hidden.bs.modal', function () {
                window.location.href = "{{ route('countries_management', app()->getLocale())}}";
            });
            document.addEventListener("DOMContentLoaded", function () {

                $('#countries_table').DataTable(
                    {
                        ordering: true,
                        retrieve: true,
                        searching: false,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        "order": [[1, 'desc']],
                        "processing": true,
                        "serverSide": true,
                        "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                        search: {return: false},
                        "ajax": {
                            url: "{{route('api_countries',['locale'=> app()->getLocale()])}}",
                            type: "GET",
                            headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                            error: function (xhr, error, thrown) {
                                loadDatatableModalError('countries_table')
                            }                        },
                        "columns": [
                            {"data": "id"},
                            {"data": "name"},
                            {"data": "phonecode"},
                            {"data": "langage"},
                            {"data": "action"},
                        ],
                        "language": {"url": urlLang}
                    }
                );
            });
        </script>
    </div>
</div>
