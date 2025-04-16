<div class="container-fluid">
    @section('title')
        {{ __('Successful Sharing Pool') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Successful Sharing Pool') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'successful_sharing_pool_cs'])
        </div>
    </div>
</div>
