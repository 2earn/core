<div class="container-fluid">
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
            @include('layouts.flash-messages')
            <div class="card mb-2 ml-4">
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model.live="id">
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 mb-3">
                                <label for="Name">{{__('Name')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="Name"
                                       @if($update) disabled @endif
                                       placeholder="{{__('Enter Name')}}" wire:model.live="name">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group  col-sm-4 col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" role="switch" wire:model.live="enabled" type="checkbox"
                                           id="Enabled" placeholder="{{__('enabled')}}" checked>
                                    <label class="form-check-label" for="Enabled">{{__('Enabled')}}</label>
                                </div>
                            </div>

                            <div class="form-group  col-sm-4 col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" role="switch" wire:model.live="published" type="checkbox"
                                           id="published" placeholder="{{__('published')}}" checked>
                                    <label class="form-check-label" for="published">{{__('Published')}}</label>
                                </div>
                            </div>

                            <div class="form-group  col-sm-4 col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" role="switch" wire:model.live="updatable" type="checkbox"
                                           id="updatable" placeholder="{{__('updatable')}}" checked>
                                    <label class="form-check-label" for="updatable">{{__('Updatable')}}</label>
                                </div>
                            </div>
                            <div class="form-group  col-sm-4 col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" role="switch" wire:model.live="showResultsAsNumber" type="checkbox"
                                           id="showResultsAsNumber" placeholder="{{__('showResultsAsNumber')}}" checked>
                                    <label class="form-check-label" for="showResultsAsNumber">{{__('Show results as number')}}</label>
                                </div>
                            </div>
                            <div class="form-group  col-sm-4 col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" role="switch" wire:model.live="showResultsAsPercentage" type="checkbox"
                                           id="showResultsAsPercentage" placeholder="{{__('showResultsAsPercentage')}}" checked>
                                    <label class="form-check-label" for="showResultsAsPercentage">{{__('Show results as percentage')}}</label>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="Description">{{__('Description')}}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="Description"
                                          @if($update) disabled @endif
                                          wire:model.live="description"
                                          placeholder="{{__('Enter Description')}}"></textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                        </div>
                        @if(is_null($idTarget)&&$targets->isNotEmpty())
                            <div class="form-group mb-3">
                                <label for="target">{{__('Target')}}</label>
                                <select
                                    class="form-select form-control @error('target') is-invalid @enderror"
                                    placeholder="{{__('Enter target')}}"
                                    wire:model.live="target"
                                    id="target"
                                    aria-label="{{__('Enter target')}}">
                                    @foreach ($targets as $targetItem)
                                        <option value="{{$targetItem->id}}"
                                                @if($targetItem->id==$target) selected @endif >
                                            {{$targetItem->id }}) {{$targetItem->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('target') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                        @endif
                        <div class="row mt-2">
                            <div class="form-group col-md-4 mt-2">
                                <label for="showResult">{{__('can show')}}</label>
                                <select
                                    class="form-select form-control @error('show') is-invalid @enderror"
                                    placeholder="{{__('Show')}}"
                                    wire:model.live="show"
                                    id="show"
                                    aria-label="{{__('Show')}}">
                                    @foreach ($targetTypes as $targetType)
                                        <option value="{{$targetType}}"
                                                @if($loop->index==0) selected @endif >{{__($targetType->name)}}</option>
                                    @endforeach
                                </select>
                                @error('show') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-md-4 mt-2">
                                <label for="showAttchivementGool">{{__('Show attchivement pourcentage')}}</label>
                                <select
                                    class="form-select form-control @error('showAttchivementGool') is-invalid @enderror"
                                    placeholder="{{__('Show attchivement pourcentage')}}"
                                    wire:model.live="showAttchivementGool"
                                    id="showAttchivementGool"
                                    aria-label="{{__('showAttchivementGool')}}">
                                    @foreach ($targetTypes as $targetType)
                                        <option value="{{$targetType}}"
                                                @if($loop->index==0) selected @endif >{{__($targetType->name)}}</option>
                                    @endforeach
                                </select>
                                @error('showAttchivementGool')
                                <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-md-4 mt-2">
                                <label for="showAttchivementChrono">{{__('Show Attchivement Chrono')}}</label>
                                <select
                                    class="form-select form-control @error('showAttchivementChrono') is-invalid @enderror"
                                    placeholder="{{__('Show Attchivement Chrono')}}"
                                    wire:model.live="showAttchivementChrono"
                                    id="showAttchivementChrono"
                                    aria-label="{{__('showAttchivementChrono')}}">
                                    @foreach ($targetTypes as $targetType)
                                        <option value="{{$targetType}}"
                                                @if($loop->index==0) selected @endif >{{__($targetType->name)}}</option>
                                    @endforeach
                                </select>
                                @error('showAttchivementChrono')
                                <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-md-4 mt-2">
                                <label for="showResult">{{__('Show result')}}</label>
                                <select
                                    class="form-select form-control @error('showResult') is-invalid @enderror"
                                    placeholder="{{__('Show result')}}"
                                    wire:model.live="showResult"
                                    id="showResult"
                                    aria-label="{{__('Show result')}}">
                                    @foreach ($targetTypes as $targetType)
                                        <option value="{{$targetType->value}}"
                                                @if($loop->index==0) selected @endif >{{__($targetType->name)}}</option>
                                    @endforeach
                                </select>
                                @error('showResult') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                                <div class="form-group mb-3">
                                    <label for="disabledResult">{{__('Disabled show result explanation')}}</label>
                                    <textarea class="form-control @error('disabledResult') is-invalid @enderror"
                                              id="disabledResult"
                                              wire:model.live="disabledResult"
                                              placeholder="{{__('Enter Description for disabled show result')}}"></textarea>
                                    @error('disabledResult') <span class="text-danger">{{ $message }}</span>@enderror
                                    <div class="form-text">{{__('Required field')}}</div>
                                </div>
                            </div>

                            <div class="form-group col-md-4 mt-2">
                                <label for="commentable">{{__('Commentable')}}</label>
                                <select
                                    class="form-select form-control @error('commentable') is-invalid @enderror"
                                    placeholder="{{__('commentable')}}"
                                    wire:model.live="commentable"
                                    id="commentable"
                                    aria-label="{{__('commentable')}}">
                                    @foreach ($targetTypes as $targetType)
                                        <option value="{{$targetType->value}}"
                                                @if($loop->index==0) selected @endif >{{__($targetType->name)}}</option>
                                    @endforeach
                                </select>
                                @error('commentable')
                                <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                                <div class="form-group mb-3">
                                    <label for="disabledComment">{{__('Disabled comment explanation')}}</label>
                                    <textarea class="form-control @error('disabledComment') is-invalid @enderror"
                                              id="disabledComment"
                                              wire:model.live="disabledComment"
                                              placeholder="{{__('Enter Description for disabled comment')}}"></textarea>
                                    @error('disabledComment') <span class="text-danger">{{ $message }}</span>@enderror
                                    <div class="form-text">{{__('Required field')}}</div>
                                </div>
                            </div>

                            <div class="form-group col-md-4 mt-2">
                                <label for="likable">{{__('Likable')}}</label>
                                <select
                                    class="form-select form-control @error('likable') is-invalid @enderror"
                                    placeholder="{{__('likable')}}"
                                    wire:model.live="likable"
                                    id="likable"
                                    aria-label="{{__('likable')}}">
                                    @foreach ($targetTypes as $targetType)
                                        <option value="{{$targetType->value}}"
                                                @if($loop->index==0) selected @endif >{{__($targetType->name)}}</option>
                                    @endforeach
                                </select>
                                @error('likable')
                                <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                                <div class="form-group mb-3">
                                    <label for="disabledLike">{{__('Disabled show like explanation')}}</label>
                                    <textarea class="form-control @error('disabledLike') is-invalid @enderror"
                                              id="disabledLike"
                                              wire:model.live="disabledLike"
                                              placeholder="{{__('Enter Description for disabled like')}}"></textarea>
                                    @error('disabledLike') <span class="text-danger">{{ $message }}</span>@enderror
                                    <div class="form-text">{{__('Required field')}}</div>
                                </div>
                            </div>

                            <div class="form-group col-md-12 mt-2">
                                <label for="showAfterArchiving">{{__('Show After Archiving')}}</label>
                                <select
                                    class="form-select form-control @error('showAfterArchiving') is-invalid @enderror"
                                    placeholder="{{__('Show After Archiving')}}"
                                    wire:model.live="showAfterArchiving"
                                    id="showAfterArchiving"
                                    aria-label="{{__('showAfterArchiving')}}">
                                    @foreach ($targetTypes as $targetType)
                                        <option value="{{$targetType}}"
                                                @if($loop->index==0) selected @endif >{{__($targetType->name)}}</option>
                                    @endforeach
                                </select>
                                @error('showAfterArchiving')
                                <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="form-group col-md-4 mt-2">
                                <label for="startDate">{{__('Start Date')}}:</label>
                                <input class="form-control" wire:model.live="startDate"
                                       type="datetime-local"
                                       id="startDate" placeholder="{{__('Start Date')}}">
                            </div>
                            <div class="form-group col-md-4 mt-2">
                                <label for="endDate">{{__('End Date')}}:</label>
                                <input class="form-control" wire:model.live="endDate"
                                       type="datetime-local"
                                       id="endDate" placeholder="{{__('End Date')}}">
                            </div>
                            <div class="form-group col-md-4 mt-2">
                                <label for="goals">{{__('Goals')}}</label>
                                <input class="form-control" wire:model.live="goals"
                                       id="goals" placeholder="{{__('Goals')}}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                @if($update)
                                    <button wire:click.prevent="updateSurvey()"
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
