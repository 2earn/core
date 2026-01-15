<div class="container">
    @section('title')
        {{ __('Partner Details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Partner Details') }}
        @endslot
        @slot('li_1')
            <a href="{{route('partner_index', app()->getLocale())}}">{{ __('Partners') }}</a>
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-building-line me-2"></i>{{ $partner->company_name }}
                        </h5>
                        @if(\App\Models\User::isSuperAdmin())
                            <div>
                                <a href="{{route('partner_create_update',['locale'=> app()->getLocale(),'id'=>$partner->id])}}"
                                   class="btn btn-sm btn-soft-primary">
                                    <i class="ri-edit-line me-1"></i>{{__('Edit')}}
                                </a>
                                <a href="{{route('partner_index', app()->getLocale())}}"
                                   class="btn btn-sm btn-soft-secondary">
                                    <i class="ri-arrow-left-line me-1"></i>{{__('Back to List')}}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                    <tr>
                                        <th class="ps-0" style="width: 200px;">{{__('Company Name')}}:</th>
                                        <td class="text-muted">{{ $partner->company_name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">{{__('Business Sector')}}:</th>
                                        <td class="text-muted">
                                            @if($partner->businessSector)
                                                <span class="badge bg-soft-info text-info">{{ $partner->businessSector->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">{{__('Platform URL')}}:</th>
                                        <td class="text-muted">
                                            @if($partner->platform_url)
                                                <a href="{{ $partner->platform_url }}" target="_blank"
                                                   class="text-primary">
                                                    <i class="ri-external-link-line me-1"></i>{{ $partner->platform_url }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 align-top">{{__('Platform Description')}}:</th>
                                        <td class="text-muted">
                                            @if($partner->platform_description)
                                                <div class="preserve-whitespace">{{ $partner->platform_description }}</div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 align-top">{{__('Partnership Reason')}}:</th>
                                        <td class="text-muted">
                                            @if($partner->partnership_reason)
                                                <div class="preserve-whitespace">{{ $partner->partnership_reason }}</div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">{{__('Created At')}}:</th>
                                        <td class="text-muted">{{ $partner->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">{{__('Updated At')}}:</th>
                                        <td class="text-muted">{{ $partner->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    @if($partner->creator)
                                        <tr>
                                            <th class="ps-0">{{__('Created By')}}:</th>
                                            <td class="text-muted">{{ $partner->creator->name ?? $partner->creator->email }}</td>
                                        </tr>
                                    @endif
                                    @if($partner->updater)
                                        <tr>
                                            <th class="ps-0">{{__('Updated By')}}:</th>
                                            <td class="text-muted">{{ $partner->updater->name ?? $partner->updater->email }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .preserve-whitespace {
        white-space: pre-wrap;
    }
</style>

