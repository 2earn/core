<div class="{{getContainerType()}}">
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
            @livewire('page-timer', ['deadline'=> 'soft_skills_cs'])
        </div>
    </div>

</div>
