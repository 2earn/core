<div class="container-fluid">
    @section('title')
        {{ __('Job Opportunities') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Job Opportunities') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline'=>'job_opportunity_cs'])
        </div>
    </div>
</div>
