@if(!is_null($createdby))
    <p>  {{getUserDisplayedName($createdby->idUser)}}

        <span class="text-muted float-end">  {{$createdby->email}}</span></p>
@else
    {{('No creator')}}
@endif

