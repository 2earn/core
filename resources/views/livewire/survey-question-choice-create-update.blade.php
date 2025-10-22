<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Question') }} : {{ __('Add Option') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    {{__('Survey')}} : {{$question->id}} - {{$question->content}} <span
                        class="text-muted"> > </span>
                    @if($update)
                        {{__('Update Choice')}}
                    @else
                        {{__('Create Choice')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <div class="card mb-2 ml-4">
                <form>
                    <div class="card-body">
                        <input type="hidden" wire:model.live="id">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="title">{{__('Choice')}}</label>
                                <textarea class="form-control @error('title') is-invalid @enderror"
                                          maxlength="80" id="title"
                                          wire:model.live="title"
                                          placeholder="{{__('Enter title')}}"
                                          @if($update) disabled aria-disabled="true" title="{{ __('Choice cannot be edited') }}" @endif></textarea>
                                @error('title') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if($update)
                            <button wire:click.prevent="updateSurveyQuestionChoice()"
                                    class="btn btn-outline-success float-end m-1">{{__('Update')}}</button>
                        @else
                            <button wire:click.prevent="store()"
                                    class="btn btn-outline-success float-end m-1">{{__('Save')}}</button>
                        @endif
                        <button wire:click.prevent="cancel()"
                                class="btn btn-outline-danger float-end m-1">{{__('Cancel')}}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
