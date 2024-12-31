<div>
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
                            class="text-muted"> > </span> {{__('Update Faq')}}
                    @else
                        {{__('Faq')}} :<span
                            class="text-muted"> > </span> {{__('Create Faq')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <div class="card mb-2 mr-2 ml-2">
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model="id">
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 mb-3">
                                <label for="Question">{{__('Question')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="Question"
                                       placeholder="{{__('Enter Question')}}" wire:model="question">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 mb-3">
                                <label for="Answer">{{__('Answer')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="Answer"
                                       placeholder="{{__('Enter Answer')}}" wire:model="answer">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                @if($update)
                                    <button wire:click.prevent="update()"
                                            class="btn btn-success btn-block mx-2 float-end ">{{__('Update')}}</button>
                                @else
                                    <button wire:click.prevent="store()"
                                            class="btn btn-success btn-block float-end ">{{__('Save')}}</button>
                                @endif

                                <button wire:click.prevent="cancel()"
                                        class="btn btn-danger float-end  mx-2">{{__('Cancel')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
