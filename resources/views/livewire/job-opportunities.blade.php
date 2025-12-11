<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Job Opportunities') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Job Opportunities') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12 card">
            <div class="card-body">
                @livewire('page-timer', ['deadline'=>'job_opportunity_cs'])
            </div>
        </div>
    </div>
</div>
