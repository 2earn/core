<div>
    @section('title')
        {{ __('Hard Skills') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Hard Skills') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'hard_skills_cs')->value('StringValue')])
        </div>
    </div>

</div>
