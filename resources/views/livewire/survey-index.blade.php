<div>
    @if($currentRouteName=="surveys_index")
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Surveys') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
    @endif
    @forelse($surveys as $survey)
        @include('livewire.survey-item', ['survey' => $survey])
    @empty
        <p>{{__('No Surveys')}}</p>
    @endforelse
    @vite('resources/js/surveys.js')
</div>
