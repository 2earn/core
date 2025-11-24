<div>
    <div @class([$currentRouteName=="surveys_show" ?getContainerType():'row'])>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Survey Show') }} : {{ $survey->id }}
                - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}
            @endslot
        @endcomponent
        @include('layouts.flash-messages')
        @include('livewire.survey-item', ['survey' => $survey])
        @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
            @vite('resources/js/surveys.js')
        @endif
    </div>
</div>
