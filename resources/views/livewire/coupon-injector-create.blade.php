<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            {{__('Create Coupon')}}
        @endslot
    @endcomponent
    <div class="row card">
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
                        <label for="value">{{__('Number of coupons')}}:</label>
                        <input class="form-control" wire:model.live="numberOfCoupons" type="number"
                               id="numberOfCoupons" placeholder="{{__('Number of coupons')}}">
                        @error('numberOfCoupons') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('1 < Number of coupons < 100')}}</div>
                    </div>
                    <div class="form-group col-md-4 mt-2">
                        <label for="platform_id">{{__('Category')}}</label>
                        <select
                            class="form-select form-control @error('category_id') is-invalid @enderror"
                            wire:model.live="category_id"
                            id="category_id"
                            aria-label="{{__('category')}}">
                            @foreach ($allCategories as $category)
                                <option value="{{$category->value}}">{{__($category->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 mt-2">
                        <label for="type">{{__('Type')}}:</label>
                        <input class="form-control" wire:model.live="type" type="text"
                               id="type" placeholder="{{__('type')}}">
                        @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('00.01 < type < 100.00 // No control')}}</div>

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
