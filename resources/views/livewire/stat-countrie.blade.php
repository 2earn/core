<div>
    @section('title')
        {{ __('history') }}
    @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('Stat By Countries') }}
            @endslot
        @endcomponent
        <div class="row">
            <div class="col">
                <div class="h-100">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1"></h4>
                                </div>
                                <div class="card-body">
                                    <div id="any1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Countries Statistics') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div id="any2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Countries Statistics') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div id="any3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="module">
    var series;
    var mapChart;
    var tableCharts;
    var dataSet;
    var tableChart;
    var populationChart;
    var areaChart;
    var houseSeatsChart;
    $(function () {
        anychart.onDocumentReady(function () {
            if ($('#any2').length > 0) {

                anychart.data.loadJsonFile(
                    "{{route('API_stat_countries',app()->getLocale())}}",
                    function (data) {
                        if (data.length) {
                            for (var i = 0; i < data.length; i++) {
                                data[i].value = data[i].COUNT_USERS;
                                data[i].short = data[i].apha2;
                            }
                            dataSet = anychart.data.set(data);
                            tableChart = getTableChart();
                            mapChart = drawMap();
                            tableCharts = getTableCharts();
                            var layoutTable = anychart.standalones.table();
                            layoutTable.cellBorder(null);
                            layoutTable.container('any2');
                            layoutTable.draw();
                        }

                        function getTableChart() {
                            var table = anychart.standalones.table();
                            table.cellBorder(null);
                            table.fontSize(11).vAlign('middle').fontColor('#212121');
                            table.getCell(0, 0).colSpan(14).fontSize(14).vAlign('bottom').border().bottom('1px #dedede').fontColor('#7c868e');
                            table
                                .useHtml(true)
                                .contents([
                                    ['Selected States Data'],
                                    [
                                        null,
                                        'Name',
                                        'Cash Balance',
                                        'Bfs',
                                        'Discount Balance',
                                        'Sms Balance',
                                        'Total Shares',
                                        'Sold Shares',
                                        'Gifted Shares',
                                        'Shares Revenue',
                                        'Transfert Made',
                                        'Users',
                                        'Traiders',
                                        'Real Traders',
                                    ],
                                    [null]
                                ]);
                            table
                                .getRow(1)
                                .cellBorder()
                                .bottom('2px #dedede')
                                .fontColor('#7c868e');
                            table.getCol(0).width(5);
                            return table;
                        }

                        function solidChart(value) {
                            var gauge = anychart.gauges.circular();
                            gauge.data([value, 100]);
                            gauge.padding(5);
                            gauge.margin(0);
                            var axis = gauge.axis().radius(100).width(1).fill(null);
                            axis.scale().minimum(0).maximum(100).ticks({interval: 1}).minorTicks({interval: 1});
                            axis.labels().enabled(false);
                            axis.ticks().enabled(false);
                            axis.minorTicks().enabled(false);
                            var stroke = '1 #e5e4e4';
                            gauge.bar(0).dataIndex(0).radius(80).width(40).fill('#64b5f6').stroke(null).zIndex(5);
                            gauge.bar(1).dataIndex(1).radius(80).width(40).fill('#F5F4F4').stroke(stroke).zIndex(4);
                            gauge.label().width('50%').height('25%').adjustFontSize(true).hAlign('center').anchor('center');
                            gauge.label().hAlign('center').anchor('center').padding(5, 10).zIndex(1);
                            gauge.background().enabled(false);
                            gauge.fill(null);
                            gauge.stroke(null);
                            return gauge;
                        }

                        function getTableCharts() {
                            var table = anychart.standalones.table(2, 3);
                            table.cellBorder(null);
                            table.getRow(0).height(45);
                            table.getRow(1).height(25);
                            table.fontSize(11).useHtml(true).hAlign('center');
                            table.getCell(0, 0).colSpan(3).fontSize(14).vAlign('bottom').border().bottom('1px #dedede');
                            table.getRow(1).cellBorder().bottom('2px #dedede');
                            populationChart = solidChart(0);
                            areaChart = solidChart(0);
                            houseSeatsChart = solidChart(0);
                            table.contents([
                                ['Percentage of Total'],
                                ['Cash Balance', 'BFS', 'Discount Balance'],
                                [populationChart, areaChart, houseSeatsChart]
                            ]);
                            return table;
                        }

                        function changeContent(ids) {
                            var i;
                            var contents = [
                                ['List of Selected States'],
                                [
                                    null,
                                    'Name',
                                    'Cash Balance',
                                    'Bfs',
                                    'Discount Balance',
                                    'Sms Balance',
                                    'Total Shares',
                                    'Sold Shares',
                                    'Gifted Shares',
                                    'Shares Revenue',
                                    'Transfert Made',
                                    'Users',
                                    'Traiders',
                                    'Real Traders',
                                ]
                            ];


                            var cash = 0;
                            var bfs = 0;
                            var discount = 0;
                            var totalsh = 0;
                            var soldsh = 0;
                            var giftedsh = 0;
                            var revenue = 0;
                            var transfert = 0;
                            var users = 0;
                            var traiders = 0;
                            var rtraiders = 0;
                            var sms = 0;

                            for (i = 0; i < ids.length; i++) {
                                var data = getDataId(ids[i]);
                                cash += parseInt(data.CASH_BALANCE);
                                bfs += parseInt(data.BFS);
                                discount += parseInt(data.DISCOUNT_BALANCE);
                                sms += parseInt(data.SMS_BALANCE);
                                totalsh += parseInt(data.TOTAL_SHARES);
                                soldsh += parseInt(data.SOLD_SHARES);
                                giftedsh += parseInt(data.GIFTED_SHARES);
                                revenue += parseInt(data.SHARES_REVENUE);
                                transfert += parseInt(data.TRANSFERT_MADE);
                                users += parseInt(data.COUNT_USERS);
                                traiders += parseInt(data.COUNT_TRAIDERS);
                                rtraiders += parseInt(data.COUNT_REAL_TRAIDERS);

                                var label = anychart.standalones.label();
                                label.width('100%').height('100%').text('').background().enabled(true)

                                contents.push([
                                    label,
                                    data.name,
                                    parseInt(data.CASH_BALANCE),
                                    parseInt(data.BFS),
                                    parseInt(data.DISCOUNT_BALANCE),
                                    parseInt(data.SMS_BALANCE),
                                    parseInt(data.TOTAL_SHARES),
                                    parseInt(data.SOLD_SHARES),
                                    parseInt(data.GIFTED_SHARES),
                                    parseInt(data.SHARES_REVENUE),
                                    parseInt(data.TRANSFERT_MADE),
                                    parseInt(data.COUNT_USERS),
                                    parseInt(data.COUNT_TRAIDERS),
                                    parseInt(data.COUNT_REAL_TRAIDERS)
                                ]);
                            }

                            populationChart.data([
                                ((cash * 100) / getDataSum('CASH_BALANCE')).toFixed(2),
                                100
                            ]);
                            populationChart.label().text(((cash * 100) / getDataSum('CASH_BALANCE')).toFixed(2) + '%');

                            areaChart.data([((bfs * 100) / getDataSum('BFS')).toFixed(2), 100]);
                            areaChart.label().text(((bfs * 100) / getDataSum('BFS')).toFixed(2) + '%');
                            houseSeatsChart.data([((discount * 100) / getDataSum('DISCOUNT_BALANCE')).toFixed(2), 100]);
                            houseSeatsChart.label().text(((discount * 100) / getDataSum('DISCOUNT_BALANCE')).toFixed(2) + '%');
                            tableChart.contents(contents);
                            for (i = 0; i < ids.length; i++) {
                                tableChart.getRow(i + 2).maxHeight(35);
                            }
                        }

                        function drawMap() {
                            var map = anychart.map();
                            map.title().enabled(true).useHtml(true).padding([10, 0, 10, 0]).text('{{ __('Countries Statistics') }}<br/>' + '<span  style="color:#929292; font-size: 12px;">2Earn.cash Concept</span>');

                            map.geoData('anychart.maps.world');
                            map.listen('pointsSelect', function (e) {
                                var selected = [];
                                var selectedPoints = e.seriesStatus[0].points;
                                for (var i = 0; i < selectedPoints.length; i++) {
                                    console.log(selectedPoints[i].id);
                                    selected.push(selectedPoints[i].id);
                                }
                                changeContent(selected);
                            });
                            map.padding(0);

                            var dataSet = anychart.data.set(data);
                            var densityData = dataSet.mapAs({id: 'apha2', value: 'COUNT_USERS'});
                            series = map.choropleth(densityData);
                            series.labels(false);
                            series.hovered().fill('#f48fb1').stroke(anychart.color.darken('#f48fb1'));

                            series.selected().fill('#c2185b').stroke(anychart.color.darken('#c2185b'));

                            series.tooltip().useHtml(true)
                                .format(function () {
                                    return (
                                        '<span style="color: #d9d9d9">Cash Balance</span>: ' +
                                        parseFloat(this.getData('CASH_BALANCE')).toLocaleString() +
                                        '$ <br/>' +
                                        '<span style="color: #d9d9d9">BFS</span>: ' +
                                        parseInt(this.getData('BFS')).toLocaleString() +
                                        '$<br/>' +
                                        '<span style="color: #d9d9d9">Discount Balance</span>: ' +
                                        parseInt(this.getData('DISCOUNT_BALANCE')).toLocaleString() +
                                        '$<br/>' +
                                        '<span style="color: #d9d9d9">SMS Balance</span>: ' +
                                        parseInt(this.getData('SMS_BALANCE')).toLocaleString() +
                                        '<br/>' +
                                        '<span style="color: #d9d9d9">Sold Shares</span>: ' +
                                        parseInt(this.getData('SOLD_SHARES')).toLocaleString() +
                                        '<br/>' +
                                        '<span style="color: #d9d9d9">Gifded Shares</span>: ' +
                                        parseInt(this.getData('GIFTED_SHARES')).toLocaleString() +
                                        '<br/>' +
                                        '<span style="color: #d9d9d9">Total Shares</span>: ' +
                                        parseInt(this.getData('TOTAL_SHARES')).toLocaleString() +
                                        '<br/>' +
                                        '<span style="color: #d9d9d9">Shares Revenue</span>: ' +
                                        parseInt(this.getData('SHARES_REVENUE')).toLocaleString() +
                                        '$<br/>' +
                                        '<span style="color: #d9d9d9">Transfert Made</span>: ' +
                                        parseInt(this.getData('TRANSFERT_MADE')).toLocaleString() +
                                        '$<br/>' +
                                        '<span style="color: #d9d9d9">Users</span>: ' +
                                        parseInt(this.getData('COUNT_USERS')).toLocaleString() +
                                        '<br/>' +
                                        '<span style="color: #d9d9d9">Traiders</span>: ' +
                                        parseInt(this.getData('COUNT_TRAIDERS')).toLocaleString() +
                                        '<br/>' +
                                        '<span style="color: #d9d9d9">Real Traiders</span>: ' +
                                        parseInt(this.getData('COUNT_REAL_TRAIDERS')).toLocaleString()
                                    );
                                });

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

                            var colorRange = map.colorRange();
                            colorRange.enabled(true).padding([0, 0, 20, 0]);
                            colorRange.ticks().enabled(true).stroke('3 #ffffff').position('center').length(7);
                            colorRange.colorLineSize(5);
                            colorRange.marker().size(7);
                            colorRange.labels().fontSize(11).padding(3, 0, 0, 0)
                                .format(function () {
                                    var range = this.colorRange;
                                    var name;
                                    if (isFinite(range.start + range.end)) {
                                        name = range.start + ' - ' + range.end;
                                    } else if (isFinite(range.start)) {
                                        name = 'More than ' + range.start;
                                    } else {
                                        name = 'Less than ' + range.end;
                                    }
                                    return name;
                                });

                            series.colorScale(scale);
                            var zoomController = anychart.ui.zoom();
                            zoomController.render(map);
                            map.zoom(2);
                            return map;
                        }

                        function fillInMainTable(flag) {
                            if (flag === 'wide') {
                                layoutTable.contents([[mapChart, tableCharts], [null, tableChart]],
                                    true
                                );
                                layoutTable.getCell(0, 0).rowSpan(2);
                                layoutTable.getRow(0).height(null);
                                layoutTable.getRow(1).height(null);
                            } else {
                                layoutTable.contents(
                                    [[mapChart], [tableCharts], [tableChart]],
                                    true
                                );
                                layoutTable.getRow(0).height(350);
                                layoutTable.getRow(1).height(200);
                                layoutTable.getRow(2).height(250);
                            }
                            layoutTable.draw();
                        }

                        if (window.innerWidth > 768) fillInMainTable('wide');
                        else {
                            fillInMainTable('slim');
                        }
                        series.select(4);
                        series.select(5);

                        changeContent(['TN', 'SA']);

                        window.onresize = function () {
                            if (layoutTable.colsCount() === 1 && window.innerWidth > 767) {
                                fillInMainTable('wide');
                            } else if (
                                layoutTable.colsCount() === 2 &&
                                window.innerWidth <= 767
                            ) {
                                fillInMainTable('slim');
                            }
                        };

                        function getDataId(id) {
                            for (var i = 0; i < data.length; i++) {
                                if (data[i].apha2 === id) {
                                    return data[i];
                                }
                            }
                        }

                        function getDataSum(field) {
                            var result = 0;
                            for (var i = 0; i < data.length; i++) {
                                result += parseInt(data[i][field]);
                            }
                            return result;
                        }
                    }
                );
            }
        });
    });
