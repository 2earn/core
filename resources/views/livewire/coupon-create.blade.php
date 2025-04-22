<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            {{__('Create Coupon')}}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-body row ">
            <p class="text-muted">
            <h4>Here are the lists separated by commas:</h4>
            <h5>List of PIN codes:</h5>
            <code>1234567890, 0987654321, 2345678901, 3456789012, 4567890123, 5678901234, 6789012345, 7890123456,
                8901234567, 9012345678</code>
            <h5>Serial numbers:</h5>
            <code>SN001, SN002, SN003, SN004, SN005, SN006, SN007, SN008, SN009, SN010</code>
            </p>
        </div>
        <div class="card-body row ">
            <form>
                <div class="row">
                    <div class="form-group col-md-4 mt-2">
                        <label for="attachment_date">{{__('Attachment Date')}}:</label>
                        <input class="form-control" wire:model.live="attachment_date" type="date"
                               id="attachment_date" placeholder="{{__('Attachment Date')}}">
                        @error('attachment_date') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4 mt-2">
                        <label for="value">{{__('value')}}:</label>
                        <input class="form-control" wire:model.live="value" type="number"
                               id="value" placeholder="{{__('value')}}">
                        @error('value') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4 mt-2">
                        <label for="platform_id">{{__('Platform')}}</label>
                        <select
                            class="form-select form-control @error('platform_id') is-invalid @enderror"
                            wire:model.live="platform_id"
                            id="platform_id"
                            aria-label="{{__('Platform')}}">
                            @foreach ($selectPlatforms as $platform)
                                <option value="{{$platform['value']}}">{{__($platform['name'])}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6 mt-2">
                        <label for="pins">{{__('Pins')}}</label>
                        <textarea class="form-control @error('pins') is-invalid @enderror"
                                  id="pins"
                                  wire:model.live="pins"
                                  placeholder="{{__('Enter pins')}}"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-md-6 mt-2">
                        <label for="sn">{{__('SN')}}</label>
                        <textarea class="form-control @error('sn') is-invalid @enderror"
                                  id="sn"
                                  wire:model.live="sn"
                                  placeholder="{{__('Enter SN')}}"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button wire:click.prevent="store()"
                                class="btn btn-success btn-block float-end ">{{__('Save')}}</button>
                        <button wire:click.prevent="cancel()"
                                class="btn btn-danger float-end  mx-2">{{__('Cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
