<div class="card mb-2 ml-4 border">
    <div class="card-header border-info fw-medium text-muted mb-0">
        @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
            <span class="badge btn btn-lg float-end
        {{ $survey->status==\Core\Enum\StatusSurvey::NEW->value ? 'btn-primary' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::OPEN->value ? 'btn-success' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::CLOSED->value ? 'btn-warning' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::ARCHIVED->value ? 'btn-dark' : ''  }}
        ">
                {{ \Core\Enum\StatusSurvey::tryFrom($survey->status)->name}}
                        </span>
        @endif
        <h5> {{$survey->id}} - {{$survey->name}}</h5>
    </div>
    @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
        <div class="card-body row">
            <div class="col-sm-12 col-md-4 col-lg-2">
  <span class="badge btn {{ $survey->enabled ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Enabled')}}
                        </span>
                <span class="badge btn {{ $survey->published ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Published')}}
                        </span>
                <span class="badge btn {{ $survey->updatable ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Updatable')}}
                        </span>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                {{__('Shows result')}}:
                <span class="badge btn btn-info">{{\Core\Enum\TargetType::tryFrom($survey->showResult)?->name}}</span>
                {{__('Shows')}}:
                <span class="badge btn btn-info">{{\Core\Enum\TargetType::tryFrom($survey->show)?->name}} </span>

                {{__('Show attchivement Chrono')}}:
                <span
                    class="badge btn btn-info">{{\Core\Enum\TargetType::tryFrom($survey->showAttchivementChrono)?->name}}</span>

                {{__('Show achievement %')}}:
                <span
                    class="badge btn btn-info">{{\Core\Enum\TargetType::tryFrom($survey->showAttchivementGool)?->name}}</span>

                {{__('Show after archiving')}}:
                <span
                    class="badge btn btn-info">{{\Core\Enum\TargetType::tryFrom($survey->showAfterArchiving)?->name}}</span>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 text-right">

                {{__('Likable')}}: <span class="badge btn btn-info"> {{\Core\Enum\TargetType::tryFrom($survey->likable)?->name}}
                        </span>

                {{__('Commentable')}}: <span class="badge btn btn-info">{{\Core\Enum\TargetType::tryFrom($survey->commentable)?->name}}
            </span>
            </div>
        </div>
    @endif



    <div class="card-body">
        <div class="row">
            @if($survey->canShowAttchivementChrono())
                <div class="col-sm-12 col-md-6 col-lg-6 mt-3" title="{{ $survey->getChronoAttchivement()}} / 100">
                    <h6 class="mt-2 text-info">{{__('Attchivement Chrono Dates')}}:</h6>
                    @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
                        <div class="survey-countdown connect-page" title="{{$survey->endDate}}">
                            <div class="survey-countdown-body">
                                <div class="survey-cd survey-cd-{{$survey->id}}" id="survey-cd-{{$survey->id}}"
                                     data-start="{{$survey->startDate}}"
                                     data-end="{{$survey->endDate}}">
                                    <div class="counter timer">
                                        <h2 class="title">{{__('time remaining')}}</h2>
                                        <div class="counter-boxes">
                                            <div class="count-box">
                                                <h3 class="value day">0</h3>
                                                <span>{{__('Days')}}</span>
                                            </div>
                                            <div class="count-box">
                                                <h3 class="value hour">0</h3>
                                                <span>{{__('Hours')}}</span>
                                            </div>
                                            <div class="count-box">
                                                <h3 class="value minute">0</h3>
                                                <span>{{__('Minutes')}}</span>
                                            </div>
                                            <div class="count-box">
                                                <h3 class="value second">0</h3>
                                                <span>{{__('Seconds')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <span class="text-muted">{{$survey->getChronoAttchivement()}} %</span>
                    @endif
                </div>
            @endif
            @if($survey->canShowAttchivementGools())
                <div class="col-sm-12 col-md-6 col-lg-2 mt-3">
                    <h6 class="mt-2 text-info">{{__('Attchivement Gools')}}:</h6>
                    <p class="card-text text-muted">
                        {{ $survey->getGoolsAttchivement()}} %
                    </p>
                </div>
            @endif
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h6 class="mt-2 text-info">{{__('Description')}}:</h6>
                <p class="card-text text-muted">
                    @if($currentRouteName=="survey_show")
                        {{ $survey->description}}
                    @else
                        {{ Str::limit($survey->description,200)}}
                    @endif
                </p>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h6 class="mt-2 text-info">{{__('Target')}}:</h6>
                @if($survey->targets->isEmpty())
                    <span class="text-muted">{{ __('No target') }}</span>
                @else
                    <ul class="list-group">
                        @foreach($survey->targets as $targetItem)
                            <li class="list-group-item">
                                <a class="link-info"
                                   href="{{route('target_show',['locale'=>app()->getLocale(),'idTarget'=> $targetItem->id])}}">  {{ $targetItem->id }}
                                    - {{ $targetItem->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>


    @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
        @if(!is_null($survey->disabledResult) or !is_null($survey->disabledComment) or !is_null($survey->disabledLike))
            <div class="card-body row">
                @if($survey->disabledBtnDescription != null && !$survey->enabled)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-3">
                        <h6 class="mt-2 text-info">{{__('Disabled button description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{ $survey->disabledBtnDescription}}
                            @else
                                {{ Str::limit($survey->disabledBtnDescription,200)}}
                            @endif
                        </p>
                    </div>
                @endif
                @if($survey->disabledResult != null)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-3">
                        <h6 class="mt-2 text-info">{{__('Disabled result description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{ $survey->disabledResult}}
                            @else
                                {{ Str::limit($survey->disabledResult,200)}}
                            @endif
                        </p>
                    </div>
                @endif
                @if($survey->disabledComment != null)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-3">
                        <h6 class="mt-2 text-info">{{__('Disabled comment description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{ $survey->disabledComment}}
                            @else
                                {{ Str::limit($survey->disabledComment,200)}}
                            @endif
                        </p>
                    </div>
                @endif
                @if($survey->disabledLike != null)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-3">
                        <h6 class="mt-2 text-info">{{__('Disabled like description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{ $survey->disabledLike}}
                            @else
                                {{ Str::limit($survey->disabledLike,200)}}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        @endif
    @endif

    @if($currentRouteName=="survey_show")
        @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
            <div class="card-body">
                <h6 class="mt-2 text-info">{{__('Details')}}:</h6>
                <p class="text-muted mx-2">
                    @if($survey->enabled)
                        @if($survey->enableDate != null && !empty($survey->enableDate))
                            <strong class="text-muted">{{__('Enable date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->enableDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        @endif
                    @else
                        @if($survey->disableDate != null && !empty($survey->disableDate))
                            <strong class="text-muted">{{__('Disable date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->disableDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        @endif
                        <br>
                    @endif
                    @if($survey->published)
                        @if($survey->publishDate != null && !empty($survey->publishDate))
                            <strong class="text-muted">{{__('Publish date')}} : </strong>
                            {{\Carbon\Carbon::parse($survey->publishDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        @endif
                    @else
                        @if($survey->unpublishDate != null && !empty($survey->unpublishDate))
                            <strong class="text-muted">{{__('Un publish date')}} :</strong>
                            {{\Carbon\Carbon::parse($survey->unpublishDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        @endif
                    @endif
                    @if($survey->openDate != null && !empty($survey->openDate))
                        <strong class="text-muted">{{__('Open date')}} :</strong>
                        {{\Carbon\Carbon::parse($survey->openDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                    @endif
                    @if($survey->closeDate != null && !empty($survey->closeDate))
                        <strong class="text-muted">{{__('Close date')}} :</strong>
                        {{\Carbon\Carbon::parse($survey->closeDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                    @endif
                    @if($survey->startDate != null && !empty($survey->startDate))
                        <strong class="text-muted">{{__('Start date')}} :</strong>
                        {{\Carbon\Carbon::parse($survey->startDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                    @endif
                    @if($survey->endDate != null && !empty($survey->endDate))
                        <strong class="text-muted">{{__('End date')}} :</strong>
                        {{\Carbon\Carbon::parse($survey->endDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                    @endif
                    @if($survey->archivedDate != null && !empty($survey->archivedDate))
                        <strong class="text-muted">{{__('Archived date')}} :</strong>
                        {{\Carbon\Carbon::parse($survey->archivedDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                    @endif
                    @if($survey->created_at != null && !empty($survey->created_at))
                        <strong class="text-muted">{{__('Creation date')}} :</strong>
                        {{\Carbon\Carbon::parse($survey->created_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                    @endif
                    @if($survey->updated_at != null && !empty($survey->updated_at))
                        <strong class="text-muted">{{__('Update date')}} :</strong>
                        {{\Carbon\Carbon::parse($survey->updated_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                    @endif
                    @if($survey->goals != null && !empty($survey->goals))
                        <strong class="text-muted">{{__('Goals')}} :</strong>
                        {{$survey->goals?? __('Not set')}}
                    @endif
                </p>
            </div>
        @endif
    @endif

    @if(!in_array( $currentRouteName,['survey_results','survey_participate']))
        <div class="card-footer bg-transparent">
            @if($currentRouteName!="survey_show")
                <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                   class="btn btn-soft-info material-shadow-none">{{__('Details')}}</a>
            @endif
            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                @if(intval($survey->status)==\Core\Enum\StatusSurvey::NEW->value)
                    <a href="{{route('survey_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Edit')}}</a>
                @endif

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
                @if($survey->status<\Core\Enum\StatusSurvey::CLOSED->value)
                    @if(!$survey->enabled)
                        <a wire:click="enable('{{$survey->id}}')"
                           class="btn btn-soft-success material-shadow-none">{{__('Enable')}}</a>
                    @else
                        <button type="button" id="disableSurveyModalbtn_{{$survey->id}}"
                                class="btn btn-soft-danger material-shadow-none" data-bs-toggle="modal"
                                data-bs-target="#disableSurveyModal_{{$survey->id}}">
                            {{__('Disable')}}
                        </button>
                    @endif
                    @if(!$survey->published)
                        <a wire:click="publish('{{$survey->id}}')"
                           class="btn btn-soft-success material-shadow-none">{{__('Publish')}}</a>
                    @else
                        <a wire:click="unpublish('{{$survey->id}}')"
                           class="btn btn-soft-danger material-shadow-none">{{__('Un Publish')}}</a>
                    @endif
                @endif
            @endif
            @if(intval($survey->status)==\Core\Enum\StatusSurvey::OPEN->value && $survey->enabled)
                @if(\App\Models\SurveyResponse::isPaticipated(auth()->user()->id, $survey->id))
                    @if($survey->updatable)
                        <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                           class="btn btn-soft-info material-shadow-none">{{__('Update Participation')}}</a>
                    @endif
                @endif
                @if(! \App\Models\SurveyResponse::isPaticipated(auth()->user()->id, $survey->id))
                    <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Paticipate')}}</a>
                @endif

            @endif

            @if(intval($survey->status)>\Core\Enum\StatusSurvey::NEW->value)
                @if( $survey->canShowResult() && $survey->enabled )
                    <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</a>
                @else
                    <btn disabled class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</btn>
                @endif
            @endif

            @if(!$survey->canShowResult())
                <div class="alert alert-info mt-2" role="alert">
                    <h6 class="alert-heading">{{__('Disabled result title')}}</h6> * {{$survey->disabledResult}}
                </div>
            @endif
        </div>
    @endif
    @if($currentRouteName=="survey_show")
        <div class="card-header border-info fw-medium text-muted mb-0">
            <h5 class="mt-2 text-info">    {{__('Questions')}}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @if($survey->question)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-12 mt-2">
                                <h5 class="text-muted mx-3">{{__('Question params')}}:</h5>
                                {{__('Choice Type')}} : <span
                                    class="badge btn {{ $survey->question->selection== \Core\Enum\Selection::MULTIPLE->value ? 'btn-success' : 'btn-danger'  }}">
                            {{__('Multiple')}}                                        </span>
                                @if($survey->question->selection== \Core\Enum\Selection::MULTIPLE->value )
                                    {{__('Max Responses')}} :  <span
                                        class="badge btn btn-info"> {{$survey->question->maxResponse}}</span>
                                @endif

                                @if(!empty($survey->question->disableNote))
                                    <span class="badge btn btn-info">
                            {{__('Disable Note')}} : {{$survey->question->disableNote}}
                        </span>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-7 mt-2">
                                <h5 class="text-muted mx-3">{{__('Question statement')}}:</h5>
                                {{ $survey->question->content }}
                            </div>
                            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                <div class="col-sm-12 col-md-6 col-lg-5">
                                    <div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('survey_question_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id,'IdQuestion'=>$survey->question->id] )}}"
                                           class="btn btn-soft-info material-shadow-none">
                                            {{__('Edit')}}
                                        </a>
                                        <a href="{{route('survey_question_choice_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id,'idQuestion'=>$survey->question->id] )}}"
                                           class="btn btn-soft-info material-shadow-none">
                                            {{__('Add Choice')}}
                                        </a>
                                    </div>
                                    <ul class="mt-3">
                                        @forelse ($survey->question->serveyQuestionChoice as $choice)
                                            <li class="list-group-item mt-2">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-lg-7 text-muted"
                                                         title="{{$choice->id}}">
                                                        {{$loop->index+1}} - {{$choice->title}}
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-5">
                                                        <div class="btn-group  btn-group-sm" role="group"
                                                             aria-label="Basic example">
                                                            <a href="{{route('survey_question_choice_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id,'idQuestion'=>$survey->question->id,'idChoice'=>$choice->id] )}}"
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
                                    @if(!$survey->question)
                                        <a wire:click="removeQuestion('{{$question->id}}')"
                                           class="btn btn-soft-danger material-shadow-none">
                                            {{__('Remove')}}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </li>
                @else

                    <li class="list-group-item">{{__('No questions')}}.
                        <br>
                        <a href="{{route('survey_question_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                           title="{{__('Add Question')}}" class="btn btn-info material-shadow-none mt-2">
                            {{__('Add Question')}}
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    @endif

    @if($currentRouteName=="survey_show")
        <div class="card">
            <div class="card-header border-info fw-medium text-muted mb-0">
                <h5 class="mt-2 text-info">       {{__('Likes')}}</h5>
            </div>
            @if(!$survey->isLikable())
                <div class="alert alert-info mt-2" role="alert">
                    <h6 class="alert-heading">{{__('Disabled like title')}}</h6> * {{$survey->disabledLike}}
                </div>
            @endif
            <div class="card-body row">
                @if($like)
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <button wire:click="dislike()" class="btn btn-warning"
                                @if(!$survey->isLikable()) disabled @endif>
                            <i class="ri-thumb-down-line align-bottom me-1"></i>
                            {{__('Un - Like')}}
                        </button>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <h5 class="mx-2"><span class="text-success">{{__('Liked')}}</span></h5>
                    </div>
                @else
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <button wire:click="like()" class="btn btn-info"
                                @if(!$survey->isLikable()) disabled @endif>
                            <i class="ri-thumb-up-line align-bottom me-1"></i>
                            {{__('Like')}}
                        </button>
                    </div>
                @endif

                @if($survey->isLikable())
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <ul class="list-group">
                            @forelse ($survey->likes as $like)
                                <li class="list-group-item mt-2">
                                    {{ getUserDisplayedName($like->user->idUser)}} <span
                                        class="text-muted">{{__('at')}}: {{ $like->created_at}} </span>
                                </li>
                            @empty
                                <li class="list-group-item mt-2">
                                    {{__('No Likes')}}
                                </li>
                            @endforelse
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($currentRouteName=="survey_show" )
        <div class="card">
            <div class="card-header border-info fw-medium text-muted mb-0">
                <h5 class="mt-2 text-info"> {{__('Comments')}}</h5>
            </div>

            @if(!$survey->isCommentable())
                <div class="alert alert-info mt-2" role="alert">
                    <h6 class="alert-heading">{{__('Disabled comment title')}}</h6> * {{$survey->disabledComment}}
                </div>
            @endif
            <div class="card-body row">
                @if($survey->isCommentable())
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <ul class="list-group mb-3">
                            @forelse ($survey->comments as $comment)

                                @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME ||$comment->validated  )
                                    <li class="list-group-item mt-1">
                                        <strong class="text-muted">{{ getUserDisplayedName($comment->user->idUser)}}
                                            :</strong>
                                        <br>
                                        <span class="mx-3">{{$comment->content }}</span>
                                        @if(!$comment->validated && strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                            <button wire:click="deleteComment('{{$comment->id}}')"
                                                    class="btn btn-danger mt-3 mx-2 float-end">
                                                {{__('Delete')}}
                                            </button>
                                            <button wire:click="validateComment('{{$comment->id}}')"
                                                    class="btn btn-success mt-3 mx-2 float-end">
                                                {{__('Validate')}}
                                            </button>
                                        @endif
                                        <span class="text-muted float-end"><strong>{{__('at')}}: </strong>  {{$comment->created_at}}</span>
                                    </li>
                                @endif

                            @empty
                                <li class="list-group-item mt-2">
                                    {{__('No Comments')}}:
                                </li>
                            @endforelse
                        </ul>
                    </div>
                @endif
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h6>{{__('Add a comment')}}</h6>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <textarea class="form-control" maxlength="190" wire:model="comment" id="comment" rows="3"
                              @if(!$survey->isCommentable()) disabled @endif></textarea>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 ">
                    <button wire:click="addComment()" class="btn btn-info mt-2"
                            @if(!$survey->isCommentable()) disabled @endif>
                        {{__('Add comment')}}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div wire:ignore class="modal fade" id="disableSurveyModal_{{$survey->id}}" tabindex="-1"
         aria-labelledby="disableSurveyModal_{{$survey->id}}Label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Disable Survey')}}</h5>
                    <button type="button" class="btn btn-close close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="disableNote">{{__('Disable Note')}}</label>
                        <textarea type="text" class="form-control @error('disableNote') is-invalid @enderror"
                                  maxlength="190" id="disableNote"
                                  wire:model="disableNote"
                                  placeholder="{{__('Enter Disable Note')}}"></textarea>
                        @error('disableNote') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="disable('{{$survey->id}}')"
                            class="btn btn-primary">{{__('Disable Survey')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
