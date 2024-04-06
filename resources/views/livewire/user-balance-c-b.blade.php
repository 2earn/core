<div>
    @section('title'){{ __('Cash Balance') }} @endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('Cash Balance') }}@endslot
        @endcomponent

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4">

                            <div class="col-sm-auto">
                                <div>

                               <img src=" {{asset('assets/images/qr_code.jpg')}}" class="rounded avatar-lg">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <p>{{ __('Cash Balance description') }}</p> </div>
                                </div>
                            </div>

                        </div>
                        <div class="card border card-border-info">
                        <div class="card card-body">
                            <div class="d-flex mb-4 align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ URL::asset('assets/images/paytabs.jpeg') }}" alt="">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="card-title mb-1">{{ __('Paytabs_Payment_Gateway') }}</h5>
                                </div>
                            </div>
                            <img src="{{ URL::asset('assets/images/pay.jpeg') }}" alt="" style="height: 60px;
    width: 120px;">
                            <div class="row g-4">
                                <div class="col-lg-6">

                                    <div class="input-group">

                                        <input aria-describedby="simulate" type="number"  class="form-control" id="ammount1" required>
                                        <span class="input-group-text">$</span>
                                        <button class="btn btn-success" type="button" data-bs-target="#tr_paytabs" data-bs-toggle="modal" id="validate"  >{{ __('validate') }}</button></div>
                                    <div class="input-group d-none">
                                        <input aria-describedby="simulate" type="number"  class="form-control" id="ammount2" required>
                                        <span class="input-group-text" >SAR</span></div>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary d-none" type="button" id="simulate1">{{ __('simulate') }}</button>

                                    </div>





                                </div>
                            </div>
                        </div></div>


                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none ">{{ __('Ref') }}</th>
                                <th style=" border: none ">{{ __('Date') }}</th>
                                <th style=" border: none ">{{ __('Operation Designation') }}</th>
                                <th style=" border: none ">{{ __('Description') }}</th>

                                <th style=" border: none ">{{ __('Value') }}</th>
                                <th style=" border: none ">{{ __('Balance') }}</th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>

</div>
   <!--end row-->


<div class="modal fade" id="tr_paytabs" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Transfert') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 >{{ __('validate_transfert') }}</h5>
                <h5 style="color:#464fed" ><div id="usd"></div></h5>
                <h6 >{{ __('markup') }}</h6>
                <form class="needs-validation" novalidate >
                    <div class="row g-3">


                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="button"   id="tran_paytabs" class="btn btn-primary">{{ __('Submit') }}</button>
                                </div>
                            </div>
                    </div><!--end col-->

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    window.onload = function() {

        // Afficher le popup
       if("{{getUsertransaction( Auth()->user()->idUser)[0]}}"!=="null")
       {if({{getUsertransaction( Auth()->user()->idUser)[0]}}===1)
        Swal.fire({
            title: "Transarction Accepted",
            text: "{{getUsertransaction( Auth()->user()->idUser)[2]}}"+"$ Transfered",
            icon: "success",

        });
        else
            Swal.fire({
                title: "Transarction declined",
                text: "{{getUsertransaction( Auth()->user()->idUser)[1]}}",
                icon: "error",

            });

       }


    };



    $(document).on("click", "#validate", function () {

        const usd = document.getElementById("usd");


        usd.innerHTML=$("#ammount1").val()+" USD"+" = "+$("#ammount1").val()* {{usdToSar()}}+" SAR";
        $('#ammount2').val($("#ammount1").val()* {{usdToSar()}});
        });
    $(document).on("click", "#tran_paytabs", function () {
        console.log($("#ammount1").val());
        $('#ammount2').val($("#ammount1").val()* {{usdToSar()}});

        var amount = $('#ammount2').val();
        var routeUrl = "{{ route('paytabs', app()->getLocale()) }}";
        //Ajouter le montant comme paramètre de requête
        routeUrl += "?amount=" + encodeURIComponent(amount);
        window.location.href = routeUrl;


    });
    //console.log("sans");



</script>









