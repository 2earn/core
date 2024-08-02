<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Participation') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h6 class="card-title mb-0 flex-grow-1">   {{__('Surveys')}}</h6>
            </div>
        </div>
        <div class="card-body row">
            @include('livewire.survey-item', ['survey' => $survey])
        </div>
    </div>
</div>
