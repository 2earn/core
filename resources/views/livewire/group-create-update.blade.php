<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Target') }} : {{ $target->id }} - {{ $target->name }}  :       @if($update)
                {{__('Update Condition')}}
            @else
                {{ __('Add Condition') }}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    @if($update)
                        {{__('Target')}} : {{$target->id}} - {{$target->name}} <span
                            class="text-muted"> > </span> {{__('Update Condition')}}
                    @else
                        {{__('Target')}} : {{$target->id}} - {{$target->name}} <span
                            class="text-muted"> > </span> {{__('Create Condition')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <div class="card mb-2 mr-2 ml-2 border border-dashed ">
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model.live="id">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="value">{{__('operator')}}</label>
                                <select
                                    class="form-select form-control @error('operator') is-invalid @enderror"
                                    placeholder="{{__('Enter operator')}}"
                                    wire:model.live="operator"
                                    id="operator"
                                    aria-label="{{__('Enter operator')}}">
                                    @foreach ($operands as $operandItem)
                                        <option value="{{$operandItem['value']}}"
                                                @if($loop->index==0) selected @endif >{{$operandItem['name']}}</option>
                                    @endforeach
                                </select>
                                @error('operator') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                @if($update)
                                    <button wire:click.prevent="update()"
                                            class="btn btn-success btn-block">{{__('Update')}}</button>
                                @else
                                    <button wire:click.prevent="store()"
                                            class="btn btn-success btn-block">{{__('Save')}}</button>
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
    </div>
</div>
