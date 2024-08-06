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
                    <a href="{{route('target_create_update',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                       class="btn btn-soft-info material-shadow-none">
                        {{__('Edit')}}
                    </a>
                    <a wire:click="deleteTarget('{{$target->id}} ')"
                       class="btn btn-soft-danger material-shadow-none">
                        {{__('Delete')}}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
