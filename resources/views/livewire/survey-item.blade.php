<div class="card mb-2 ml-4 border">
    <div class="card-header border-info fw-medium text-muted mb-0">
        @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
            <span class="badge btn btn-lg float-end
        {{ $survey->status==\Core\Enum\StatusSurvey::NEW->value ? 'btn-primary' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::OPEN->value ? 'btn-success' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::CLOSED->value ? 'btn-warning' : ''  }}
        {{ $survey->status==\Core\Enum\StatusSurvey::ARCHIVED->value ? 'btn-dark' : ''  }}
        ">
                {{ __(\Core\Enum\StatusSurvey::tryFrom($survey->status)->name)}}
                        </span>
        @endif
        <h5>
            @if(in_array($currentRouteName,["survey_show","survey_participate","survey_results"]))
                <a href="{{route('surveys_index', app()->getLocale())}}" class="btn btn-info mx-2">
                    {{__('Back to surveys')}}
                </a>
            @endif
            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                {{$survey->id}} -
            @endif
            {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}
            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                <small class="mx-2">
                    <a class="link-info"
                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'name')])}}">{{__('See or update Translation')}}</a>
                </small>
            @endif
        </h5>

    </div>
    @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
        <div class="card-body row">
            <div class="col-sm-12 col-md-4 col-lg-3  mt-1">

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Enabled')}}
                        <span class="badge btn {{ $survey->enabled ? 'btn-success' : 'btn-danger'  }}">
                            {{__($survey->enabled ? 'True' : 'False')}}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Published')}}
                        <span class="badge btn {{ $survey->published ? 'btn-success' : 'btn-danger'  }}">
                            {{__($survey->published ? 'True' : 'False')}}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Updatable')}}
                        <span class="badge btn {{ $survey->updatable ? 'btn-success' : 'btn-danger'  }}">
                             {{__($survey->updatable ? 'True' : 'False')}}
                        </span>
                    </li>
                </ul>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3 mt-1">

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Shows result')}}
                        <span
                            class="badge btn btn-info">{{__(\Core\Enum\TargetType::tryFrom($survey->showResult)?->name)}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Shows')}}
                        <span
                            class="badge btn btn-info">{{__(\Core\Enum\TargetType::tryFrom($survey->show)?->name)}} </span>
                    </li>
                </ul>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3 mt-1">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Show attchivement Chrono')}} <span
                            class="badge btn btn-info">{{__(\Core\Enum\TargetType::tryFrom($survey->showAttchivementChrono)?->name)}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Show achievement %')}} <span
                            class="badge btn btn-info">{{__(\Core\Enum\TargetType::tryFrom($survey->showAttchivementGool)?->name)}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Show after archiving')}}: <span
                            class="badge btn btn-info">{{__(\Core\Enum\TargetType::tryFrom($survey->showAfterArchiving)?->name)}}</span>
                    </li>
                </ul>

            </div>
            <div class="col-sm-12 col-md-4 col-lg-3 text-right mt-1">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Likable')}} <span
                            class="badge btn btn-info"> {{__(\Core\Enum\TargetType::tryFrom($survey->likable)?->name)}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{__('Commentable')}} <span
                            class="badge btn btn-info">{{__(\Core\Enum\TargetType::tryFrom($survey->commentable)?->name)}}</span>
                    </li>
                </ul>
            </div>
        </div>
    @endif

    <div class="card-body border-top border-muted">
        <div class="row">
            @if($survey->canShowAttchivementChrono() && $survey->getChronoAttchivement()!=0)
                <div class="col-sm-12 col-md-6 col-lg-6 mt-1 " title="{{ $survey->getChronoAttchivement()}} / 100">
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
                <div class="col-sm-12 col-md-3 col-lg-2 mt-1">
                    <h6 class="mt-2 text-info">{{__('Attchivement Gools')}}:</h6>
                    <p class="card-text text-muted">
                        {{ $survey->getGoolsAttchivement()}} %
                    </p>
                </div>
            @endif
            @if($survey->goals != null && !empty($survey->goals))
                <div class="col-sm-12 col-md-3 col-lg-2 mt-1">
                    <h6 class="mt-2 text-info">{{__('Goals')}} :</h6>
                    {{$survey->goals?? __('Not set')}}
                </div>
            @endif

            <div class="col-sm-12 col-md-6 col-lg-6">
                <h6 class="mt-2 text-info">{{__('Description')}}:</h6>
                <p class="card-text text-muted">
                    @if($currentRouteName=="survey_show")
                        {{\App\Models\TranslaleModel::getTranslation($survey,'description',$survey->description)}}
                    @else
                        {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'description',$survey->description),200)}}
                    @endif
                    @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                        <br>  <a class="link-info"
                                 href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'description')])}}">{{__('See or update Translation')}}</a>
                    @endif
                </p>
            </div>

            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
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
            @endif
        </div>
    </div>

    @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
        @if(!is_null($survey->disabledResult) or !is_null($survey->disabledComment) or !is_null($survey->disabledLike) or !is_null($survey->disabledBtnDescription))
            <div class="card-body row">
                <hr class="text-muted">
                @if($survey->disabledBtnDescription != null && !$survey->enabled)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-1">
                        <h6 class="mt-2 text-info">{{__('Disabled button description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{\App\Models\TranslaleModel::getTranslation($survey,'disabledBtnDescription',$survey->disabledBtnDescription)}}
                            @else
                                {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledBtnDescription',$survey->disabledBtnDescription),200)}}
                            @endif
                        </p>
                        @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                            <br>  <a class="link-info"
                                     href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledBtnDescription')])}}">{{__('See or update Translation')}}</a>
                        @endif
                    </div>
                @endif
                @if($survey->disabledResult != null)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-1">
                        <h6 class="mt-2 text-info">{{__('Disabled result description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{\App\Models\TranslaleModel::getTranslation($survey,'disabledResult',$survey->disabledResult)}}
                            @else
                                {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledResult',$survey->disabledResult),200)}}
                            @endif
                            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                <br>  <a class="link-info"
                                         href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledResult')])}}">{{__('See or update Translation')}}</a>
                            @endif
                        </p>
                    </div>
                @endif
                @if($survey->disabledComment != null)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-3">
                        <h6 class="mt-2 text-info">{{__('Disabled comment description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{\App\Models\TranslaleModel::getTranslation($survey,'disabledComment',$survey->disabledComment)}}
                            @else
                                {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledComment',$survey->disabledComment),200)}}
                            @endif
                            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                <br>  <a class="link-info"
                                         href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledComment')])}}">{{__('See or update Translation')}}</a>
                            @endif
                        </p>
                    </div>
                @endif
                @if($survey->disabledLike != null)
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-3">
                        <h6 class="mt-2 text-info">{{__('Disabled like description')}}:</h6>
                        <p class="card-text text-muted">
                            @if($currentRouteName=="survey_show")
                                {{\App\Models\TranslaleModel::getTranslation($survey,'disabledLike',$survey->disabledLike)}}
                            @else
                                {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledLike',$survey->disabledLike),200)}}
                            @endif
                            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                <br>  <a class="link-info"
                                         href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledLike')])}}">{{__('See or update Translation')}}</a>
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
                <hr class="text-muted">
                <h6 class="mt-2 text-info">{{__('Details')}}:</h6>
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-3 text-right  mt-1">
                        <ul class="list-group">
                            @if($survey->enableDate != null && !empty($survey->enableDate))
                                @if($survey->enabled)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong class="text-muted">{{__('Enable date')}} :</strong>
                                        {{\Carbon\Carbon::parse($survey->enableDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                    </li>
                                @endif
                            @else
                                @if($survey->disableDate != null && !empty($survey->disableDate))
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong class="text-muted">{{__('Disable date')}} :</strong>
                                        {{\Carbon\Carbon::parse($survey->disableDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                    </li>
                                @endif
                            @endif

                            @if($survey->published)
                                @if($survey->publishDate != null && !empty($survey->publishDate))
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong class="text-muted">{{__('Publish date')}} : </strong>
                                        {{\Carbon\Carbon::parse($survey->publishDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                    </li>
                                @endif
                            @else
                                @if($survey->unpublishDate != null && !empty($survey->unpublishDate))
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong class="text-muted">{{__('Un publish date')}} :</strong>
                                        {{\Carbon\Carbon::parse($survey->unpublishDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 text-right  mt-1">
                        <ul class="list-group">
                            @if($survey->openDate != null && !empty($survey->openDate))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="text-muted">{{__('Open date')}} :</strong>
                                    {{\Carbon\Carbon::parse($survey->openDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                </li>
                            @endif

                            @if($survey->closeDate != null && !empty($survey->closeDate))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="text-muted">{{__('Close date')}} :</strong>
                                    {{\Carbon\Carbon::parse($survey->closeDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 text-right   mt-1">
                        <ul class="list-group">
                            @if($survey->startDate != null && !empty($survey->startDate))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="text-muted">{{__('Start date')}} :</strong>
                                    {{\Carbon\Carbon::parse($survey->startDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                </li>
                            @endif
                            @if($survey->endDate != null && !empty($survey->endDate))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="text-muted">{{__('End date')}} :</strong>
                                    {{\Carbon\Carbon::parse($survey->endDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 text-right  mt-1">
                        <ul class="list-group">
                            @if($survey->archivedDate != null && !empty($survey->archivedDate))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="text-muted">{{__('Archived date')}} :</strong>
                                    {{\Carbon\Carbon::parse($survey->archivedDate)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                </li>
                            @endif
                            @if($survey->created_at != null && !empty($survey->created_at))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="text-muted">{{__('Creation date')}} :</strong>
                                    {{\Carbon\Carbon::parse($survey->created_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                </li>
                            @endif
                            @if($survey->updated_at != null && !empty($survey->updated_at))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="text-muted">{{__('Update date')}} :</strong>
                                    {{\Carbon\Carbon::parse($survey->updated_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if(!in_array( $currentRouteName,['survey_results','survey_participate']))
        <div class="card-footer bg-transparent">
            @if($currentRouteName!="survey_show")
                <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                   class="btn btn-soft-info material-shadow-none  mt-1">{{__('Details')}}</a>
            @endif
            @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                @if(intval($survey->status)==\Core\Enum\StatusSurvey::NEW->value)
                    <a href="{{route('survey_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none mt-1">
                        {{__('Edit')}}
                    </a>
                @endif

                @if($survey->status==\Core\Enum\StatusSurvey::NEW->value)
                    <a wire:click="open('{{$survey->id}}')"
                       class="btn btn-soft-secondary material-shadow-none mt-1">
                        {{__('Open')}}
                    </a>
                @endif

                @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
                    <a wire:click="close('{{$survey->id}}')"
                       class="btn btn-soft-secondary material-shadow-none mt-1">
                        {{__('Close')}}
                    </a>
                @endif

                @if($survey->status==\Core\Enum\StatusSurvey::CLOSED->value)
                    <a wire:click="archive('{{$survey->id}}')"
                       class="btn btn-soft-secondary material-shadow-none mt-1">
                        {{__('Archive')}}
                    </a>
                @endif
                @if($survey->status<\Core\Enum\StatusSurvey::CLOSED->value)
                    @if(!$survey->enabled)
                        <a wire:click="enable('{{$survey->id}}')"
                           class="btn btn-soft-success material-shadow-none mt-1">{{__('Enable')}}</a>
                    @else
                        <button type="button" id="disableSurveyModalbtn_{{$survey->id}}"
                                class="btn btn-soft-danger material-shadow-none mt-1" data-bs-toggle="modal"
                                data-bs-target="#disableSurveyModal_{{$survey->id}}">
                            {{__('Disable')}}
                        </button>
                    @endif
                    @if(!$survey->published)
                        <a wire:click="publish('{{$survey->id}}')"
                           class="btn btn-soft-success material-shadow-none mt-1">
                            {{__('Publish')}}
                        </a>
                    @else
                        <a wire:click="unpublish('{{$survey->id}}')"
                           class="btn btn-soft-danger material-shadow-none mt-1">
                            {{__('Un Publish')}}
                        </a>
                    @endif
                    @if(!$survey->updatable)
                        <a wire:click="changeUpdatable('{{$survey->id}}')"
                           class="btn btn-soft-success material-shadow-none mt-1">{{__('Make it updatable')}}</a>
                    @else
                        <a wire:click="changeUpdatable('{{$survey->id}}')"
                           class="btn btn-soft-danger material-shadow-none mt-1">{{__('Make it not updatable')}}</a>
                    @endif
                @endif
            @endif
            @if(intval($survey->status)==\Core\Enum\StatusSurvey::OPEN->value && $survey->enabled)
                @if(\App\Models\SurveyResponse::isPaticipated(auth()->user()->id, $survey->id))
                    @if($survey->updatable)
                        <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                           class="btn btn-soft-info material-shadow-none mt-1">{{__('Update Participation')}}</a>
                    @endif
                @endif
                @if(! \App\Models\SurveyResponse::isPaticipated(auth()->user()->id, $survey->id))
                    <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none mt-1">{{__('Paticipate')}}</a>
                @endif

            @endif

            @if(intval($survey->status)>\Core\Enum\StatusSurvey::NEW->value)
                @if( $survey->canShowResult() && $survey->enabled )
                    <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none  mt-1">{{__('Show results')}}</a>
                @else
                    <btn disabled class="btn btn-soft-info material-shadow-none mt-1">{{__('Show results')}}</btn>
                @endif
            @endif

            @if(!$survey->canShowResult() )
                <div class="alert alert-info material-shadow material-shadow mt-2" role="alert">
                    <h6 class="alert-heading">{{__('Disabled result title')}}</h6> * {{$survey->disabledResult}}
                </div>
            @endif            @if($survey->canShowResult() && !$survey->enabled )
                <div class="alert alert-info  material-shadow mt-2" role="alert">
                    <h6 class="alert-heading">{{__('Disabled result title')}}</h6> * {{__('Disabled')}}
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
                            <div class="col-sm-12 col-md-12 col-lg-7 mt-3">
                                <h5 class="text-muted mx-3">{{__('Question statement')}}:</h5>
                                <figure class="mt-2 ">
                                    <blockquote class="blockquote ml-2">
                                        {{\App\Models\TranslaleModel::getTranslation($survey->question,'content',$survey->question->content)}}
                                    </blockquote>
                                </figure>

                                @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                    <a class="link-info"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey->question,'content')])}}">{{__('See or update Translation')}}</a>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-5">

                                @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME && intval($survey->status)==\Core\Enum\StatusSurvey::NEW->value)
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
                                @endif
                                <ul class="mt-3">
                                    @forelse ($survey->question->serveyQuestionChoice as $choice)
                                        <li class="list-group-item mt-2">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-7 text-muted"
                                                     title="{{$choice->id}}">
                                                    {{$loop->index+1}}
                                                    - {{\App\Models\TranslaleModel::getTranslation($choice,'title',$choice->title)}}
                                                    <br>
                                                    @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                                        <a class="link-info"
                                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($choice,'title')])}}">{{__('See or update Translation')}}</a>
                                                    @endif
                                                </div>
                                                @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME && intval($survey->status)==\Core\Enum\StatusSurvey::NEW->value)
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
                                            @endif
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
                        </div>
                    </li>
                @else
                    <li class="list-group-item">{{__('No questions')}}.
                        @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                            <br>
                            <a href="{{route('survey_question_create_update', ['locale'=> request()->route("locale"),'idSurvey'=>$survey->id] )}}"
                               title="{{__('Add Question')}}" class="btn btn-soft-info material-shadow-none mt-2">
                                {{__('Add Question')}}
                            </a>
                        @endif
                    </li>
                @endif

            </ul>
        </div>
    @endif

    @if($currentRouteName=="survey_show")
        <div class="card">
            <div class="card-header border-info fw-medium text-muted mb-0">
                <h5 class="mt-2 text-info">{{__('Likes')}} : ({{ $survey->likes->count() }})</h5>
            </div>
            @if(!$survey->isLikable())
                <div class="alert alert-info material-shadow mt-2" role="alert">
                    <h6 class="alert-heading">{{__('Disabled like title')}}</h6>
                                        * {{\App\Models\TranslaleModel::getTranslation($survey,'disabledLike',$survey->disabledLike)}}
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
                        <button wire:click="like()" class="btn btn-soft-info"
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
                                <li class="list-group-item mt-2 text-muted">
                                    {{$loop->index+1}} ) {{ getUserDisplayedName($like->user->idUser)}} <span
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
                <h5 class="mt-2 text-info"> {{__('Comments')}} : ({{ $survey->comments->count() }})</h5>
            </div>

            @if(!$survey->isCommentable())
                <div class="alert alert-info material-shadow mt-2" role="alert">
                    <h6 class="alert-heading">{{__('Disabled comment title')}}</h6> * {{\App\Models\TranslaleModel::getTranslation($survey,'disabledComment',$survey->disabledComment)}}

                </div>
            @endif
            <div class="card-body row">
                @if($survey->isCommentable())
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <ul class="list-group mb-3">
                            @forelse ($survey->comments as $comment)
                                @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME ||$comment->validated||$comment->user_id ==auth()->user()->id  )
                                    <li class="list-group-item mt-2">

                                        <blockquote class="blockquote ml-2 mt-2">
                                            <span class=" text-muted mx-3">{{$comment->content }}</span>
                                        </blockquote>

                                        <span class="text-muted float-end" title="{{$loop->index+1}}"><strong
                                                class="text-muted">{{ getUserDisplayedName($comment->user->idUser)}}</strong> <strong>{{__('at')}}: </strong>  {{$comment->created_at}}</span>
                                        @if(!$comment->validated)
                                            <span
                                                class="badge badge-soft-warning float-end mx-2">{{ __('Waiting for admin approving')}}</span>
                                        @endif

                                        @if(!$comment->validated && strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
                                            <br>
                                            <button wire:click="deleteComment('{{$comment->id}}')"
                                                    class="btn btn-soft-danger mt-3 mx-2 float-end">
                                                {{__('Delete')}}
                                            </button>
                                            <button wire:click="validateComment('{{$comment->id}}')"
                                                    class="btn btn-soft-success mt-3 mx-2 float-end">
                                                {{__('Validate')}}
                                            </button>
                                        @endif
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
                    <button wire:click="addComment()" class="btn btn-soft-info mt-2"
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>

                </div>
            </div>
        </div>
    </div>
</div>
