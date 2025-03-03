@if(count($commissions))
    <div class="col-md-12 text-muted">
        <div class="card mt-2">
            <div class="card-header">
                <h6 class="card-title mb-0">{{__('Commission break down')}}</h6>
            </div>
            <div class="card-body">
                <table class="table table-border table-striped table-card table-nowrap">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{__('Generals')}}</th>
                        <th scope="col">{{__('Turnover')}}</th>
                        <th scope="col">{{__('Purchase value')}}</th>
                        <th scope="col">{{__('Commission')}}</th>
                        <th scope="col">{{__('Additional')}}</th>
                        <th scope="col">{{__('Camembert')}}</th>
                        <th scope="col">{{__('Camembert parts')}}</th>
                        <th scope="col">{{__('Created at')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($commissions as $key => $commission)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>
                            <span title="{{__('Commission brackdown Status')}}" class="badge
                             @if($commission->type->value==\Core\Enum\CommissionTypeEnum::IN->value)
                             badge-outline-primary
                             @elseif($commission->type->value==\Core\Enum\CommissionTypeEnum::OUT->value)
                          badge-outline-secondary
                             @else
                          badge-outline-warning
                             @endif
                             ">
                                {{__(\Core\Enum\CommissionTypeEnum::tryFrom($commission->type->value)->name)}}
                            </span>
                                @if($commission->trigger)
                                    <hr>
                                    <span class="badge badge-outline-danger"
                                          title="{{__('Order trigger')}}">{{$commission->trigger}}</span>
                                @endif
                            </td>
                            <td>
                                @if($commission->type->value!==\Core\Enum\CommissionTypeEnum::OUT->value)
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <strong>{{__('Old')}}</strong>
                                            <span
                                                class="badge badge-outline-info float-end"> {{$commission->old_turnover}} {{config('app.currency')}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>{{__('New')}}</strong>
                                            <span
                                                class="badge badge-outline-info float-end"> {{$commission->new_turnover}} {{config('app.currency')}}</span>
                                        </li>
                                    </ul>
                                @else
                                    <div class="alert alert-light material-shadow" role="alert">
                                        {{__('No data')}}
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{$commission->purchase_value}}  {{config('app.currency')}}
                            </td>
                            <td>
                                {{$commission->commission_value}} {{config('app.currency')}}
                                <hr>
                                {{$commission->commission_percentage}} %
                            </td>
                            <td>
                                @if($commission->type->value!==\Core\Enum\CommissionTypeEnum::OUT->value)
                                    <span
                                        class="badge bg-warning text-end fs-14 float-end">{{$commission->additional_amount}}  {{config('app.currency')}}</span>
                                @else
                                    <div class="alert alert-light material-shadow" role="alert">
                                        {{__('No data')}}
                                    </div>
                                @endif


                            </td>
                            <td>
                                @if($commission->type->value!==\Core\Enum\CommissionTypeEnum::OUT->value)
                                    <span
                                        class="badge bg-success text-end fs-14 float-end">      {{$commission->camembert}} {{config('app.currency')}}</span>
                                @else
                                    <div class="alert alert-light material-shadow" role="alert">
                                        {{__('No data')}}
                                    </div>
                                @endif

                            </td>
                            <td>
                                @if($commission->type->value!==\Core\Enum\CommissionTypeEnum::OUT->value)
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-primary"
                                            title="{{__('Cash company profit')}}">
                                            <strong>{{__('Cash company profit')}}</strong>
                                            <span
                                                class="badge badge-outline-info float-end">    {{$commission->cash_company_profit}}  {{config('app.currency')}}
                                    </span>
                                        </li>
                                        <li class="list-group-item" title="{{__('Cash cashback')}}">
                                            <strong>{{__('Cash cashback')}}</strong>
                                            <span
                                                class="badge badge-outline-info float-end">  {{$commission->cash_cashback}}  {{config('app.currency')}}                                    </span>
                                        </li>
                                        <li class="list-group-item" title="{{__('Cash jackpot')}}">
                                            <strong>{{__('Cash jackpot')}}</strong>

                                            <span
                                                class="badge badge-outline-info float-end"> {{$commission->cash_jackpot}}  {{config('app.currency')}}
                                                                  </span>
                                        </li>
                                        <li class="list-group-item" title="{{__('Cash tree')}}">
                                            <strong>{{__('Cash tree')}}</strong>
                                            <span
                                                class="badge badge-outline-info float-end">     {{$commission->cash_tree}}  {{config('app.currency')}}                                    </span>
                                        </li>
                                    </ul>
                                @else
                                    <div class="alert alert-light material-shadow" role="alert">
                                        {{__('No data')}}
                                    </div>
                                @endif
                            </td>
                            <td>{{$commission->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
