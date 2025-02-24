@if(!is_null($businessSector))
    {{$businessSector->id}} - {{$businessSector->name}}
@else
    <div class="alert alert-link">
        {{__('No Sector')}}
    </div>
@endif
