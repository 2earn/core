<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }
</style>
<div>
    @section('title')
        {{ __('Trading') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Trading') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => $deadline])
        </div>
    </div>

</div>
