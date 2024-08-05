<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Show') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-body row">
            @include('livewire.survey-item', ['survey' => $survey])
        </div>
    </div>
</div>
