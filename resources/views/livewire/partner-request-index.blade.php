<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Partner Requests') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Partner Requests Management') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">{{ __('Partner Requests') }}</h5>
            </div>
            <div class="card-body">
                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text"
                               class="form-control"
                               wire:model.live="searchTerm"
                               placeholder="{{ __('Search by company name or user...') }}">
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" wire:model.live="statusFilter">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="1">{{ __('In Progress') }}</option>
                            <option value="2">{{ __('Validated 2earn') }}</option>
                            <option value="3">{{ __('Validated') }}</option>
                            <option value="4">{{ __('Rejected') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Company Name') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Business Sector') }}</th>
                                <th>{{ __('Platform URL') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Request Date') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($partnerRequests as $request)
                                <tr>
                                    <td>
                                        <strong>{{ $request->company_name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ $request->user?->name }}
                                        <br>
                                        <small class="text-muted">{{ $request->user?->email }}</small>
                                    </td>
                                    <td>{{ $request->businessSector?->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ $request->platform_url }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-link"></i> {{ __('Visit') }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($request->status == \Core\Enum\BePartnerRequestStatus::InProgress->value)
                                            <span class="badge bg-warning">{{ __('In Progress') }}</span>
                                        @elseif($request->status == \Core\Enum\BePartnerRequestStatus::Validated->value)
                                            <span class="badge bg-success">{{ __('Validated') }}</span>
                                        @elseif($request->status == \Core\Enum\BePartnerRequestStatus::Validated2earn->value)
                                            <span class="badge bg-info">{{ __('Validated 2earn') }}</span>
                                        @elseif($request->status == \Core\Enum\BePartnerRequestStatus::Rejected->value)
                                            <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->request_date?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('requests_partner_show', [$request->id], false) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted">{{ __('No partner requests found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $partnerRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

