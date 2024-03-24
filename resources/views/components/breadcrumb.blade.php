
@php
$bg="#464fed" ;

if(\Request::getRequestUri()=="/".app()->getLocale()."/user_balance_bfs")
    {
        $bg = '#bc34b6'  ;
    }
elseif (\Request::getRequestUri()=="/".app()->getLocale()."/user_balance_db")
{
 $bg = '#009fe3';
}
@endphp
<x-page-title bg="{{$bg}}" pageTitle="{{ $title }}">

</x-page-title>
