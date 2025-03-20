<div class="container-fluid">
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
                    <span class="text-info float-end">{{__('Platform')}}: {{$platform?->name}}</span>
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
                        <input type="hidden" wire:model.live="id">
                        <div class="row">
                            <div class="form-group col-3 mb-3">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       wire:model.live="name"
                                       placeholder="{{__('Enter name')}}">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="description">{{__('Description')}}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          wire:model.live="description"
                                          placeholder="{{__('Enter description')}}"></textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="start_date">{{__('Start Date')}}:</label>
                                <input class="form-control" wire:model.live="start_date" type="date"
                                       id="start_date" placeholder="{{__('Start Date')}}">
                                @error('start_date') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="end_date">{{__('End Date')}}:</label>
                                <input class="form-control" wire:model.live="end_date" type="date"
                                       id="end_date" placeholder="{{__('End Date')}}">
                                @error('end_date') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <hr class="text-muted">
                        <div class="row">
                            <div class="form-group col-3 mb-3">
                                <label for="provider_turnover">{{__('Out provider turnover')}}</label> <span
                                    class="text-info float-end">{{__('$')}}</span>

                                <input type="number"
                                       class="form-control @error('provider_turnover') is-invalid @enderror"
                                       id="provider_turnover"
                                       wire:model.live="provider_turnover"
                                       placeholder="{{__('Enter Out provider turnover')}}">
                                @error('provider_turnover') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="objective_turnover">{{__('Objective turnover')}}</label> <span
                                    class="text-info float-end">{{__('$')}}</span>
                                <input type="number"
                                       class="form-control @error('objective_turnover') is-invalid @enderror"
                                       id="objective_turnover"
                                       wire:model.live="objective_turnover"
                                       placeholder="{{__('Enter Objective turnover')}}">
                                @error('objective_turnover') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="items_profit_average">{{__('Items profit average')}}</label>
                                <span class="text-info float-end">{{__('$')}}</span>

                                <input type="number"
                                       class="form-control @error('items_profit_average') is-invalid @enderror"
                                       id="items_profit_average"
                                       wire:model.live="items_profit_average"
                                       placeholder="{{__('Enter Items profit average')}}">
                                @error('items_profit_average') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>


                            <div class="form-group col-3 mb-3">
                                <label for="initial_commission">{{__('Initial commission')}}</label>
                                <span class="text-info float-end">{{__('$')}}</span>

                                <input type="number"
                                       class="form-control @error('initial_commission') is-invalid @enderror"
                                       id="initial_commission"
                                       wire:model.live="initial_commission"
                                       placeholder="{{__('Enter Initial commission')}}">
                                @error('initial_commission') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>


                            <div class="form-group col-3 mb-3">
                                <label for="final_commission">{{__('Final commission')}}</label>
                                <span class="text-info float-end">{{__('$')}}</span>
                                <input type="number"
                                       class="form-control @error('final_commission') is-invalid @enderror"
                                       id="final_commission"
                                       wire:model.live="final_commission"
                                       placeholder="{{__('Enter Final commission')}}">
                                @error('final_commission') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="precision">{{__('Precision')}}</label>
                                <span class="text-info float-end">{{__('0.00001')}}</span>

                                <input type="number" class="form-control @error('precision') is-invalid @enderror"
                                       id="precision"
                                       wire:model.live="precision"
                                       placeholder="{{__('Enter Precision')}}">
                                @error('precision') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                        </div>
                        <hr class="text-muted">
                        <div class="row">
                            <div class="form-group col-3 mb-3">
                                <label for="margin_percentage">{{__('Margin percentage')}}</label> <span
                                    class="text-info float-end">{{__('%')}}</span>

                                <input type="number"
                                       class="form-control @error('margin_percentage') is-invalid @enderror"
                                       id="margin_percentage"
                                       wire:model.live="margin_percentage"
                                       placeholder="{{__('Enter Margin percentage')}}">
                                @error('margin_percentage') <span class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label for="cash_back_margin_percentage">{{__('Cash back margin percentage')}}</label>
                                <span class="text-info float-end">{{__('%')}}</span>

                                <input type="number"
                                       class="form-control @error('cash_back_margin_percentage') is-invalid @enderror"
                                       id="cash_back_margin_percentage"
                                       wire:model.live="cash_back_margin_percentage"
                                       placeholder="{{__('Enter Cash back margin percentage')}}">
                                @error('cash_back_margin_percentage') <span
                                    class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label
                                    for="proactive_consumption_margin_percentage">{{__('Proactive consumption margin percentage')}}</label>
                                <span class="text-info float-end">{{__('%')}}</span>
                                <input type="number"
                                       class="form-control @error('proactive_consumption_margin_percentage') is-invalid @enderror"
                                       id="proactive_consumption_margin_percentage"
                                       wire:model.live="proactive_consumption_margin_percentage"
                                       placeholder="{{__('Enter Proactive consumption margin percentage')}}">
                                @error('proactive_consumption_margin_percentage') <span
                                    class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label
                                    for="shareholder_benefits_margin_percentage">{{__('Shareholder benefits margin percentage')}}</label>
                                <span class="text-info float-end">{{__('%')}}</span>
                                <input type="number"
                                       class="form-control @error('shareholder_benefits_margin_percentage') is-invalid @enderror"
                                       id="shareholder_benefits_margin_percentage"
                                       wire:model.live="shareholder_benefits_margin_percentage"
                                       placeholder="{{__('Enter Shareholder benefits margin percentage')}}">
                                @error('shareholder_benefits_margin_percentage') <span
                                    class="text-danger">{{ $message }}</span>@enderror
                                <div class="form-text">{{__('Required field')}}</div>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label for="tree_margin_percentage">{{__('Tree margin percentage')}}</label>
                                <span class="text-info float-end">{{__('%')}}</span>
                                <input type="number"
                                       class="form-control @error('tree_margin_percentage') is-invalid @enderror"
                                       id="tree_margin_percentage"
                                       wire:model.live="tree_margin_percentage"
                                       placeholder="{{__('Enter Tree margin percentage')}}">
                                @error('tree_margin_percentage') <span
                                    class="text-danger">{{ $message }}</span>@enderror
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
