<div>
    @section('title')
        {{ __('Trading') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Trading') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12 mt-2 mb-2">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 id="exampleModalgridLabel">{{ __('Buy Shares') }}
                                @if($flash)
                                    <div class="flash-background float-end">{{__('V I P')}}</div>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="modal-body">
                        @if($flash)
                            <div class="row pink col-12" role="alert">
                                <p>{{__('A mode for a')}} <span
                                        class="pinkbold col-auto">{{$flashTimes}}</span> {{__('times bonus over')}}
                                    <span
                                        class="pinkbold col-auto">{{$flashPeriod}} {{__('hours')}}</span> {{__('with a minimum of')}}
                                    <span
                                        class="pinkbold col-auto">{{$flashMinShares}} {{__('Shares')}}</span>
                                </p>
                            </div>
                        @endif
                        <div class="row @if($flash) alert-flash @else alert  @endif alert-info" role="alert">
                            <strong>{{ __('Notice') }}: </strong>{{ __('buy_shares_notice') }}
                        </div>
                        <a href="{{route('user_balance_cb',app()->getLocale())}}"
                           class="@if($cashBalance < $ammount) logoTopCashDanger  @else logoTopCash  @endif">
                            <div class="row d-flex mt-1">
                                <div class="col-4 avatar-xs flex-shrink-1 ">
                                <span class="avatar-title bg-soft-info custom rounded fs-3">
                                    <i class="bx bx-dollar-circle text-info"></i>
                                </span>
                                </div>
                                <div class="col-8 text-primary text-uppercase fs-16 pt-1 ms-5">
                                    <h5 class="@if($cashBalance < $ammount) logoTopCashDanger  @else logoTopCashLabel  @endif">  {{ __('Cash Balance') }}
                                        : {{__('DPC')}}{{$soldeBuyShares->soldeCB}}</h5>
                                </div>
                            </div>
                        </a>
                        <div class="row d-flex">
                            <form class="needs-validation" novalidate>
                                <div class="row mt-2 ml-1 @if($flash) alert-flash @else alert  @endif alert-light">
                                    <h5 class="ml-3">
                                        <span class="form-label">{{ __('Buy For') }}:</span>
                                    </h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item list-group-item-light">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="inlineRadioOptions"
                                                       checked
                                                       id="inlineRadio1" value="me">
                                                <label class="form-check-label"
                                                       for="inlineRadio1">{{ __('me') }}</label>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-light">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="inlineRadioOptions"
                                                       id="inlineRadio2" value="other" disabled>
                                                <label class="form-check-label"
                                                       for="inlineRadio2">{{ __('other') }}</label>
                                            </div>
                                        </li>

                                    </ul>
                                    <div class="col-6 d-none" id="contact-select">
                                        <div>
                                            <label for="phone" class="form-label">{{ __('Mobile_Number') }}</label>
                                            <input type="tel"
                                                   class="@if($flash) form-control-flash @else form-control  @endif"
                                                   name="mobile" id="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-6 d-none" id="bfs-select">
                                        <span class="form-label mb-3">{{ __('BFS bonuses  for') }} </span>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="bfs-for"
                                                       id="bfs-for-1"
                                                       value="me">
                                                <label for="bfs-for-1"
                                                       class="form-check-label">{{ __('me') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="bfs-for"
                                                       id="bfs-for-2"
                                                       value="other">
                                                <label for="bfs-for-2"
                                                       class="form-check-label">{{ __('The chosen user') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="simulator" class="row mt-3 mb-3">
                                    @if($flash)
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="discount-time text-center">
                                                <h5 id="flash-timer1" class="mb-0 text-black"></h5>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-6  @if($flash) ribbon-box right overflow-hidden @endif ">
                                        <label for="ammount" class="col-form-label">{{ __('Amount_pay') }}
                                            ({{$currency}}
                                            )</label>
                                        <div class="input-group mb-3">

                                            <input aria-describedby="simulateAmmount" type="number"
                                                   max="{{$cashBalance}}"
                                                   wire:keyup.debounce="simulateAmmount()" wire:model="ammount"
                                                   id="ammount"
                                                   class="form-control @if($flash) flash @endif">
                                            <div class="input-group-append">
                                                <button wire:click="simulateAmmount()"
                                                        class="btn @if($flash) btn-outline-flash @else btn-outline-primary  @endif">
                                                    <div wire:loading wire:target="simulateAmmount">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                        <span class="sr-only">{{__('Loading')}}...</span>
                                                    </div>
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6 @if($flash) ribbon-box right overflow-hidden @endif ">
                                        <label for="action" class="col-form-label">
                                            {{ __('Number of shares') }}
                                        </label>
                                        <div class="input-group mb-3">
                                            <input aria-describedby="simulateAction" type="number"
                                                   max="{{$maxActions}}"
                                                   wire:keyup.debounce="simulateAction()" wire:model="action"
                                                   id="action"
                                                   class="form-control @if($flash) flash @endif">
                                            <div class="input-group-append">
                                                <button wire:click="simulateAction()"
                                                        class="btn @if($flash) btn-outline-flash @else btn-outline-primary  @endif">
                                                    <div wire:loading wire:target="simulateAction">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                        <span class="sr-only">{{__('Loading')}}...</span>
                                                    </div>
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <label for="number-of-gifted-action" class="col-form-label">
                                            {{ __('Gifted Shares') }}
                                        </label>
                                        <input type="number" disabled
                                               class="@if($flash) form-control-flash @else form-control  @endif"
                                               wire:model.live="gift"
                                               id="number-of-gifted-action"
                                               value="0000">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <label for="profit" class="col-form-label">{{ __('Profit') }}
                                            ({{$currency}}) </label>
                                        <input type="text" inputmode="numeric" pattern="[-+]?[0-9]*[.,]?[0-9]+"
                                               disabled
                                               class="@if($flash) form-control-flash @else form-control  @endif"
                                               id="profit"
                                               value="0000"
                                               wire:model.live="profit">
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="hstack gap-2 justify-content-end">
                                            @if($flash)
                                                <button type="button" class="btn btn-outline-gold">
                                                    {{__('Flash gift')}}
                                                    <span class="flash-background">{{$flashGift}}</span>
                                                </button>
                                                <button type="button" class="btn btn-outline-gold">
                                                    {{__('Flash gain')}}
                                                    <span class="flash-background">{{$flashGain}}$</span>
                                                </button>
                                            @endif
                                            @if(!$flash)
                                                <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                            @endif
                                            <button type="button" id="buy-action-submit"
                                                    wire:loading.attr="disabled"
                                                    wire:target="simulate"
                                                    class="btn @if($flash) btn-flash @else btn-primary  @endif swal2-styled d-inline-flex">
                                                {{ __('Submit') }}
                                                <div
                                                    class="spinner-border spinner-border-sm mx-2 mt-1 buy-action-submit-spinner"
                                                    role="status"></div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Sale Shares')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center pb-2" title="{{$selledActions." / ".$totalActions}}">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-xs">
                                <div class="avatar-title bg-light rounded-circle text-muted fs-16">
                                    <i class=" ri-creative-commons-nc-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="progress animated-progress custom-progress progress-label">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{$precentageOfActions}}%"
                                     aria-valuenow="{{$selledActions}}" aria-valuemin="0"
                                     aria-valuemax="{{$totalActions}}">
                                    <div class="label">{{$precentageOfActions}}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Estimated Sale Shares')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body row">

                    <div class="col-12">
                        <label for="estimatedGain" class="form-label">{{__('Gain')}}</label>
                        <input aria-describedby="estimatedGain" type="number" wire:keyup.debounce="simulateAction()"
                               wire:model="estimatedGain" id="estimatedGain" class="form-control">
                    </div>
                    <div class="col-12">
                        totalPaied : {{$totalPaied}}
                    </div>
                    <div class="col-12">
                        actualActionValue : {{$actualActionValue}}
                    </div>
                    <div class="col-12">
                        selledActionCursor : {{$selledActionCursor}}
                    </div>
                    <div class="col-12">
                        <input type="range" min="0" max="{{$this->totalActions}}" title="{{$this->totalActions}}"
                               wire:model="selledActionCursor" step="1" wire:change="simulateGain()" id="customRange3">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Share Price Evolution')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart1">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Sale Shares tables')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="shares-solde"
                           class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                           style="width:100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{__('date_purchase')}}</th>
                            <th>{{__('number_of_shares')}}</th>
                            <th>{{__('gifted_shares')}}</th>
                            <th>{{__('total_shares')}}</th>
                            <th>{{__('total_price')}}</th>
                            <th>{{__('present_value')}}</th>
                            <th>{{__('current_earnings')}}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <script type="module">
        $(document).ready(function () {
                const input = document.querySelector("#phone");
                const iti = window.intlTelInput(input, {
                    initialCountry: "auto",
                    useFullscreenPopup: false,
                    utilsScript: " {{asset('/build/utils.js/utils.js')}}"
                });
                $('[name="inlineRadioOptions"]').on('change', function () {
                    if ($('#inlineRadio2').is(':checked')) {
                        $('#contact-select').removeClass('d-none');
                        $('#bfs-select').removeClass('d-none');
                    } else {
                        $('#contact-select').addClass('d-none');
                        $('#bfs-select').addClass('d-none');
                    }
                });
                $("#buy-action-submit").one("click", function () {
                    this.disabled = true;
                    $('.buy-action-submit-spinner').show();
                    let ammount = parseFloat($('#ammount').val());
                    let phone = $('#phone').val();
                    let me_or_other = $("input[name='inlineRadioOptions']:checked").val();
                    let bfs_for = $("input[name='bfs-for']:checked").val();
                    let country_code = iti.getSelectedCountryData().iso2;
                    $.ajax({
                        url: "{{ route('buyAction', app()->getLocale()) }}",
                        type: "POST",
                        data: {
                            me_or_other: me_or_other,
                            bfs_for: bfs_for,
                            phone: phone,
                            country_code: country_code,
                            ammount: ammount,
                            vip: {{$flashTimes}},
                            flashMinShares: {{$flashMinShares}},
                            flash: "{{$flash}}",
                            actions: {{$actions}},
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            let backgroundColor = "#27a706"
                            if (data.error) {
                                backgroundColor = "#ba0404";
                                Swal.fire({
                                    icon: "error",
                                    title: "{{__('Validation failed')}}",
                                    html: response.error.join('<br>')
                                });
                            }

                            $('.btn-close-buy-share').trigger('click')

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: data.message,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000,
                                showCloseButton: true
                            });

                            $('.buy-action-submit-spinner').hide();
                            location.reload();
                        },
                        error: function (data) {
                            var responseData = JSON.parse(data.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: "{{__('Error in action purchase transaction')}}",
                                cancelButtonText: '{{__('Cancel')}}',
                                confirmButtonText: '{{__('Confirm')}}',
                                text: responseData.error[0]
                            });
                            $('.buy-action-submit-spinner').hide();
                        }
                    });
                    setTimeout(() => {
                        this.disabled = false;
                        $('.buy-action-submit-spinner').hide();
                    }, 2000);

                })
            }
        );

    </script>
    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script id="rendered-js" type="module">
        $(document).on('turbolinks:load', function () {
            var chart1Origin = document.querySelector('#chart1');
            if (chart1Origin) {
                var options1 = {
                    colors: ['#008ffb', '#00e396', '#d4526e'],
                    chart: {height: 350, type: 'area',},
                    dataLabels: {enabled: false},
                    series: [],
                    title: {text: '{{__('Share Price Evolution')}}',},
                    noData: {text: '{{__('Loading')}}...'},
                    xaxis: {type: 'numeric',},
                    stroke: {curve: 'straight',},
                    annotations: {
                        points: [{
                            x: {{getSelledActions() * 1.05/2}},
                            y: {{getHalfActionValue()*1.01}},
                            marker: {
                                size: 0,
                                fillColor: '#fff',
                                strokeColor: 'transparent',
                                radius: 0,
                                cssClass: 'apexcharts-custom-class'
                            },
                            label: {
                                borderColor: '#ffffff',
                                offsetY: 0,
                                style: {
                                    color: '#fff',
                                    background: '#00e396',
                                    fontSize: '15px',
                                },
                                text: "{{__('x_times')}}",
                            }
                        }]
                    }
                }
                var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
                chart1.render();
                var url1 = '{{route('api_share_evolution',['locale'=> app()->getLocale()])}}';
                var url2 = '{{route('api_action_values',['locale'=> app()->getLocale()])}}';
                var url3 = '{{route('api_share_evolution_user',['locale'=> app()->getLocale()])}}';
                $.when(
                    $.getJSON(url1),
                    $.getJSON(url2),
                    $.getJSON(url3)
                ).then(function (response1, response2, response3) {
                    var series1 = {
                        name: 'Sales',
                        type: 'area',
                        data: response1[0],
                    };
                    var series2 = {name: 'Function', type: 'line', data: response2[0]};
                    var series3 = {name: 'My Shares', type: 'area', data: response3[0]};
                    chart1.updateSeries([series1, series2, series3]);
                });
            }
        });

        $(document).on('turbolinks:load', function () {
            $('#shares-solde').DataTable({
                "ordering": true,
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[5, 'asc']],
                "processing": true,
                "serverSide": false,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {return: true},
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('api_shares_solde',['locale'=> app()->getLocale()])}}",
                "columns": [
                    {data: 'formatted_created_at'},
                    {data: 'value_format'},
                    {data: 'gifted_shares'},
                    {data: 'total_shares'},
                    {data: 'total_price'},
                    {data: 'present_value'},
                    {data: 'current_earnings'},
                ],
                "language": {"url": urlLang}
            });
        });
    </script>
</div>
