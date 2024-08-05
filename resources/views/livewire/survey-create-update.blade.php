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
                        <div class="row mt-2">
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="enabled" type="checkbox"
                                       id="Enabled" placeholder="{{__('enabled')}}" checked>
                                <label class="form-check-label" for="Enabled">{{__('Enabled')}}</label>
                            </div>
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="published" type="checkbox"
                                       id="published" placeholder="{{__('published')}}" checked>
                                <label class="form-check-label" for="published">{{__('Published')}}</label>
                            </div>

                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="updatable" type="checkbox"
                                       id="updatable" placeholder="{{__('updatable')}}" checked>
                                <label class="form-check-label" for="updatable">{{__('Updatable')}}</label>
                            </div>

                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="showResult" type="checkbox"
                                       id="showResult" placeholder="{{__('showResult')}}" checked>
                                <label class="form-check-label" for="showResult">{{__('Show result')}}</label>
                            </div>
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="showAttchivementPourcentage" type="checkbox"
                                       id="showAttchivementPourcentage" placeholder="{{__('showAttchivementPourcentage')}}" checked>
                                <label class="form-check-label"
                                       for="showAttchivementPourcentage">{{__('Show achievement %')}}</label>
                            </div>
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="showAttchivementChrono" type="checkbox"
                                       id="showAttchivementChrono" placeholder="{{__('showAttchivementChrono')}}" checked>
                                <label class="form-check-label"
                                       for="showAttchivementChrono">{{__('Show achievement Chrono')}}</label>
                            </div>
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="showAfterArchiving" type="checkbox"
                                       id="showAfterArchiving" placeholder="{{__('showAfterArchiving')}}" checked>
                                <label class="form-check-label"
                                       for="showAfterArchiving">{{__('Show after archiving')}}</label>
                            </div>
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="commentable" type="checkbox"
                                       id="commentable" placeholder="{{__('Commentable')}}" checked>
                                <label class="form-check-label"
                                       for="commentable">{{__('Commentable')}}</label>
                            </div>
                            <div class="form-group col-md-4">
                                <input class="form-check-input" wire:model="likable" type="checkbox"
                                       id="likable" placeholder="{{__('Likable')}}" checked>
                                <label class="form-check-label"
                                       for="likable">{{__('Likable')}}</label>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="form-group col-md-4">
                                <label for="startDate">{{__('Start Date')}}:</label>
                                <input class="form-control" wire:model="startDate" type="date"
                                       id="startDate" placeholder="{{__('Start Date')}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="endDate">{{__('End Date')}}:</label>
                                <input class="form-control" wire:model="endDate" type="date"
                                       id="endDate" placeholder="{{__('End Date')}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="goals">{{__('Goals')}}</label>
                                <input class="form-control" wire:model="goals"
                                       id="goals" placeholder="{{__('Goals')}}">
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
