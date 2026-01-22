<div class=" text-end fs-14
     @if($ammount==\App\Enums\BalanceEnum::CASH->value)
         logoTopCashLabel
     @endif
     @if($ammount==\App\Enums\BalanceEnum::BFS->value)
         logoTopBFSLabel
     @endif
     @if($ammount==\App\Enums\BalanceEnum::DB->value)
         logoTopDBLabel
@endif
">
    <h5>
        {{__(\App\Enums\BalanceEnum::tryFrom($ammount)?->name)}}
    </h5>
</div>
