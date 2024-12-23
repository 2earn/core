<div>

    @section('title')
        {{ __('User guide') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('User guide') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline'=>'job_opportunity_cs'])
        </div>
    </div>
</div>
