<div>
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
            <div class="card mb-2 ml-4 border border-dashed ">
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model="id">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="title">{{__('Choice')}}</label>
                                <textarea class="form-control @error('title') is-invalid @enderror"
                                          maxlength="80" id="title"
                                          wire:model="title"
                                          placeholder="{{__('Enter title')}}"></textarea>
                                @error('title') <span class="text-danger">{{ __($message) }}</span>@enderror
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
