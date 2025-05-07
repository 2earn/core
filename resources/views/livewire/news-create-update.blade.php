<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update News')}}
            @else
                {{__('Create News')}}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-body row ">
            <form>
                <input type="hidden" wire:model.live="id">
                <div class="row">
                    <div class="form-group col-12">
                        <label for="title">{{__('title')}}</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               @if($update) disabled @endif
                               placeholder="{{__('Enter title')}}" wire:model.live="title">
                        @error('title') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-12">
                        <label for="content">{{__('Content')}}</label>
                        <textarea class="form-control @error('content') is-invalid @enderror"
                                  id="content"
                                  @if($update) disabled @endif
                                  wire:model.live="content"
                                  placeholder="{{__('Enter content')}}"></textarea>
                        @error('content') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" role="switch" wire:model.live="enabled" type="checkbox"
                                   id="Enabled" placeholder="{{__('enabled')}}" checked>
                            <label class="form-check-label" for="Enabled">{{__('Enabled')}}</label>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label for="logoImage">{{__('Main Image')}}</label>
                        <input type="file" id="mainImage" wire:model.live="mainImage" class="form-control">
                        @error('mainImage') <span class="error">{{ $message }}</span> @enderror
                        @if ($news?->mainImage)
                            <div class="mt-3">
                                <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                                     alt="Business Sector logoImage" class="img-thumbnail">
                            </div>
                        @endif
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
