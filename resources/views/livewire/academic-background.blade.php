<div>
    @section('title')
        {{ __('Academic Background') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Academic Background') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'academic_background_cs')->value('StringValue')])
        </div>
    </div>

</div>
