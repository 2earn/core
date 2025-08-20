@php
    $idOrder=0;
    $balanceModel=\App\Models\BFSsBalances::find($balance->id);
    $user=\App\Models\User::find($balanceModel->beneficiary_id_auto);
@endphp
@if(!is_null($user))
    <span class="text-muted">{{__('About the cash operation')}}:</span>
    <div data-simplebar style="max-height: 215px;">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0">{{__('Name')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="text-danger">{{getUserDisplayedName($balance->beneficiary_id)}}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0">{{__('Full phone number')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="text-success">{{$user->fullphone_number}}</span>
                    </div>
                </div>
            </li>

        </ul>
    </div>
@else
    <span class="text-muted">{{__('No user found')}}:</span>
@endif
