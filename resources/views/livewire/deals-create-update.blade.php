<div>
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Deal')}}
            @else
                {{__('Create Deal')}}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    @if($update)
                        {{__('Update Deal')}}
                    @else
                        {{__('Create Deal')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <div class="card mb-2 mr-2 ml-2">
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model="id">
                        <div class="row">
                            <div class="form-group  mb-3">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       wire:model="name"
                                       placeholder="{{__('Enter name')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">{{__('Description')}}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          wire:model="description"
                                          placeholder="{{__('Enter description')}}"></textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                @if($update)
                                    <button wire:click.prevent="update()"
                                            class="btn btn-success btn-block mx-2 float-end ">{{__('Update')}}</button>
                                @else
                                    <button wire:click.prevent="store()"
                                            class="btn btn-success btn-block float-end ">{{__('Save')}}</button>
                                @endif

                                <button wire:click.prevent="cancel()"
                                        class="btn btn-danger float-end  mx-2">{{__('Cancel')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
