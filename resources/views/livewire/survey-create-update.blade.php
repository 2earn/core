<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Survey Update')}}
            @else
                {{__('Survey Create')}}
            @endif
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        @if($update)
                            {{__('Survey Update')}} - <span class="text-muted">ID: {{$idSurvey}}</span> - {{$name}}
                        @else
                            {{__('Survey Create')}}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @include('layouts.flash-messages')

                    <form>
                        <input type="hidden" wire:model.live="id">

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Basic Information')}}</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="Name" class="form-label fw-semibold">
                                        {{__('Name')}} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="Name"
                                           @if($update) disabled @endif
                                           placeholder="{{__('Enter Name')}}"
                                           wire:model.live="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="Description" class="form-label fw-semibold">
                                        {{__('Description')}} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="Description"
                                              @if($update) disabled @endif
                                              wire:model.live="description"
                                              rows="4"
                                              placeholder="{{__('Enter Description')}}"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status Switches Section -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Status Settings')}}</h6>
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   role="switch"
                                                   wire:model.live="enabled"
                                                   type="checkbox"
                                                   id="Enabled"
                                                   checked>
                                            <label class="form-check-label fw-semibold" for="Enabled">
                                                {{__('Enabled')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   role="switch"
                                                   wire:model.live="published"
                                                   type="checkbox"
                                                   id="published"
                                                   checked>
                                            <label class="form-check-label fw-semibold" for="published">
                                                {{__('Published')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   role="switch"
                                                   wire:model.live="updatable"
                                                   type="checkbox"
                                                   id="updatable"
                                                   checked>
                                            <label class="form-check-label fw-semibold" for="updatable">
                                                {{__('Updatable')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   role="switch"
                                                   wire:model.live="showResultsAsNumber"
                                                   type="checkbox"
                                                   id="showResultsAsNumber"
                                                   checked>
                                            <label class="form-check-label fw-semibold" for="showResultsAsNumber">
                                                {{__('Show results as number')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   role="switch"
                                                   wire:model.live="showResultsAsPercentage"
                                                   type="checkbox"
                                                   id="showResultsAsPercentage"
                                                   checked>
                                            <label class="form-check-label fw-semibold" for="showResultsAsPercentage">
                                                {{__('Show results as percentage')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Section -->
                        @if(is_null($idTarget)&&$targets->isNotEmpty())
                            <div class="mb-4">
                                <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Target Settings')}}</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="target" class="form-label fw-semibold">
                                            {{__('Target')}} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('target') is-invalid @enderror"
                                                wire:model.live="target"
                                                id="target"
                                                aria-label="{{__('Enter target')}}">
                                            @foreach ($targets as $targetItem)
                                                <option value="{{$targetItem->id}}"
                                                        @if($targetItem->id==$target) selected @endif>
                                                    {{$targetItem->id}}) {{$targetItem->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('target')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Display Settings Section -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Display Settings')}}</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="show" class="form-label fw-semibold">{{__('Can show')}}</label>
                                    <select class="form-select @error('show') is-invalid @enderror"
                                            wire:model.live="show"
                                            id="show"
                                            aria-label="{{__('Show')}}">
                                        @foreach ($targetTypes as $targetType)
                                            <option value="{{$targetType}}"
                                                    @if($loop->index==0) selected @endif>{{__($targetType->name)}}</option>
                                        @endforeach
                                    </select>
                                    @error('show')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="showAttchivementGool" class="form-label fw-semibold">
                                        {{__('Show attchivement pourcentage')}}
                                    </label>
                                    <select class="form-select @error('showAttchivementGool') is-invalid @enderror"
                                            wire:model.live="showAttchivementGool"
                                            id="showAttchivementGool"
                                            aria-label="{{__('showAttchivementGool')}}">
                                        @foreach ($targetTypes as $targetType)
                                            <option value="{{$targetType}}"
                                                    @if($loop->index==0) selected @endif>{{__($targetType->name)}}</option>
                                        @endforeach
                                    </select>
                                    @error('showAttchivementGool')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="showAttchivementChrono" class="form-label fw-semibold">
                                        {{__('Show Attchivement Chrono')}}
                                    </label>
                                    <select class="form-select @error('showAttchivementChrono') is-invalid @enderror"
                                            wire:model.live="showAttchivementChrono"
                                            id="showAttchivementChrono"
                                            aria-label="{{__('showAttchivementChrono')}}">
                                        @foreach ($targetTypes as $targetType)
                                            <option value="{{$targetType}}"
                                                    @if($loop->index==0) selected @endif>{{__($targetType->name)}}</option>
                                        @endforeach
                                    </select>
                                    @error('showAttchivementChrono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="showAfterArchiving" class="form-label fw-semibold">
                                        {{__('Show After Archiving')}}
                                    </label>
                                    <select class="form-select @error('showAfterArchiving') is-invalid @enderror"
                                            wire:model.live="showAfterArchiving"
                                            id="showAfterArchiving"
                                            aria-label="{{__('showAfterArchiving')}}">
                                        @foreach ($targetTypes as $targetType)
                                            <option value="{{$targetType}}"
                                                    @if($loop->index==0) selected @endif>{{__($targetType->name)}}</option>
                                        @endforeach
                                    </select>
                                    @error('showAfterArchiving')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Interaction Settings Section -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Interaction Settings')}}</h6>

                            <!-- Show Result -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="showResult" class="form-label fw-semibold">
                                                {{__('Show result')}}
                                            </label>
                                            <select class="form-select @error('showResult') is-invalid @enderror"
                                                    wire:model.live="showResult"
                                                    id="showResult"
                                                    aria-label="{{__('Show result')}}">
                                                @foreach ($targetTypes as $targetType)
                                                    <option value="{{$targetType->value}}"
                                                            @if($loop->index==0) selected @endif>{{__($targetType->name)}}</option>
                                                @endforeach
                                            </select>
                                            @error('showResult')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-8">
                                            <label for="disabledResult" class="form-label fw-semibold">
                                                {{__('Disabled show result explanation')}}
                                            </label>
                                            <textarea class="form-control @error('disabledResult') is-invalid @enderror"
                                                      id="disabledResult"
                                                      wire:model.live="disabledResult"
                                                      rows="3"
                                                      placeholder="{{__('Enter Description for disabled show result')}}"></textarea>
                                            @error('disabledResult')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Commentable -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="commentable" class="form-label fw-semibold">
                                                {{__('Commentable')}}
                                            </label>
                                            <select class="form-select @error('commentable') is-invalid @enderror"
                                                    wire:model.live="commentable"
                                                    id="commentable"
                                                    aria-label="{{__('commentable')}}">
                                                @foreach ($targetTypes as $targetType)
                                                    <option value="{{$targetType->value}}"
                                                            @if($loop->index==0) selected @endif>{{__($targetType->name)}}</option>
                                                @endforeach
                                            </select>
                                            @error('commentable')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-8">
                                            <label for="disabledComment" class="form-label fw-semibold">
                                                {{__('Disabled comment explanation')}}
                                            </label>
                                            <textarea class="form-control @error('disabledComment') is-invalid @enderror"
                                                      id="disabledComment"
                                                      wire:model.live="disabledComment"
                                                      rows="3"
                                                      placeholder="{{__('Enter Description for disabled comment')}}"></textarea>
                                            @error('disabledComment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Likable -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="likable" class="form-label fw-semibold">
                                                {{__('Likable')}}
                                            </label>
                                            <select class="form-select @error('likable') is-invalid @enderror"
                                                    wire:model.live="likable"
                                                    id="likable"
                                                    aria-label="{{__('likable')}}">
                                                @foreach ($targetTypes as $targetType)
                                                    <option value="{{$targetType->value}}"
                                                            @if($loop->index==0) selected @endif>{{__($targetType->name)}}</option>
                                                @endforeach
                                            </select>
                                            @error('likable')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-8">
                                            <label for="disabledLike" class="form-label fw-semibold">
                                                {{__('Disabled show like explanation')}}
                                            </label>
                                            <textarea class="form-control @error('disabledLike') is-invalid @enderror"
                                                      id="disabledLike"
                                                      wire:model.live="disabledLike"
                                                      rows="3"
                                                      placeholder="{{__('Enter Description for disabled like')}}"></textarea>
                                            @error('disabledLike')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Goals Section -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Schedule & Goals')}}</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="startDate" class="form-label fw-semibold">{{__('Start Date')}}</label>
                                    <input class="form-control"
                                           wire:model.live="startDate"
                                           type="datetime-local"
                                           id="startDate"
                                           placeholder="{{__('Start Date')}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="endDate" class="form-label fw-semibold">{{__('End Date')}}</label>
                                    <input class="form-control"
                                           wire:model.live="endDate"
                                           type="datetime-local"
                                           id="endDate"
                                           placeholder="{{__('End Date')}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="goals" class="form-label fw-semibold">{{__('Goals')}}</label>
                                    <input class="form-control"
                                           wire:model.live="goals"
                                           type="number"
                                           id="goals"
                                           placeholder="{{__('Goals')}}">
                                </div>
                            </div>
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
                                        wire:click.prevent="updateSurvey()"
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
