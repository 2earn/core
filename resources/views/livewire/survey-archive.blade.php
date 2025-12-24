<div class="container">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Surveys archive') }}
            @endslot
        @endcomponent
        <div class="row card">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h6 class="card-title flex-grow-1">{{__('Surveys archive list')}}</h6>
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
                </div>
            </div>
            <div class="card-body row mx-1">
                @forelse($surveys as $survey)
                    @if($survey->canShowAfterArchiving())
                        @include('livewire.survey-item', ['survey' => $survey])
                    @endif
                @empty
                    <p>{{__('No archived surveys')}}.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
