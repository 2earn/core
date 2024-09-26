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

    @if($currentRouteName=="surveys_index")
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <a href="{{route('surveys_create_update', ['locale'=> request()->route("locale")] )}}"
                   title="{{__('Create matched target Survey')}}"
                   class="btn btn-soft-secondary material-shadow-none mb-2  float-end">
                    {{__('Create Survey')}}
                </a>
            </div>
        </div>
    @endif

    @forelse($surveys as $survey)
        @include('livewire.survey-item', ['survey' => $survey])
    @empty
        @if($currentRouteName=="surveys_index")
            <p>{{__('No Surveys')}}</p>
        @endif
    @endforelse
    @vite('resources/js/surveys.js')
</div>
