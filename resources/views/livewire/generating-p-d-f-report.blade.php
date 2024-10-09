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
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'generating_pdf_cs')->value('StringValue')])
        </div>
    </div>

</div>
