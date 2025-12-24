<div class="container">
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
                        {{$operationCategory->id}} - {{$operationCategory->name}} <span
                            class="text-muted"> > </span> {{__('Update Operation category')}}
                    @else
                        {{$operationCategory->id}} - {{$operationCategory->name}} <span
                            class="text-muted"> > </span> {{__('Create Operation category')}}
                    @endif
                </h6>
            </div>
        </div>
        <form>
            <div class="card-body">
                <input type="hidden" wire:model.live="id">
                <div class="form-group">
                    <label for="name">{{__('name')}}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           placeholder="{{__('Enter name')}}" wire:model.live="name">
                    @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                    <div class="form-text">{{__('Required field')}}</div>
                </div>
                <div class="form-group">
                    <label for="code">{{__('code')}}</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                           id="code"
                           placeholder="{{__('Enter code')}}" wire:model.live="code">
                    @error('code') <span class="text-danger">{{ $message }}</span>@enderror
                    <div class="form-text">{{__('Required field')}}</div>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              placeholder="{{ __('Enter description') }}"
                              wire:model.live="description"
                              rows="4"></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    <div class="form-text">{{ __('Required field') }}</div>
                </div>

            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-2">
                        @if($update)
                            <button wire:click.prevent="updateCategory()"
                                    class="btn btn-success btn-block">{{__('Update')}}</button>
                        @else
                            <button wire:click.prevent="storeCategory()"
                                    class="btn btn-success btn-block float-end">{{__('Save')}}</button>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <button wire:click.prevent="cancel()"
                                class="btn btn-danger float-end">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
