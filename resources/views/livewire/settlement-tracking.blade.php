<div class="container-fluid">
    @section('title')
        {{ __('Settlement tracking') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Settlement tracking') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'settlement_tracking_cs'])
        </div>
    </div>
</div>
