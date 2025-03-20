<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Platform')}}
            @else
                {{ __('Create Platform') }}
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
                        {{__('Update Platform')}}
                    @else
                        {{__('Create Platform')}}
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
                            <div class="form-group col-sm-12 col-md-6 mb-3">
                                <label for="Name">{{__('Name')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="Name"
                                       placeholder="{{__('Enter Name')}}" wire:model.live="name">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-sm-12 col-md-6 mb-3">
                                <label for="value">{{__('Type')}}</label>
                                <select
                                    class="form-select form-control @error('type') is-invalid @enderror"
                                    wire:model.live="type"
                                    id="type"
                                    aria-label="{{__('Enter type')}}">
                                    @foreach ($types as $typeItem)
                                        <option value="{{$typeItem['value']}}">{{$typeItem['name']}}</option>
                                    @endforeach
                                </select>
                                @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 mb-3">
                                <label for="Link">{{__('Link')}}</label>
                                <input type="text" class="form-control @error('link') is-invalid @enderror"
                                       id="Link"
                                       placeholder="{{__('Enter Link')}}" wire:model.live="link">
                                @error('link') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-sm-12 col-md-6 mb-3">
                                <label for="value">{{__('Sector')}}</label>
                                <select
                                    class="form-select form-control @error('sector') is-invalid @enderror"
                                    wire:model.live="sector"
                                    id="sector"
                                    aria-label="{{__('Enter sector')}}">
                                    @foreach ($sectors as $sectorsItem)
                                        <option value="{{$sectorsItem['value']}}">
                                            {{$sectorsItem['name']}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sector') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group  col-sm-4 col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" role="switch" wire:model.live="enabled"
                                           type="checkbox"
                                           id="Enabled" placeholder="{{__('enabled')}}" checked>
                                    <label class="form-check-label" for="Enabled">{{__('Enabled')}}</label>
                                </div>
                            </div>
                            <div class="form-group  col-sm-4 col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" role="switch" wire:model.live="show_profile"
                                           type="checkbox"
                                           id="show_profile" placeholder="{{__('show_profile')}}" checked>
                                    <label class="form-check-label" for="show_profile">{{__('Show profile')}}</label>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="Description">{{__('Description')}}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="Description"
                                          wire:model.live="description"
                                          placeholder="{{__('Enter Description')}}"></textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group">
                                <label for="logoImage">{{__('Logo Image')}}</label>
                                <input type="file" id="logoImage" wire:model.live="logoImage" class="form-control">
                                @error('logoImage') <span class="error">{{ $message }}</span> @enderror
                                @if ($platform?->logoImage)
                                    <div class="mt-3">
                                        <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                             alt="Business Sector logoImage" class="img-thumbnail">
                                    </div>
                                @endif
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
