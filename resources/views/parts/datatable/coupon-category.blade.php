<span class="badge bg-info text-end fs-14">
    {{__(\Core\Enum\BalanceEnum::tryFrom($coupon->category)->name)}}
</span>

@if($coupon->category==\Core\Enum\BalanceEnum::BFS->value)
    <hr>
    <span class="badge bg-vertical-gradient text-end fs-12">
        {{$coupon->type}}
    </span>
@endif
