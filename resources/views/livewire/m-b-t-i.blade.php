<div>
    @section('title')
        {{ __('Myers-Briggs Type Indicator (MBTI)') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Myers-Briggs Type Indicator (MBTI)') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'mbti_cs'])
        </div>
    </div>

</div>
