<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Platform') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Platform') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3 align-items-center">
                    <div class="col-12">
                        <div class="position-relative">
                            <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                            <input type="text"
                                   class="form-control form-control ps-5 pe-3 border-0 bg-light"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="{{__('Search platforms by name, type or ID...')}}">
                        </div>
                    </div>
                    <div class="col-12 text-md-end">
                        <a href="{{route('platform_all_requests', app()->getLocale())}}"
                           class="btn btn-sm btn-outline-info ms-auto">
                            <i class="fas fa-list me-1"></i>
                            {{__('All Requests')}}
                        </a>
                        <a href="{{route('platform_create_update', app()->getLocale())}}"
                           class="btn btn-sm btn-outline-info ms-auto">
                            {{__('Create platform')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($platforms as $platform)
            <div class="col-12 card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 me-3">
                            @if($platform?->logoImage)
                                <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                     alt="{{ $platform->name }} logo"
                                     class="img-fluid rounded"
                                     style="max-height: 150px; object-fit: contain;">
                            @else
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded bg-soft-primary text-primary fs-1">
                                        {{strtoupper(substr($platform->name, 0, 1))}}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="mb-1 text-truncate">
                                {{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
                            </h5>

                            @if(\App\Models\User::isSuperAdmin())
                                <div class="mt-3 pt-3 border-top">
                                    <a class="btn btn-sm btn-soft-info"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">
                                        <i class="ri-translate-2 align-middle me-1"></i>{{__('Update Translation')}}
                                    </a>
                                </div>
                            @endif
                            @if($platform->pendingValidationRequest)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="alert alert-info py-2 px-3 mb-2 d-flex align-items-center"
                                         role="alert">
                                        <i class="ri-shield-check-line me-2 fs-5"></i>
                                        <div class="flex-grow-1">
                                            <strong class="small">{{__('Pending Validation Request')}}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($platform->pendingTypeChangeRequest)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="alert alert-warning py-2 px-3 mb-2 d-flex align-items-center"
                                         role="alert">
                                        <i class="ri-alert-line me-2 fs-5"></i>
                                        <div class="flex-grow-1">
                                            <strong class="small">{{__('Pending Type Change Request')}}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($platform->pendingChangeRequest)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="alert alert-success py-2 px-3 mb-2 d-flex align-items-center"
                                         role="alert">
                                        <i class="ri-file-edit-line me-2 fs-5"></i>
                                        <div class="flex-grow-1">
                                            <strong class="small">{{__('Pending Platform Update Request')}}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <p class="text-muted mb-0">
                                <span class="badge badge-soft-secondary">ID: {{$platform->id}}</span>
                                @if($platform->enabled)
                                    <span class="badge bg-success-subtle text-success ms-2">
                                            <i class="ri-checkbox-circle-line align-middle me-1"></i>{{__('Enabled')}}
                                        </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger ms-2">
                                            <i class="ri-close-circle-line align-middle me-1"></i>{{__('Disabled')}}
                                        </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row g-2">
                            <div class="col-sm-6 col-md-4 col-lg-4">
                                <div class="rounded">
                                    <p class="text-muted mb-0 small">{{__('Business sector')}}</p>
                                    <h6 class="mb-0 text-truncate">{{$platform->businessSector->name ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4">
                                <div class="rounded">
                                    <p class="text-muted mb-1 fs-6">{{__('Type')}}</p>
                                    <h6 class="mb-0">{{__(\Core\Enum\PlatformType::tryFrom($platform->type)->name) ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4">
                                <div class="rounded">
                                    <p class="text-muted mb-1 fs-6">{{__('Created')}}</p>
                                    <h6 class="mb-0">{{$platform->created_at->format(config('app.date_format'))}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($platform->description)
                        <div class="pt-3 border-top">
                            <p class="text-muted mb-0">
                                {!! \App\Models\TranslaleModel::getTranslation($platform,'description',$platform->description) !!}
                            </p>
                            @if(\App\Models\User::isSuperAdmin())
                                <a class="btn btn-sm btn-soft-info float-end"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'description')])}}">
                                    <i class="ri-translate-2 align-middle me-1"></i>{{__('Update Translation')}}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent border-top p-3">
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <div class="d-flex gap-1">
                            @if(\App\Models\User::isSuperAdmin())
                                @if($platform->pendingValidationRequest)
                                    <div class="alert alert-info p-2 mb-0 w-100" role="alert">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="flex-grow-1">
                                                <small class="mb-0">
                                                    <i class="ri-shield-check-line me-1"></i>
                                                    <strong>{{__('Platform Validation Pending')}}</strong>
                                                </small>
                                            </div>
                                            <a href="{{route('platform_validation_requests', app()->getLocale())}}"
                                               class="btn btn-primary btn-sm">
                                                <i class="ri-check-double-line align-middle me-1"></i>{{__('Review')}}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if($platform->pendingTypeChangeRequest)
                                    <div class="alert alert-warning p-2 mb-0 w-100" role="alert">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="flex-grow-1">
                                                <small class="mb-0">
                                                    <i class="ri-arrow-left-right-line me-1"></i>
                                                    <strong>{{__('Type Change')}}: </strong>
                                                    {{__(\Core\Enum\PlatformType::tryFrom($platform->pendingTypeChangeRequest->old_type)->name)}}
                                                    <i class="ri-arrow-right-s-line"></i>
                                                    {{__(\Core\Enum\PlatformType::tryFrom($platform->pendingTypeChangeRequest->new_type)->name)}}
                                                </small>
                                            </div>
                                            <a href="{{route('platform_type_change_requests', app()->getLocale())}}"
                                               class="btn btn-warning btn-sm">
                                                <i class="ri-check-double-line align-middle me-1"></i>{{__('Validate')}}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if($platform->pendingChangeRequest)
                                    <div class="alert alert-success p-2 mb-0 w-100" role="alert">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="flex-grow-1">
                                                <small class="mb-0">
                                                    <i class="ri-file-edit-line me-1"></i>
                                                    <strong>{{__('Platform Update Pending')}}</strong>
                                                    @if($platform->pendingChangeRequest->changes)
                                                        <span
                                                            class="ms-1">({{ count($platform->pendingChangeRequest->changes) }} {{__('field(s)')}})</span>
                                                    @endif
                                                </small>
                                            </div>
                                            <a href="{{route('platform_change_requests', app()->getLocale())}}"
                                               class="btn btn-success btn-sm">
                                                <i class="ri-check-double-line align-middle me-1"></i>{{__('Review')}}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <a href="{{route('platform_show', ['locale' => app()->getLocale(), 'id' => $platform->id])}}"
                               class="btn btn-soft-secondary btn-sm">
                                <i class="ri-eye-line align-middle me-1"></i>{{__('View')}}
                            </a>
                            @if(!$platform->pendingValidationRequest && !$platform->pendingTypeChangeRequest && $platform->enabled)
                                <a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'idPlatform' => $platform->id])}}"
                                   class="btn btn-soft-primary btn-sm">
                                    {{__('Create Deal')}}
                                </a>
                                <a href="{{route('items_platform_create_update', ['locale' => app()->getLocale(), 'platformId' => $platform->id])}}"
                                   class="btn btn-soft-secondary btn-sm">
                                    {{__('Create Item')}}
                                </a>
                            @endif
                            <a href="{{route('platform_create_update', ['locale' => app()->getLocale(), 'id' => $platform->id])}}"
                               class="btn btn-soft-info btn-sm">
                                <i class="ri-pencil-line align-middle me-1"></i>{{__('Edit')}}
                            </a>

                            <a class="btn btn-soft-danger btn-sm"
                               href="#"
                               onclick="confirmDelete('{{$platform->id}}', '{{$platform->name}}')">
                                <i class="ri-delete-bin-line me-2 align-middle"></i>{{__('Delete')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="avatar-xl mx-auto mb-4">
                        <div class="avatar-title bg-soft-info text-info rounded-circle">
                            <i class="ri-stack-line display-4"></i>
                        </div>
                    </div>
                    <h4 class="mb-2">{{__('No platforms found')}}</h4>
                    <p class="text-muted mb-4">{{__('Try adjusting your search or create a new platform')}}</p>
                    <a href="{{route('platform_create_update', app()->getLocale())}}"
                       class="btn btn-info btn-lg">
                        <i class="ri-add-circle-line me-1"></i>{{__('Create platform')}}
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    @if($platforms->hasPages())
        <div class="row mt-2">
            <div class="col-12 card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            <i class="ri-file-list-line me-1"></i>
                            {{__('Showing')}}
                            <span class="fw-semibold text-dark">{{$platforms->firstItem() ?? 0}}</span>
                            {{__('to')}}
                            <span class="fw-semibold text-dark">{{$platforms->lastItem() ?? 0}}</span>
                            {{__('of')}}
                            <span class="fw-semibold text-dark">{{$platforms->total()}}</span>
                            {{__('results')}}
                        </div>
                        <div>
                            {{$platforms->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function confirmDelete(id, name) {
            event.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '{{__('Are you sure to delete this platform')}}?',
                    html: '<h5 class="mt-2">' + name + '</h5>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{__('Delete')}}',
                    cancelButtonText: '{{__('Cancel')}}'
                }).then((result) => {
                    if (result.isConfirmed) {
                    @this.call('delete', id)
                        ;
                    }
                });
            } else {
                if (confirm('{{__('Are you sure to delete this platform')}}? ' + name)) {
                @this.call('delete', id)
                    ;
                }
            }
        }
    </script>
</div>
