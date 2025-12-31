@php
    $displayMessage = $message ?? '';
@endphp

@if(strlen($displayMessage) > 50)
    <span title="{{ $displayMessage }}">{{ substr($displayMessage, 0, 50) }}...</span>
@else
    {{ $displayMessage }}
@endif

