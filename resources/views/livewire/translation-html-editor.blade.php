<div class="container-fluid">
    @section('title')
        {{ __('Html translation') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Html translation') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <ul class="list-group list-group-horizontal-md">
                <li class="list-group-item">
                    <a
                            href="{{\App\Models\TranslaleModel::getLink($this->translateModel->name)}}">
                                                                            <span
                                                                                    class="text-info">{{__('Go to the')}} </span>
                    </a>
                </li>
                <li class="list-group-item">
                    <img
                            src="{{Vite::asset("resources/images/flags/" . strtolower($this->flag) . ".svg")}}"
                            class="avatar-xs mx-2">
                </li>
                <li class="list-group-item">
                    {{__('Class')}} : <span
                            class="badge text-info">{{\App\Models\TranslaleModel::getClassNameFromName($this->translateModel->name)}}</span>
                </li>
                <li class="list-group-item">
                    > {{__('Property')}} : <span
                            class="badge text-info">{{\App\Models\TranslaleModel::getPropertyFromName($this->translateModel->name)}}</span>
                </li>
                <li class="list-group-item">
                    > {{__('ID')}} : <span
                            class="badge text-dark">{{\App\Models\TranslaleModel::getIdFromName($this->translateModel->name)}}</span>
                </li>
            </ul>

        </div>
        <div class="card-body">
            <div wire:ignore>
                <textarea id="editor" wire:model.defer="content"></textarea>
            </div>

            <ul class="list-group list-group-horizontal-md my-2">
                <li class="list-group-item">
                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$idT,'lang'=>'en'])}}"
                       id="add-item" class="btn btn-soft-secondary fw-medium float-end"><i
                                class="ri-globe-fill"></i> {{__('HTML')}}
                        <img
                                src="{{Vite::asset("resources/images/flags/" . strtolower('gb') . ".svg")}}"
                                alt="{{__('English')}}" title="{{__('English')}}"
                                class="avatar-xxs mx-2">
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$idT,'lang'=>'ar'])}}"
                       id="add-item" class="btn btn-soft-secondary fw-medium float-end"><i
                                class="ri-globe-fill"></i> {{__('HTML')}}
                        <img
                                src="{{Vite::asset("resources/images/flags/" . strtolower('sa') . ".svg")}}"
                                alt="{{__('Arabe')}}" title="{{__('Arabe')}}"
                                class="avatar-xxs mx-2">
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$idT,'lang'=>'fr'])}}"
                       id="add-item" class="btn btn-soft-secondary fw-medium float-end"><i
                                class="ri-globe-fill"></i> {{__('HTML')}}
                        <img
                                src="{{Vite::asset("resources/images/flags/" . strtolower('fr') . ".svg")}}"
                                alt="{{__('Francais')}}" title="{{__('Francais')}}"
                                class="avatar-xxs mx-2">
                    </a>
                </li>
                <li class="list-group-item">

                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$idT,'lang'=>'tr'])}}"
                       id="add-item" class="btn btn-soft-secondary fw-medium float-end"><i
                                class="ri-globe-fill"></i> {{__('HTML')}}
                        <img
                                src="{{Vite::asset("resources/images/flags/" . strtolower('tr') . ".svg")}}"
                                alt="{{__('Turkish')}}" title="{{__('Turkish')}}"
                                class="avatar-xxs mx-2">
                    </a>
                </li>
                <li class="list-group-item">

                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$idT,'lang'=>'es'])}}"
                       id="add-item" class="btn btn-soft-secondary fw-medium float-end"><i
                                class="ri-globe-fill"></i> {{__('HTML')}}
                        <img
                                src="{{Vite::asset("resources/images/flags/" . strtolower('es') . ".svg")}}"
                                alt="{{__('Spanish')}}" title="{{__('Spanish')}}"
                                class="avatar-xxs mx-2">
                    </a></li>
                <li class="list-group-item">
                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$idT,'lang'=>'ru'])}}"
                       id="add-item" class="btn btn-soft-secondary fw-medium float-end"><i
                                class="ri-globe-fill"></i> {{__('HTML')}}
                        <img
                                src="{{Vite::asset("resources/images/flags/" . strtolower('ru') . ".svg")}}"
                                alt="{{__('Russian')}}" title="{{__('Russian')}}"
                                class="avatar-xxs mx-2">
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{route('translate_html',['locale'=>app()->getLocale(),'id'=>$idT,'lang'=>'de'])}}"
                       id="add-item" class="btn btn-soft-secondary fw-medium float-end"><i
                                class="ri-globe-fill"></i> {{__('HTML')}}
                        <img
                                src="{{Vite::asset("resources/images/flags/" . strtolower('de') . ".svg")}}"
                                alt="{{__('German')}}" title="{{__('German')}}"
                                class="avatar-xxs mx-2">
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <button type="button" wire:click="saveTranslation()" class="btn btn-primary float-end material-shadow-none">
                {{__('Save Translation')}}
            </button>
        </div>
    </div>
    @vite(['resources/js/ckeditor.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            window.ClassicEditor
                .create(document.querySelector('#editor'), {
                    addPlugins: ['SourceEditing'], removePlugins: [
                        'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar',
                        'ImageUpload', 'MediaEmbed', 'CKFinder', 'CKFinderUploadAdapter', 'EasyImage'
                    ],
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                    @this.set('content', editor.getData())
                        ;
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
</div>
