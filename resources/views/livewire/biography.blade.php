<div class="container">
    @section('title')
        {{ __('Biography') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Biography') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'career_experience_cs'])
        </div>
    </div>
</div>
