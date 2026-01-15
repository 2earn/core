<div class="container">
    @section('title')
        {{ __('Partners') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Partners') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card">
            <div class="card-body">
                <div class="card-header border-info">
                    <div class="row">
                        <div class="float-end col-sm-12 col-md-6 col-lg-6">
                            <form class="items-center">
                                <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                                <div class="w-full">
                                    <input wire:model.live="search" type="text" id="simple-search"
                                           class="form-control float-end"
                                           placeholder="{{__('Search partner')}}">
                                </div>
                            </form>
                        </div>
                        @if(\App\Models\User::isSuperAdmin())
                            <div class="col-sm-12 col-md-3  col-lg-6">
                                <a href="{{route('partner_create_update', app()->getLocale())}}"
                                   class="btn btn-outline-info add-btn float-end"
                                   id="create-btn">
                                    {{__('Create new partner')}}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-body row">
                    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">{{ __('Showing') }} {{ $partners->count() }}
                            / {{ $partners->total() }} {{ __('partners') }}</div>
                        <div></div>
                    </div>

                    @if($partners->count())
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th>{{__('Company Name')}}</th>
                                        <th>{{__('Business Sector')}}</th>
                                        <th>{{__('Platform URL')}}</th>
                                        <th>{{__('Created At')}}</th>
                                        @if(\App\Models\User::isSuperAdmin())
                                            <th class="text-end">{{__('Actions')}}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($partners as $partner)
                                        <tr>
                                            <td>
                                                <strong>{{ $partner->company_name }}</strong>
                                            </td>
                                            <td>
                                                @if($partner->business_sector)
                                                    <span class="badge bg-soft-info text-info">{{ $partner->business_sector }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($partner->platform_url)
                                                    <a href="{{ $partner->platform_url }}" target="_blank"
                                                       class="text-primary" title="{{__('Visit website')}}">
                                                        <i class="ri-external-link-line"></i>
                                                        {{ Str::limit($partner->platform_url, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ $partner->created_at->format('Y-m-d H:i') }}</small>
                                            </td>
                                            @if(\App\Models\User::isSuperAdmin())
                                                <td class="text-end">
                                                    <a href="{{route('partner_show',['locale'=> app()->getLocale(),'id'=>$partner->id])}}"
                                                       class="btn btn-sm btn-soft-info me-1"
                                                       title="{{__('View Details')}}">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a href="{{route('partner_create_update',['locale'=> app()->getLocale(),'id'=>$partner->id])}}"
                                                       class="btn btn-sm btn-soft-primary me-1"
                                                       title="{{__('Edit')}}">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <button wire:click="deletePartner('{{ $partner->id }}')"
                                                            class="btn btn-sm btn-soft-danger"
                                                            title="{{__('Delete Partner')}}"
                                                            onclick="return confirm('{{__('Are you sure you want to delete this partner?')}}')">
                                                        <i class="ri-delete-bin-line"></i>
                                                        <span wire:loading wire:target="deletePartner('{{ $partner->id }}')"
                                                              class="spinner-border spinner-border-sm ms-1"
                                                              role="status" aria-hidden="true"></span>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mt-3">{{ $partners->links() }}</div>
                    @else
                        <div class="col-12 py-5 text-center">
                            <i class="ri-team-line display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No partners') }}</h5>
                            <p class="text-muted">{{ __('There are no partners yet.') }}</p>
                            @if(\App\Models\User::isSuperAdmin())
                                <p class="text-muted">{{ __('Use the "Create new partner" button above to add partners.') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

