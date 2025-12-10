<div class="{{getContainerType()}}">
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
        <div class="card-body row ">
            <div class="card-body">
                <form>
                    <input type="hidden" wire:model.live="id">
                    <div class="row">
                        <div class="form-group col-6 mb-3">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   wire:model.live="name"
                                   placeholder="{{__('Enter name')}}">
                            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="description">{{__('Description')}}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      wire:model.live="description"
                                      placeholder="{{__('Enter description')}}"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>

                        <div class="form-group col-6 mb-3">
                            <label for="start_date">{{__('Start Date')}}:</label>
                            <input class="form-control" wire:model.live="start_date" type="datetime-local"
                                   id="start_date" placeholder="{{__('Start Date')}}">
                            @error('start_date') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="end_date">{{__('End Date')}}:</label>
                            <input class="form-control" wire:model.live="end_date" type="datetime-local"
                                   id="end_date" placeholder="{{__('End Date')}}">
                            @error('end_date') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <hr class="text-muted">
                    <div class="row">
                        <div class="form-group col-4">
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
                        <div class="form-group col-4">
                            <label for="target_turnover">{{__('Target turnover')}}</label> <span
                                class="text-info float-end">{{__('$')}}</span>
                            <input type="number"
                                   class="form-control @error('target_turnover') is-invalid @enderror"
                                   id="target_turnover"
                                   wire:model.live="target_turnover"
                                   placeholder="{{__('Enter Objective turnover')}}">
                            @error('target_turnover') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>
                        <div class="form-group col-4">
                            <label for="second_target_turnover">{{__('Second Target Turnover')}}</label> <span
                                class="text-info float-end">{{__('$')}}</span>
                            <input type="number"
                                   class="form-control @error('second_target_turnover') is-invalid @enderror"
                                   id="second_target_turnover"
                                   wire:model.live="second_target_turnover"
                                   placeholder="{{__('Enter Second Target Turnover')}}">
                            @error('second_target_turnover') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-4">
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


                        <div class="form-group col-4">
                            <label for="initial_commission">{{__('Initial Commission')}}</label>
                            <span class="text-info float-end">{{__('%')}}</span>
                            <input type="number"
                                   step="0.01"
                                   class="form-control @error('initial_commission') is-invalid @enderror"
                                   id="initial_commission"
                                   wire:model.live="initial_commission"
                                   placeholder="{{__('Enter Initial Commission')}}">
                            @error('initial_commission') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>

                        <div class="form-group col-4">
                            <label for="final_commission">{{__('Final Commission')}}</label>
                            <span class="text-info float-end">{{__('%')}}</span>
                            <input type="number"
                                   step="0.01"
                                   class="form-control @error('final_commission') is-invalid @enderror"
                                   id="final_commission"
                                   wire:model.live="final_commission"
                                   placeholder="{{__('Enter Final Commission')}}">
                            @error('final_commission') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>
                    </div>
                    <hr class="text-muted">
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="earn_profit">{{__('2earn profit')}}</label> <span
                                class="text-info float-end">{{__('%')}}</span>

                            <input type="number"
                                   class="form-control @error('earn_profit') is-invalid @enderror"
                                   id="earn_profit"
                                   wire:model.live="earn_profit"
                                   placeholder="{{__('Enter earn_profit')}}">
                            @error('earn_profit') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>
                        <div class="form-group col-4">
                            <label for="tree_remuneration">{{__('tree_remuneration')}}</label> <span
                                class="text-info float-end">{{__('%')}}</span>

                            <input type="number"
                                   class="form-control @error('tree_remuneration') is-invalid @enderror"
                                   id="tree_remuneration"
                                   wire:model.live="tree_remuneration"
                                   placeholder="{{__('Enter tree_remuneration')}}">
                            @error('tree_remuneration') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>
                        <div class="form-group col-4">
                            <label for="earn_profit">{{__('proactive_cashback')}}</label> <span
                                class="text-info float-end">{{__('%')}}</span>

                            <input type="number"
                                   class="form-control @error('proactive_cashback') is-invalid @enderror"
                                   id="proactive_cashback"
                                   wire:model.live="proactive_cashback"
                                   placeholder="{{__('Enter proactive_cashback')}}">
                            @error('proactive_cashback') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>
                        <div class="form-group col-4">
                            <label for="jackpot">{{__('jackpot')}}</label> <span
                                class="text-info float-end">{{__('%')}}</span>

                            <input type="number"
                                   class="form-control @error('jackpot') is-invalid @enderror"
                                   id="jackpot"
                                   wire:model.live="jackpot"
                                   placeholder="{{__('Enter jackpot')}}">
                            @error('jackpot') <span class="text-danger">{{ $message }}</span>@enderror
                            <div class="form-text">{{__('Required field')}}</div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            @if($update)
                                <button wire:click.prevent="updateDeal()"
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
</div>
