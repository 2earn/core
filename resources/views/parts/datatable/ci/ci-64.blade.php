@php
    $balanceModel=\App\Models\DiscountBalances::find($balance->id);
@endphp

@if(!is_null($balanceModel))
    <span class="text-muted" title="{{$balanceModel->reference}}">{{__('About the Discount operation')}}:</span>
    <div data-simplebar style="max-height: 215px;">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0">{{__('Value')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="text-danger">{{formatSolde($balanceModel->value,0)}}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0">{{__('Note')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="text-success">{{$balanceModel->note}}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0">{{__('Created at')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="text-success">{{$balanceModel->created_at}}</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@else
    <span class="text-muted">{{__('No Discount operation found')}}</span>
@endif
