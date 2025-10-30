<div
    @if(in_array($currentRouteName,["surveys_show","home","surveys_results"]))
        class="container-fluid"
    @else
        class="container"
    @endif
>
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    @if($showDetail)
        @component('components.breadcrumb')
            @slot('title')
                {{__('Survey')}} > {{$survey->id}}
                - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}} > {{__('Participation')}}
            @endslot
        @endcomponent
    @endif
    @if($showDetail)
        <div class="card-body row">
            @include('livewire.survey-item', ['survey' => $survey])
        </div>
    @endif
    <div class="row">
        @if($survey->question)
            <div class="col-12">
                <div class="card border shadow-none material-shadow mb-3">
                    <form wire:submit="participate()">
                        <div class="card-header bg-light">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    <i class="ri-question-answer-line me-2 text-success"></i>
                                    {{__('Survey Question')}}
                                </h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge {{ $survey->question->selection== \Core\Enum\Selection::MULTIPLE->value ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'  }}">
                                        <i class="{{ $survey->question->selection== \Core\Enum\Selection::MULTIPLE->value ? 'ri-checkbox-multiple-line' : 'ri-radio-button-line' }} me-1"></i>
                                        {{ $survey->question->selection== \Core\Enum\Selection::MULTIPLE->value ? __('Multiple') : __('Unique')  }}
                                    </span>
                                    @if($survey->question->selection== \Core\Enum\Selection::MULTIPLE->value )
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ri-list-check me-1"></i>
                                            {{__('Max')}} {{$survey->question->maxResponse}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="alert alert-info border-0 material-shadow" role="alert">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <i class="ri-question-line fs-20"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="alert-heading mb-2">{{__('Question statement')}}</h6>
                                                <p class="mb-0 text-muted">
                                                    {{\App\Models\TranslaleModel::getTranslation($survey->question,'content',$survey->question->content)}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h6 class="mb-3">
                                        <i class="ri-list-check-2 me-2 text-primary"></i>
                                        {{__('Response')}}
                                    </h6>
                                    <div class="row g-3">
                                        @forelse ($survey->question->serveyQuestionChoice as $choice)
                                            <div class="col-12">
                                                <div class="card card-body border shadow-none bg-light mb-0">
                                                    @if($survey->question->selection == \Core\Enum\Selection::UNIQUE->value)
                                                        <div class="form-check">
                                                            <input
                                                                wire:model.live="responces"
                                                                class="form-check-input"
                                                                type="radio"
                                                                value="{{$choice->id}}"
                                                                name="flexRadioDefault"
                                                                id="flexRadio_{{$survey->question->id}}_{{$choice->id}}"
                                                            >
                                                            <label class="form-check-label fw-medium" for="flexRadio_{{$survey->question->id}}_{{$choice->id}}">
                                                                <span class="badge bg-primary-subtle text-primary me-2">{{$loop->index+1}}</span>
                                                                {{\App\Models\TranslaleModel::getTranslation($choice,'title',$choice->title)}}
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="form-check">
                                                            <input
                                                                wire:model.live="responces"
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                value="{{$choice->id}}"
                                                                id="flexCheck_{{$survey->question->id}}_{{$choice->id}}"
                                                            >
                                                            <label class="form-check-label fw-medium" for="flexCheck_{{$survey->question->id}}_{{$choice->id}}">
                                                                <span class="badge bg-primary-subtle text-primary me-2">{{$loop->index+1}}</span>
                                                                {{\App\Models\TranslaleModel::getTranslation($choice,'title',$choice->title)}}
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-warning material-shadow" role="alert">
                                                    <i class="ri-alert-line me-2"></i>
                                                    {{__('No Choices')}}
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-success waves-effect waves-light"
                                        type="submit"
                                        wire:loading.attr="disabled">
                                    <i class="ri-send-plane-fill me-1 align-bottom"></i>
                                    {{__('Participate')}}
                                    <span wire:loading class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
    @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
        @vite('resources/js/surveys.js')
    @endif

    <script type="module">
        $(function () {
            $('#appointment_form').on('submit', function () {
                $('#register').attr('disabled', 'true');
            });
        });
    </script>
</div>
