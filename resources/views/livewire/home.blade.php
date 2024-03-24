<div>
    @component('components.breadcrumb')
        @slot('title') {{ __('Home') }} @endslot
    @endcomponent
    <style>
        .logoExterne {
            display: inline-block;
            max-width: 40%;
            height: auto;
            width: 6%;
            margin: 3%;
        }

    </style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <div class="row">
        <div class="col-xl-3 col-md-6" >
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0">{{ __('Cash Balance') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">

                                @if($cashBalance - $arraySoldeD[0] > 0)
                                    <p class="text-success" style="max-height: 5px">+{{$cashBalance - $arraySoldeD[0]}}
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                @elseif($cashBalance - $arraySoldeD[0] < 0)
                                    <p class="text-danger" style="max-height: 5px">{{$cashBalance - $arraySoldeD[0]}} <i
                                            class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                @endif
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            {{--                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{__('DPC')}}<span--}}
                            {{--                                    class="counter-value" data-target="{{$cashBalance}}">0</span>--}}
                            {{--                            </h4>--}}
                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary">$<span class="counter-value"
                                                                                   data-target="{{(int)$cashBalance}}">0</span>
                                <small class="text-muted fs-13">
                                    <?php $val = explode('.', number_format($cashBalance, 2))[1]  ?>
                                    @if($val>0)
                                        .{{$val}}k
                                    @endif
                                </small></h3>
                            <a href="{{route('user_balance_cb' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="{{ URL::asset('assets/icons/298-coins-gradient-edited.json') }}" trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0">{{ __('Balance For Shopping') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                @if($balanceForSopping - $arraySoldeD[1] > 0)
                                    <p class="text-success" style="max-height: 5px">
                                        +{{$balanceForSopping - $arraySoldeD[1]}} <i
                                            class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                @elseif($balanceForSopping - $arraySoldeD[1] < 0)
                                    <p class="text-danger"
                                       style="max-height: 5px">{{$balanceForSopping - $arraySoldeD[1]}} <i
                                            class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                @endif
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            {{--                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{__('DPC')}}<span--}}
                            {{--                                    class="counter-value" data-target="{{$balanceForSopping}}">0</span>--}}
                            {{--                            </h4>--}}
                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary">$<span class="counter-value"
                                                                                   data-target="{{(int)$balanceForSopping}}">0</span><small
                                    class="text-muted fs-13">
                                    <?php $val = explode('.', number_format($balanceForSopping, 2))[1]  ?>
                                        @if($val>0)
                                            .{{$val}}k
                                        @endif
                                </small></h3>
                            <a href="{{route('user_balance_bfs' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="{{ URL::asset('assets/icons/146-basket-trolley-shopping-card-gradient-edited.json') }}"
                                trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate  mb-0">{{ __('Discounts Balance') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">

                                @if($discountBalance - $arraySoldeD[2] > 0)
                                    <p class="text-success" style="max-height: 5px">
                                        +{{$discountBalance - $arraySoldeD[2]}} <i
                                            class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                @elseif($discountBalance - $arraySoldeD[2] < 0)
                                    <p class="text-danger"
                                       style="max-height: 5px">{{$discountBalance - $arraySoldeD[2]}} <i
                                            class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                @endif
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            {{--                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{__('DPC')}}<span--}}
                            {{--                                    class="counter-value" data-target="{{$discountBalance}}">0</span>--}}
                            {{--                            </h4>--}}
                            <h4 class="mb-4 fs-22 fw-semibold ff-secondary">{{__('DPC')}}<span class="counter-value"
                                                                                               data-target="{{(int)$discountBalance}}">0</span><small
                                    class="text-muted fs-13">
                                    <?php $val = explode('.', number_format($discountBalance, 2))[1]  ?>
                                        @if($val>0)
                                            .{{$val}}k
                                        @endif
                                </small></h4>
                            <a href="{{route('user_balance_db' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>

                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="https://cdn.lordicon.com/qrbokoyz.json"
                                trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6"
                                style="width:55px;height:55px">
                            </lord-icon>
                            {{--                            <lord-icon--}}
                            {{--                                src="{{ URL::asset('assets/icons/501-free-0-morph-gradient-edited.json') }}" trigger="loop"--}}
                            {{--                                colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">--}}
                            {{--                            </lord-icon>--}}
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0">{{ __('SMS Solde') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">

                                @if($discountBalance - $arraySoldeD[2] > 0)
                                    <p class="text-success" style="max-height: 5px">
                                        +{{$discountBalance - $arraySoldeD[2]}} <i
                                            class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                @elseif($discountBalance - $arraySoldeD[2] < 0)
                                    <p class="text-danger"
                                       style="max-height: 5px">{{$discountBalance - $arraySoldeD[2]}} <i
                                            class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                @endif
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{__('DPC')}}<span
                                    class="counter-value" data-target="{{$SMSBalance}}">0</span>
                            </h4>
                            <a href="{{route('user_balance_sms' , app()->getLocale() )}} "
                               class="text-decoration-underline">{{ __('see_details') }}</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="{{ URL::asset('assets/icons/981-consultation-gradient-edited.json') }}"
                                trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-3 col-md-6">
            <!-- card -->

            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                        <a href="{{route('sharessolde' , app()->getLocale() )}} "
                               class="text-decoration-underline"><p class="text-uppercase fw-medium text-muted text-truncate   mb-0">{{ __('Actions (Shares)') }}</p></a>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">


                                    <p class="text-success" style="max-height: 5px">{{actualActionValue(getSelledActions())}}
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i></p>

                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            {{--                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{__('DPC')}}<span--}}
                            {{--                                    class="counter-value" data-target="{{$cashBalance}}">0</span>--}}
                            {{--                            </h4>--}}
                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                                                                   data-target="{{getUserSelledActions(Auth()->user()->idUser)}}">0</span>
                                  <small class="text-muted fs-13">
                                    <?php $val =number_format(getUserSelledActions(Auth()->user()->idUser)*actualActionValue(getSelledActions()), 2)  ?>
                                    @if(1>0)
                                        ({{$val}}$)
                                    @endif
                                </small></h3>
                            <button data-bs-target="#buy-action" data-bs-toggle="modal"
                               class="btn btn-sm btn-secondary">{{ __('Buy Shares') }}</button>
                            <span class="badge bg-light text-success  ms-2 mb-0"><i class="ri-arrow-up-line align-middle"></i>{{number_format(getUserActualActionsProfit(Auth()->user()->idUser),2) }} $</span>

                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="{{ URL::asset('assets/icons/wired-gradient-751-share.json') }}" trigger="loop"
                                colors="primary:#464fed,secondary:#bc34b6" style="width:55px;height:55px">
                            </lord-icon>
                        </div>

                    </div>
                </div><!-- end card body -->
            </div>
            <!-- end card -->
        </div>

    </div>
        <h4 class="card-title" style="text-align: center">{{ __('we_are_present_in') }} </h4>


        <div class="col-12" style="padding-right: 0;padding-left: 0;">
            <div class="card" style="height: 500px;">
                <div class="card-body">
                        <div id="any4" ></div></div></div>



            </div> <!-- .col-->
        <div class="col-12" style="padding-right: 0;padding-left: 0;">

            <div class="card" style="height: 500px;">
                <div class="card-body">
                    <div id="any5" ></div></div></div>



        </div> <!-- .col-->
         <!-- end row-->



