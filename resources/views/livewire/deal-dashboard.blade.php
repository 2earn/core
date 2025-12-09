<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Deal Dashboard') }}
    @endsection

    @component('components.breadcrumb')
        @slot('title')
            <i class="ri-dashboard-line me-2"></i>{{ __('Deal Dashboard') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12 card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="text-info mb-0">
                    <i class="ri-filter-3-line me-2"></i>{{ __('Filters') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Platform Filter -->
                    @if(count($availablePlatforms) > 0)
                        <div class="col-md-6 col-lg-3">
                            <label for="platformFilter" class="form-label">
                                <i class="ri-layout-grid-line me-1"></i>{{ __('Platform') }}
                            </label>
                            <select wire:model.live="selectedPlatformId" id="platformFilter" class="form-select">
                                <option value="">{{ __('All Platforms') }}</option>
                                @foreach($availablePlatforms as $platform)
                                    <option value="{{ $platform->id }}">{{ $platform->id }}
                                        - {{ $platform->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Deal Filter -->
                    <div class="col-md-6 col-lg-3">
                        <label for="dealFilter" class="form-label">
                            <i class="ri-handshake-line me-1"></i>{{ __('Deal') }} <span class="text-danger">*</span>
                        </label>
                        <select wire:model.live="dealId" id="dealFilter" class="form-select" required>
                            <option value="">{{ __('Select Deal') }}</option>
                            @foreach($availableDeals as $dealOption)
                                <option value="{{ $dealOption->id }}">{{ $dealOption->id }}
                                    - {{ $dealOption->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-6 col-lg-2">
                        <label for="startDate" class="form-label">
                            <i class="ri-calendar-line me-1"></i>{{ __('Start Date') }}
                        </label>
                        <input type="date" wire:model.live="startDate" id="startDate" class="form-control">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6 col-lg-2">
                        <label for="endDate" class="form-label">
                            <i class="ri-calendar-check-line me-1"></i>{{ __('End Date') }}
                        </label>
                        <input type="date" wire:model.live="endDate" id="endDate" class="form-control">
                    </div>

                    <!-- View Mode -->
                    <div class="col-md-6 col-lg-2">
                        <label for="viewMode" class="form-label">
                            <i class="ri-bar-chart-box-line me-1"></i>{{ __('View Mode') }}
                        </label>
                        <select wire:model.live="viewMode" id="viewMode" class="form-select">
                            <option value="daily">{{ __('Daily') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-3">
                    <button wire:click="refreshData" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="refreshData">
                            <span wire:loading.remove wire:target="refreshData">
                                <i class="ri-refresh-line me-1"></i>
                            </span>
                        <span class="spinner-border spinner-border-sm me-1" role="status" wire:loading
                              wire:target="refreshData">
                                <span class="visually-hidden">{{ __('Loading...') }}</span>
                            </span>
                        {{ __('Refresh') }}
                    </button>
                    <button wire:click="resetFilters" class="btn btn-outline-secondary">
                        <i class="ri-restart-line me-1"></i>{{ __('Reset Filters') }}
                    </button>
                </div>

                @if($error)
                    <div class="alert alert-danger mt-3 mb-0" role="alert">
                        <i class="ri-error-warning-line me-2"></i>{{ $error }}
                    </div>
                @endif
            </div>
        </div>

        @if($loading)
            <div class="col-12 card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                    <p class="mt-3 text-muted">{{ __('Loading performance data...') }}</p>
                </div>
            </div>
        @endif

        @if(!$loading && $deal)
            <div class="col-12 card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 text-primary">
                                <i class="ri-handshake-line me-2"></i>{{ $deal->name }}
                            </h4>
                            @if($deal->platform)
                                <span class="badge bg-primary-subtle text-primary">
                                        <i class="ri-layout-grid-line me-1"></i>{{ $deal->platform->name }}
                                    </span>
                            @endif
                        </div>
                        <div>
                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                    <i class="ri-information-line me-1"></i>{{ __(\Core\Enum\DealStatus::tryFrom($deal->status)?->name) }}
                                </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 row g-3 mb-4">
                <!-- Target Amount -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 text-truncate">{{ __('Target Amount') }}</p>
                                    <h4 class="mb-0 text-primary">{{ number_format($targetAmount, 2) }} {{ $currency }}</h4>
                                </div>
                                <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                            <i class="ri-target-line"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Revenue -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 text-truncate">{{ __('Current Revenue') }}</p>
                                    <h4 class="mb-0 text-success">{{ number_format($currentRevenue, 2) }} {{ $currency }}</h4>
                                </div>
                                <div class="avatar-sm">
                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expected Progress -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 text-truncate">{{ __('Expected Progress') }}</p>
                                    <h4 class="mb-0 text-info">{{ number_format($expectedProgress, 2) }}%</h4>
                                </div>
                                <div class="avatar-sm">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-2">
                                            <i class="ri-time-line"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-info" role="progressbar"
                                     style="width: {{ min($expectedProgress, 100) }}%"
                                     aria-valuenow="{{ $expectedProgress }}" aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actual Progress -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 text-truncate">{{ __('Actual Progress') }}</p>
                                    <h4 class="mb-0 text-{{ $actualProgress >= $expectedProgress ? 'success' : 'warning' }}">
                                        {{ number_format($actualProgress, 2) }}%
                                    </h4>
                                </div>
                                <div class="avatar-sm">
                                        <span
                                            class="avatar-title bg-{{ $actualProgress >= $expectedProgress ? 'success' : 'warning' }}-subtle text-{{ $actualProgress >= $expectedProgress ? 'success' : 'warning' }} rounded-circle fs-2">
                                            <i class="ri-line-chart-line"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div
                                    class="progress-bar bg-{{ $actualProgress >= $expectedProgress ? 'success' : 'warning' }}"
                                    role="progressbar" style="width: {{ min($actualProgress, 100) }}%"
                                    aria-valuenow="{{ $actualProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-bar-chart-line me-2"></i>{{ __('Revenue Performance Chart') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div id="dealPerformanceChart" style="height: 400px;" wire:ignore></div>
                </div>
            </div>
        @endif

        @if(!$loading && !$deal && !$error)
            <div class="col-12 card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="avatar-xl mx-auto mb-4">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                            <i class="ri-handshake-line"></i>
                        </div>
                    </div>
                    <h5 class="text-muted">{{ __('No deal selected') }}</h5>
                    <p class="text-muted">{{ __('Please select a deal from the filters above to view performance data') }}</p>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
        <script>
            let dealChart = null;
            let chartEventListenerRegistered = false;
            let pendingChartData = @json($chartData ?? []);

            function initChart() {
                const chartElement = document.querySelector('#dealPerformanceChart');
                if (!chartElement) {
                    console.log('Chart element not found');
                    return;
                }

                console.log('Initializing chart');

                dealChart = echarts.init(chartElement);

                const option = {
                    title: {
                        text: '{{ __("Revenue") }}',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'axis',
                        formatter: function(params) {
                            if (params && params.length > 0) {
                                const value = params[0].value;
                                const formattedValue = new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: '{{ config("app.currency_code", "USD") }}'
                                }).format(value);
                                return params[0].name + '<br/>' + params[0].marker + ' {{ __("Revenue") }}: ' + formattedValue;
                            }
                            return '';
                        }
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {
                                title: '{{ __("Download") }}'
                            },
                            dataZoom: {
                                title: {
                                    zoom: '{{ __("Zoom") }}',
                                    back: '{{ __("Reset") }}'
                                }
                            },
                            restore: {
                                title: '{{ __("Restore") }}'
                            }
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: [],
                        name: '{{ __("Date") }}',
                        nameLocation: 'middle',
                        nameGap: 30
                    },
                    yAxis: {
                        type: 'value',
                        name: '{{ __("Revenue") }}',
                        axisLabel: {
                            formatter: function(value) {
                                return new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: '{{ config("app.currency_code", "USD") }}',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    },
                    series: [{
                        name: '{{ __("Revenue") }}',
                        type: 'line',
                        data: [],
                        smooth: true,
                        areaStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0,
                                    color: 'rgba(75, 192, 192, 0.7)'
                                }, {
                                    offset: 1,
                                    color: 'rgba(75, 192, 192, 0.1)'
                                }]
                            }
                        },
                        lineStyle: {
                            color: '#4bc0c0',
                            width: 2
                        },
                        itemStyle: {
                            color: '#4bc0c0'
                        }
                    }],
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '10%',
                        containLabel: true
                    }
                };

                dealChart.setOption(option);

                // Update chart with pending data if available
                if (pendingChartData && pendingChartData.length > 0) {
                    console.log('Updating chart with pending data:', pendingChartData);
                    updateChart(pendingChartData);
                }

                window.addEventListener('resize', function() {
                    if (dealChart) {
                        dealChart.resize();
                    }
                });
            }

            function updateChart(chartData) {
                console.log('updateChart called with data:', chartData);

                const chartElement = document.querySelector('#dealPerformanceChart');
                if (!chartElement) {
                    console.log('Chart element not found');
                    return;
                }

                if (!dealChart) {
                    console.log('Chart not initialized, initializing now');
                    initChart();
                    return; // Chart will be updated in initChart
                }

                if (!chartData || chartData.length === 0) {
                    console.log('No chart data provided');
                    dealChart.setOption({
                        xAxis: {
                            data: []
                        },
                        series: [{
                            data: []
                        }]
                    });
                    return;
                }

                const labels = chartData.map(item => item.date);
                const data = chartData.map(item => parseFloat(item.revenue));

                console.log('Chart labels:', labels);
                console.log('Chart data:', data);

                dealChart.setOption({
                    xAxis: {
                        data: labels
                    },
                    series: [{
                        data: data
                    }]
                });
            }

            function setupChart() {
                if (typeof echarts === 'undefined') {
                    console.log('Echarts not loaded yet, waiting...');
                    setTimeout(setupChart, 100);
                    return;
                }

                if (typeof Livewire === 'undefined') {
                    console.log('Livewire not loaded yet, waiting...');
                    setTimeout(setupChart, 100);
                    return;
                }

                console.log('Setting up chart');

                if (!chartEventListenerRegistered) {
                    chartEventListenerRegistered = true;

                    Livewire.on('chartDataUpdated', (event) => {
                        console.log('chartDataUpdated event received:', event);
                        const data = Array.isArray(event) ? event[0] : event;
                        if (data && data.chartData) {
                            pendingChartData = data.chartData;
                            updateChart(data.chartData);
                        }
                    });
                }

                // Initialize chart after a short delay to ensure DOM is ready
                setTimeout(() => {
                    const chartElement = document.querySelector('#dealPerformanceChart');
                    if (chartElement) {
                        initChart();
                    }
                }, 100);
            }

            // Re-initialize chart after Livewire updates
            document.addEventListener('livewire:update', () => {
                console.log('Livewire updated, re-initializing chart');
                setTimeout(() => {
                    const chartElement = document.querySelector('#dealPerformanceChart');
                    if (chartElement) {
                        // Get latest chart data from Livewire component
                        pendingChartData = @this.chartData || [];
                        console.log('Chart data from component:', pendingChartData);
                        initChart();
                    }
                }, 150);
            });

            document.addEventListener('livewire:navigating', () => {
                console.log('Livewire navigating, destroying chart');
                chartEventListenerRegistered = false;
            });

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupChart);
            } else {
                setupChart();
            }
        </script>
    @endpush
</div>
