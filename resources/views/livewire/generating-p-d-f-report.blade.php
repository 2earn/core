<div>
    @section('title')
        {{ __('Generating a PDF Report') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Generating a PDF Report') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'generating_pdf_cs'])
        </div>
    </div>

</div>
