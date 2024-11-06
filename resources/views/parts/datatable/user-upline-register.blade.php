<p><strong class="ml-3">{{__('Upline Register')}}</strong>
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
</p>
<p>
    <strong class="ml-3">{{__('Upline ')}}</strong>
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
</p>
