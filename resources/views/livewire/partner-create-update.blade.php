<div class="container">
    @section('title')
        @if($update)
            {{__('Update Partner')}}
        @else
            {{__('Create Partner')}}
        @endif
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Partner')}} > {{$company_name}}
            @else
                {{__('Create Partner')}}
            @endif
        @endslot
        @slot('li_1')
            <a href="{{route('partner_index', app()->getLocale())}}">{{ __('Partners') }}</a>
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12 card">
            <div class="card-body row">
                <form>
                    <input type="hidden" wire:model.live="id">

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6 mb-3">
                            <label for="CompanyName">{{__('Company Name')}} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                   id="CompanyName"
                                   placeholder="{{__('Enter Company Name')}}"
                                   wire:model.live="company_name">
                            @error('company_name') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>

                        <div class="form-group col-sm-12 col-md-6 mb-3">
                            <label for="BusinessSector">{{__('Business Sector')}}</label>
                            <select class="form-select @error('business_sector_id') is-invalid @enderror"
                                   id="BusinessSector"
                                   wire:model.live="business_sector_id">
                                <option value="">{{__('Select Business Sector')}}</option>
                                @foreach($businessSectors as $sector)
                                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                @endforeach
                            </select>
                            @error('business_sector_id') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 mb-3">
                            <label for="PlatformUrl">{{__('Platform URL')}}</label>
                            <input type="url" class="form-control @error('platform_url') is-invalid @enderror"
                                   id="PlatformUrl"
                                   placeholder="{{__('Enter Platform URL (e.g., https://example.com)')}}"
                                   wire:model.live="platform_url">
                            @error('platform_url') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 mb-3">
                            <label for="PlatformDescription">{{__('Platform Description')}}</label>
                            <textarea class="form-control @error('platform_description') is-invalid @enderror"
                                      id="PlatformDescription"
                                      rows="4"
                                      placeholder="{{__('Enter Platform Description')}}"
                                      wire:model.live="platform_description"></textarea>
                            @error('platform_description') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 mb-3">
                            <label for="PartnershipReason">{{__('Partnership Reason')}}</label>
                            <textarea class="form-control @error('partnership_reason') is-invalid @enderror"
                                      id="PartnershipReason"
                                      rows="4"
                                      placeholder="{{__('Enter Partnership Reason')}}"
                                      wire:model.live="partnership_reason"></textarea>
                            @error('partnership_reason') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            @if($update)
                                <button wire:click.prevent="updatePartner()"
                                        class="btn btn-success btn-block mx-2 float-end">
                                    <i class="ri-save-line me-1"></i>{{__('Update')}}
                                </button>
                            @else
                                <button wire:click.prevent="store()"
                                        class="btn btn-success btn-block float-end">
                                    <i class="ri-save-line me-1"></i>{{__('Save')}}
                                </button>
                            @endif

                            <button wire:click.prevent="cancel()"
                                    class="btn btn-outline-danger float-end mx-2">
                                <i class="ri-close-line me-1"></i>{{__('Cancel')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

