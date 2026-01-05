<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('User Guide Details') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12 card shadow-sm border-0 mb-4">
            <!-- Card Header -->
            <div class="card-header bg-primary bg-gradient text-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-book-open-line fs-4"></i>
                        <h5 class="card-title mb-0 fw-semibold">
                            {{\App\Models\TranslaleModel::getTranslation($guide,'title',$guide->title)}}
                        </h5>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <a class="btn btn-sm btn-light text-primary"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($guide,'title')])}}">
                            <i class="ri-translate-2 align-bottom"></i> {{__('Update Translation')}}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Left Column - Metadata -->
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-uppercase text-muted mb-3 fw-semibold">
                                <i class="ri-information-line me-1"></i>{{ __('Information') }}
                            </h6>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">{{ __('Created by:') }}</small>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-user-line text-primary"></i>
                                    <span
                                        class="fw-medium">{{ getUserDisplayedName($guide->user->idUser) ?? __('Unknown') }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">{{ __('Created at:') }}</small>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-calendar-line text-success"></i>
                                    <span>{{ $guide->created_at ? $guide->created_at->format(config('app.date_format')) : __('N/A') }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">{{ __('Updated at:') }}</small>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-calendar-check-line text-info"></i>
                                    <span>{{ $guide->updated_at ? $guide->updated_at->format(config('app.date_format')) : __('N/A') }}</span>
                                </div>
                            </div>

                            @if($guide->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($guide->file_path))
                                <div class="mt-3 pt-3 border-top">
                                    <a href="{{ asset('storage/' . $guide->file_path) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary btn-sm w-100">
                                        <i class="ri-download-2-line me-1"></i>{{ __('Download') }} {{ __('Attachment') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Description -->
                    <div class="col-md-8">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-uppercase text-muted mb-0 fw-semibold">
                                    <i class="ri-file-text-line me-1"></i>{{ __('Description:') }}
                                </h6>
                                @if(\App\Models\User::isSuperAdmin())
                                    <a class="btn btn-sm btn-outline-info"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($guide,'description')])}}">
                                        <i class="ri-translate-2 align-bottom"></i> {{__('Update Translation')}}
                                    </a>
                                @endif
                            </div>
                            <div class="text-muted lh-lg">
                                {!! \App\Models\TranslaleModel::getTranslation($guide,'description',$guide->description) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Routes Section -->
                @if($routeDetails && count($routeDetails))
                    <hr class="my-4">
                    <div class="border rounded p-3 bg-light">
                        <h6 class="text-uppercase text-muted mb-3 fw-semibold">
                            <i class="ri-route-line me-1"></i>{{ __('Routes:') }}
                        </h6>
                        <div class="row g-2">
                            @foreach($routeDetails as $route)
                                <div class="col-md-6 col-lg-4">
                                    <div class="d-flex align-items-start gap-2 p-2 bg-white rounded border">
                                        <i class="ri-link text-primary mt-1"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium text-dark">{{ $route['name'] }}</div>
                                            @if($route['uri'])
                                                <small class="text-muted">{{ $route['uri'] }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card Footer -->
            @if(\App\Models\User::isSuperAdmin())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('user_guides_index', app()->getLocale()) }}"
                           class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i>{{ __('Back to list') }}
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('user_guides_edit', [app()->getLocale(), $guide->id]) }}"
                               class="btn btn-warning">
                                <i class="ri-edit-line me-1"></i>{{ __('Edit') }}
                            </a>
                            <button type="button" class="btn btn-danger"
                                    wire:click="confirmDelete({{ $guide->id }})">
                                <i class="ri-delete-bin-line me-1"></i>{{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