</script>
<script type="module">
    $(function () {
        anychart.onDocumentReady(function () {
            if ($('#any1').length > 0) {
                anychart.data.loadJsonFile(
                    "{{route('API_stat_countries',app()->getLocale())}}",
                    function (data) {
                        var dataSet = anychart.data.set(data);
                        var mapping = dataSet.mapAs({x: "name", value: "COUNT_USERS", category: "continant"});
                        var colors = anychart.scales.ordinalColor().colors(['#26959f', '#f18126', '#3b8ad8', '#60727b', '#e24b26']);
                        var chart = anychart.tagCloud();
                        chart.title('Distribution of users by country').data(mapping).colorScale(colors).angles([-90, 0, 90]);
                        var colorRange = chart.colorRange();
                        colorRange.enabled(true).colorLineSize(15);
                        chart.container('any1');
                        chart.draw();
                        var normalFillFunction = chart.normal().fill();
                        var hoveredFillFunction = chart.hovered().fill();
                        chart.listen('pointsHover', function (e) {
                            if (e.actualTarget === colorRange) {
                                if (e.points.length) {
                                    chart.normal({fill: 'black 0.1'});
                                    chart.hovered({fill: chart.colorScale().valueToColor(e.point.get('category'))});
                                } else {
                                    chart.normal({fill: normalFillFunction});
                                    chart.hovered({fill: hoveredFillFunction});
                                }
                            }
                        });
                    }
                );
            }
        });
    });

