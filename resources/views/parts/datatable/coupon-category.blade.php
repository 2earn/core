<span class="badge bg-info text-end fs-14">
    {{__(\App\Enums\BalanceEnum::tryFrom($coupon->category)->name)}}
</span>

@if($coupon->category==\App\Enums\BalanceEnum::BFS->value)
    <hr>
    <span class="badge bg-vertical-gradient text-end fs-12">
        {{$coupon->type}}
    </span>
@endif
