<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Deals') }} > {{$deal->name}}
    @endsection
    @if(!in_array($currentRouteName,["deals_archive"]))
        @component('components.breadcrumb')
            @slot('title')
                <a class="link-light"
                   href="{{route('deals_index',['locale'=>app()->getLocale()])}}">{{ __('Deals') }}</a>
                <i class="ri-arrow-right-s-line"></i>
                {{$deal->name}}
            @endslot
        @endcomponent
    @endif
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 text-primary">
                        <i class="fas fa-handshake me-2"></i>{{$deal->name}}
                    </h4>
                    @if(\App\Models\User::isSuperAdmin())
                        <a class="text-decoration-none"
                           href="{{route('sales_tracking',['locale'=>app()->getLocale(),'id'=>$deal->id])}}">
                            <i class="fas fa-chart-line me-1"></i>{{ __('See details for Platform role') }}
                        </a>
                    @endif
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

        <!-- Key Metrics Section -->
        <div class="card-body">
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

        <!-- Deal Terms Section -->
        <div class="card-body border-top">
            <h5 class="text-primary mb-3">
                <i class="fas fa-file-contract me-2"></i>{{__('Deal Terms')}}
            </h5>
            <div class="row g-3">
                <!-- Commission Details -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-percent me-2"></i>{{__('Commission Details')}}
                            </h6>
                            <div class="vstack gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">{{__('Discount deal')}}</span>
                                    <span class="badge bg-info-subtle text-info">{{$deal->discount}} {{config('app.percentage')}}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">{{__('Initial commission')}}</span>
                                    <span class="badge bg-success-subtle text-success">{{$deal->initial_commission}} %</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">{{__('Final commission')}}</span>
                                    <span class="badge bg-primary-subtle text-primary">{{$deal->final_commission}} %</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Values -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-money-bill-wave me-2"></i>{{__('Financial Values')}}
                            </h6>
                            <div class="vstack gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">{{__('Total commission value')}}</span>
                                    <span class="fw-semibold text-success">{{$deal->total_commission_value}} {{config('app.currency')}}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">{{__('Total unused cashback value')}}</span>
                                    <span class="fw-semibold text-info">{{$deal->total_unused_cashback_value}} {{config('app.currency')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribution Breakdown -->
                <div class="col-md-12 col-lg-4">
                    <div class="card border h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-chart-pie me-2"></i>{{__('Distribution Breakdown')}}
                            </h6>
                            <div class="vstack gap-2">
                                <!-- 2 Earn Profit -->
                                <div class="border-bottom pb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted fs-13">{{__('2 Earn profit')}}</span>
                                        <span class="badge bg-info-subtle text-info">{{$deal->earn_profit}} %</span>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <small class="badge bg-success-subtle text-success">{{formatSolde($earn_profit,2)}} {{config('app.currency')}}</small>
                                        <small class="badge bg-primary-subtle text-primary">{{formatSolde($deal->cash_company_profit,2)}} {{config('app.currency')}}</small>
                                    </div>
                                </div>

                                <!-- Jackpot -->
                                <div class="border-bottom pb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted fs-13">{{__('Jackpot')}}</span>
                                        <span class="badge bg-warning-subtle text-warning">{{$deal->jackpot}} %</span>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <small class="badge bg-success-subtle text-success">{{formatSolde($jackpot,2)}} {{config('app.currency')}}</small>
                                        <small class="badge bg-primary-subtle text-primary">{{formatSolde($deal->cash_jackpot,2)}} {{config('app.currency')}}</small>
                                    </div>
                                </div>

                                <!-- Tree Remuneration -->
                                <div class="border-bottom pb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted fs-13">{{__('Tree remuneration')}}</span>
                                        <span class="badge bg-success-subtle text-success">{{$deal->tree_remuneration}} %</span>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <small class="badge bg-success-subtle text-success">{{formatSolde($tree_remuneration,2)}} {{config('app.currency')}}</small>
                                        <small class="badge bg-primary-subtle text-primary">{{formatSolde($deal->cash_tree,2)}} {{config('app.currency')}}</small>
                                    </div>
                                </div>

                                <!-- Proactive Cashback -->
                                <div class="pb-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted fs-13">{{__('Proactive cashback')}}</span>
                                        <span class="badge bg-primary-subtle text-primary">{{$deal->proactive_cashback}} %</span>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <small class="badge bg-success-subtle text-success">{{formatSolde($proactive_cashback)}} {{config('app.currency')}}</small>
                                        <small class="badge bg-primary-subtle text-primary">{{formatSolde($deal->cash_cashback,2)}} {{config('app.currency')}}</small>
                                    </div>
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
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            $('body').on('click', '.deleteDeal', function (event) {
                Swal.fire({
                    title: '{{__('Are you sure to delete this Deal')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                    showCancelButton: true,
                    confirmButtonText: "{{__('Delete')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch("delete", [$(event.target).attr('data-id')]);
                    }
                });
            });

            $('body').on('click', '.updateDeal', function (event) {
                var status = $(event.target).attr('data-status');
                var id = $(event.target).attr('data-id');
                var name = $(event.target).attr('data-status-name');
                var title = '{{__('Are you sure to')}} ' + name + ' ?';
                var confirmButtonText = name;
                Swal.fire({
                    title: title,
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch("updateDeal", [id, status]);
                    }
                });
            });


        });
    </script>
</div>
