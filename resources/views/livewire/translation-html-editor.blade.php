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

    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div wire:ignore>
                <textarea id="editor" wire:model.defer="content"></textarea>
            </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor
                .create(document.querySelector('#editor'))
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
