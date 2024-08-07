<div class="card mb-2 ml-4 border">
    <div class="card-header border-info fw-medium text-muted mb-0">
        {{$survey->id}} - {{$survey->name}}
        @if(auth()?->user()?->getRoleNames()->first()=="Super admin")
            <span class="ml-2 mr-2 badge btn btn-lg
        {{ $survey->status==\Core\Enum\StatusSurvey::NEW->value ? 'btn-primary' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::OPEN->value ? 'btn-success' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::CLOSED->value ? 'btn-warning' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::ARCHIVED->value ? 'btn-dark' : ''  }}
        ">
                {{ \Core\Enum\StatusSurvey::tryFrom($survey->status)->name}}
                        </span>
        @endif
    </div>
    @if(auth()?->user()?->getRoleNames()->first()=="Super admin")
        <div class="card-body">
            <span class="badge btn {{ $survey->enabled ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Enabled')}}
                        </span>
            <span class="badge btn {{ $survey->published ? 'btn-success' : 'btn-danger'  }}">
                            {{__('published')}}
                        </span>
            <span class="badge btn {{ $survey->updatable ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Updatable')}}
                        </span>
            <span class="badge btn {{ $survey->showResult ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Shows result')}}
                        </span>
            <span class="badge btn {{ $survey->showAttchivementChrono ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Show attchivement Chrono')}}
                        </span>
            <span class="badge btn {{ $survey->showAttchivementPourcentage ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Show achievement %')}}
                        </span>
            <span class="badge btn {{ $survey->showAfterArchiving ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Show after archiving')}}
                        </span>
            <span class="badge btn {{ $survey->likable ? 'btn-success' : 'btn-danger'  }}">
                            {{__('likable')}}
                        </span>
            <span class="badge btn {{ $survey->commentable ? 'btn-success' : 'btn-danger'  }}">
                            {{__('commentable')}}
                        </span>
        </div>
    @endif

    <div class="card-body">
        <h5 class="mt-2">{{__('Description')}}:</h5>
        <p class="card-text text-muted">
            @if(Route::currentRouteName()=="survey_show")
                {{ $survey->description}}
            @else
                {{ Str::limit($survey->description,200)}}
            @endif
        </p>
    </div>
    @if(Route::currentRouteName()=="survey_show")
        @if(auth()?->user()?->getRoleNames()->first()=="Super admin")
            <div class="card-body">
                <h5 class="mt-2">{{__('Details')}}:</h5>
                <blockquote class="blockquote mb-0">
                    <p class="card-text text-muted">
                        @if($survey->enabled)
                            @if($survey->enableDate != null && !empty($survey->enableDate))
                                <strong>{{__('Enable date')}} :</strong>
                                {{\Carbon\Carbon::parse($survey->enableDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                            @endif
                        @else
                            @if($survey->disableDate != null && !empty($survey->disableDate))
                                <strong>{{__('Disable date')}} :</strong>
                                {{\Carbon\Carbon::parse($survey->disableDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                            @endif
                        @endif
                    </p>
                    <p class="card-text text-muted">
                        @if($survey->published)
                            @if($survey->publishDate != null && !empty($survey->publishDate))
                                <strong>{{__('Publish date')}} : </strong>
                                {{\Carbon\Carbon::parse($survey->publishDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                            @endif
                        @else
                            @if($survey->unpublishDate != null && !empty($survey->unpublishDate))
                                <strong>{{__('Un publish date')}} :</strong>
                                {{\Carbon\Carbon::parse($survey->unpublishDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                            @endif
                        @endif
                    </p>
                    @if($survey->openDate != null && !empty($survey->openDate))
                        <p class="card-text text-muted">
                            <strong>{{__('Open date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->openDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </p>
                    @endif

                    @if($survey->closeDate != null && !empty($survey->closeDate))
                        <p class="card-text text-muted">
                            <strong>{{__('Close date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->closeDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </p>
                    @endif

                    @if($survey->startDate != null && !empty($survey->startDate))
                        <p class="card-text text-muted">
                            <strong>{{__('Start date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->startDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </p>
                    @endif

                    @if($survey->endDate != null && !empty($survey->endDate))
                        <p class="card-text text-muted">
                            <strong>{{__('End date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->endDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}

                        </p>
                    @endif

                    @if($survey->archivedDate != null && !empty($survey->archivedDate))
                        <p class="card-text text-muted">
                            <strong>{{__('Archived date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->archivedDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </p>

                    @endif

                    @if($survey->created_at != null && !empty($survey->created_at))
                        <p class="card-text text-muted">
                            <strong>{{__('Creation date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->created_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </p>
                    @endif

                    @if($survey->updated_at != null && !empty($survey->updated_at))
                        <p class="card-text text-muted">
                            <strong>{{__('Update date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->updated_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </p>
                    @endif

                    @if($survey->goals != null && !empty($survey->goals))
                        <p class="card-text text-muted">
                            <strong>{{__('Goals')}} :</strong>
                            {{$survey->goals?? __('Not set')}}
                        </p>
                    @endif

                </blockquote>
            </div>
        @endif
    @endif

    @if($survey->disabledBtnDescription != null && !empty($survey->disabledBtnDescriptions))
        <div class="card-body">
            <h5 class="mt-2">{{__('Disabled button description')}}:</h5>
            <blockquote class="blockquote mb-0">
                <p class="card-text text-muted">
                    @if(Route::currentRouteName()=="survey_show")
                        {{ $survey->disabledBtnDescription}}
                    @else
                        {{ Str::limit($survey->disabledBtnDescription,200)}}
                    @endif
                </p>
            </blockquote>
        </div>
    @endif
    <div class="card-footer bg-transparent">
        @if(Route::currentRouteName()!="survey_show")
            <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
               class="btn btn-soft-info material-shadow-none">{{__('Details')}}</a>
        @endif
        @if(auth()?->user()?->getRoleNames()->first()=="Super admin")
            <a href="{{route('survey_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
               class="btn btn-soft-info material-shadow-none">{{__('Edit')}}</a>

            @if($survey->status==\Core\Enum\StatusSurvey::NEW->value)
                <a wire:click="open('{{$survey->id}}')"
                   class="btn btn-soft-secondary material-shadow-none">{{__('Open')}}</a>
            @endif

            @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
                <a wire:click="close('{{$survey->id}}')"
                   class="btn btn-soft-secondary material-shadow-none">{{__('Close')}}</a>
            @endif

            @if($survey->status==\Core\Enum\StatusSurvey::CLOSED->value)
                <a wire:click="archive('{{$survey->id}}')"
                   class="btn btn-soft-secondary material-shadow-none">{{__('Archive')}}</a>
            @endif

            @if(!$survey->enabled)
                <a wire:click="enable('{{$survey->id}}')"
                   class="btn btn-soft-success material-shadow-none">{{__('Enable')}}</a>
            @else
                <a wire:click="disable('{{$survey->id}}')"
                   class="btn btn-soft-danger material-shadow-none">{{__('Disable')}}</a>
            @endif
            @if(!$survey->published)
                <a wire:click="publish('{{$survey->id}}')"
                   class="btn btn-soft-success material-shadow-none">{{__('Publish')}}</a>
            @else
                <a wire:click="unpublish('{{$survey->id}}')"
                   class="btn btn-soft-danger material-shadow-none">{{__('Un Publish')}}</a>
            @endif
        @endif

        <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
           class="btn btn-soft-info material-shadow-none">{{__('Paticipate')}}</a>

        <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
           class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</a>
    </div>
    @if(Route::currentRouteName()=="survey_show")
        <div class="card-header border-info fw-medium text-muted mb-0">
            {{__('Questions')}}
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse ($survey->questions as $question)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-7 text-info">
                                {{ $question->id }} - {{ $question->content }}
                            </div>
                            @if(auth()?->user()?->getRoleNames()->first()=="Super admin")
                                <div class="col-sm-12 col-md-6 col-lg-5">
                                    <div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('survey_question_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id,'IdQuestion'=>$question->id] )}}"
                                           class="btn btn-soft-info material-shadow-none">
                                            {{__('Edit')}}
                                        </a>
                                        <a href="{{route('survey_question_choice_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id,'idQuestion'=>$question->id] )}}"
                                           class="btn btn-soft-info material-shadow-none">
                                            {{__('Add Choice')}}
                                        </a>
                                    </div>
                                    <ul>
                                        @forelse ($question->serveyQuestions as $choice)
                                            <li class="list-group-item mt-2">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-lg-7 text-muted">
                                                        {{$choice->id}} - {{$choice->title}}
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-5">
                                                        <div class="btn-group  btn-group-sm" role="group"
                                                             aria-label="Basic example">
                                                            <a href="{{route('survey_question_choice_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id,'idQuestion'=>$question->id,'idChoice'=>$choice->id] )}}"
                                                               title="{{__('Update Choice')}}"
                                                               class="btn btn-soft-info material-shadow-none">
                                                                {{__('Update')}}
                                                            </a>
                                                            <a wire:click="removeChoice('{{$choice->id}}')"
                                                               title="{{__('Remove Choice')}}"
                                                               class="btn btn-soft-danger material-shadow-none">
                                                                {{__('Remove')}}
                                                            </a>
                                                        </div>
                                                    </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item mt-2">
                                                {{__('No Choices')}}
                                            </li>
                                        @endforelse
                                    </ul>
                                    @if($survey->questions->count()>1)
                                        <a wire:click="removeQuestion('{{$question->id}}')"
                                           class="btn btn-soft-danger material-shadow-none">
                                            {{__('Remove')}}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">{{__('No questions')}}.
                        <br>
                        <a href="{{route('survey_question_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                           class="btn btn-soft-info material-shadow-none mt-2">
                            {{__('Add')}}
                        </a>
                    </li>
                @endforelse
            </ul>
        </div>
    @endif
    @if(Route::currentRouteName()=="survey_show")
        <div class="card-header border-info fw-medium text-muted mb-0">
            {{__('Likes')}}
        </div>
        <div class="card-body">
            <ul class="list-group">

            </ul>
        </div>
    @endif
    @if(Route::currentRouteName()=="survey_show")
        <div class="card-header border-info fw-medium text-muted mb-0">
            {{__('Comments')}}
        </div>
        <div class="card-body">
            <ul class="list-group">

            </ul>
        </div>
    @endif
</div>
