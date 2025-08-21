@php
    $balanceModel=\App\Models\BFSsBalances::find($balance->id);
    $bfsModels=\App\Models\BFSsBalances::where('beneficiary_id_auto',$balanceModel->beneficiary_id_auto)
   ->limit(3)->get();
@endphp

@if($bfsModels->count())
    <span class="text-muted my-2" title="{{$balanceModel->reference}}">{{__('Last  operations <3')}}:</span>
@endif
<div data-simplebar style="max-height: 215px;">
    <ul class="list-group">
        @forelse ($bfsModels as $bfsModel)
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0" title="{{$bfsModel->balance_operation_id}}">{{__('Details')}}
                                    :</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span
                            class="text-muted">{{$bfsModel->reference}} / {{formatSolde($bfsModel->value,0)}}  {{config('app.currency')}}  / {{$bfsModel->created_at}}</span>
                    </div>
                </div>
            </li>
        @empty
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <span class="text-muted">{{__('No history found')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endforelse
    </ul>
</div>
