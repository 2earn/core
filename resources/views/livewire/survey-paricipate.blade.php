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
    <div class="card-body row">
        @if($survey->question)
            <div class="card">
                <form wire:submit="participate()">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                                                       <span
                                                           class="badge btn {{ $survey->question->selection== \Core\Enum\Selection::MULTIPLE->value ? 'btn-success' : 'btn-danger'  }}">
                          {{ $survey->question->selection== \Core\Enum\Selection::MULTIPLE->value ? __('Multiple') : __('Unique')  }}                                      </span>
                                @if($survey->question->selection== \Core\Enum\Selection::MULTIPLE->value )
                                    <span class="badge btn btn-info"> {{$survey->question->maxResponse}}</span>
                                @endif
                                <h6 class="card-header border-muted mb-0 flex-grow-1">{{__('Question statement')}}
                                    :</h6>
                                {{\App\Models\TranslaleModel::getTranslation($survey->question,'content',$survey->question->content)}}
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <h6 class="card-header border-muted mx-2 flex-grow-1">                                            {{__('Response')}}
                                    :</h6>
                                <ul class="list-group">
                                    @forelse ($survey->question->serveyQuestionChoice as $choice)
                                        <li class="list-group-item mt-2">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 col-lg-12 text-muted">
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
                                                            <label class="form-check-label"
                                                                   for="flexRadio_{{$survey->question->id}}_{{$choice->id}}">
                                                                {{$loop->index+1}}
                                                                - {{\App\Models\TranslaleModel::getTranslation($choice,'title',$choice->title)}}
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
                                                            <label class="form-check-label"
                                                                   for="flexCheck_{{$survey->question->id}}_{{$choice->id}}">
                                                                {{$loop->index+1}}
                                                                - {{\App\Models\TranslaleModel::getTranslation($choice,'title',$choice->title)}}
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item mt-2">
                                            {{__('No Choices')}}
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-info add-btn float-end mb-2"
                                type="submit"
                                wire:loading.attr="disabled">
                            {{__('Participate')}}
                            <div wire:loading>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                <span class="sr-only">{{__('Loading')}}...</span>
                            </div>
                        </button>
                    </div>
                </form>
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
