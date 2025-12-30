<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Question') }} : {{ __('Add Option') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12 card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <span class="text-primary">{{__('Question')}} #{{$question->id}}</span>
                    <span class="text-muted mx-2">›</span>
                    <span class="fw-normal text-truncate d-inline-block" style="max-width: 400px;"
                          title="{{$question->content}}">
                            {{$question->content}}
                        </span>
                    <span class="text-muted mx-2">›</span>
                    @if($update)
                        {{__('Update Choice')}}
                    @else
                        {{__('Create Choice')}}
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <input type="hidden" wire:model.live="id">

                    <!-- Choice Input -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">
                            {{__('Choice')}} <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('title') is-invalid @enderror"
                                  maxlength="80"
                                  id="title"
                                  wire:model.live="title"
                                  rows="3"
                                  placeholder="{{__('Enter choice text')}}"
                                  @if($update) disabled aria-disabled="true" @endif></textarea>
                        <div class="form-text">
                            <span id="charCount">0</span> / 80 {{__('characters')}}
                            @if($update)
                                <span class="text-warning ms-2">
                                        <i class="fa fa-info-circle"></i> {{__('Choice cannot be edited')}}
                                    </span>
                            @endif
                        </div>
                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                                    wire:click.prevent="updateSurveyQuestionChoice()"
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
