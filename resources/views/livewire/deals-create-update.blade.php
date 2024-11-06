<div>
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Deal')}}
            @else
                {{__('Create Deal')}}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    @if($update)
                        {{__('Update Deal')}}
                    @else
                        {{__('Create Deal')}}
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
                            <div class="form-group col-3 mb-3">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       wire:model="name"
                                       placeholder="{{__('Enter name')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="description">{{__('Description')}}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          wire:model="description"
                                          placeholder="{{__('Enter description')}}"></textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="start_date">{{__('Start Date')}}:</label>
                                <input class="form-control" wire:model="start_date" type="date"
                                       id="start_date" placeholder="{{__('Start Date')}}">
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="end_date">{{__('End Date')}}:</label>
                                <input class="form-control" wire:model="end_date" type="date"
                                       id="end_date" placeholder="{{__('End Date')}}">
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="objective_turnover">{{__('Objective turnover')}}</label>
                                <input type="number" class="form-control @error('name') is-invalid @enderror"
                                       id="objective_turnover"
                                       wire:model="objective_turnover"
                                       placeholder="{{__('Enter Objective turnover')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="out_provider_turnover">{{__('Out provider turnover')}}</label>
                                <input type="number" class="form-control @error('name') is-invalid @enderror"
                                       id="out_provider_turnover"
                                       wire:model="out_provider_turnover"
                                       placeholder="{{__('Enter Out provider turnover')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="items_profit_average">{{__('Items profit average')}}</label>
                                <input type="number" class="form-control @error('name') is-invalid @enderror"
                                       id="items_profit_average"
                                       wire:model="items_profit_average"
                                       placeholder="{{__('Enter Items profit average')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>


                            <div class="form-group col-3 mb-3">
                                <label for="initial_commission">{{__('Initial commission')}}</label>
                                <input type="number" class="form-control @error('name') is-invalid @enderror"
                                       id="initial_commission"
                                       wire:model="initial_commission"
                                       placeholder="{{__('Enter Initial commission')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>


                            <div class="form-group col-3 mb-3">
                                <label for="final_commission">{{__('Final commission')}}</label>
                                <input type="number" class="form-control @error('name') is-invalid @enderror"
                                       id="final_commission"
                                       wire:model="final_commission"
                                       placeholder="{{__('Enter Final commission')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>


                            <div class="form-group col-3 mb-3">
                                <label for="precision">{{__('Precision')}}</label>
                                <input type="precision" class="form-control @error('name') is-invalid @enderror"
                                       id="precision"
                                       wire:model="precision"
                                       placeholder="{{__('Enter Precision')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="margin_percentage">{{__('Margin percentage')}}</label>
                                <input type="margin_percentage" class="form-control @error('name') is-invalid @enderror"
                                       id="margin_percentage"
                                       wire:model="margin_percentage"
                                       placeholder="{{__('Enter Margin percentage')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>


                            <div class="form-group col-3 mb-3">
                                <label for="cash_back_margin_percentage">{{__('Cash back margin percentage')}}</label>
                                <input type="cash_back_margin_percentage"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="cash_back_margin_percentage"
                                       wire:model="cash_back_margin_percentage"
                                       placeholder="{{__('Enter Cash back margin percentage')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>


                            <div class="form-group col-3 mb-3">
                                <label
                                    for="proactive_consumption_margin_percentage">{{__('Proactive consumption margin percentage')}}</label>
                                <input type="proactive_consumption_margin_percentage"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="proactive_consumption_margin_percentage"
                                       wire:model="proactive_consumption_margin_percentage"
                                       placeholder="{{__('Enter Proactive consumption margin percentage')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label
                                    for="shareholder_benefits_margin_percentage">{{__('Shareholder benefits margin percentage')}}</label>
                                <input type="shareholder_benefits_margin_percentage"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="shareholder_benefits_margin_percentage"
                                       wire:model="shareholder_benefits_margin_percentage"
                                       placeholder="{{__('Enter Shareholder benefits margin percentage')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="tree_margin_percentage">{{__('Tree margin percentage')}}</label>
                                <input type="tree_margin_percentage"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="tree_margin_percentage"
                                       wire:model="tree_margin_percentage"
                                       placeholder="{{__('Enter Tree margin percentage')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="current_turnover">{{__('Current turnover')}}</label>
                                <input type="current_turnover"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="current_turnover"
                                       wire:model="current_turnover"
                                       placeholder="{{__('Enter Current turnover')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="current_turnover_index">{{__('Current turnover index')}}</label>
                                <input type="current_turnover_index"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="current_turnover_index"
                                       wire:model="current_turnover_index"
                                       placeholder="{{__('Enter Current turnover index')}}"></input>
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="item_price">{{__('Item price')}}</label>
                                <input type="item_price"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="item_price"
                                       wire:model="item_price"
                                       placeholder="{{__('Enter Item price')}}"></input>
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
