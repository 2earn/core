<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Configuration amounts') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="{{ __('Search...') }}">
                </div>

                <div class="list-group">
                    @forelse($amounts as $amount)
                        <div class="list-group-item list-group-item-action mb-3 border rounded">
                            <div class="row align-items-center g-3">
                                <div class="col-md-3">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ __('Name') }}</small>
                                        <span class="fw-semibold">{{ $amount->amountsname }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ __('ShortName') }}</small>
                                        <span>{{ $amount->amountsshortname }}</span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ __('WithHoldinTax') }}</small>
                                        <div class="mt-1">
                                            @if ($amount->amountswithholding_tax == 1)
                                                <span class="badge bg-success">{{__('Yes')}}</span>
                                            @else
                                                <span class="badge bg-danger">{{__('No')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ __('transfer') }}</small>
                                        <div class="mt-1">
                                            @if ($amount->amountstransfer == 1)
                                                <span class="badge bg-success">{{__('Yes')}}</span>
                                            @else
                                                <span class="badge bg-danger">{{__('No')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ __('PaymentRequest') }}</small>
                                        <div class="mt-1">
                                            @if ($amount->amountspaymentrequest == 1)
                                                <span class="badge bg-success">{{__('Yes')}}</span>
                                            @else
                                                <span class="badge bg-danger">{{__('No')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ __('Cash') }}</small>
                                        <div class="mt-1">
                                            @if ($amount->amountscash == 1)
                                                <span class="badge bg-success">{{__('Yes')}}</span>
                                            @else
                                                <span class="badge bg-danger">{{__('No')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ __('Active') }}</small>
                                        <div class="mt-1">
                                            @if ($amount->amountsactive == 1)
                                                <span class="badge bg-success">{{__('Yes')}}</span>
                                            @else
                                                <span class="badge bg-danger">{{__('No')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a wire:click="editAmounts({{ $amount->idamounts }})" data-bs-toggle="modal" data-bs-target="#AmountsModal"
                                       class="btn btn-sm btn-primary btn2earnTable" style="cursor: pointer;">
                                        <i class="glyphicon glyphicon-edit"></i>
                                        {{__('Update')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center" role="alert">
                            <i class="mdi mdi-information-outline me-2"></i>
                            {{ __('No records found') }}
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ $amounts->links() }}
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="AmountsModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="AmountsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AmountsModalLabel">{{__('Edit amounts')}}</h5>
                    <button type="button" class="btn-close btn-close-amount" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-xl-4">
                                <label class="me-sm-2">{{ __('Amount Name') }}</label>
                                <input wire:model="amountsnameAm" type="text" class="form-control"
                                       disabled placeholder="amountsname" name="amountsname">
                            </div>
                            <div class="col-xl-4">
                                <label class="me-sm-2">{{ __('Amount Short Name') }}</label>
                                <input wire:model="amountsshortnameAm" type="text" class="form-control"
                                       placeholder="amountsshortname" name="amountsshortname">
                            </div>

                            <div class="col-xl-4">
                                <label class="me-sm-2">{{ __('With Holding Tax') }}</label>
                                <select wire:model="amountswithholding_taxAm" class="form-control"
                                        name="amountswithholding_tax">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <label class="me-sm-2">{{ __('Payment Request') }}</label>
                                <select wire:model="amountspaymentrequestAm" class="form-control"
                                        name="amountspaymentrequest">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <label class="me-sm-2">{{ __('Transfer') }}</label>
                                <select wire:model="amountstransferAm" class="form-control"
                                        name="amountstransfer">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <label class="me-sm-2">{{ __('Cash') }}</label>
                                <select wire:model="amountscashAm" class="form-control" name="amountscash">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <label class="me-sm-2">{{ __('Active') }}</label>
                                <select wire:model="amountsactiveAm" class="form-control" name="amountsactive">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="saveAmounts"
                            class="btn btn-primary">{{__('Save changes')}}</button>
                    <button type="button" class="btn btn-secondary btn-close-amount"
                            data-bs-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>

</div>








