<div>
    @section('title')
        {{ __('e-Business Card (EBC)') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('e-Business Card (EBC)') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'ebc_cs')->value('StringValue')])
        </div>
    </div>

</div>
