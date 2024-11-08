@if(!is_null($createdby))
    <p>
        {{getUserDisplayedName($createdby->idUser)}}
        <br>
        <span class="text-muted float-end">  {{$createdby->email}}</span>
    </p>
@else
    {{('No creator')}}
@endif

