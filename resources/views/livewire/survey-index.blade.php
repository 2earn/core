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
            <div class="col-sm-12 col-md-6 col-lg-4 float-end ">
                <a href="{{route('surveys_create_update', ['locale'=> request()->route("locale")] )}}"
                   title="{{__('Create matched target Survey')}}"
                   class="btn btn-soft-success material-shadow-none mb-2">
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
