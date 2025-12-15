<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Deal sales tracking') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Deal sales tracking') }}
        @endslot
    @endcomponent
        <div class="row">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 text-primary">
                                <i class="fas fa-chart-line me-2"></i>{{__('Deal sales tracking')}}
                            </h4>
                            <p class="text-muted mb-0 fs-14">{{$deal->name}}</p>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            @if($deal->platform()->count())
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                            <i class="fas fa-desktop me-1"></i>{{$deal->platform()->first()->name}}
                        </span>
                            @endif
                            <span class="badge bg-info-subtle text-info px-3 py-2">
                        <i class="fas fa-circle-notch me-1"></i>{{__(\Core\Enum\DealStatus::tryFrom($deal->status)?->name)}}
                    </span>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                @if($deal->description)
                    <div class="card-body border-top">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-alt text-muted fs-5"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-2">{{__('Description')}}</h6>
                                <p class="text-dark mb-0">{{$deal->description}}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Key Metrics Section -->
                <div class="card-body border-top">
                    <div class="row g-3">
                        <!-- Turnover Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm rounded bg-success-subtle text-success d-flex align-items-center justify-content-center">
                                            <i class="fas fa-dollar-sign fs-5"></i>
                                        </div>
                                        <h6 class="mb-0 ms-3 text-muted">{{__('Turnover')}}</h6>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted fs-12 mb-1">{{__('Current')}}</p>
                                            <h5 class="mb-0 text-success">{{$deal->current_turnover}} {{config('app.currency')}}</h5>
                                        </div>
                                        <i class="ri-arrow-right-line text-muted fs-4"></i>
                                        <div>
                                            <p class="text-muted fs-12 mb-1">{{__('Target')}}</p>
                                            <h5 class="mb-0 text-danger">{{$deal->target_turnover}} {{config('app.currency')}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Camembert Value Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                                            <i class="fas fa-chart-pie fs-5"></i>
                                        </div>
                                        <h6 class="mb-0 ms-3 text-muted">{{__('Camembert value')}}</h6>
                                    </div>
                                    <h4 class="mb-0 text-primary">{{\App\Models\Deal::getCamombertPercentage($deal)}} {{config('app.currency')}}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Card -->
                        <div class="col-md-12 col-lg-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <i class="fas fa-calendar-alt fs-5"></i>
                                        </div>
                                        <h6 class="mb-0 ms-3 text-muted">{{__('Duration')}}</h6>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted fs-12 mb-1">{{__('Start Date')}}</p>
                                            <p class="mb-0 fw-semibold text-dark">{{$deal->start_date}}</p>
                                        </div>
                                        <i class="ri-arrow-right-line text-muted fs-4"></i>
                                        <div>
                                            <p class="text-muted fs-12 mb-1">{{__('End date')}}</p>
                                            <p class="mb-0 fw-semibold text-dark">{{$deal->end_date}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="text-muted">
                            <i class="fas fa-user-circle me-1"></i>
                            <strong>{{__('Created by')}}:</strong>
                            {{getUserDisplayedName($deal->createdBy?->idUser)}}
                            <span class="mx-2">•</span>
                            {{$deal->createdBy?->email}}
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <strong>{{__('Created at')}}:</strong>
                            {{$deal->created_at}}
                        </div>
                    </div>
                </div>
            </div>
            @if(!empty($commissions))
                @include('livewire.commission-breackdowns', ['commissions' => $commissions])
            @endif
        </div>
</div>
