<div class="card mb-2 ml-4 border">
    <div class="card-header border-info fw-medium text-muted mb-0">
        {{$target->id}} - {{$target->name}}
    </div>
    <div class="card-body">
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
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
