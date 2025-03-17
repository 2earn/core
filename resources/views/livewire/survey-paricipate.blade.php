<div>
    @component('components.breadcrumb')
        @slot('title')
            {{__('Survey')}} > {{$survey->id}}
            - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}} > {{__('Participation')}}
        @endslot
    @endcomponent
    <div class="row">
        <div class="card">
            <div class="card-body row">
                @include('livewire.survey-item', ['survey' => $survey])

                @if($survey->question)
                    <div class="card">
                        <form wire:submit="participate()">
                            <div class="card-header border-info fw-medium text-muted mb-0">
                                <h6 class="card-title mb-0">     {{ __('Participation') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @include('layouts.flash-messages')
                                </div>
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
                                        <h6 class="card-header border-muted mb-0 flex-grow-1">                                            {{__('Response')}}
                                            :</h6>
                                        <ul class="list-group">
                                            @forelse ($survey->question->serveyQuestionChoice as $choice)
                                                <li class="list-group-item mt-2">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-lg-6 text-muted">
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
                                        type="submit">
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
        </div>
        @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
            @vite('resources/js/surveys.js')
        @endif
    </div>
</div>
