<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Show') }} : {{ $survey->id }} - {{ $survey->name }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        @include('livewire.survey-item', ['survey' => $survey])
    </div>
    @vite('resources/js/surveys.js')
</div>
