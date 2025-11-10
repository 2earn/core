<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Target') }} <span class="text-muted mx-2">›</span>
            <span class="text-primary">#{{ $target->id }}</span>
            <span class="text-muted mx-2">-</span>
            {{ $target->name }} <span class="text-muted mx-2">›</span>
            {{ __('Add Condition') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <span class="text-muted">{{__('Target')}}</span>
                        <span class="text-muted mx-2">›</span>
                        <span class="text-primary">#{{$target->id}}</span>
                        <span class="text-muted mx-2">-</span>
                        <span class="fw-normal">{{$target->name}}</span>
                        <span class="text-muted mx-2">›</span>
                        @if($update)
                            {{__('Update Condition')}}
                        @else
                            {{__('Create Condition')}}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model.live="id">

                        <!-- Condition Expression -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                {{__('Condition Expression')}} <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <!-- Operand Field -->
                                <div class="col-md-5">
                                    <label for="operand" class="form-label fw-semibold text-muted small">
                                        {{__('Operand')}}
                                    </label>
                                    <select class="form-select @error('operand') is-invalid @enderror"
                                            wire:model.live="operand"
                                            id="operand"
                                            aria-label="{{__('Enter operand')}}">
                                        @foreach ($operators as $operatorItem)
                                            <option value="{{$operatorItem['value']}}"
                                                    @if($loop->index==0) selected @endif>
                                                {{$operatorItem['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('operand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Operator Field -->
                                <div class="col-md-3">
                                    <label for="operator" class="form-label fw-semibold text-muted small">
                                        {{__('Operator')}}
                                    </label>
                                    <select class="form-select @error('operator') is-invalid @enderror"
                                            wire:model.live="operator"
                                            id="operator"
                                            aria-label="{{__('Enter operator')}}">
                                        @foreach ($operands as $operandItem)
                                            <option value="{{$operandItem}}"
                                                    @if($loop->index==0) selected @endif>
                                                {{$operandItem}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('operator')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Value Field -->
                                <div class="col-md-4">
                                    <label for="value" class="form-label fw-semibold text-muted small">
                                        {{__('Value')}}
                                    </label>
                                    <input type="text"
                                           class="form-control @error('value') is-invalid @enderror"
                                           id="value"
                                           wire:model.live="value"
                                           placeholder="{{__('Enter value')}}">
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-text mt-2">
                                <i class="fa fa-info-circle me-1"></i>
                                {{__('Define the condition: Operand + Operator + Value')}}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button"
                                    wire:click.prevent="cancel()"
                                    class="btn btn-outline-secondary px-4">
                                {{__('Cancel')}}
                            </button>
                            @if($update)
                                <button type="button"
                                        wire:click.prevent="updateCondition()"
                                        class="btn btn-success px-4">
                                    {{__('Update')}}
                                </button>
                            @else
                                <button type="button"
                                        wire:click.prevent="store()"
                                        class="btn btn-success px-4">
                                    {{__('Save')}}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
