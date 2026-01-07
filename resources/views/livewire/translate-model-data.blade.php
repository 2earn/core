<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Translate Models') }}
        @endslot
    @endcomponent
    <div wire:ignore.self class="modal fade modal-fullscreen" id="editTranslationModal" tabindex="-1"
         aria-labelledby="editTranslationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-primary" id="editTranslationModalLabel">
                        {{__('Edit field')}} : <strong>{{$name}}</strong>
                    </h5>
                    <button type="button" id="editTranslationModalClose" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info border-0 mb-4" role="alert">
                                <i class="ri-information-line me-2"></i>
                                {{__('Edit translations for all supported languages below')}}
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="arabicValue" class="form-label d-flex align-items-center">
                                <img src="{{Vite::asset("resources/images/flags/" . strtolower('sa') . ".svg")}}"
                                     alt="{{__('Arabe')}}" title="{{__('Arabe')}}"
                                     class="avatar-xxs me-2">
                                <span class="fw-medium">{{__('Arabe')}}</span>
                            </label>
                            <textarea id="arabicValue" rows="7" class="form-control" wire:model="arabicValue"
                                      maxlength="1500" required
                                      placeholder="{{__('Enter Arabic translation')}}"></textarea>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="frenchValue" class="form-label d-flex align-items-center">
                                <img src="{{Vite::asset("resources/images/flags/" . strtolower('fr') . ".svg")}}"
                                     alt="{{__('Francais')}}" title="{{__('Francais')}}"
                                     class="avatar-xxs me-2">
                                <span class="fw-medium">{{__('Francais')}}</span>
                            </label>
                            <textarea id="frenchValue" rows="7" class="form-control" wire:model="frenchValue"
                                      maxlength="1500" required
                                      placeholder="{{__('Enter French translation')}}"></textarea>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="englishValue" class="form-label d-flex align-items-center">
                                <img src="{{Vite::asset("resources/images/flags/" . strtolower('gb') . ".svg")}}"
                                     alt="{{__('English')}}" title="{{__('English')}}"
                                     class="avatar-xxs me-2">
                                <span class="fw-medium">{{__('English')}}</span>
                            </label>
                            <textarea id="englishValue" rows="7" class="form-control" wire:model="englishValue"
                                      maxlength="1500" required
                                      placeholder="{{__('Enter English translation')}}"></textarea>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="turkishValue" class="form-label d-flex align-items-center">
                                <img src="{{Vite::asset("resources/images/flags/" . strtolower('tr') . ".svg")}}"
                                     alt="{{__('Turkish')}}" title="{{__('Turkish')}}"
                                     class="avatar-xxs me-2">
                                <span class="fw-medium">{{__('Turkish')}}</span>
                            </label>
                            <textarea id="turkishValue" rows="7" class="form-control" wire:model="turkishValue"
                                      maxlength="1500" required
                                      placeholder="{{__('Enter Turkish translation')}}"></textarea>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="spanishValue" class="form-label d-flex align-items-center">
                                <img src="{{Vite::asset("resources/images/flags/" . strtolower('es') . ".svg")}}"
                                     alt="{{__('Spanish')}}" title="{{__('Spanish')}}"
                                     class="avatar-xxs me-2">
                                <span class="fw-medium">{{__('Spanish')}}</span>
                            </label>
                            <textarea id="spanishValue" rows="7" class="form-control" wire:model="spanishValue"
                                      maxlength="1500" required
                                      placeholder="{{__('Enter Spanish translation')}}"></textarea>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="russianValue" class="form-label d-flex align-items-center">
                                <img src="{{Vite::asset("resources/images/flags/" . strtolower('ru') . ".svg")}}"
                                     alt="{{__('Russian')}}" title="{{__('Russian')}}"
                                     class="avatar-xxs me-2">
                                <span class="fw-medium">{{__('Russian')}}</span>
                            </label>
                            <textarea id="russianValue" rows="7" class="form-control" wire:model="russianValue"
                                      maxlength="1500" required
                                      placeholder="{{__('Enter Russian translation')}}"></textarea>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="germanValue" class="form-label d-flex align-items-center">
                                <img src="{{Vite::asset("resources/images/flags/" . strtolower('de') . ".svg")}}"
                                     alt="{{__('German')}}" title="{{__('German')}}"
                                     class="avatar-xxs me-2">
                                <span class="fw-medium">{{__('German')}}</span>
                            </label>
                            <textarea id="germanValue" rows="7" class="form-control" wire:model="germanValue"
                                      maxlength="1500" required
                                      placeholder="{{__('Enter German translation')}}"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>{{__('Close')}}
                    </button>
                    <button type="button" wire:click="saveTranslate" class="btn btn-success">
                            <span wire:loading wire:target="saveTranslate">
                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                      aria-hidden="true"></span>
                                {{__('Loading')}}...
                            </span>
                        <span wire:loading.remove wire:target="saveTranslate">
                                <i class="ri-save-line me-1"></i>{{__('Save translation')}}
                            </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div wire:loading>
        <div
            class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-75"
            style="z-index: 9999;">
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
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card">
            <div class="card-header bg-light">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="nbrPagibation"
                               class="visually-hidden">{{__('Elements per page')}}</label>
                        <select wire:model.live="nbrPagibation" class="form-select" id="nbrPagibation"
                                aria-label="{{__('Elements per page')}}">
                            @foreach ($nbrPagibationArray as $nbrPagibationItem)
                                <option value="{{$nbrPagibationItem}}">
                                    {{$nbrPagibationItem}} {{__('Element per page')}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="ri-search-line"></i>
                                            </span>
                            <input type="text" class="form-control"
                                   placeholder="{{__('Search')}}..."
                                   id="search"
                                   wire:model.live="search"
                                   aria-label="{{__('Search')}}"/>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-grid d-md-flex justify-content-md-end">
                            <button class="btn btn-primary btn-label waves-effect waves-light"
                                    wire:click="PreAjout">
                                <i class="ri-file-add-fill label-icon align-middle fs-16 me-2"></i>
                                {{__('Add a new')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($translates as $value)
                        <div class="col-12">
                            <div class="card border shadow-sm translate-item">
                                <div class="card-header bg-light">
                                    <div
                                        class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 text-muted small">
                                        <div>
                                            <i class="ri-add-line me-1"></i>
                                            <span>{{__('Created at')}}: {{ \Carbon\Carbon::parse($value->created_at)->format(config('app.date_format')) }}</span>
                                        </div>
                                        <div>
                                            <i class="ri-edit-2-line me-1"></i>
                                            <span>{{__('Updated at')}}: {{ \Carbon\Carbon::parse($value->updated_at)->format(config('app.date_format')) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center gap-2 flex-wrap w-100">

                                                <div class="alert alert-primary mb-0 py-1 px-3 flex-grow-1"
                                                     role="alert">
                                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                                        <a href="{{\App\Models\TranslaleModel::getLink($value->name)}}"
                                                           class="btn btn-sm btn-soft-info">
                                                            <i class="ri-external-link-line me-1"></i>{{__('Go to the')}}
                                                        </a>
                                                        <span class="badge bg-secondary-subtle text-secondary fs-12">
                                                #{{$value->id}}
                                            </span>
                                                        <span class="badge bg-info-subtle text-info">
                                                        {{__('Class')}}: {{\App\Models\TranslaleModel::getClassNameFromName($value->name)}}
                                                    </span>
                                                        <span class="badge bg-info-subtle text-info">
                                                        {{__('Property')}}: {{\App\Models\TranslaleModel::getPropertyFromName($value->name)}}
                                                    </span>
                                                        <span class="badge bg-dark-subtle text-dark">
                                                        {{__('ID')}}: {{\App\Models\TranslaleModel::getIdFromName($value->name)}}
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-3 bg-light">
                                                <div
                                                    class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('gb') . ".svg")}}"
                                                            alt="{{__('English')}}"
                                                            title="{{__('English')}}"
                                                            class="avatar-xxs me-2">
                                                        <strong class="text-muted">{{__('English')}}</strong>
                                                    </div>
                                                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$value->id,'lang'=>'en'])}}"
                                                       class="btn btn-sm btn-soft-secondary">
                                                        <i class="ri-globe-fill me-1"></i>{{__('HTML')}}
                                                    </a>
                                                </div>
                                                <p class="mb-0">{!! Str::limit($value->valueEn, 150) !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3">
                                                <div
                                                    class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('sa') . ".svg")}}"
                                                            alt="{{__('Arabe')}}" title="{{__('Arabe')}}"
                                                            class="avatar-xxs me-2">
                                                        <strong class="text-muted">{{__('Arabe')}}</strong>
                                                    </div>
                                                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$value->id,'lang'=>'ar'])}}"
                                                       class="btn btn-sm btn-soft-secondary">
                                                        <i class="ri-globe-fill"></i>
                                                    </a>
                                                </div>
                                                <p class="mb-0">{!! Str::limit($value->value, 150) !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3">
                                                <div
                                                    class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('fr') . ".svg")}}"
                                                            alt="{{__('Francais')}}"
                                                            title="{{__('Francais')}}"
                                                            class="avatar-xxs me-2">
                                                        <strong class="text-muted">{{__('Francais')}}</strong>
                                                    </div>
                                                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$value->id,'lang'=>'fr'])}}"
                                                       class="btn btn-sm btn-soft-secondary">
                                                        <i class="ri-globe-fill"></i>
                                                    </a>
                                                </div>
                                                <p class="mb-0">{!! Str::limit($value->valueFr, 150) !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3">
                                                <div
                                                    class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('tr') . ".svg")}}"
                                                            alt="{{__('Turkish')}}"
                                                            title="{{__('Turkish')}}"
                                                            class="avatar-xxs me-2">
                                                        <strong class="text-muted">{{__('Turkish')}}</strong>
                                                    </div>
                                                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$value->id,'lang'=>'tr'])}}"
                                                       class="btn btn-sm btn-soft-secondary">
                                                        <i class="ri-globe-fill"></i>
                                                    </a>
                                                </div>
                                                <p class="mb-0">{!! Str::limit($value->valueTr, 150) !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3">
                                                <div
                                                    class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('es') . ".svg")}}"
                                                            alt="{{__('Spanish')}}"
                                                            title="{{__('Spanish')}}"
                                                            class="avatar-xxs me-2">
                                                        <strong class="text-muted">{{__('Spanish')}}</strong>
                                                    </div>
                                                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$value->id,'lang'=>'es'])}}"
                                                       class="btn btn-sm btn-soft-secondary">
                                                        <i class="ri-globe-fill"></i>
                                                    </a>
                                                </div>
                                                <p class="mb-0">{!! Str::limit($value->valueEs, 150) !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3">
                                                <div
                                                    class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('ru') . ".svg")}}"
                                                            alt="{{__('Russian')}}"
                                                            title="{{__('Russian')}}"
                                                            class="avatar-xxs me-2">
                                                        <strong class="text-muted">{{__('Russian')}}</strong>
                                                    </div>
                                                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$value->id,'lang'=>'ru'])}}"
                                                       class="btn btn-sm btn-soft-secondary">
                                                        <i class="ri-globe-fill"></i>
                                                    </a>
                                                </div>
                                                <p class="mb-0">{!! Str::limit($value->valueRu, 150) !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3">
                                                <div
                                                    class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{Vite::asset("resources/images/flags/" . strtolower('de') . ".svg")}}"
                                                            alt="{{__('German')}}" title="{{__('German')}}"
                                                            class="avatar-xxs me-2">
                                                        <strong class="text-muted">{{__('German')}}</strong>
                                                    </div>
                                                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$value->id,'lang'=>'de'])}}"
                                                       class="btn btn-sm btn-soft-secondary">
                                                        <i class="ri-globe-fill"></i>
                                                    </a>
                                                </div>
                                                <p class="mb-0">{!! Str::limit($value->valueDe, 150) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

                                        <div class="d-flex gap-2 w-100 w-md-auto flex-shrink-0">
                                            <button type="button" wire:click="initTranslate({{$value->id}})"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTranslationModal"
                                                    class="btn btn-info btn-sm flex-fill flex-md-grow-0">
                                                <i class="ri-edit-line me-1"></i>{{__('Edit')}}
                                            </button>
                                            <button type="button" onclick="confirmDelete({{$value->id}})"
                                                    class="btn btn-danger btn-sm flex-fill flex-md-grow-0">
                                                <i class="ri-delete-bin-line me-1"></i>{{__('Delete')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{$translates->links()}}
                </div>
            </div>
        </div>
        {{$translates->links()}}
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

        window.addEventListener('PreAjoutTrans', event => {
            Swal.fire({
                title: '{{__('Enter field name')}}',
                input: 'text',
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
