<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Translate') }}
        @endslot
    @endcomponent
    <div wire:ignore.self class="modal fade" id="editTranslationModal" tabindex="-1"
         aria-labelledby="editTranslationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTranslationModalLabel">{{__('Edit field')}} : </h5>
                    <button type="button" id="editTranslationModalClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-primary">
                        {{$name}}
                    </p>
                    <span class="text-warning">
                        {{ __('Max char is 190! every translation item will be shrinked to 190 char.') }}
                    </span>

                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">{{__('Arabe')}}</label>
                            <textarea rows="4" class="form-control" wire:model.defer="arabicValue" maxlength="190"
                                      required>
                            </textarea>
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">{{__('Francais')}}</label>
                            <textarea rows="4" class="form-control" wire:model.defer="frenchValue" maxlength="190"
                                      required>
                            </textarea>

                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">{{__('English')}}</label>
                            <textarea rows="4" class="form-control" wire:model.defer="englishValue" maxlength="190"
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
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mt-1">
            <div class="btn-group material-shadow" role="group" aria-label="Basic example">
                <a class="btn btn-secondary btn-label waves-effect right waves-light" type="button"
                   wire:click="PreImport('arToData')">
                    <i class="ri-dashboard-2-fill label-icon align-middle fs-16 ms-2"></i>
                    {{__('Arabic field To base')}}
                </a>
                <a class="btn btn-secondary btn-label waves-effect right waves-light" type="button"
                   wire:click="PreImport('enToData')">
                    <i class="ri-dashboard-2-fill label-icon align-middle fs-16 ms-2"></i>
                    {{__('English field To base')}}
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-5 mt-1">
            <div class="btn-group material-shadow" role="group" aria-label="Basic example">
                <a class="btn btn-secondary btn-label waves-effect right waves-light" type="button"
                   wire:click="PreImport('mergeToData')">
                    <i class="ri-database-2-fill label-icon align-middle fs-16 ms-2"></i>
                    {{__('Merge field To base')}}
                </a>
                <a class="btn btn-secondary btn-label waves-effect right waves-light" type="button"
                   wire:click="PreImport('databaseToFile')">
                    <i class="ri-file-2-line label-icon align-middle fs-16 ms-2"></i>
                    {{__('Database To file')}}
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-2 mt-1">
            <div class="btn-group material-shadow" role="group" aria-label="Basic example">
                <a class="btn btn-secondary btn-label waves-effect right waves-light" type="button"
                   wire:click="PreAjout">
                    <i class="ri-file-add-fill label-icon align-middle fs-16 ms-2"></i>
                    {{__('Add a new')}}
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-2">
            @include('layouts.flash-messages')
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header headerTranslate">
                            <div class="row ">
                                <div class="col-md-6 form-row">
                                    <label for="nbrPagibation" class="col-4"> {{__('Show')}} </label>
                                    <select wire:model="nbrPagibation" class="form-control col-6"
                                            id="nbrPagibation">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-row">
                                    <label for="search" class="col-4">{{__('Search')}} </label>
                                    <input type="text" class="form-control col-6" placeholder="{{__('Search')}}..."
                                           id="search"
                                           wire:model="search"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                            <thead>
                                <tr>
                                    <th scope="Id">{{__('Id')}}</th>
                                    <th scope="key">{{__('key')}}</th>
                                    <th scope="English">{{__('English')}}</th>
                                    <th scope="Arabe">{{__('Arabe')}}</th>
                                    <th scope="Francais">{{__('Francais')}}</th>
                                    <th scope="Actions">{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($translates as $value)
                                    <tr>
                                        <td><span> {{$value->id}}</span></td>
                                        <td><span>{{$value->name}}</span></td>
                                        <td><span>{{$value->valueEn}}</span></td>
                                        <td><span> {{$value->value}}</span></td>
                                        <td><span>{{$value->valueFr}}</span></td>
                                        <td>
                                            <a type="btn" wire:click="initTranslate({{$value->id}})"
                                               data-bs-toggle="modal" data-bs-target="#editTranslationModal"
                                               class="btn btn-info">{{__('Edit')}}
                                            </a>
                                            <a type="btn" onclick="confirmDelete({{$value->id}})"
                                               class="btn btn-danger mt-1">{{__('Delete')}}
                                            </a>
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
                        window.Livewire.emit('deleteTranslate', idTranslate);
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
                    title: '{{ __('Pass') }}',
                    input: 'text',
                    inputAttributes: {autocapitalize: 'off'},
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((resultat) => {
                    if (resultat.value) {
                        switch (event.detail.ev) {
                            case 'arToData':
                                window.Livewire.emit('addArabicField', resultat.value);
                                break;
                            case 'enToData':
                                window.Livewire.emit('addEnglishField', resultat.value);
                                break;
                            case 'mergeToData':
                                window.Livewire.emit('mergeTransaction', resultat.value);
                                break;
                            case 'databaseToFile':
                                window.Livewire.emit('databaseToFile', resultat.value);
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
                    inputAttributes: {autocapitalize: 'off'},
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((resultat) => {
                    if (resultat.value) {
                        window.Livewire.emit('AddFieldTranslate', resultat.value);
                    }
                    if (resultat.isDismissed) {
                        location.reload();
                    }
                });
            });
    </script>
</div>
