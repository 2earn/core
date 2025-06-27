<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Translate') }}
            @endslot
        @endcomponent

        <div wire:ignore.self class="modal fade modal-fullscreen" id="editTranslationModal" tabindex="-1"
             aria-labelledby="editTranslationModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTranslationModalLabel"><p
                                class="text-primary">{{__('Edit field')}} : {{$name}}</p></h5>
                        <button type="button" id="editTranslationModalClose" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form class="row">
                            <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                <label for="recipient-name" class="col-form-label"> <img
                                        src="{{Vite::asset("resources/images/flags/" . strtolower('sa') . ".svg")}}"
                                        alt="{{__('Arabe')}}" title="{{__('Arabe')}}"
                                        class="avatar-xxs m-2"></label>
                                <textarea rows="7" class="form-control" wire:model="arabicValue" maxlength="1500"
                                          required>
                            </textarea>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                <label for="message-text" class="col-form-label"> <img
                                        src="{{Vite::asset("resources/images/flags/" . strtolower('fr') . ".svg")}}"
                                        alt="{{__('Francais')}}" title="{{__('Francais')}}"
                                        class="avatar-xxs m-2"></label>
                                <textarea rows="7" class="form-control" wire:model="frenchValue" maxlength="1500"
                                          required>
                            </textarea>

                            </div>
                            <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                <label for="message-text" class="col-form-label"><img
                                        src="{{Vite::asset("resources/images/flags/" . strtolower('gb') . ".svg")}}"
                                        alt="{{__('English')}}" title="{{__('English')}}"
                                        class="avatar-xxs m-2"></label>
                                <textarea rows="7" class="form-control" wire:model="englishValue" maxlength="1500"
                                          required>
                            </textarea>

                            </div>
                            <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                <label for="message-text" class="col-form-label"><img
                                        src="{{Vite::asset("resources/images/flags/" . strtolower('tr') . ".svg")}}"
                                        alt="{{__('Turkish')}}" title="{{__('Turkish')}}"
                                        class="avatar-xxs m-2"></label>
                                <textarea rows="7" class="form-control" wire:model="turkishValue" maxlength="1500"
                                          required>
                            </textarea>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                <label for="message-text" class="col-form-label"> <img
                                        src="{{Vite::asset("resources/images/flags/" . strtolower('es') . ".svg")}}"
                                        alt="{{__('Spanish')}}" title="{{__('Spanish')}}"
                                        class="avatar-xxs m-2"></label>
                                <textarea rows="7" class="form-control" wire:model="spanishValue" maxlength="1500"
                                          required>
                            </textarea>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                <label for="message-text" class="col-form-label"> <img
                                        src="{{Vite::asset("resources/images/flags/" . strtolower('ru') . ".svg")}}"
                                        alt="{{__('Russian')}}" title="{{__('Russian')}}"
                                        class="avatar-xxs m-2"></label>
                                <textarea rows="7" class="form-control" wire:model="russianValue" maxlength="1500"
                                          required>
                            </textarea>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                <label for="message-text" class="col-form-label"> <img
                                        src="{{Vite::asset("resources/images/flags/" . strtolower('de') . ".svg")}}"
                                        alt="{{__('German')}}" title="{{__('German')}}"
                                        class="avatar-xxs m-2"></label>
                                <textarea rows="7" class="form-control" wire:model="germanValue" maxlength="1500"
                                          required>
                            </textarea>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click="saveTranslate"
                                class="btn btn-success">
                            <div wire:loading wire:target="saveTranslate">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true">
                                                </span>
                                <span class="sr-only">{{__('Loading')}}...</span>
                            </div>
                            {{__('Save translation')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading>
            <div style="display: flex;justify-content: center;
align-items: center;background-color: black;position: fixed;top: 0px;left: 0px;z-index: 9999;width: 100%;height: 100%;opacity: 0.75">
                <div class="la-ball-pulse-rise">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <div class="row card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.flash-messages')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-row">
                        <div class="btn-group material-shadow" role="group" aria-label="Basic example">
                            <a class="btn btn-outline-warning btn-label waves-effect right waves-light" type="button"
                               wire:click="PreImport('mergeToData')">
                                <i class="ri-database-2-fill label-icon align-middle fs-16 ms-2"></i>
                                {{__('Merge field To base')}}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 form-row float-end">
                        <div class="btn-group material-shadow" role="group" aria-label="Basic example">
                            <a class="btn btn-outline-success btn-label waves-effect right waves-light float-end"
                               wire:click="PreImport('databaseToFile')">
                                <i class="ri-file-2-line label-icon align-middle fs-16 ms-2"></i>
                                {{__('Database To file')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card row">
                    <div class="card-header headerTranslate">
                        <div class="row">
                            <div class="col-md-4 form-row">
                                <select wire:model.live="nbrPagibation" class="form-control col-5"
                                        id="nbrPagibation">
                                    @foreach ($nbrPagibationArray as $nbrPagibationItem)
                                        <option
                                            value="{{$nbrPagibationItem}}">{{$nbrPagibationItem}} {{__('Element per page')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-row">
                                <input type="text" class="form-control col-7"
                                       placeholder="{{__('Search')}}..."
                                       id="search"
                                       wire:model.live="search"/>
                            </div>
                            <div class="col-md-4 form-row">
                                <a class="btn btn-outline-secondary btn-label waves-effect right waves-light float-end"
                                   wire:click="PreAjout">
                                    <i class="ri-file-add-fill label-icon align-middle fs-16 ms-2"></i>
                                    {{__('Add a new')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                                <thead>
                                <tr>
                                    <th>{{__('Id')}}</th>
                                    <th class="d-none d-md-block">{{__('Translation')}}</th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($translates as $value)
                                    <tr>
                                        <td><span> {{$value->id}}</span></td>
                                        <td class="d-sm-block d-md-none">
                                            <ul class="list-group col-md-12 mb-2">
                                                <li class="list-group-item list-group-item-action list-group-item-primary">
                                                    {{$value->name}}
                                                </li>
                                            </ul>
                                        </td>
                                        <td class="d-none d-md-block text-info">
                                            <div class="row">
                                                <ul class="list-group col-md-12 mb-2">
                                                    <li class="list-group-item list-group-item-action list-group-item-primary">
                                                        {{$value->name}}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('gb') . ".svg")}}"
                                                            alt="{{__('English')}}" title="{{__('English')}}"
                                                            class="avatar-xxs m-2">
                                                        @if(!empty($value->valueEn))
                                                            <span
                                                                class="text-muted mx-1">{{ Str::limit($value->valueEn,300)}}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">{{__('Not assigned')}}</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                                <ul class="list-group col-md-6">
                                                    <li class="list-group-item">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('sa') . ".svg")}}"
                                                            alt="{{__('Arabe')}}" title="{{__('Arabe')}}"
                                                            class="avatar-xxs m-2">
                                                        @if(!empty($value->value))
                                                            <span
                                                                class="text-muted mx-1">{{ Str::limit($value->value,300)}}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">{{__('Not assigned')}}</span>
                                                        @endif

                                                    </li>
                                                    <li class="list-group-item">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('fr') . ".svg")}}"
                                                            alt="{{__('Francais')}}" title="{{__('Francais')}}"
                                                            class="avatar-xxs m-2">
                                                        @if(!empty($value->valueFr))
                                                            <span
                                                                class="text-muted mx-1">{{ Str::limit($value->valueFr,300)}}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">{{__('Not assigned')}}</span>
                                                        @endif
                                                    </li>
                                                    <li class="list-group-item">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('tr') . ".svg")}}"
                                                            alt="{{__('Turkish')}}" title="{{__('Turkish')}}"
                                                            class="avatar-xxs m-2">
                                                        @if(!empty($value->valueTr))
                                                            <span
                                                                class="text-muted mx-1">{{ Str::limit($value->valueTr,300)}}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">{{__('Not assigned')}}</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                                <ul class="list-group col-md-6">

                                                    <li class="list-group-item">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('es') . ".svg")}}"
                                                            alt="{{__('Spanish')}}" title="{{__('Spanish')}}"
                                                            class="avatar-xxs m-2">
                                                        @if(!empty($value->valueEs))
                                                            <span
                                                                class="text-muted mx-1">{{ Str::limit($value->valueEs,300)}}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">{{__('Not assigned')}}</span>
                                                        @endif
                                                    </li>
                                                    <li class="list-group-item">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('ru') . ".svg")}}"
                                                            alt="{{__('Russian')}}" title="{{__('Russian')}}"
                                                            class="avatar-xxs m-2">
                                                        @if(!empty($value->valueRu))
                                                            <span
                                                                class="text-muted mx-1">{{ Str::limit($value->valueRu,300)}}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">{{__('Not assigned')}}</span>
                                                        @endif
                                                    </li>
                                                    <li class="list-group-item">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('de') . ".svg")}}"
                                                            alt="{{__('German')}}" title="{{__('German')}}"
                                                            class="avatar-xxs m-2">
                                                        @if(!empty($value->valueDe))
                                                            <span
                                                                class="text-muted mx-1">{{ Str::limit($value->valueDe,300)}}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning">{{__('Not assigned')}}</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <a type="btn" wire:click="initTranslate({{$value->id}})"
                                               data-bs-toggle="modal" data-bs-target="#editTranslationModal"
                                               class="btn btn-soft-info  mt-1">{{__('Edit')}}
                                            </a>
                                            <a type="btn" onclick="confirmDelete({{$value->id}})"
                                               class="btn btn-soft-danger mt-1 float-end">{{__('Delete')}}
                                            </a>
                                            <hr class="my-3 text-muted">
                                            <span class="text-muted mt-4">
                                                <i class="fa-solid fa-plus mx-2"></i> {{ \Carbon\Carbon::parse($value->created_at)->format('d M Y, H:i') }}
                                            </span>
                                            <hr class="my-3 text-muted">
                                            <span class="text-muted mt-4">
                                                <i class="fa-solid fa-pen-to-square mx-2"></i> {{ \Carbon\Carbon::parse($value->updated_at)->format('d M Y, H:i') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$translates->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(idTranslate) {
            Swal.fire({
                title: '{{__('Confirm delete')}}',
                showDenyButton: true,
                confirmButtonText: '{{__('yes')}}',
                denyButtonText: '{{__('no')}}'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch('deleteTranslate', [idTranslate]);
                } else if (result.isDenied) {
                    location.reload();
                }
            })
        }

        window.addEventListener('closeModal', event => {
            $("#editTranslationModalClose").trigger('click');
        });

        window.addEventListener('PassEnter', event => {

            Swal.fire({
                title: '{{ __('Can you type') }}' + ": " + '{{ implode(' - ',str_split($defRandomNumber)) }}',
                input: 'text',
                inputAttributes: {autocapitalize: 'off'},
                showCancelButton: true,
                cancelButtonText: '{{__('Cancel')}}',
                confirmButtonText: '{{__('Confirm')}}',
            }).then((resultat) => {
                if (resultat.isConfirmed) {
                    switch (event.detail[0].ev) {
                        case 'mergeToData':
                            window.Livewire.dispatch('mergeTransaction', [resultat.value]);
                            break;
                        case 'databaseToFile':
                            window.Livewire.dispatch('databaseToFile', [resultat.value]);
                            break;
                    }
                }
                if (resultat.isDismissed) {
                    Swal.hideLoading()
                }
            })
        })

        window.addEventListener('PreAjoutTrans', event => {
            Swal.fire({
                title: '{{__('Enter field name')}}',
                input: 'text',
                allowOutsideClick: false,
                inputAttributes: {autocapitalize: 'off'},
                showCancelButton: true,
                cancelButtonText: '{{__('Cancel')}}',
                confirmButtonText: '{{__('Confirm')}}',
            }).then((resultat) => {
                if (resultat.value) {
                    window.Livewire.dispatch('AddFieldTranslate', [resultat.value]);
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            });
        });
    </script>
</div>
