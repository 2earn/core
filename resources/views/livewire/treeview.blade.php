<div>
    <script src="https://balkan.app/js/OrgChart.js"></script>
    <style>
        /*CSS*/

        html, body {
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            font-family: Helvetica;
            overflow: hidden;
        }

        #tree {
            width: 100%;
            height: 100%;
        }

        [data-top] {
            cursor: pointer;
        }

        .see_more {
            cursor: pointer;
        }

        .see_more:hover text {
            fill: #F57C00 !important;
        }
    </style>
    <div id="tree"></div>
    <script>
        //JavaScript

        var sun = `<svg x="120" y="75" height="15" width="15" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 476.127 476.127" style="enable-background:new 0 0 476.127 476.127;" xml:space="preserve">
<g>
	<path style="fill:#BDDBFF;" d="M88.72,192.388C38.476,200.564,0,244.263,0,296.779c0,58.318,47.445,105.763,105.763,105.763h132.3
		V73.585C165.992,73.585,104.459,123.616,88.72,192.388z"/>
	<path style="fill:#9BC9FF;" d="M387.407,192.388c-15.739-68.772-77.271-118.804-149.343-118.804v328.958h132.3
		c58.318,0,105.763-47.445,105.763-105.763C476.127,244.263,437.651,200.564,387.407,192.388z"/>
</g>
</svg>`;

        var cloud = `<svg x="120" y="75" height="15" width="15" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 476.127 476.127" style="enable-background:new 0 0 476.127 476.127;" xml:space="preserve">
<g>
	<path style="fill:#BDDBFF;" d="M88.72,192.388C38.476,200.564,0,244.263,0,296.779c0,58.318,47.445,105.763,105.763,105.763h132.3
		V73.585C165.992,73.585,104.459,123.616,88.72,192.388z"/>
	<path style="fill:#9BC9FF;" d="M387.407,192.388c-15.739-68.772-77.271-118.804-149.343-118.804v328.958h132.3
		c58.318,0,105.763-47.445,105.763-105.763C476.127,244.263,437.651,200.564,387.407,192.388z"/>
</g>
</svg>`;

        var ok = `<svg x="270" y="85" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <!-- Uploaded to SVGRepo https://www.svgrepo.com -->
    <title>OK</title>
    <desc>Created with Sketch.</desc>
    <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="ic_fluent_checkbox_checked_24_filled" fill="#3AB54A" fill-rule="nonzero">
            <path d="M18,3 C19.6568542,3 21,4.34314575 21,6 L21,18 C21,19.6568542 19.6568542,21 18,21 L6,21 C4.34314575,21 3,19.6568542 3,18 L3,6 C3,4.34314575 4.34314575,3 6,3 L18,3 Z M16.4696699,7.96966991 L10,14.4393398 L7.53033009,11.9696699 C7.23743687,11.6767767 6.76256313,11.6767767 6.46966991,11.9696699 C6.1767767,12.2625631 6.1767767,12.7374369 6.46966991,13.0303301 L9.46966991,16.0303301 C9.76256313,16.3232233 10.2374369,16.3232233 10.5303301,16.0303301 L17.5303301,9.03033009 C17.8232233,8.73743687 17.8232233,8.26256313 17.5303301,7.96966991 C17.2374369,7.6767767 16.7625631,7.6767767 16.4696699,7.96966991 Z" id="🎨-Color"></path>
        </g>
    </g>
</svg>`;

        var warming = `<svg x="230" y="80" width="20px" height="20px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
    aria-hidden="true" role="img" class="iconify iconify--fxemoji" preserveAspectRatio="xMidYMid meet">
    <path fill="#FFB636" d="M12.51 470.379L234.371 16.008c6.439-13.187 25.17-13.363 31.855-.299l232.51 454.371c6.064 11.849-2.542 25.92-15.853 25.92H28.512c-13.164
    0-21.778-13.791-16.002-25.621z"></path><path fill="#2B3B47" d="M284.332 173L272.15 336.498c-.911 12.233-11.567 21.411-23.8
    20.499c-11.116-.828-19.706-9.707-20.499-20.499L215.668 173c-1.413-18.961 12.813-35.478 31.774-36.89s35.478 12.813 36.89 31.774c.124 1.662.109
    3.5 0 5.116zM250 391.873c-17.432 0-31.564 14.131-31.564 31.564C218.436 440.869 232.568 455 250 455s31.564-14.131
    31.564-31.564c0-17.432-14.132-31.563-31.564-31.563z"></path></svg>`

        OrgChart.templates.cool = Object.assign({}, OrgChart.templates.ana);
        OrgChart.templates.cool.defs = '<filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="cool-shadow"><feOffset dx="0" dy="4" in="SourceAlpha" result="shadowOffsetOuter1" /><feGaussianBlur stdDeviation="10" in="shadowOffsetOuter1" result="shadowBlurOuter1" /><feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.1 0" in="shadowBlurOuter1" type="matrix" result="shadowMatrixOuter1" /><feMerge><feMergeNode in="shadowMatrixOuter1" /><feMergeNode in="SourceGraphic" /></feMerge></filter>';

        OrgChart.templates.cool.size = [310, 190];
        OrgChart.templates.cool.node =
            `<rect filter="url(#cool-shadow)" x="0" y="0" height="190" width="310" fill="#ffffff" stroke-width="1" stroke="#eeeeee"></rect>
    <rect x="0" y="0" height="10" width="310" fill="#52AB5E" stroke-width="1"></rect>
    <rect stroke="#eeeeee" stroke-width="1" x="10" y="20" width="90" fill="#ffffff" rx="10" ry="10" height="90" filter="url(#cool-shadow)"></rect>
    '<circle stroke="#aeaeae" stroke-width="3" fill="#aeaeae" cx="55" cy="70" r="5"></circle> ' +
    '<path d="M42,90 C42,75 68,75 68,90" stroke="#aeaeae" stroke-width="1" fill="#aeaeae"></path>';
    <rect fill="#ffffff" x="110" y="20" width="190" height="90" rx="10" ry="10" filter="url(#cool-shadow)"></rect>
    <rect stroke="#eeeeee" stroke-width="1" x="10" y="120" width="90" fill="#ffffff" rx="10" ry="10" height="60" filter="url(#cool-shadow)"></rect>
    <rect stroke="#eeeeee" stroke-width="1" x="110" y="120" width="90" fill="#ffffff" rx="10" ry="10" height="60" filter="url(#cool-shadow)"></rect>
    <rect stroke="#eeeeee" stroke-width="1" x="210" y="120" width="90" fill="#ffffff" rx="10" ry="10" height="60" filter="url(#cool-shadow)"></rect>
    <text  style="font-size: 9px; font-weight: bold" fill="#afafaf" x="120" y="70">SHARES</text>
    <path fill="#3AB54A" d="M123,105 133,105 128,95"></path>


    <text style="font-size: 10px;" fill="#afafaf" x="20" y="135">CASH</text>
    <text style="font-size: 10px;" fill="#afafaf" x="120" y="135">BFS</text>
    <text style="font-size: 10px;" fill="#afafaf" x="220" y="135">DISCOUNT</text>`
            + sun + ok;

        OrgChart.templates.cool.points = '<text data-width="90" style="font-size: 24px; font-weight: bold" fill="#afafaf" x="55" y="50" text-anchor="middle">{val}</text>';
        OrgChart.templates.cool.name = '<text data-width="190" style="font-size: 12px; font-weight: bold" fill="#afafaf" x="205" y="50" text-anchor="middle">{val}</text>';
        OrgChart.templates.cool.title = '<text data-width="190" style="font-size: 14px; font-weight: bold" fill="#aeaeae" x="205" y="35" text-anchor="middle">{val}</text>';
        OrgChart.templates.cool.performance = '<text style="font-size: 16px;" fill="#F57C00" x="145" y="88" >{val}</text>';
        OrgChart.templates.cool.scoreChange = '<text style="font-size: 12px;" fill="#3AB54A" x="140" y="105" >{val}</text>';
        OrgChart.templates.cool.kpi1 = '<text style="font-size: 18px; font-weight: bold" fill="#3AB54A" x="15" y="155">{val}</text>';
        OrgChart.templates.cool.kpi2 = '<text style="font-size: 18px; font-weight: bold" fill="#3AB54A" x="115" y="155">{val}</text>';
        OrgChart.templates.cool.kpi3 = '<text style="font-size: 18px; font-weight: bold" fill="#3AB54A" x="215" y="155">{val}</text>';
        OrgChart.templates.cool.kpi1Percentage = '<text data-width="90" style="font-size: 12px; font-weight: bold" fill="#ED1B24" x="55" y="170" text-anchor="middle">{val}</text>';
        OrgChart.templates.cool.kpi2Percentage = '<text data-width="90" style="font-size: 12px; font-weight: bold" fill="#3AB54A" x="155" y="170" text-anchor="middle">{val}</text>';
        OrgChart.templates.cool.kpi3Percentage = '<text data-width="90" style="font-size: 12px; font-weight: bold" fill="#3AB54A" x="255" y="170" text-anchor="middle">{val}</text>';
        OrgChart.templates.cool.svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="background-color:#F2F2F2;display:block;" width="{w}" height="{h}" viewBox="{viewBox}">{content}</svg>';

        OrgChart.templates.cool.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle>'
            + '<text text-anchor="middle" style="font-size: 18px;cursor:pointer;" fill="#757575" x="15" y="22">{collapsed-children-total-count}</text>';
        var chart;
        chart = new OrgChart(document.getElementById('tree'), {
            mouseScrool: OrgChart.action.none,
            template: 'cool',
            nodeBinding: {

                name: 'name',
                title: 'title',
                kpi1: 'cash',
                kpi2: 'bfs',
                kpi3: 'discount',
                kpi1Percentage: 'kpi1Percentage',
                kpi2Percentage: 'kpi2Percentage',
                kpi3Percentage: 'kpi3Percentage',
                points: 'cname',
                performance: 'SHARES',
                scoreChange: 'ActionsValue',
                warnings: "warnings"
            },
            layout: OrgChart.layout.grid,

            align: OrgChart.ORIENTATION,
            scaleInitial: OrgChart.match.boundary,
            toolbar: {
                layout: true,
                zoom: true,
                fit: true,
                expandAll: true
            },
            tags: {
                bad: {
                    template: "bad"
                }
            }
        });
        var app = @json($nodes);
        console.log(app);
        chart.load(app);




    </script>
</div>
