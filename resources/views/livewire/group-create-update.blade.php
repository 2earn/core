<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Target') }} <span class="text-muted mx-2">›</span>
            <span class="text-primary">#{{ $target->id }}</span>
            <span class="text-muted mx-2">-</span>
            {{ $target->name }} <span class="text-muted mx-2">›</span>
            @if($update)
                {{__('Update Condition')}}
            @else
                {{__('Add Condition')}}
            @endif
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

                        <!-- Operator Field -->
                        <div class="mb-4">
                            <label for="operator" class="form-label fw-semibold">
                                {{__('Operator')}} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('operator') is-invalid @enderror"
                                    wire:model.live="operator"
                                    id="operator"
                                    aria-label="{{__('Enter operator')}}">
                                @foreach ($operands as $operandItem)
                                    <option value="{{$operandItem['value']}}"
                                            @if($loop->index==0) selected @endif>
                                        {{$operandItem['name']}}
                                    </option>
                                @endforeach
                            </select>
                            @error('operator')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                        wire:click.prevent="updateGroup()"
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
