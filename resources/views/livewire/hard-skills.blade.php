<div class="{{getContainerType()}}">
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
            @livewire('page-timer', ['deadline' => 'hard_skills_cs'])
        </div>
    </div>

</div>
