<div class="card mb-2 ml-2 border">
    <div class="card-header border-info fw-medium text-muted mb-0">
        <h4> {{$target->id}} - {{$target->name}}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-7">
                <h6 class="mt-2 text-info">{{__('Description')}}:</h6>
                {{$target->description}}
                <h6 class="mt-2 text-info">{{__('Details')}}:</h6>
                <p class="text-muted mx-2">
                    @if($target->created_at != null && !empty($target->created_at))
                        <strong>{{__('Creation date')}} :</strong>
                        {{\Carbon\Carbon::parse($target->created_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        <br>
                    @endif
                    @if($target->updated_at != null && !empty($target->updated_at))
                        <strong>{{__('Update date')}} :</strong>
                        {{\Carbon\Carbon::parse($target->updated_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        <br>
                    @endif
                </p>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-5">
                <h5 class="mt-2 text-info">{{__('Links')}}:</h5>
                <h6 class="mt-2 text-info">{{__('Surveys')}}:</h6>
                @if($target->surveys->isEmpty())
                    <span class="text-muted"> {{ __('No Surveys') }}</span>
                @else
                    <ul class="list-group">
                        @foreach($target->surveys as $surveysItem)
                            <li class="list-group-item">    {{ $surveysItem->id }} - {{ $surveysItem->name}}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
        <div class="card-body row">
            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="btn-group mt-2" role="group">
                    @if($currentRouteName!=="target_show")
                        <a href="{{route('target_show',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                           title="{{__('Show target')}}" class="btn btn-soft-info material-shadow-none">
                            {{__('Details')}}
                        </a>
                    @endif
                    <a href="{{route('target_create_update',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                       title="{{__('Edit target')}}" class="btn btn-soft-info material-shadow-none">
                        {{__('Edit')}}
                    </a>
                    <a wire:click="deleteTarget('{{$target->id}} ')"
                       title="{{__('Delete target')}}" class="btn btn-soft-danger material-shadow-none">
                        {{__('Delete')}}
                    </a>
                    <a href="{{route('survey_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$target->id] )}}"
                       title="{{__('Create matched target Survey')}}" class="btn btn-soft-primary material-shadow-none">
                        {{__('Create Survey')}}
                    </a>
                </div>
            </div>
            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="btn-group mt-2" role="group">
                    @if($currentRouteName=="target_show")
                        <a href="{{route('condition_create_update',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                           title="{{__('Add Condition')}}" class="btn btn-soft-info material-shadow-none">
                            {{__('Add Condition')}}
                        </a>
                        <a href="{{route('group_create_update',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                           title="{{__('Add Group')}}" class="btn btn-soft-info material-shadow-none">
                            {{__('Add Group ')}}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
    @if($target->condition->isNotEmpty())
        <div class="card-header border-opacity-50 fw-medium text-muted mb-0">
            <h5 class="text-info"> {{ __('Conditions details') }}:</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($target->condition as $condition)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12 col-md-1 col-lg-1 text-muted">
                                {{$loop->index + 1}} )
                            </div>
                            <div class="col-sm-12 col-md-5 col-lg-6 text-info">
                                <h4>
                                    <span
                                        class="badge border  text-primary  btn-lg">{{ $condition->operand }}</span>
                                    <span class="text-danger">{{ $condition->operator }}</span>
                                    <span
                                        class="badge border text-primary  btn-lg">{{ $condition->value }}</span>
                                </h4>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-5 mt-2">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{route('condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$condition->target_id,'idCondition'=>$condition->id] )}}"
                                       title="{{__('Edit Condition')}}" class="btn btn-soft-info material-shadow-none">
                                        {{__('Edit')}}
                                    </a>
                                </div>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a wire:click="removeCondition('{{$condition->id}}','{{$target->id}}')"
                                       title="{{__('Remove Condition')}}"
                                       class="btn btn-soft-danger material-shadow-none">
                                        {{__('Remove')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    @if($target->group->isNotEmpty())
        <div class="card-header border-opacity-50 fw-medium text-muted mb-0">
            <h5 class="text-info"> {{ __('Groups details') }}</h5>
        </div>
        <div class="card-body">
            <h6 class="text-muted"> {{ __('Groups details') }}:</h6>
            <ul class="list-group">
                @foreach($target->group as $group)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12 col-md-1 col-lg-1 text-muted">
                                {{$loop->index + 1}} )
                            </div>
                            <div class="col-sm-12 col-md-5 col-lg-6 text-info mt-2">
                                <span class="text-danger">{{ $group->operator }}</span>
                            </div>

                            @if($currentRouteName=="target_show")
                                <div class="col-sm-12 col-md-6 col-lg-5">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{route('group_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idCondition'=>$group->id] )}}"
                                           title="{{__('Edit Group')}}" class="btn btn-soft-info material-shadow-none">
                                            {{__('Edit')}}
                                        </a>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a wire:click="removeGroup('{{$group->id}}','{{$target->id}}')"
                                           title="{{__('Remove group')}}"
                                           class="btn btn-soft-danger material-shadow-none">
                                            {{__('Remove')}}
                                        </a>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{route('condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idGroup'=>$group->id] )}}"
                                           title="{{__('Add Condition')}}"
                                           class="btn btn-soft-info material-shadow-none">
                                            {{__('Add Condition')}}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if($group->condition->isNotEmpty())
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <h6 class="text-muted">{{ __('Conditions details') }}:</h6>
                                    <ul class="list-group">
                                        @foreach($group->condition as $conditionItem)
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-1 col-lg-1 text-muted">
                                                        {{$loop->index + 1}} )
                                                    </div>
                                                    <div class="col-sm-12 col-md-5 col-lg-6 text-info">
                                                        <h4>
                                                            <span  class="badge border text-primary  btn-lg">{{ $conditionItem->operand }}</span>
                                                            <span class="text-danger">{{ $conditionItem->operator }}</span>
                                                            <span class="badge border text-primary  btn-lg">{{ $conditionItem->value }}</span>
                                                        </h4>
                                                    </div>
                                                    @if($currentRouteName=="target_show")
                                                        <div class="col-sm-12 col-md-6 col-lg-5 mt-2">
                                                            <div class="btn-group btn-group-sm" role="group"
                                                            >
                                                                <a href="{{route('condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idGroup'=>$conditionItem->target_group_id,'idCondition'=>$conditionItem->id] )}}"
                                                                   title="{{__('Edit Condition')}}"
                                                                   class="btn btn-soft-info material-shadow-none">
                                                                    {{__('Edit')}}
                                                                </a>
                                                            </div>
                                                            <div class="btn-group btn-group-sm" role="group"
                                                            >
                                                                <a wire:click="removeCondition('{{$conditionItem->id}}','{{$target->id}}')"
                                                                   title="{{__('Remove Condition')}}"
                                                                   class="btn btn-soft-danger material-shadow-none">
                                                                    {{__('Remove')}}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
