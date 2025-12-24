<div class="container">
    <div>
        <div>
            @component('components.breadcrumb')
                @slot('title')
                    {{ __('Survey Show') }} : {{ $survey->id }}
                    - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}
                @endslot
            @endcomponent
            <div class="row">
                @include('layouts.flash-messages')
            </div>
                @include('livewire.survey-item', ['survey' => $survey])
            @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
                @vite('resources/js/surveys.js')
            @endif
        </div>
    </div>
</div>
