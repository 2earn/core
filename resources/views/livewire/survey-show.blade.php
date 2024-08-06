<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Show') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
            @include('livewire.survey-item', ['survey' => $survey])
    </div>
</div>
