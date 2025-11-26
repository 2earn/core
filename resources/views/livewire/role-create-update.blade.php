<div class="{{getContainerType()}}">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                @if($update)
                    {{__('Update role')}}
                @else
                    {{ __('Create role') }}
                @endif
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row">
            <div class="col-12 card">
                <div class="card-body row ">

                    <form>
                        <input type="hidden" wire:model.live="id">
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 mb-3">
                                <label for="Name">{{__('Name')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="Name"
                                       placeholder="{{__('Enter Name')}}" wire:model.live="name">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                    </form>

                </div>
                <div class="card-footer">
                    @if($update)
                        <button wire:click.prevent="updateRole()"
                                class="btn btn-outline-success float-end mx-2 btn-block">{{__('Update')}}</button>
                    @else
                        <button wire:click.prevent="store()"
                                class="btn btn-outline-success float-end mx-2 btn-block">{{__('Save')}}</button>
                    @endif
                    <button wire:click.prevent="cancel()"
                            class="btn btn-outline-warning mx-2 float-end">{{__('Cancel')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
