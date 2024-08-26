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
    <div class="row card">
        <div class="card-header border-info">
            <div class="row">
                @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                    @if($currentRouteName!=="home"&&Route::currentRouteName()!=="main")
                        <div class="col-sm-12 col-md-3  col-lg-6  mt-1 mx-2">
                            <a href="{{route('survey_create_update', app()->getLocale())}}" class="btn btn-info add-btn"
                               id="create-btn">
                                <i class="ri-add-line align-bottom me-1 ml-2"></i>
                                {{__('Create new Survey')}}
                            </a>
                        </div>
                    @endif
                @endif
                @if($currentRouteName=="surveys_index")
                    <div class="float-end mt-1 col-sm-12 col-md-6  col-lg-5  mx-2">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control"
                                       placeholder="{{__('Search Survey')}}">
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body row mx-1">
            @forelse($surveys as $survey)
                @include('livewire.survey-item', ['survey' => $survey])
            @empty
                <p>{{__('No Surveys')}}</p>
            @endforelse
        </div>
        @vite('resources/js/surveys.js')
    </div>
</div>
