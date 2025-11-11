<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey') }}
            : {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}    {{ __('Add Question') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <span class="text-primary">{{__('Survey')}} #{{$survey->id}}</span>
                        <span class="text-muted mx-2">›</span>
                        <span class="fw-normal">{{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}</span>
                        <span class="text-muted mx-2">›</span>
                        @if($update)
                            {{__('Update question')}}
                        @else
                            {{__('Create question')}}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model.live="id">

                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                {{__('Content')}} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content"
                                      maxlength="190"
                                      wire:model.live="content"
                                      rows="4"
                                      placeholder="{{__('Enter content')}}"></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span> / 190 {{__('characters')}}
                            </div>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Question Settings -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Question Settings')}}</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="operand" class="form-label fw-semibold">
                                        {{__('Selection')}} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('Selection') is-invalid @enderror"
                                            wire:model.live="selection"
                                            id="operand"
                                            aria-label="{{__('Enter Selection')}}">
                                        @foreach ($selections as $selectionItem)
                                            <option value="{{$selectionItem['value']}}"
                                                    @if($loop->index==0) selected @endif>
                                                {{__($selectionItem['name'])}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Selection')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="maxResponse" class="form-label fw-semibold">
                                        {{__('Max response')}} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control @error('maxResponse') is-invalid @enderror"
                                           id="maxResponse"
                                           wire:model.live="maxResponse"
                                           min="1"
                                           placeholder="{{__('Enter max response')}}">
                                    @error('maxResponse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                        wire:click.prevent="updateSurveyQuestion()"
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
