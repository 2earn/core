<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Configuration Settings') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="card">
        <div class="card-body">
            <!-- Search and Per Page Controls -->
            <div class="row mb-3">
                <div class="col-md-6 col-12 mb-2 mb-md-0">
                    <div class="d-flex align-items-center">
                        <label class="me-2 text-nowrap">{{ __('Show') }}</label>
                        <select wire:model.live="perPage" class="form-select form-select-sm" style="width: auto;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="ms-2 text-nowrap">{{ __('entries') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm"
                           placeholder="{{ __('Search') }}...">
                </div>
            </div>
                @forelse($settings as $setting)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4 fw-bold text-muted small">{{ __('id') }}:</div>
                                <div class="col-8">{{ $setting->idSETTINGS }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 fw-bold text-muted small">{{ __('Name') }}:</div>
                                <div class="col-8">{{ $setting->ParameterName }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 fw-bold text-muted small">{{ __('Value') }}:</div>
                                <div class="col-8">
                                    @if(!is_null($setting->IntegerValue))
                                        {{ __('Integer') }} : {{ $setting->IntegerValue }}
                                    @elseif(!is_null($setting->StringValue))
                                        {{ __('String') }} : {{ $setting->StringValue }}
                                    @elseif(!is_null($setting->DecimalValue))
                                        {{ __('Decimal') }} : {{ $setting->DecimalValue }}
                                    @else
                                        0
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 fw-bold text-muted small">{{ __('Unit') }}:</div>
                                <div class="col-8">{{ $setting->Unit }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 fw-bold text-muted small">{{ __('AutoCalculated') }}:</div>
                                <div class="col-8">
                                    @if($setting->Automatically_calculated == 1)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('No') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-grid">
                                <button wire:click="editSetting({{ $setting->idSETTINGS }})"
                                        data-bs-toggle="modal" data-bs-target="#settingModal"
                                        class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">{{ __('No records found') }}</div>
                @endforelse
            <div class="row mt-3">
                <div class="col-12">
                    {{ $settings->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="settingModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">{{__('Update param')}}</h5>
                    <button type="button" class="btn-close btn-close-setting " data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Name') }}</label>
                                <input type="text" class="form-control" disabled wire:model.live="parameterName"
                                       placeholder="{{ __('Name') }}">
                            </div>
                            @if(!is_null($IntegerValue))
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('IntegerValue') }}</label>
                                    <input type="number" class="form-control" wire:model.live="IntegerValue"
                                           placeholder="{{ __('IntegerValue') }}" name="IntegerValue">
                                </div>
                            @endif
                            @if(!is_null($StringValue))
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('StringValue') }}</label>
                                    <input type="text" class="form-control" wire:model.live="StringValue"
                                           placeholder="{{ __('StringValue') }}" name="StringValue">
                                </div>
                            @endif
                            @if(!is_null($DecimalValue))
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2"> {{ __('DecimalValue') }}</label>
                                    <input type="number" class="form-control" wire:model.live="DecimalValue"
                                           placeholder="{{ __('DecimalValue') }}" name="DecimalValue">
                                </div>
                            @endif
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Unit') }}</label>
                                <input maxlength="5" type="text" class="form-control" wire:model.live="Unit"
                                       placeholder="{{ __('Unit') }}" name="Unit">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('I/O') }}</label>
                                <select class="form-control" name="Automatically_calculated"
                                        wire:model.live="Automatically_calculated">
                                    <option value="0"> {{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-12">
                                <label class="me-sm-2">{{ __('description') }}</label>
                                <textarea maxlength="250" class="form-control" wire:model.live="Description"
                                          placeholder="{{ __('description') }}" name="Description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="saveSetting"
                            class="btn btn-primary">{{__('Save changes')}}</button>
                    <button type="button" class="btn btn-light btn-close-setting"
                            data-bs-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>








