<div>


    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Show') }} : {{ $survey->id }}
            - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
            @include('livewire.survey-item', ['survey' => $survey])
        </div>
    </div>
    @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
        @vite('resources/js/surveys.js')
    @endif
</div>

