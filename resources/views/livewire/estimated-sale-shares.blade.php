<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">{{__('Estimated Sale Shares')}}</h5>
            </div>
        </div>
    </div>
    <div class="card-body row">
        <div class="col-12">
            <ul class="list-group  mt-2">
                <li class="list-group-item  text-info">{{__('User Selled Action Number')}} : <span
                        class="float-end">{{$userSelledActionNumber}}</span>
                </li>
            </ul>
        </div>
        <div class="col-12 mt-2">
            <label for="estimatedGain" class="form-label">{{__('Gain')}}</label>
            <input aria-describedby="estimatedGain" type="number" wire:keyup.debounce="simulateAction()"
                   disabled wire:model.live="estimatedGain" id="estimatedGain" class="form-control">
        </div>
        <div class="col-12">
            <ul class="list-group mt-2">
                <li class="list-group-item">
                            <span class="text-info font-weight-bold">
                                {{__('Total Paied')}} <i class=" ri-arrow-drop-right-line"></i>
                                <span class="text-success">{{__('Estimated Gain')}}</span>
                                                               </span>

                    <span class="badge badge-light text-info float-end">
                                    {{$totalPaied}}
                                    <i class=" ri-arrow-drop-right-line"></i>
                                    <span class="text-success">{{$estimatedGain}}</span>
                                </span>
                </li>
                <li class="list-group-item text-secondary">
                    {{__('Action value')}} : <span
                        class="float-end">{{$actionValue}}</span></li>
                @if(\App\Models\User::isSuperAdmin())
                    <li class="list-group-item">{{__('Selled Action Cursor')}} <i
                            class=" ri-arrow-drop-right-line"></i> {{__('Total number of shares for sale')}}
                        <span class="badge badge-light text-info float-end">{{$selledActionCursor}} <i
                                class=" ri-arrow-drop-right-line"></i>{{$numberSharesSale}}</span>
                    </li>
                @endif
                <li class="list-group-item text-info">{{__('Percent of progress of selled shares')}}
                    <span class="float-end">{{round((($selledActionCursor / $totalActions)*100),2) }} <i
                            class="ri-percent-line"></i>
                                </span>
                </li>
            </ul>
        </div>
        <div class="col-12">
            <input type="range" min="0" max="{{$totalActions}}" title="{{$totalActions}}"
                   class="w-100" wire:model.live="selledActionCursor" step="1" wire:change="simulateGain()"
                   id="selledActionCursor">
        </div>
    </div>
</div>
