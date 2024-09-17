<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey') }}
            : {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}    {{ __('Add question ') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    @if($update)
                        {{__('Survey')}} : {{$survey->id}}
                        - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}<span
                                class="text-muted"> > </span> {{__('Update question')}}
                    @else
                        {{__('Survey')}} : {{$survey->id}}
                        - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}} <span
                                class="text-muted"> > </span> {{__('Create question')}}
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
                                <label for="content">{{__('Content')}}</label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content"
                                          maxlength="190" wire:model="content"
                                          placeholder="{{__('Enter content')}}"></textarea>
                                @error('content') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="operand">{{__('Selection')}}</label>
                                <select
                                        class="form-select form-control @error('Selection') is-invalid @enderror"
                                        placeholder="{{__('Enter Selection')}}"
                                        wire:model="selection"
                                        id="operand"
                                        aria-label="{{__('Enter Selection')}}">
                                    @foreach ($selections as $selectionItem)
                                        <option value="{{$selectionItem['value']}}"
                                                @if($loop->index==0) selected @endif >{{__($selectionItem['name'])}}</option>
                                    @endforeach
                                </select>
                                @error('Selection') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="maxResponse">{{__('Max response')}}</label>
                                <input type="number" class="form-control @error('maxResponse') is-invalid @enderror"
                                       id="maxResponse"
                                       wire:model="maxResponse"
                                       placeholder="{{__('Enter max response')}}"></input>
                                @error('maxResponse') <span class="text-danger">{{ $message }}</span>@enderror
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
