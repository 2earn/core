<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Target')}}
            @else
                {{__('Create Target')}}
            @endif
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        @if($update)
                            {{__('Update Target')}} <span class="text-muted mx-2">›</span>
                            <span class="text-primary">#{{$target->id}}</span>
                            <span class="text-muted mx-2">-</span>
                            <span class="fw-normal">{{$target->name}}</span>
                        @else
                            {{__('Create New Target')}}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model.live="id">

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                {{__('Name')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   wire:model.live="name"
                                   placeholder="{{__('Enter name')}}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                {{__('Description')}} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      wire:model.live="description"
                                      rows="4"
                                      placeholder="{{__('Enter description')}}"></textarea>
                            @error('description')
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
                                        wire:click.prevent="updateTarget()"
                                        class="btn btn-success px-4"
                                        wire:loading.attr="disabled"
                                        wire:target="updateTarget">
                                    <span wire:loading.remove wire:target="updateTarget">{{__('Update')}}</span>
                                    <span wire:loading wire:target="updateTarget">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        {{__('Updating')}}...
                                    </span>
                                </button>
                            @else
                                <button type="button"
                                        wire:click.prevent="store()"
                                        class="btn btn-success px-4"
                                        wire:loading.attr="disabled"
                                        wire:target="store">
                                    <span wire:loading.remove wire:target="store">{{__('Save')}}</span>
                                    <span wire:loading wire:target="store">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        {{__('Saving')}}...
                                    </span>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
