<div class="container">
    @section('title')
        {{ $update ? __('Edit Partner Payment') : __('Create Partner Payment') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ $update ? __('Edit Partner Payment') : __('Create Partner Payment') }}
        @endslot
        @slot('li_1')
            <a href="{{route('partner_payment_index', app()->getLocale())}}">{{ __('Partner Payments') }}</a>
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row justify-content-center">
            <div class="col-12 card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        {{ $update ? __('Edit Payment Information') : __('New Payment Information') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            <!-- Amount -->
                            <div class="col-md-6">
                                <label for="amount" class="form-label required">
                                    <i class="ri-money-dollar-circle-line me-1"></i>
                                    {{__('Amount')}}
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           wire:model="amount"
                                           id="amount"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           placeholder="0.00">
                                    <span class="input-group-text">USD</span>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div class="col-md-6">
                                <label for="method" class="form-label required">
                                    <i class="ri-wallet-line me-1"></i>
                                    {{__('Payment Method')}}
                                </label>
                                <select wire:model="method"
                                        id="method"
                                        class="form-select @error('method') is-invalid @enderror">
                                    @foreach($paymentMethods as $key => $label)
                                        <option value="{{$key}}">{{__($label)}}</option>
                                    @endforeach
                                </select>
                                @error('method')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div class="col-md-6">
                                <label for="payment_date" class="form-label">
                                    <i class="ri-calendar-line me-1"></i>
                                    {{__('Payment Date')}}
                                </label>
                                <input type="datetime-local"
                                       wire:model="payment_date"
                                       id="payment_date"
                                       class="form-control @error('payment_date') is-invalid @enderror">
                                @error('payment_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Demand ID -->
                            <div class="col-md-6">
                                <label for="demand_id" class="form-label">
                                    <i class="ri-file-list-line me-1"></i>
                                    {{__('Demand ID (Optional)')}}
                                </label>
                                <input type="text"
                                       wire:model="demand_id"
                                       id="demand_id"
                                       class="form-control @error('demand_id') is-invalid @enderror"
                                       placeholder="{{__('Enter demand/request ID')}}"
                                       maxlength="9">
                                @error('demand_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @if($selectedDemand)
                                    <small class="text-success">
                                        <i class="ri-checkbox-circle-line"></i>
                                        {{__('Demand found')}} - {{__('Amount')}}: {{number_format($selectedDemand->amount, 2)}}
                                    </small>
                                @endif
                            </div>

                            <div class="col-12">
                                <hr class="my-3">
                            </div>

                            <!-- Partner (Receiver) -->
                            <div class="col-md-12">
                                <label for="partner_id" class="form-label required">
                                    <i class="ri-user-star-line me-1"></i>
                                    {{__('Receiver (Partner)')}}
                                </label>
                                @if($selectedPartner)
                                    <div class="card bg-light mb-2">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <div class="avatar-title bg-soft-success text-success rounded-circle">
                                                            {{substr($selectedPartner->name, 0, 1)}}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{$selectedPartner->name}}</h6>
                                                        <small class="text-muted">ID: {{$selectedPartner->id}}</small>
                                                    </div>
                                                </div>
                                                <button type="button"
                                                        wire:click="$set('partner_id', null)"
                                                        class="btn btn-sm btn-danger">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="text"
                                           wire:model.live="searchPartner"
                                           class="form-control @error('partner_id') is-invalid @enderror"
                                           placeholder="{{__('Search partner by name, email or ID...')}}">
                                    @if(!empty($searchPartner))
                                        <div class="list-group mt-2 position-absolute" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                            @forelse($this->searchPartners() as $partner)
                                                <button type="button"
                                                        wire:click="selectPartner({{$partner->id}})"
                                                        class="list-group-item list-group-item-action">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <div class="avatar-title bg-soft-success text-success rounded-circle">
                                                                {{substr($partner->name, 0, 1)}}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{$partner->name}}</h6>
                                                            <small class="text-muted">ID: {{$partner->id}} - {{$partner->email}}</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            @empty
                                                <div class="list-group-item text-muted">
                                                    {{__('No partners found')}}
                                                </div>
                                            @endforelse
                                        </div>
                                    @endif
                                @endif
                                @error('partner_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Information Notice -->
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>{{__('Note')}}:</strong>
                                    {{__('Fields marked with')}} <span class="text-danger">*</span> {{__('are required')}}.
                                    {{__('The payment will be created in pending status and will need validation.')}}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button"
                                    wire:click="cancel"
                                    class="btn btn-secondary">
                                <i class="ri-close-line me-1"></i>
                                {{__('Cancel')}}
                            </button>
                            <button type="submit"
                                    class="btn btn-primary"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="ri-save-line me-1"></i>
                                    {{ $update ? __('Update Payment') : __('Create Payment') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    {{__('Saving...')}}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>

