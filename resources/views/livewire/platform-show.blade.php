<div class="container">
    @section('title')
        {{ __('Platform Profile') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')
            <li class="breadcrumb-item"><a
                    href="{{route('platform_index',['locale'=>app()->getLocale()])}}">{{__('Platforms')}}</a></li>
        @endslot
        @slot('title')
            {{ __('Platform Profile') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12 card border-0 shadow-sm overflow-hidden">
            <div class="bg-soft-primary" style="height: 120px;"></div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-end mb-3" style="margin-top: -60px;">

                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xl border border-4 border-white rounded-circle bg-white shadow">
                                    @if ($platform?->logoImage)
                                        <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                             class="avatar-xl rounded-circle"
                                             alt="{{$platform->name}}">
                                    @else
                                        <img src="{{Vite::asset(\App\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                             class="avatar-xl rounded-circle"
                                             alt="{{$platform->name}}">
                                    @endif
                                </div>
                            </div>


                            <div class="flex-grow-1">
                                <h3 class="mb-1" title="ID: {{$platform->id}}">
                                    {{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
                                    @if($platform->enabled)
                                        <span class="badge bg-success-subtle text-success align-middle">
                                                <i class="ri-checkbox-circle-line align-middle"></i> {{__('Enabled')}}
                                            </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger align-middle">
                                                <i class="ri-close-circle-line align-middle"></i> {{__('Disabled')}}
                                            </span>
                                    @endif
                                </h3>
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    @if($platform->type)
                                        <div class="text-muted">
                                            <i class="ri-stack-line align-middle me-1"></i>
                                            <span
                                                class="fw-medium">{{__(\App\Enums\PlatformType::tryFrom($platform->type)->name) ?? 'N/A'}}</span>
                                        </div>
                                    @endif
                                    @if($platform->businessSector)
                                        <div class="text-muted">
                                            <i class="ri-building-line align-middle me-1"></i>
                                            <span class="fw-medium">{{$platform->businessSector->name}}</span>
                                        </div>
                                    @endif
                                    @if($platform->created_at)
                                        <div class="text-muted">
                                            <i class="ri-calendar-line align-middle me-1"></i>
                                            <span>{{__('Joined')}} {{$platform->created_at->format(config('app.date_format'))}}</span>
                                        </div>
                                    @endif
                                </div>
                                @if(\App\Models\User::isSuperAdmin())
                                    <div class="mt-2">
                                        <a class="btn btn-sm btn-soft-info"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">
                                            <i class="ri-translate-2 align-middle me-1"></i>{{__('Update Translation')}}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-2">
                        <div class="d-flex justify-content-lg-end mx-2 mt-lg-0">
                            @if($platform->link)
                                <a href="{{$platform->link}}"
                                   target="_blank"
                                   class="btn btn-info">
                                    <i class="ri-external-link-line align-middle me-1"></i>
                                    {{__('Visit Website')}}
                                </a>
                            @endif
                            <a href="{{route('platform_create_update',['locale'=>app()->getLocale(), 'id' => $platform->id])}}"
                               class="btn btn-soft-secondary">
                                <i class="ri-pencil-line align-middle me-1"></i>
                                {{__('Edit')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Total Deals')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-success text-success rounded fs-3">
                                    <i class="ri-price-tag-3-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                {{$platform->deals_count ?? 0}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Total Items')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-info text-info rounded fs-3">
                                    <i class="ri-shopping-bag-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                {{$platform->items_count ?? 0}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Total Coupons')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-warning text-warning rounded fs-3">
                                    <i class="ri-coupon-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                {{$platform->coupons_count ?? 0}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{__('Status')}}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                    <i class="ri-pulse-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h5 class="mb-0">
                                @if($platform->enabled)
                                    <span class="badge bg-success fs-6">{{__('Enabled')}}</span>
                                @else
                                    <span class="badge bg-danger fs-6">{{__('Disabled')}}</span>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-file-text-line align-middle me-1"></i>
                        {{__('Description')}}
                    </h5>
                </div>
                <div class="card-body">
                    @if($platform->description)
                        <div class="text-muted">
                            {!! \App\Models\TranslaleModel::getTranslation($platform,'description',$platform->description) !!}
                        </div>
                        @if(\App\Models\User::isSuperAdmin())
                            <div class="mt-3 pt-3 border-top">
                                <a class="btn btn-sm btn-soft-info"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'description')])}}">
                                    <i class="ri-translate-2 align-middle me-1"></i>{{__('Update Translation')}}
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="ri-file-text-line display-4 mb-3 d-block text-muted opacity-50"></i>
                            <p class="mb-0">{{__('No description available')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-links-line align-middle me-1"></i>
                        {{__('Quick Actions')}}
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{route('platform_create_update',['locale'=>app()->getLocale(), 'id' => $platform->id])}}"
                       class="btn btn-soft-info mx-2">
                        <i class="ri-pencil-line align-middle me-1"></i>{{__('Edit Platform')}}
                    </a>
                    @if($platform->link)
                        <a href="{{$platform->link}}"
                           target="_blank"
                           class="btn btn-soft-secondary mx-2">
                            <i class="ri-external-link-line align-middle me-1"></i>{{__('Visit Website')}}
                        </a>
                    @endif
                    <a href="{{route('platform_index',['locale'=>app()->getLocale()])}}"
                       class="btn btn-soft-secondary mx-2">
                        <i class="ri-arrow-left-line align-middle me-1"></i>{{__('Back to List')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line align-middle me-1"></i>
                        {{__('Platform Details')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                            <tr>
                                <td class="text-muted">{{__('ID')}}</td>
                                <td class="fw-medium text-end">
                                    <span class="badge bg-soft-secondary text-secondary">{{$platform->id}}</span>
                                </td>
                            </tr>
                            @if($platform->type)
                                <tr>
                                    <td class="text-muted">{{__('Type')}}</td>
                                    <td class="fw-medium text-end">{{__(\App\Enums\PlatformType::tryFrom($platform->type)->name) ?? 'N/A'}}</td>
                                </tr>
                            @endif
                            @if($platform->businessSector)
                                <tr>
                                    <td class="text-muted">{{__('Business Sector')}}</td>
                                    <td class="fw-medium text-end">{{$platform->businessSector->name}}</td>
                                </tr>
                            @endif
                            @if($platform->show_profile)
                                <tr>
                                    <td class="text-muted">{{__('Show Profile')}}</td>
                                    <td class="text-end">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="ri-eye-line align-middle"></i> {{__('Yes')}}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-muted">{{__('Created At')}}</td>
                                <td class="fw-medium text-end">{{$platform->created_at->format(config('app.date_format'))}}</td>
                            </tr>
                            @if($platform->updated_at)
                                <tr>
                                    <td class="text-muted">{{__('Updated At')}}</td>
                                    <td class="fw-medium text-end">{{$platform->updated_at->format(config('app.date_format'))}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-team-line align-middle me-1"></i>
                        {{__('Management Team')}}
                    </h5>
                </div>
                <div class="card-body">
                    @if($platform->owner_id || $platform->marketing_manager_id || $platform->financial_manager_id)
                        <div class="vstack gap-3">
                            @if($platform->owner_id)
                                <div class="d-flex align-items-center p-2 bg-soft-primary rounded">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="ri-user-star-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="text-muted mb-0 small">{{__('Owner')}}</p>
                                        <h6 class="mb-0">ID: {{$platform->owner_id}}</h6>
                                    </div>
                                </div>
                            @endif
                            @if($platform->marketing_manager_id)
                                <div class="d-flex align-items-center p-2 bg-soft-info rounded">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-info">
                                                <i class="ri-bar-chart-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="text-muted mb-0 small">{{__('Marketing Manager')}}</p>
                                        <h6 class="mb-0">ID: {{$platform->marketing_manager_id}}</h6>
                                    </div>
                                </div>
                            @endif
                            @if($platform->financial_manager_id)
                                <div class="d-flex align-items-center p-2 bg-soft-success rounded">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-success">
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="text-muted mb-0 small">{{__('Financial Manager')}}</p>
                                        <h6 class="mb-0">ID: {{$platform->financial_manager_id}}</h6>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="ri-user-line display-5 mb-2 d-block text-muted opacity-50"></i>
                            <p class="mb-0 small">{{__('No managers assigned')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
