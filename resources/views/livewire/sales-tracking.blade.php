<div class="container-fluid">
    @section('title')
        {{ __('Sales tracking') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Sales tracking') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'academic_background_cs'])
        </div>
    </div>
</div>
