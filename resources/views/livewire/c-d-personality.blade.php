<div>
    @section('title')
        {{ __('CD Personality') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('CD Personality') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'c_d_personality_cs')->value('StringValue')])
        </div>
    </div>

</div>
