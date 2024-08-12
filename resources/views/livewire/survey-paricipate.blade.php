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
            @if($survey->question)
                <div class="card">
                    <form wire:submit="participate()">
                        <div class="card-header border-info fw-medium text-muted mb-0">
                            {{ __('Participation') }}
                        </div>
                        <div class="card-body row">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <h6 class="card-header border-info mb-0 flex-grow-1">   {{__('Question statement')}}
                                        :</h6>
                                    {{ $survey->question->content }}
                                </div>
                                @if(auth()?->user()?->getRoleNames()->first()=="Super admin")
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <ul class="list-group">
                                            @forelse ($survey->question->serveyQuestions as $choice)
                                                <li class="list-group-item mt-2">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-lg-6 text-muted">
                                                            @if($survey->question->selection == \Core\Enum\Selection::UNIQUE)
                                                                <div class="form-check">
                                                                    <input
                                                                        wire:model="responces"
                                                                        class="form-check-input"
                                                                        type="radio"
                                                                        value="{{$choice->id}}"
                                                                        name="flexRadioDefault"
                                                                        id="flexRadio_{{$survey->question->id}}_{{$choice->id}}">
                                                                    <label class="form-check-label"
                                                                           for="flexRadio_{{$survey->question->id}}_{{$choice->id}}">
                                                                        {{$choice->id}} - {{$choice->title}}
                                                                    </label>
                                                                </div>
                                                            @else
                                                                <div class="form-check">
                                                                    <input
                                                                        wire:model="responces"
                                                                        class="form-check-input"
                                                                        type="checkbox"
                                                                        value="{{$choice->id}}"
                                                                        id="flexCheck_{{$survey->question->id}}_{{$choice->id}}">
                                                                    <label class="form-check-label"
                                                                           for="flexCheck_{{$survey->question->id}}_{{$choice->id}}">
                                                                        {{$choice->id}} - {{$choice->title}}
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
                                    responces : {{var_export($responces)}}
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-info add-btn float-end"
                                    type="submit">{{__('Participate')}}</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