</script>
<script type="module">
    $(function () {
        anychart.onDocumentReady(function () {
            if ($('#any3').length > 0) {
                anychart.data.loadJsonFile(
                    "{{route('API_sankey',app()->getLocale())}}",
                    function (data) {
                        var chart = anychart.sankey();
                        chart.title('Cash Flows');
                        chart.data(data);
                        chart.padding(20, 80, 20, 40);
                        chart.curveFactor(0.2);
                        chart.nodeWidth(50);
                        chart.nodePadding(30);
                        chart.node().normal().labels().anchor('center-bottom').position('center-top');
                        chart.node().tooltip().anchor('center-bottom');
                        chart.flow().tooltip().enabled(false);
                        chart.dropoff().tooltip().enabled(false);
                        chart
                            .node().tooltip().useHtml(true).format(function () {
                            var tooltip = '';
                            var i;
                            var ul;
                            var income;
                            var outcome;
                            var conflict;
                            if (this.income.length) {
                                income = 0;
                                ul = '<ul>';
                                for (i = 0; i < this.income.length; i++) {
                                    ul +=
                                        '<li>' +
                                        this.income[i].name +
                                        ':' +
                                        this.income[i].value +
                                        '</li>';
                                    console.log(this.income[i]);
                                    income += this.income[i].value;
                                }
                                ul += '</ul>';
                                tooltip += '<h5>Income(' + income + '):</h5>' + ul;
                            }
                            if (this.outcome.length) {
                                outcome = 0;
                                ul = '<ul>';
                                for (i = 0; i < this.outcome.length; i++) {
                                    ul +=
                                        '<li>' +
                                        this.outcome[i].name +
                                        ':' +
                                        this.outcome[i].value +
                                        '</li>';
                                    outcome += this.outcome[i].value;
                                }
                                ul += '</ul>';
                                tooltip += '<h5>Outcome(' + outcome + '):</h5>' + ul;
                            }
                            if (this.dropoff > 0) {
                                tooltip += '<h5>Dropoff: ' + this.dropoff + '</h5>';
                            }
                            if (this.isConflict) {
                                if (income > outcome + this.dropoff) {
                                    conflict = income - (outcome + this.dropoff);
                                    tooltip +=
                                        '<h5>Conflict:</h5><ul><li> income is greater than outcome by ' +
                                        conflict +
                                        '</li></ul>';
                                } else {
                                    conflict = outcome + this.dropoff - income;
                                    tooltip +=
                                        '<h5>Conflict:</h5><ul><li>outcome is greater than income by ' +
                                        conflict +
                                        '</li></ul>';
                                }
                            }
                            return tooltip;
                        });
                        chart.node().labels().useHtml(true).format(function () {
                            if (this.isConflict) {
                                return '<b style="color: red">' + this.name + '</b>';
                            }
                            return this.name;
                        });

                        chart.flow({
                            normal: {
                                fill: function () {
                                    return anychart.color.lighten(this.sourceColor, 0.5) + ' ' + 0.3;
                                }
                            },
                            hovered: {
                                fill: function () {
                                    return this.sourceColor + ' ' + 0.9;
                                }
                            }
                        });
                        chart.container('any3');
                        chart.draw();
                    });
            }
        });
    });
</script>
