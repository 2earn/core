<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Operation category')}}
            @else
                {{ __('Add Operation category') }}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-name flex-grow-1">
                    @if($update)
                        {{$balance->id}} - {{$balance->operation}} <span
                            class="text-muted"> > </span> {{__('Update Operation category')}}
                    @else
                        {{$balance->id}} - {{$balance->operation}} <span
                            class="text-muted"> > </span> {{__('Create Operation category')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <form>
                <input type="hidden" wire:model.live="id">
                <div class="row">
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('operation') }}</label>
                        <input wire:model="operation" type="text" class="form-control"
                               placeholder="operation" name="operation">
                    </div>
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('source') }}</label>
                        <input wire:model="source" type="text" class="form-control"
                               placeholder="source" name="source">
                    </div>
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('I/O') }}</label>
                        <select wire:model="io" class="form-control" name="io">
                            @foreach($allIO as $ioItem)
                                <option value="{{$ioItem}}">{{$ioItem}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('Amount') }}</label>
                        <select class="form-control" id="amounts_id" name="amounts_id"
                                wire:model="amounts_id">
                            @foreach($allAmounts as $amountItem)
                                <option value="{{$amountItem->idamounts}}">{{$amountItem->amountsname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('Parent') }}</label>
                        <select class="form-control" id="parent_id" name="parent_id"
                                wire:model="parent_id">
                            @foreach($allParent as $parentItem)
                                <option value="{{$parentItem->id}}">{{$parentItem->operation}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('Operation category') }}</label>
                        <select class="form-control" id="operation_category_id" name="operation_category_id"
                                wire:model="operation_category_id">
                            @foreach($allCategory as $catItem)
                                <option value="{{$catItem->id}}">{{$catItem->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('Modify Amount') }}</label>
                        <select wire:model="modify_amount" class="form-control" name="modify_amount">
                            @foreach($allModify as $modifyItem)
                                <option value="{{$modifyItem['value']}}">{{ __($modifyItem['name']) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 mt-2">
                        <label class="me-sm-2">{{ __('Note') }}</label>
                        <input wire:model="note" type="text" class="form-control"
                               placeholder="note" name="note">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-2">
                        @if($update)
                            <button wire:click.prevent="saveBO()"
                                    class="btn btn-success btn-block">{{__('Update')}}</button>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <button wire:click.prevent="cancel()"
                                class="btn btn-danger">{{__('Cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
