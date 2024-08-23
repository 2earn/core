<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Surveys archive') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">   {{__('Surveys')}}</h6>
                @if($currentRouteName=="surveys_index")
                    <div class="float-end mx-2">
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
                @if(strtoupper(auth()?->user()?->getRoleNames()->first())== \App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                    @if($currentRouteName!=="home" && Route::currentRouteName()!=="main"&& Route::currentRouteName()!=="surveys_archive")
                        <div class="float-end d-inline  mx-2">
                            <a href="{{route('survey_create_update', app()->getLocale())}}" class="btn btn-info add-btn"
                               id="create-btn">
                                <i class="ri-add-line align-bottom me-1 ml-2"></i>
                                {{__('Create new Survey')}}
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="card-body row mx-1">
            @forelse($surveys as $survey)
                @if($survey->canShowAfterArchiving())
                    @include('livewire.survey-item', ['survey' => $survey])
                @endif
            @empty
                <p>{{__('No Surveys')}}</p>
            @endforelse
        </div>
    </div>
</div>
