<div class="container">
    @section('title')
        {{ __('Business Sector') }}: {{ $businessSector->name }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Business Sector') }}: {{ $businessSector->name }}
        @endslot
    @endcomponent

    <div class="row card mb-2 overflow-hidden">
        <div class="profile-foreground position-relative">
            <div class="profile-wid-bg">
                @if ($businessSector?->thumbnailsImage)
                    <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}"
                         alt="{{ $businessSector->name }} banner"
                         class="profile-wid-img">
                @else
                    <img src="{{ Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB) }}"
                         alt="{{ __('Default business sector banner') }}"
                         class="profile-wid-img">
                @endif
            </div>
        </div>
        <div class="pt-3 mb-2 mb-lg-2 pb-lg-3 profile-wrapper">
            <div class="row g-4 px-3 mt-2">
                <div class="col-auto">
                    <div class="avatar-lg">
                        @if ($businessSector?->logoImage)
                            <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}"
                                 alt="{{ $businessSector->name }} logo"
                                 class="img-thumbnail rounded-circle bg-white border-3">
                        @else
                            <img src="{{ Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO) }}"
                                 alt="{{ __('Default business sector logo') }}"
                                 class="img-thumbnail rounded-circle bg-white border-3">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row card">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-8">
                    <h5 class="card-title mb-3">{{ __('Description') }}</h5>
                    <div class="text-muted">
                        {!! \App\Models\TranslaleModel::getTranslation($businessSector, 'description', $businessSector->description) !!}
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="mt-2">
                            <a class="btn btn-sm btn-link text-info"
                               href="{{ route('translate_model_data', ['locale' => app()->getLocale(), 'search' => \App\Models\TranslaleModel::getTranslateName($businessSector, 'description')]) }}">
                                <i class="ri-translate-2 align-bottom me-1"></i>{{ __('Update Translation') }}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        @if ($businessSector->thumbnailsHomeImage)
                            <img src="{{ asset('uploads/' . $businessSector->thumbnailsHomeImage->url) }}"
                                 alt="{{ $businessSector->name }} home image"
                                 class="img-fluid img-business-square rounded shadow-sm">
                        @else
                            <img src="{{ Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB_HOME) }}"
                                 alt="{{ __('Default home image') }}"
                                 class="img-fluid img-business-square rounded shadow-sm">
                        @endif
                    </div>
                </div>
            </div>

            @if(\App\Models\User::isSuperAdmin())
                <div class="card-footer">
                    <a href="{{ route('business_sector_create_update', ['locale' => app()->getLocale(), 'id' => $businessSector->id]) }}"
                       class="btn btn-outline-info float-end mx-2">
                        <i class="ri-edit-line align-bottom me-1"></i>{{ __('Edit') }}
                    </a>
                    <button wire:click="deleteBusinessSector('{{ $businessSector->id }}')"
                            class="btn btn-outline-danger float-end"
                            onclick="return confirm('{{ __('Are you sure you want to delete this business sector?') }}')">
                        <i class="ri-delete-bin-line align-bottom me-1"></i>{{ __('Delete') }}
                        <span wire:loading wire:target="deleteBusinessSector('{{ $businessSector->id }}')">
                                <span class="spinner-border spinner-border-sm ms-1" role="status"
                                      aria-hidden="true"></span>
                            </span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if($platforms->count() > 0)
        <div class="row card mt-3">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">
                    <i class="ri-store-2-line align-bottom me-2"></i>{{ __('Platforms') }}
                </h4>
                <span class="badge bg-primary fs-12">{{ $platforms->count() }}</span>
            </div>
        </div>
        <div class="row g-3">
            @foreach($platforms as $platform)
                    <div class="col-12 card">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-lg-3 col-md-4">
                                    <div class="text-center">
                                        @if ($platform?->logoImage)
                                            <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                                 alt="{{ $platform->name }} logo"
                                                 class="img-fluid rounded"
                                                 style="max-height: 150px; object-fit: contain;">
                                        @else
                                            <img src="{{ Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO) }}"
                                                 alt="{{ __('Default platform logo') }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 150px; object-fit: contain;">
                                        @endif
                                    </div>
                                    @if ($platform?->show_profile && $platform?->link)
                                        <div class="text-center mt-3">
                                            <a href="{{ $platform->link }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="btn btn-primary btn-sm w-100">
                                                <i class="ri-external-link-line align-bottom me-1"></i>{{ __('Visit Platform') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>


                                <div class="col-lg-9 col-md-8">
                                    <h4 class="card-title mb-2">
                                        {{ \App\Models\TranslaleModel::getTranslation($platform, 'name', $platform->name) }}
                                        @if(\App\Models\User::isSuperAdmin())
                                            <small class="text-muted">(ID: {{ $platform->id }})</small>
                                            <a class="btn btn-sm btn-link text-info"
                                               href="{{ route('translate_model_data', ['locale' => app()->getLocale(), 'search' => \App\Models\TranslaleModel::getTranslateName($platform, 'name')]) }}">
                                                <i class="ri-translate-2 align-bottom"></i>
                                            </a>
                                        @endif
                                    </h4>

                                    <p class="text-muted mb-3">
                                        {!! \App\Models\TranslaleModel::getTranslation($platform, 'description', $platform->description) !!}
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a class="btn btn-sm btn-link text-info"
                                               href="{{ route('translate_model_data', ['locale' => app()->getLocale(), 'search' => \App\Models\TranslaleModel::getTranslateName($platform, 'description')]) }}">
                                                <i class="ri-translate-2 align-bottom"></i>
                                            </a>
                                        @endif
                                    </p>

                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        @php
                                            $activeCouponsCount = $platform->deals()
                                                ->where('type', \Core\Enum\DealTypeEnum::coupons->value)
                                                ->where('start_date', '<=', now())
                                                ->where('end_date', '>=', now())
                                                ->count();
                                        @endphp

                                        @if($activeCouponsCount > 0)
                                            <a href="{{ route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $platform->id]) }}"
                                               class="btn btn-success btn-sm">
                                                <i class="ri-coupon-line align-bottom me-1"></i>{{ __('Top up your balance') }}
                                                <span
                                                    class="badge bg-light text-success ms-1">{{ $activeCouponsCount }}</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('coupon_history', ['locale' => app()->getLocale()]) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="ri-history-line align-bottom me-1"></i>{{ __('Coupon History') }}
                                        </a>

                                        <small class="text-muted ms-auto">
                                            <i class="ri-time-line align-bottom"></i> {{ $platform->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @php
                            $hasItems = false;
                            foreach($platform->deals as $deal) {
                                if($deal->items->count() > 0) {
                                    $hasItems = true;
                                    break;
                                }
                            }
                        @endphp

                        @if($hasItems)
                            <div class="card-body border-top bg-light">
                                <h5 class="mb-3">
                                    <i class="ri-shopping-bag-line align-bottom me-1"></i>{{ __('Available Items') }}
                                </h5>
                                @foreach($platform->deals as $deal)
                                    @if($deal->items->count() > 0)
                                        <div class="row g-3 mb-3">
                                            @foreach($deal->items as $item)
                                                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                                    <livewire:items-show :item="$item" :key="'item-'.$item->id"/>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
            @endforeach
        </div>
    @else
        <div class="row card mt-3">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="ri-store-2-line display-4 text-muted"></i>
                </div>
                <h5 class="text-muted">{{ __('No platforms available') }}</h5>
                <p class="text-muted">{{ __('There are currently no platforms in this business sector.') }}</p>
            </div>
        </div>
    @endif
</div>
