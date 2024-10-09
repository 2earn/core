<div>
    @section('title')
        {{ __('Sensory Representation System') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Sensory Representation System') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'sensory_rep_sys_cs')->value('StringValue')])
        </div>
    </div>

</div>
