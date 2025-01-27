<div class="col-md-12">
    <div class="card mt-2">
        <div class="card-header">
            <h6 class="card-title mb-0">{{__('Commission break down')}}</h6>
        </div>
        <div class="card-body">
            <table class="table table-border table-card table-nowrap">
                <thead>
                <tr>
                    <th scope="col">{{__('Order')}}</th>
                    <th scope="col">{{__('Trigger')}}</th>
                    <th scope="col">{{__('Type')}}</th>
                    <th scope="col">{{__('Old turnover')}}</th>
                    <th scope="col">{{__('New turnover')}}</th>
                    <th scope="col">{{__('Purchase value')}}</th>
                    <th scope="col">{{__('Commission')}}</th>
                    <th scope="col">{{__('Cumulative commission')}}</th>
                    <th scope="col">{{__('Camembert')}}</th>
                    <th scope="col">{{__('Others')}}</th>
                    <th scope="col">{{__('Created at')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($commissions as $key => $commission)
                    <tr>
                        <th scope="row">{{$key+1}}</th>
                        <td>{{$commission->trigger}}</td>
                        <td>
                            <span class="badge badge-gradient-info">
                                {{__(\Core\Enum\CommissionTypeEnum::tryFrom($commission->type->value)->name)}}
                            </span>
                        </td>
                        <td>{{$commission->old_turnover}}</td>
                        <td>{{$commission->new_turnover}}</td>
                        <td>{{$commission->purchase_value}}</td>
                        <td>
                            {{$commission->commission_percentage}} %
                            <hr>
                            {{$commission->commission_value}} %
                        </td>
                        <td>
                            {{$commission->cumulative_commission}}
                            <hr>
                            {{$commission->cumulative_commission_percentage}} %
                        </td>
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-primary"> {{$commission->cash_company_profit}}</li>
                                <li class="list-group-item list-group-item-secondary"> {{$commission->cashback_proactif}}</li>
                                <li class="list-group-item list-group-item-info"> {{$commission->cash_jackpot}}</li>
                                <li class="list-group-item list-group-item-light"> {{$commission->cash_tree}}</li>
                            </ul>
                        </td>
                        <td>

                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>{{__('Earned cashback')}}</strong> <span
                                        class="badge badge-outline-info float-end">{{$commission->earned_cashback}}</span>
                                </li>
                                <li class="list-group-item">
                                    <strong>{{__('Commission_difference')}}</strong> <span
                                        class="badge badge-outline-info float-end">{{$commission->commission_difference}}</span>
                                </li>
                                <li class="list-group-item">
                                    <strong>{{__('Additional commission value')}}</strong> <span
                                        class="badge badge-outline-info float-end">{{$commission->additional_commission_value}}</span>
                                </li>
                                <li class="list-group-item">
                                    <strong>{{__('Final cashback')}}</strong> <span class="badge badge-outline-info float-end">
                                        {{$commission->final_cashback}}
                            <hr>
                            {{$commission->final_cashback_percentage}} %</span>
                                </li>
                            </ul>


                        </td>
                        <td>{{$commission->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
