<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Surveys') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">   {{__('Surveys')}}</h6>
                <div class="float-end d-inline-flex">
                    <form class="items-center mr-2">
                        <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                        <div class="w-full">
                            <input wire:model.live="search" type="text" id="simple-search"
                                   class="form-control"
                                   placeholder="{{__('Search Survey')}}" required="">
                        </div>
                    </form>
                    <a href="{{route('survey_create_update', app()->getLocale())}}" class="btn btn-info add-btn"
                       id="create-btn">
                        <i class="ri-add-line align-bottom me-1 ml-2"></i>
                        {{__('Create new Servey')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body row ">
            @foreach($surveys as $survey)
                @include('livewire.survey-item', ['survey' => $survey])
            @endforeach
            {{ $surveys->links() }}
        </div>
    </div>
</div>
