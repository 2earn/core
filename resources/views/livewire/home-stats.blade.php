<div class="row" id="stats">
    <div class="container-fluid">
        <!-- Section Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-2">{{__('Statistics')}}</h2>
                <p class="text-muted fs-15 mb-0">{{__('Explore our statistics')}}</p>
            </div>
        </div>
        <div class="card col-12">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1 text-info">{{ __('we_are_present_in') }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12" style="padding-right: 0;padding-left: 0;">
                        <div class="card border-0 " style="height: 480px;box-shadow: none">
                            <div class="card-body" wire:ignore>
                                <div id="any4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card col-12">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1 text-info">{{ __('Country ponderation') }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="card border-0 " style="height: 480px;box-shadow: none">
                        <div class="card-body" wire:ignore>
                            <div id="any5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        function initHomeStatsCharts() {
            if (typeof anychart === 'undefined') {
                console.warn('AnyChart library not loaded yet');
                return;
            }

            anychart.onDocumentReady(function () {
                var $any4 = $('#any4');
                if ($any4.length > 0 && $any4.is(':empty')) {
                    anychart.data.loadJsonFile(
                        "{{route('api_stat_countries',app()->getLocale())}}",
                        function (data) {
                            var map = anychart.map();
                            map.geoData('anychart.maps.world');
                            map.padding(0);
                            var dataSet = anychart.data.set(data);
                            var densityData = dataSet.mapAs({id: 'apha2', value: 'COUNT_USERS'});
                            var series = map.choropleth(densityData);
                            series.labels(false);
                            series.hovered().fill('#f48fb1').stroke(anychart.color.darken('#f48fb1'));
                            series.tooltip(false);
                            var scale = anychart.scales.ordinalColor([
                                {less: 2},
                                {from: 2, to: 5},
                                {from: 5, to: 10},
                                {from: 10, to: 15},
                                {from: 15, to: 30},
                                {from: 30, to: 50},
                                {from: 50, to: 100},
                                {from: 100, to: 500},
                                {greater: 500}
                            ]);
                            scale.colors(['#81d4fa', '#4fc3f7', '#29b6f6', '#039be5', '#0288d1', '#0277bd', '#01579b', '#014377', '#000000']);
                            series.colorScale(scale);
                            var zoomController = anychart.ui.zoom();
                            zoomController.render(map);
                            map.container('any4');
                            map.draw();
                            var mapping = dataSet.mapAs({
                                x: "name",
                                value: "COUNT_USERS",
                                category: "continant"
                            });
                            var colors = anychart.scales.ordinalColor().colors(['#26959f', '#f18126', '#3b8ad8', '#60727b', '#e24b26']);
                            var chart = anychart.tagCloud();
                            chart.data(mapping).colorScale(colors).angles([-90, 0, 90,]);
                            chart.tooltip(false);
                            var colorRange = chart.colorRange();
                            colorRange.enabled(true).colorLineSize(15);
                            var normalFillFunction = chart.normal().fill();
                            var hoveredFillFunction = chart.hovered().fill();
                            chart.listen('pointsHover', function (e) {
                                if (e.actualTarget === colorRange) {
                                    if (e.points.length) {
                                        chart.normal({
                                            fill: 'black 0.1'
                                        });
                                        chart.hovered({
                                            fill: chart.colorScale().valueToColor(e.point.get('category'))
                                        });
                                    } else {
                                        chart.normal({fill: normalFillFunction});
                                        chart.hovered({fill: hoveredFillFunction});
                                    }
                                }
                            });
                            chart.container('any5');
                            chart.draw();
                        }
                    );
                }
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHomeStatsCharts);
        } else {
            initHomeStatsCharts();
        }
        document.addEventListener('livewire:load', function() {
            setTimeout(initHomeStatsCharts, 100);
        });
    </script>
@endpush
