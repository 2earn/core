<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Configuration Settings') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card">
            <div class="card-body">
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
                    <div class="card mb-3 setting-item border-0">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-secondary mb-1">ID: {{ $setting->idSETTINGS }}</span>
                                    <h6 class="mb-0 fw-bold">{{ $setting->ParameterName }}</h6>
                                </div>
                                @if($setting->Automatically_calculated == 1)
                                    <span class="badge bg-success">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('No') }}</span>
                                @endif
                            </div>

                            <div class="mb-3 p-2 rounded">

                                <small class="text-muted d-block mb-1">{{ __('Value') }}</small>
                                <div class="fw-semibold">
                                    @if(!is_null($setting->IntegerValue))
                                        <span class="badge bg-info text-light me-1">{{ __('Integer') }}</span>
                                        <span>{{ $setting->IntegerValue }}</span>
                                    @elseif(!is_null($setting->StringValue))
                                        <span class="badge bg-warning text-light me-1">{{ __('String') }}</span>
                                        <span>{{ $setting->StringValue }}</span>
                                    @elseif(!is_null($setting->DecimalValue))
                                        <span class="badge bg-primary me-1">{{ __('Decimal') }}</span>
                                        <span>{{ $setting->DecimalValue }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </div>
                            </div>
                            @if($setting->Description)
                                <div class="mb-3 p-2 bg-light rounded">
                                    <small class="text-muted d-block mb-1">{{ __('Description') }}</small>
                                    <div class="fw-semibold">
                                        {{$setting->Description}}
                                    </div>
                                </div>
                            @endif

                            @if($setting->Unit)
                                <div class="mb-3">
                                    <small class="text-muted">{{ __('Unit') }}:</small>
                                    <span class="ms-1 fw-semibold">{{ $setting->Unit }}</span>
                                </div>
                            @endif

                            <div class="d-grid">
                                <a href="{{ route('configuration_setting_edit', ['locale' => app()->getLocale(), 'id' => $setting->idSETTINGS]) }}"
                                   class="btn btn-outline-primary">
                                    {{ __('Edit') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">
                        {{ __('No records found') }}
                    </div>
                @endforelse
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $settings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








