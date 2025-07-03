<div class=" text-end fs-14
     @if($ammount==\Core\Enum\BalanceEnum::CASH->value)
         logoTopCashLabel
     @endif
     @if($ammount==\Core\Enum\BalanceEnum::BFS->value)
         logoTopBFSLabel
     @endif
     @if($ammount==\Core\Enum\BalanceEnum::DB->value)
         logoTopDBLabel
@endif
">
<h5>
    {{__(Core\Enum\BalanceEnum::tryFrom($ammount)?->name)}}
</h5>
</div>
