<div class="container">
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
            <div class="col-12 card">
                <div class="card-body d-flex justify-content-end ">
                    <input wire:model.live="search" type="text" id="simple-search"
                           class="form-control"
                           placeholder="{{__('Search Survey')}}">
                    <a href="{{route('surveys_create_update', ['locale'=> app()->getLocale()] )}}"
                       title="{{__('Create matched target Survey')}}"
                       class="btn btn-soft-secondary mx-2 float-end">
                        {{__('Create Survey')}}
                    </a>
                </div>
            </div>
        </div>
    @endif

    @forelse($surveys as $survey)
        @include('livewire.survey-item', ['survey' => $survey])
    @empty
        @if($currentRouteName=="surveys_index")
            <div class="card">
                <div class="alert alert-info material-shadow m-2" role="alert">
                    {{__('No Surveys')}}
                </div>
            </div>
        @endif
    @endforelse
    @vite('resources/js/surveys.js')
</div>
