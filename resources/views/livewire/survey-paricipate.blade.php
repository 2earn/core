<div>
    @component('components.breadcrumb')
        @slot('title')
            {{__('Survey')}} > {{$survey->id}} - {{$survey->name}} > {{__('Participation')}}
        @endslot
    @endcomponent
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h6 class="card-title mb-0">   {{__('Survey details')}}</h6>
                </div>
            </div>
            <div class="card-body row">
                @include('livewire.survey-item', ['survey' => $survey])
            </div>
        </div>
        @if($survey->question)
            <div class="card">
                <form wire:submit.prevent="participate()">
                    <div class="card-header border-info fw-medium text-muted mb-0">
                        <h6 class="card-title mb-0">     {{ __('Participation') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('layouts.flash-messages')
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <h6 class="card-header border-muted mb-0 flex-grow-1">                                        {{__('Question statement')}}
                                    :</h6>
                                {{ $survey->question->content }}
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
                                                                wire:model="responces"
                                                                class="form-check-input"
                                                                type="radio"
                                                                value="{{$choice->id}}"
                                                                name="flexRadioDefault"
                                                                id="flexRadio_{{$survey->question->id}}_{{$choice->id}}"
                                                            >
                                                            <label class="form-check-label"
                                                                   for="flexRadio_{{$survey->question->id}}_{{$choice->id}}">
                                                                {{$loop->index+1}} - {{$choice->title}}
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="form-check">
                                                            <input
                                                                wire:model="responces"
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                value="{{$choice->id}}"
                                                                id="flexCheck_{{$survey->question->id}}_{{$choice->id}}"
                                                            >
                                                            <label class="form-check-label"
                                                                   for="flexCheck_{{$survey->question->id}}_{{$choice->id}}">
                                                                {{$loop->index+1}} - {{$choice->title}}
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
                        <button class="btn btn-info add-btn float-end"
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
        @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
            @vite('resources/js/surveys.js')
        @endif
    </div>
</div>
