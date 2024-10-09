<div>
    @section('title')
        {{ __('Soft skill') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Soft skill') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => DB::table('settings')->where('ParameterName', 'soft_skills_cs')->value('StringValue')])
        </div>
    </div>

</div>
