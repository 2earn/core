<ul class="list-group list-group-flush w-100">
    <li class="list-group-item">
        <i class="mdi mdi-check-bold align-middle lh-1 me-2"></i>  <strong class="ml-3">{{__('Upline Register')}}</strong>
        @if(!is_null($uplineRegister))
            <span class="text-muted float-end ml-3">
        {{getUserDisplayedName($uplineRegister->idUser)}}
    <i class="ri-separator"></i>
    {{$uplineRegister->email}}
    <i class="ri-separator"></i>
    {{$uplineRegister->mobile}}
   </span>
        @else
            <span class="text-muted float-end ml-3">
            {{__('No register upline')}}
        </span>
        @endif
    </li>
    <li class="list-group-item">
        <i class="mdi mdi-check-bold align-middle lh-1 me-2"></i>
        <strong class="ml-3">{{__('Upline')}}</strong>
        @if(!is_null($upline))
            <span class="text-muted float-end ml-3">
        {{getUserDisplayedName($upline->idUser)}}
    <i class="ri-separator"></i>
    {{$upline->email}}
    <i class="ri-separator"></i>
    {{$upline->mobile}}
   </span>
        @else
            <span class="text-muted float-end ml-3">
            {{__('No upline')}}
        </span>
        @endif
    </li>
</ul>
