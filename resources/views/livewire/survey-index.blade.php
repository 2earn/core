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
                @if($currentRouteName=="home"|| $currentRouteName=="main")
                    <div class="col-sm-12 col-md-6  col-lg-6">
                        <h6 class="card-title mb-0 flex-grow-1"> {{__('Surveys')}}</h6>
                    </div>
                @endif
                @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                    @if($currentRouteName!=="home"&& $currentRouteName!=="main")
                        <div class="col-sm-12 col-md-3  col-lg-6">
                            <a href="{{route('surveys_create_update', app()->getLocale())}}" class="btn btn-info add-btn"
                               id="create-btn">
                                {{__('Create new Survey')}}
                            </a>
                        </div>
                    @endif
                @endif
                @if($currentRouteName=="surveys_index")
                    <div class="mt-1 col-sm-12 col-md-6  col-lg-6">
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