{{--            <div class="row">--}}
{{--                <div class="d-flex justify-content-center">--}}
{{--                    <img class="logoExterne" src="https://v2.2earn.cash/assets/images/icon-shop.png">--}}
{{--                    <img  class="logoExterne" src="https://v2.2earn.cash/assets/images/icon-learn.png">--}}
{{--                    <img class="logoExterne" src="https://v2.2earn.cash/assets/images/Move2earn Icon.png">--}}
{{--                </div>--}}
{{--            </div>--}}

</div>
<!--end card-->



<div class="modal fade" id="buy-action" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Buy Shares') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 style="color:#464fed" >{{ __('buy_shares_notice') }}</h5>
                <h3 style="color:#464fed ;position:absolute; top:50px;  z-index:99999">↓</h3>
                <div class="d-flex mt-5">
                    <!-- LOGO -->



                    <div class="ms-1 header-item   d-flex me-5  ">
                        <div class="d-flex align-items-end justify-content-between logoTopCash">
                            <a href="{{route('user_balance_cb',app()->getLocale())}}">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info rounded fs-3">
                                       <i class="bx bx-dollar-circle text-info"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center logoTopCashLabel">
                            <div class="flex-grow-1 overflow-hidden">
                                <a href="{{route('user_balance_cb',app()->getLocale())}}">
                                    <p class="text-uppercase fw-medium     mb-0 ms-2">
                                        {{ __('Cash Balance') }}</p>
                                    <h5 class="fs-14 mb-0 ms-2">
                                        {{__('DPC')}}  <?php echo $solde->soldeCB /1 ?>
                                    </h5></a>
                            </div>
                            <div class="flex-shrink-0">

                            </div>
                        </div>
                    </div>
                    <div class="ms-1 header-item  d-flex me-5">
                        <div class="d-flex align-items-end justify-content-between logoTopBFS">
                            <a href="{{route('user_balance_bfs',app()->getLocale())}}">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success rounded fs-3">
                                        <i class="ri-shopping-cart-2-line text-success"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center logoTopBFSLabel">
                            <div class="flex-grow-1 overflow-hidden">
                                <a href="{{route('user_balance_bfs',app()->getLocale())}}">
                                    <p class="text-uppercase fw-medium     mb-0 ms-2">
                                        {{ __('Balance For Shopping') }}</p>
                                    <h5 class="text-success fs-14 mb-0  ms-2">

                                        {{__('DPC')}} <?php echo $solde->soldeBFS /1 ?>
                                    </h5></a>
                            </div>
                            <div class="flex-shrink-0">

                            </div>
                        </div>


                    </div>
                    <div class="ms-1 header-item  d-flex me-5">
                        <div class="d-flex align-items-end justify-content-between logoTopDB">
                            <a href="{{route('user_balance_db',app()->getLocale())}}">
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title bg-soft-secondary rounded fs-3">
                                        <i
                                            class=" ri-coupon-4-line text-secondary"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center logoTopDBLabel">
                            <div class="flex-grow-1 overflow-hidden">
                                <a   href="{{route('user_balance_db',app()->getLocale())}}">
                                    <p
                                        class="text-uppercase fw-medium     mb-0 ms-2">
                                        {{ __('Discounts Balance') }}</p>
                                    <h5   class="text-secondary fs-14 mb-0 ms-2">

                                        {{__('DPC')}} </span> <?php  echo $solde->soldeDB /1 ?>
                                    </h5></a>
                            </div>
                            <div class="flex-shrink-0">

                            </div>
                        </div>


                    </div>
                </div>
                <form class="needs-validation" novalidate >
                    <div class="row g-3">
                        <div class="col-lg-12">
                                                        <label class="form-label">{{ __('Buy For') }} </label>
                                                        <div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"  checked id="inlineRadio1" value="me">
                                                                <label class="form-check-label" for="inlineRadio1">{{ __('me') }}</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="other">
                                                                <label class="form-check-label" for="inlineRadio2">{{ __('other') }}</label>
                                                            </div>

                                                        </div>
                        </div><!--end col-->

                        <div class="col-xxl-6 d-none" id="contact-select">
                            <div>
                                <label for="firstName" class="form-label">{{ __('Mobile_Number') }}</label>
                                  <input type="tel" class="form-control" name="mobile" id="phone" required>

                            </div>
                        </div><!--end col-->
                        <div class="col-xxl-6 d-none" id="bfs-select">



                            <label  class="form-label mb-3">{{ __('BFS bonuses  for') }} </label>
                            <div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="bfs-for"   value="me">
                                                                        <label class="form-check-label" >{{ __('me') }}</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="bfs-for"   value="other">
                                                                        <label class="form-check-label">{{ __('The chosen user') }}</label>
                                                                    </div>
                            </div>
                        </div><!--end col-->

                        <div class="col-12">

                            <label for="customer-name" class="col-form-label">{{ __('Amount_pay') }}($)</label>
                            <div class="input-group">

                                                                <input aria-describedby="simulate" type="number" max="{{intval($cashBalance)}}"  placeholder="{{$cashBalance}}" class="form-control" id="ammount" required>
                                                                <button type="button"   id="simulate" class="btn btn-outline-primary">{{ __('simulate') }}</button>

                            </div>
                        </div><!--end col-->
                        <div class="col-12">
                            <div>
                                                                <label for="customer-name" class="col-form-label">{{ __('Number Of Shares') }} </label>
                                                                <input type="number"  disabled class="form-control" id="number-of-action" value="0000" >

                            </div>
                            <div class="col-12">
                            <div>
                                                                <label for="customer-name" class="col-form-label">{{ __('Gifted Shares') }}</label>
                                                                <input type="number"  disabled class="form-control" id="number-of-gifted-action" value="0000" >
                            </div>
                        </div><!--end col-->
                            <div class="col-12 mb-3">
                                <div>
                                    <label for="customer-name" class="col-form-label">{{ __('Profit') }}($) </label>
                                    <input type="number"  disabled class="form-control" id="profit" value="0000" >
                                </div>
                            </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                                <button type="button"   id="buy-action-submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script >
        $(document).on('ready ', function () {
            const input = document.querySelector("#phone");
            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
               // showSelectedDialCode: true,
                useFullscreenPopup: false,

                utilsScript: "{{asset('assets/js/utils.js')}}" // just for formatting/placeholders etc
            });




            $('[name="inlineRadioOptions"]').on('change', function() {

                if ($('#inlineRadio2').is(':checked')) {



                    $('#contact-select').removeClass('d-none') ;
                    $('#bfs-select').removeClass('d-none') ;
                } else {
                    $('#contact-select').addClass('d-none') ;
                    $('#bfs-select').addClass('d-none') ;
                }
            });


                    $(document).on("click", "#simulate", function () {
                       // console.log($("#ammount").val());
                        $.ajax({
                            url: "{{ route('action-by-ammount') }}",
                            type: "GET",
                            data: {
                                ammount: $("#ammount").val(),
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function (data) {
                                //console.log(data);
                                $('#number-of-action').val(data.action);
                                $('#number-of-gifted-action').val(data.gift);
                                $('#profit').val(data.profit);
                            }
                        });
                    });


// Utilisation de la fonction générique avec votre exemple



            $(document).on("click", "#buy-action-submit", function () {

                let ammount=$('#ammount').val() ;
                let phone=$('#phone').val() ;
                let me_or_other=$("input[name='inlineRadioOptions']:checked").val() ;
                let bfs_for=$("input[name='bfs-for']:checked").val() ;
                let country_code =iti.getSelectedCountryData().iso2 ;

                //console.log(phone) ;
                //console.log(ammount) ;
                //console.log(me_or_other) ;
                //console.log(bfs_for) ;
                //console.log(country_code) ;

                $.ajax({
                    url: "{{ route('buyAction') }}",
                    type: "POST",
                    data: {
                        me_or_other:me_or_other ,
                        bfs_for:bfs_for ,
                        phone:phone ,
                        country_code:country_code ,
                        ammount:ammount ,

                        "_token": "{{ csrf_token() }}"
                    },




                    success: function(data) {
                        //console.log(data);
                        let backgroundColor= "#27a706"
                        if(data.error)
                            {backgroundColor= "#ba0404";
                               // console.log("aaaaa");
                                Swal.fire({
                icon: "error",
                title: "Validation Failed",
                html: response.error.join('<br>')
            });}

                        $('#buy-action').modal('hide');
                        Toastify({
                            text: data.message,
                            gravity: "top",
                            duration: 4000,
                            className: "info",
                            position: "center",
                            backgroundColor: backgroundColor
                        }).showToast();
                    },
                    error: function(data) {
                       // console.log(data.responseText);
                       // console.log(data);
                        var responseData = JSON.parse(data.responseText);
                       // console.log(responseData.error[0]);
                        Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: responseData.error[0],
                                });


                    }

                });


            })

            }
        );
    </script>
    <script >

        var series;



        anychart.onDocumentReady(function ()  {

            //console.log("aaaaa");
            //document.getElementById("any4").innerHTML = "";
            // The data used in this sample can be obtained from the CDN
            // https://cdn.anychart.com/samples/maps-in-dashboard/states-of-united-states-dashboard-with-multi-select/data.json
            anychart.data.loadJsonFile(
                "{{route('API_stat_countries',app()->getLocale())}}",
                function (data) {


                    var map = anychart.map();
                    map.geoData('anychart.maps.world');
                    map.padding(0);
                    var dataSet = anychart.data.set(data);
                    var densityData = dataSet.mapAs({ id:'apha2',value: 'COUNT_USERS' });
                    series = map.choropleth(densityData);
                    series.labels(false);
                    series
                        .hovered()
                        .fill('#f48fb1')
                        .stroke(anychart.color.darken('#f48fb1'));
                    series
                        .tooltip(false);
                    var scale = anychart.scales.ordinalColor([
                        { less: 2 },
                        { from: 2, to: 5 },
                        { from: 5, to: 10 },
                        { from: 10, to: 15 },
                        { from: 15, to: 30 },
                        { from: 30, to: 50 },
                        { from: 50, to: 100 },
                        { from: 100, to: 500 },
                        { greater: 500 }
                    ]);
                    scale.colors([
                        '#81d4fa',
                        '#4fc3f7',
                        '#29b6f6',
                        '#039be5',
                        '#0288d1',
                        '#0277bd',
                        '#01579b',
                        '#014377',
                        '#000000'
                    ]);
                    series.colorScale(scale);
                    var zoomController = anychart.ui.zoom();
                    zoomController.render(map);

                    map.container('any4');
                    map.draw();

                    var mapping = dataSet.mapAs({ x: "name", value: "COUNT_USERS",category:"continant" });
                    //console.log(mapping);
                    var colors = anychart.scales
                        .ordinalColor()
                        .colors(['#26959f', '#f18126', '#3b8ad8', '#60727b', '#e24b26']);

                    // create tag cloud
                    var chart = anychart.tagCloud();
                    //var serie = anychart.tagCloud();

                    // set chart title
                    chart

                        // set data with settings
                        .data(mapping)
                        // set color scale
                        .colorScale(colors)
                        // set array of angles, by which words will be placed
                        .angles([-90, 0, 90,]);
                    chart
                        .tooltip(false);
                    var colorRange = chart.colorRange();
                    // enabled color range
                    colorRange
                        .enabled(true)
                        // sets color line size
                        .colorLineSize(15);

                    // set container id for the chart
                    //chart.container('any5');
                    // initiate chart drawing
                    //chart.draw();

                    // save normal fill function to variable
                    var normalFillFunction = chart.normal().fill();
                    // save hover fill function to variable
                    var hoveredFillFunction = chart.hovered().fill();

                    // create custom interactivity to hover colorRange
                    chart.listen('pointsHover', function (e) {
                        if (e.actualTarget === colorRange) {
                            // if points exist
                            if (e.points.length) {
                                // set settings for normal state
                                chart.normal({
                                    fill: 'black 0.1'
                                });
                                // set settings for hovered state
                                chart.hovered({
                                    // get fill color ratio by its number and set fill to hovered state
                                    fill: chart
                                        .colorScale()
                                        .valueToColor(e.point.get('category'))
                                });
                            } else {
                                // set function for normal state
                                chart.normal({
                                    fill: normalFillFunction
                                });
                                // set function for hovered state
                                chart.hovered({
                                    fill: hoveredFillFunction
                                });
                            }
                        }
                    });

                    chart.container('any5');
                    chart.draw();











                }
            );
        });

    </script>

@endpush

