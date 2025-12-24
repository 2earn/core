<div class="container">
    @section('title')
        {{ __('Sales Dashboard') }} - {{ $platform->name }}
    @endsection

    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{route('platform_index', app()->getLocale())}}">{{ __('Platforms') }}</a>
        @endslot
        @slot('title')
            {{ __('Sales Dashboard') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row mb-4">
        <div class="col-12 card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        @if($platform->logoImage)
                            <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                 alt="{{ $platform->name }}"
                                 class="img-fluid rounded"
                                 style="max-height: 60px; object-fit: contain;">
                        @else
                            <div class="avatar-lg">
                                <div class="avatar-title rounded bg-soft-primary text-primary fs-2">
                                    {{strtoupper(substr($platform->name, 0, 1))}}
                                </div>
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-1">{{ $platform->name }}</h4>
                            <p class="text-muted mb-0">
                                <span class="badge badge-soft-secondary">ID: {{$platform->id}}</span>
                                @if($platform->businessSector)
                                    <span class="badge badge-soft-info ms-2">{{$platform->businessSector->name}}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{route('platform_index', app()->getLocale())}}" class="btn btn-soft-secondary">
                        <i class="ri-arrow-left-line align-middle me-1"></i>{{__('Back to Platforms')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">
                    <i class="ri-filter-3-line align-middle me-2"></i>{{__('Filters')}}
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">{{__('Start Date')}}</label>
                        <input type="date"
                               class="form-control"
                               id="startDate"
                               wire:model.live="startDate"
                               max="{{date('Y-m-d')}}">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">{{__('End Date')}}</label>
                        <input type="date"
                               class="form-control"
                               id="endDate"
                               wire:model.live="endDate"
                               max="{{date('Y-m-d')}}">
                    </div>
                    <div class="col-md-3">
                        <label for="viewMode" class="form-label">{{__('View Mode')}}</label>
                        <select class="form-select"
                                id="viewMode"
                                wire:model.live="viewMode">
                            <option value="daily">{{__('Daily')}}</option>
                            <option value="weekly">{{__('Weekly')}}</option>
                            <option value="monthly">{{__('Monthly')}}</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button"
                                class="btn btn-soft-info w-100"
                                wire:click="resetFilters">
                            <i class="ri-refresh-line align-middle me-1"></i>{{__('Reset Filters')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" wire:loading.class="opacity-50">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('Total Sales')}}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{$kpis['total_sales'] ?? 0}}">
                                    {{number_format($kpis['total_sales'] ?? 0)}}
                                </span>
                            </h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="ri-shopping-cart-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('Orders In Progress')}}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{$kpis['orders_in_progress'] ?? 0}}">
                                    {{number_format($kpis['orders_in_progress'] ?? 0)}}
                                </span>
                            </h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                <i class="ri-time-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('Successful Orders')}}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{$kpis['orders_successful'] ?? 0}}">
                                    {{number_format($kpis['orders_successful'] ?? 0)}}
                                </span>
                            </h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded fs-3">
                                <i class="ri-checkbox-circle-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('Failed Orders')}}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{$kpis['orders_failed'] ?? 0}}">
                                    {{number_format($kpis['orders_failed'] ?? 0)}}
                                </span>
                            </h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-danger rounded fs-3">
                                <i class="ri-close-circle-line text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title mb-0">
                            <i class="ri-line-chart-line align-middle me-2"></i>{{__('Sales Evolution')}}
                        </h5>
                        <span class="badge bg-soft-info text-info">
                            {{ucfirst($viewMode)}} {{__('View')}}
                        </span>
                    </div>
                    <div id="salesEvolutionChart" style="min-height: 350px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                    @if(empty($chartData))
                        <div class="text-center py-5">
                            <div class="avatar-xl mx-auto mb-4">
                                <div class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="ri-line-chart-line display-4"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">{{__('No Chart Data Available')}}</h5>
                            <p class="text-muted">{{__('There is no sales data for the selected period')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title mb-0">
                            <i class="ri-team-line align-middle me-2"></i>{{__('Customer Statistics')}}
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-primary rounded fs-3">
                                        <i class="ri-user-line text-primary"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">{{__('Total Unique Customers')}}</p>
                                    <h4 class="mb-0">{{number_format($kpis['total_customers'] ?? 0)}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-success rounded fs-3">
                                        <i class="ri-percent-line text-success"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">{{__('Success Rate')}}</p>
                                    <h4 class="mb-0">
                                        @if(($kpis['total_sales'] ?? 0) > 0)
                                            {{number_format((($kpis['orders_successful'] ?? 0) / $kpis['total_sales']) * 100, 1)}}%
                                        @else
                                            0%
                                        @endif
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.flex class="position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">{{__('Loading...')}}</span>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let salesChart = null;

    function initializeChart(chartData) {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        if (salesChart) {
            salesChart.destroy();
        }

        const labels = chartData.map(item => item.date);
        const data = chartData.map(item => item.revenue);

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '{{__('Revenue')}}',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgb(75, 192, 192)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('en-US', {
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value);
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    document.addEventListener('livewire:initialized', function() {
        console.log('Platform Sales Dashboard initialized');

        const chartData = @json($chartData ?? []);
        if (chartData && chartData.length > 0) {
            initializeChart(chartData);
        }
    });

    Livewire.on('chartDataUpdated', (chartData) => {
        console.log('Chart data updated', chartData);
        if (chartData && chartData.length > 0) {
            initializeChart(chartData[0]);
        }
    });

    window.addEventListener('resize', function() {
        if (salesChart) {
            salesChart.resize();
        }
    });
</script>
@endpush

