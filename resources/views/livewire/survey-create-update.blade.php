<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Create') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">


                    @if($update)
                        {{__('Survey Update')}} : {{$idSurvey}} - {{$name}}

                    @else
                        {{__('Survey Create')}}
                    @endif

                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <div class="card mb-2 ml-4 border border-dashed ">
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model="id">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="Name">{{__('Name')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="Name"
                                       placeholder="{{__('Enter Name')}}" wire:model="name">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="Description">{{__('Description')}}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="Description"
                                          wire:model="description"
                                          placeholder="{{__('Enter Description')}}"></textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="enabled" type="checkbox" value=""
                                       id="Enabled" placeholder="{{__('enabled')}}" checked>
                                <label class="form-check-label" for="Enabled">{{__('Enabled')}}</label>
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="archived" type="checkbox" value=""
                                       id="archived" placeholder="{{__('enabled')}}" checked>
                                <label class="form-check-label" for="archived">{{__('Archived')}}</label>
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="updatable" type="checkbox" value=""
                                       id="updatable" placeholder="{{__('updatable')}}" checked>
                                <label class="form-check-label" for="updatable">{{__('Updatable')}}</label>
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="showResult" type="checkbox" value=""
                                       id="showResult" placeholder="{{__('showResult')}}" checked>
                                <label class="form-check-label" for="showResult">{{__('Show result')}}</label>
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="showAchievement" type="checkbox"
                                       value=""
                                       id="showAchievement" placeholder="{{__('showAchievement')}}" checked>
                                <label class="form-check-label"
                                       for="showAchievement">{{__('Show achievement')}}</label>
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
