<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Faq')}}
            @else
                {{__('Create Faq')}}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    @if($update)
                        {{__('Faq')}} : <span
                            class="text-muted"> > </span> {{__('Update Faq')}} > {{$question}}
                    @else
                        {{__('Faq')}} :<span
                            class="text-muted"> > </span> {{__('Create Faq')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <form>
                <input type="hidden" wire:model.live="id">
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="Question">{{__('Question')}}</label>
                        <input type="text" class="form-control @error('question') is-invalid @enderror"
                               id="Question"
                               placeholder="{{__('Enter Question')}}" wire:model.live="question"
                               @if($update) disabled aria-disabled="true" title="{{ __('Question cannot be edited') }}" @endif>
                        @error('question') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="Answer">{{__('Answer')}}</label>
                        <textarea class="form-control @error('answer') is-invalid @enderror"
                                  id="Answer"
                                  @if($update) disabled aria-disabled="true" title="{{ __('Answer cannot be edited') }}" @endif
                                  placeholder="{{__('Enter Answer')}}" wire:model.live="answer"></textarea>
                        @error('answer') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        @if($update)
                            <button wire:click.prevent="updateFaq()"
                                    class="btn btn-outline-success btn-block mx-2 float-end ">{{__('Update')}}</button>
                        @else
                            <button wire:click.prevent="store()"
                                    class="btn btn-outline-success btn-block float-end ">{{__('Save')}}</button>
                        @endif

                        <button wire:click.prevent="cancel()"
                                class="btn btn-outline-danger float-end  mx-2">{{__('Cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
