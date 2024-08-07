<div class="card mb-2 ml-4 border">
    <div class="card-header border-info fw-medium text-muted mb-0">
        <h4> {{$target->id}} - {{$target->name}}</h4>
    </div>
    <div class="card-body">
        <h6 class="mt-2">{{__('Description')}}:</h6>
        {{$target->description}}
    </div>
    @if(auth()?->user()?->getRoleNames()->first()=="Super admin")
        <div class="card-footer row">
            <div class="col-sm-12 col-md-6 col-lg-5">
                <div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                    @if(Route::currentRouteName()!=="target_show")
                        <a href="{{route('target_show',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                           title="{{__('Show target')}}" class="btn btn-soft-info material-shadow-none">
                            {{__('Show')}}
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
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-5">
                <div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                    @if(Route::currentRouteName()=="target_show")
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
            <h5>{{ __('Conditions') }}</h5>
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
                                <span class="badge border border-primary text-primary">{{ $condition->operand }}</span>
                                <span class="text-danger">{{ $condition->operator }}</span>
                                <span class="badge border border-primary text-primary">{{ $condition->value }}</span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-5">
                                <div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="{{route('condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$condition->target_id,'idCondition'=>$condition->id] )}}"
                                       title="{{__('Edit Condition')}}" class="btn btn-soft-info material-shadow-none">
                                        {{__('Edit')}}
                                    </a>
                                </div>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a wire:click="removeCondition('{{$condition->id}}')"
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
            <h5> {{ __('Groups') }}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($target->group as $group)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12 col-md-1 col-lg-1 text-muted">
                                {{$loop->index + 1}} )
                            </div>
                            <div class="col-sm-12 col-md-5 col-lg-6 text-info">
                                <span class="text-danger">{{ $group->operator }}</span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-5">
                                <div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="{{route('group_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idCondition'=>$group->id] )}}"
                                       title="{{__('Edit Group')}}" class="btn btn-soft-info material-shadow-none">
                                        {{__('Edit')}}
                                    </a>
                                </div>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a wire:click="removeGroup('{{$group->id}}')"
                                       title="{{__('Remove Group')}}"
                                       class="btn btn-soft-danger material-shadow-none">
                                        {{__('Remove')}}
                                    </a>
                                </div>
                                <div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="{{route('group_condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idGroup'=>$group->id] )}}"
                                       title="{{__('Add Group')}}" class="btn btn-soft-info material-shadow-none">
                                        {{__('Add Condition')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
