<div class="container-fluid">
    @section('title')
        {{ __('Personal Characterization') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Personal Characterization') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'personal_characterization_cs'])
        </div>
    </div>

</div>
