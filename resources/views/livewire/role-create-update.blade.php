<div class="container-fluid">
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
        <div class="row card">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h6 class="card-title flex-grow-1">
                        @if($update)
                            {{__('Update role')}}
                        @else
                            {{__('Create role')}}
                        @endif
                    </h6>
                </div>
            </div>
            <div class="card-body row ">
                <div class="card mb-2 mr-2 ml-2 border">
                    <div class="card-body">
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

                                <div class="row mt-3">
                                    <div class="col-md-2">
                                        @if($update)
                                            <button wire:click.prevent="updateRole()"
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
</div>
