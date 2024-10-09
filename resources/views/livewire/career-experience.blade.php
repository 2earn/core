<div>
    @section('title')
        {{ __('Career Experience') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Career Experience') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'career_experience_cs')->value('StringValue')])
        </div>
    </div>

</div>
