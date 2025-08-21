@php
    $balanceModel=\App\Models\SharesBalances::find($balance->id);
    $bfsModel=\App\Models\BFSsBalances::where('reference',$balanceModel->reference)->first();
@endphp

@if(!is_null($bfsModel))
    <span class="text-muted" title="{{$balanceModel->reference}}">{{__('About the share operation')}}:</span>
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
                        <span class="text-danger">{{formatSolde($bfsModel->value,0)}}</span>
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
                        <span class="text-success">{{$bfsModel->created_at}}</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@else
    <span class="text-muted">{{__('No share operation found')}}</span>
@endif
