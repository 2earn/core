<ul class="list-group list-group-horizontal-md">
    <li class="list-group-item">
        <span class="text-info btn btn-soft-primary" title="{{$status}}">
    {{__(strtoupper(\Core\Enum\DealStatus::from($status)->name))}}
</span>
    </li>
    <li class="list-group-item">
        <span class="text-info btn btn-soft-secondary" title="{{$type}}">
    {{__(strtoupper(\Core\Enum\DealTypeEnum::from($type)->name))}}
</span>
    </li>
    <li class="list-group-item">@if($validated)
            <span class="btn btn-soft-success"> {{__('Validated')}}</span>
        @else
            <span class="btn btn-soft-warning"> {{__('Not validated')}}</span>
        @endif
    </li>
</ul>
