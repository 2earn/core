<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <style>
        .input-container {
            display: block;
            position: relative;
            padding-left: 25px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 18px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .input-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 50%;
        }

        .input-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        .input-container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .input-container input:checked ~ .checkmark:after {
            display: block;
        }

        .input-container .checkmark:after {
            top: 7px;
            left: 6px;
            width: 8px;
            height: 8px;
            border-radius: 0%;
            background: white;
        }

        .payment-options-container {
            display: flex;
            justify-content: space-around;
        }

        img.funding-icon {
            width: 32px;
            height: 32px;
            margin: 0 2px;
        }

        .center {
            display: flex;
            justify-content: start;
            align-items: center;
            /*border: 3px solid green;*/
        }
    </style>
    <script data-turbolinks-eval="false">
        var SuccesExchange = '{{Session::has('SuccesExchange')}}';
        if (SuccesExchange) {
            toastr.success('{{Session::get('SuccesExchange')}}');
        }

        var exist = '{{Session::has('ErrorOptCodeForget')}}';
        var msg = "your Opt code is incorrect !";
        if (exist) {
            var local = '{{Session::has('locale')}}';
            if (local == 'ar') {
                msg = "هاتفك أو كلمة المرور الخاصة بك غير صحيحة !";
            }
            Swal.fire({
                title: ' ',
                text: msg,
                icon: 'error',
                confirmButtonText: {{trans('ok')}}
            }).then(okay => {
                if (okay) {
                    window.location.reload();
                }
            });
        }
        var existvalide = '{{Session::has('succesOpttSms')}}';
        if (existvalide) {
            var someTabTriggerEl = document.querySelector('#pills-UpdatePhone-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            toastr.success('Succées');
        }
        var SuccesRequstAccepted = '{{Session::has('SuccesRequstAccepted')}}';
        if (SuccesRequstAccepted) {
            var someTabTriggerEl = document.querySelector('#pills-contact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            toastr.success('Succées');
        }
        var ErreurSoldeReqBFS2 = '{{Session::has('ErreurSoldeReqBFS2')}}';
        if (ErreurSoldeReqBFS2) {
            var someTabTriggerEl = document.querySelector('#pills-contact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            Swal.fire({
                title: '{{trans('SureTransfertCashBFS')}}',
                text: '{{trans('SoldeRequestInsuffisant')}} ',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{trans('Cancel')}}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{trans('Yes')}}'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var paramValue = url.searchParams.get("FinRequestN");

                    window.Livewire.emit('redirectToTransfertCash', '{{Session::get('ErreurSoldeReqBFS2')}}', paramValue);
                }
            })
        }
        var ErreurSoldeReqCash = '{{Session::has('ErreurSoldeReqCash')}}';
        if (ErreurSoldeReqCash) {
            var someTabTriggerEl = document.querySelector('#pills-contact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{Session::get('ErreurSoldeReqCash')}}',
                footer: '<a href="">Why do I have this issue?</a>'
            })
        }
        var SuccesSendPublicRequest = '{{Session::has('SuccesSendPublicRequest')}}';
        if (SuccesSendPublicRequest) {
            var someTabTriggerEl = document.querySelector('#pills-profile-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            Swal.fire({
                icon: 'success',
                title: '{{trans('success send public request')}}',
                html: '<span style ="color : red;" > {{trans('verification_code')}} </span>' + '{{Session::get('SuccesSendPublicRequest')}}',
                footer: '{{trans('save security code')}}',
                confirmButtonText: '{{trans('ok')}}',
            })
        }
        var tabRequest = '{{Session::has('tabRequest')}}';
        if (tabRequest) {
            var someTabTriggerEl = document.querySelector('#pills-profile-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();

        }
    </script>

    @component('components.breadcrumb')
        @slot('title')
            {{ __('Financial_Transaction') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-body">
                    <ul id="pills-tab" class="nav nav-tabs nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#cash_bfs" role="tab"
                               aria-selected="false" tabindex="-1">
                                {!! __('Cash >> BFS') !!}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_funding" role="tab"
                               aria-selected="false" tabindex="-1">
                                {{ __('BFS Funding') }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_sms" role="tab"
                               aria-selected="false" tabindex="-1">
                                {!! __('BFS >> SMS') !!}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="pills-profile-tab" class="nav-link" data-bs-toggle="tab" href="#me_others" role="tab"
                               aria-selected="true">
                                {!! __('Requests: Me >> Others') !!}
                                @if($requestOutAccepted>0)
                                    <button id="btnNotRequestOutAcccepted" type="button"
                                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                        <i class=" ri-user-follow-line text-success fs-22"></i>
                                        <span id="pOutAccepted"
                                              class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success">
                                            {{$requestOutAccepted}}
                                        </span>
                                    </button>
                                @endif
                                @if($requestOutRefused>0)
                                    <button id="btnNotRequestOutRefused" type="button"
                                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                        <i class=" ri-user-unfollow-line text-danger fs-22"></i>
                                        <span id="pOutRefused"
                                              class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">{{$requestOutRefused}}</span>
                                    </button>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="pills-contact-tab" class="nav-link" data-bs-toggle="tab" href="#others_me" role="tab"
                               aria-selected="true">
                                {!! __('Requests: Others >> Me') !!}
                                @if($requestInOpen>0)
                                    <button id="btnNotRequestInOpen" type="button"
                                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                        <i class="  ri-user-received-2-line text-primary fs-22"></i>
                                        <span id="pIn"
                                              class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-primary">  {{$requestInOpen}}</span>
                                    </button>
                                @endif
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane active show" id="cash_bfs" role="tabpanel">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">{{ __('BFS_Transaction') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-4">
                                        <div class="col-xxl-4 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Enter your amount') }}</span>
                                                <input type="number"
                                                       name="montantExchange"
                                                       id="montantExchange"
                                                       onpaste="handlePaste(event)"
                                                       class="form-control text-center "
                                                       placeholder="{{ __('Enter your amount') }}"
                                                       onpaste="handlePaste(event)" wire:model.defer="soldeExchange">
                                            </div>
                                        </div>
                                        @if(config('app.available_locales')[app()->getLocale()]['direction']=='ltr')
                                            <div class="col-1 mx-auto d-none d-xxl-block ">
                                                <i class="ri-arrow-right-s-line fs-3 me-n3 text-primary"></i>
                                                <i class="ri-arrow-right-s-line fs-3 ms-n1  text-primary"></i>
                                            </div>
                                        @else
                                            <div class="col-1 mx-auto d-none d-xxl-block ">
                                                <i class="ri-arrow-left-s-line fs-3 me-n3 text-primary"></i>
                                                <i class="ri-arrow-left-s-line fs-3 ms-n1  text-primary"></i>
                                            </div>
                                        @endif
                                        <div class="col-1 mx-auto d-xxl-none">
                                            <i class=" ri-download-line fs-3 mt-n3 text-primary"></i>

                                        </div>
                                        <div class="col-xxl-4 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Balance For Shopping') }}</span>
                                                <input type="number"
                                                       name="soldeBFS" id="soldeBFS" class="form-control text-center"
                                                       value="" disabled
                                                       onpaste="handlePaste(event)">
                                            </div>
                                        </div>
                                        <div class="col-6 mx-auto ">
                                            <button class="btn btn-primary w-100 mt-3 btn2earn"
                                                    onclick="ConfirmExchange()"
                                                    id="exchange">{{ __('Exchange Now') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bfs_funding" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('backand_BFS_Account_Funding') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-4">
                                        <div class="col-xxl-8 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('backand_Amount_to_Fund_in_DCP') }}</span>
                                                <input style="color: #939393" type="number" name="fundAmountTXT"
                                                       id="amount"
                                                       class="form-control text-center"
                                                       placeholder="{{ __('backand_Enter_the_funding_amount') }}"
                                                       onpaste="handlePaste(event)">
                                            </div>
                                        </div>
                                        <div class="col-xxl-8 mx-auto text-center ">
                                            <h5 class="mb-5 text-center "> {{ __('backand_Choose_payment_option') }}</h5>
                                            <div class="form-check form-check-inline ">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="paypal" id="paypal" onclick="setPaymentFormTarget(0)">
                                                <label class="form-check-label fs-5 text-primary"
                                                       for="flexSwitchCheckChecked">
                                                    <i class="ri-paypal-fill me-2 "></i>
                                                    {{__('Paypal')}}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="creditCard" id="creditCard"
                                                       onclick="setPaymentFormTarget(1)">
                                                <label class="form-check-label fs-5 text-success "
                                                       for="flexSwitchCheckChecked">
                                                    <i class="ri-bank-card-fill me-2 "></i>
                                                    {{__('Creditcard')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="publicUser" id="publicUser"
                                                       onclick="setPaymentFormTarget(3)">
                                                <label class="form-check-label fs-5 text-danger "
                                                       for="flexSwitchCheckChecked">
                                                    <i class="ri-team-fill me-2"></i>
                                                    {{__('PublicUsers')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="upline" id="upline" onclick="setPaymentFormTarget(2)">
                                                <label class="form-check-label fs-5 text-warning"
                                                       for="flexSwitchCheckChecked">
                                                    <i class="ri-user-2-fill me-2 "></i>
                                                    {{__('requstAdmin')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xxl-8 mx-auto ">
                                            <button class="btn btn-primary w-100 mt-3 btn2earn" id="pay">
                                                {{ __('backand.Fund') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bfs_sms" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('backand_BFS_Account_Funding') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div
                                        class="alert alert-info border-0 rounded-top rounded-0 m-0 d-flex align-items-center mb-3">
                                        <div class="flex-grow-1 text-truncate ">
                                            {{ __('SMS price') }} <b>{{ $prix_sms}} </b> {{__('DPC')}}
                                        </div>
                                    </div>
                                    <div class="row gy-4">
                                        <div class="col-xxl-8 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Enter number of SMS') }}</span>
                                                <input type="number"
                                                       oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       name="NSMS" id="NSMS"
                                                       class="form-control text-center" placeholder=""
                                                       onpaste="handlePaste(event)">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Enter your amount') }}</span>
                                                <input type="number" name="soldeSMS" id="soldeSMS"
                                                       class="form-control text-center"
                                                       placeholder="{{ __('Enter your amount') }}"
                                                       onpaste="handlePaste(event)">
                                            </div>
                                        </div>
                                        <div class="col-xxl-8 mx-auto text-center ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Balance For Shopping') }}</span>
                                                <input type="number" name="soldeBFSSMS" id="soldeBFSSMS"
                                                       class="form-control text-center" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xxl-8 mx-auto ">
                                            <button class="btn btn-primary w-100 mt-3 btn2earn" id="submitExchangeSms"
                                                    onclick="ConfirmExchangeSms()"> {{ __('Exchange Now') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="me_others" role="tabpanel">
                            <div class="card-header">
                                <div class="form-check">
                                    <input onclick="ShowCanceledRequest()" class="form-check-input" type="checkbox"
                                           @if($showCanceled == 1) checked @endif value="" id="ShowCanceled">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        {{__('show_canceled')}}
                                    </label>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive ">
                                    <table class=" table table-responsive tableEditAdmin"
                                           id="ReqFromMe_table2"
                                           style="width: 100%">
                                        <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('numeroReq')}}</th>
                                            <th>{{__('date')}}</th>
                                            <th>{{__('Amount')}}</th>
                                            <th>{{__('Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse  ($requestFromMee as $value)
                                            <tr>
                                                <td onclick="hiddenTr({{$value->numeroReq}})">
                                                    <i style="color: #51A351" class="fas fa-plus-circle"></i>
                                                </td>
                                                <td onclick="hiddenTr({{$value->numeroReq}})">
                                                    <span>{{$value->numeroReq}}</span></td>
                                                <td onclick="hiddenTr({{$value->numeroReq}})">
                                                    <span> {{$value->date}}</span>
                                                </td>
                                                <td onclick="hiddenTr({{$value->numeroReq}})">
                                                    <span>{{$value->amount}}</span></td>
                                                <td><span>
                                                    @if($value->FStatus == 0)
                                                            <a style="background-color: #F89406;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding:@if(app()->getLocale()=="ar") 1px @else 5px @endif ; ">{{__('Opened')}}</a>
                                                            <a onclick="cancelRequestF('{{$value->numeroReq}}')"
                                                               style="background-color: #3595f6;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: @if(app()->getLocale()=="ar") 1px @else 5px @endif ; ">{{__('Cancel')}}</a>
                                                        @elseif($value->FStatus == 1)
                                                            <a style="background-color: #51A351;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: @if(app()->getLocale()=="ar") 1px @else 5px @endif ; ">{{__('Accepted')}}</a>
                                                        @elseif($value->FStatus == 3)
                                                            <a style="background-color: #f02602;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: @if(app()->getLocale()=="ar") 1px @else 5px @endif ; ">{{__('Canceled')}}</a>
                                                        @else
                                                            <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: @if(app()->getLocale()=="ar") 1px @else 5px @endif ; ">{{__('Rejected')}}</a>
                                                        @endif
                                                </span>
                                                </td>
                                            </tr>
                                            <tr hidden="true" id={{$value->numeroReq}}>
                                                <td colspan="12">
                                                    <table class=" table table-responsive table2earn "
                                                           style="width: 100%">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('user')}}</th>
                                                            <th>{{__('Mobile Number')}}</th>
                                                            <th>{{__('response')}}</th>
                                                            <th>{{__('dateResponse')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($value->details  as $valueD)
                                                            @if($valueD->user !=null )
                                                                <tr>
                                                                    <td><span> {{$valueD->User->name}}</span></td>
                                                                    <td><span> {{$valueD->User->mobile}}</span></td>
                                                                    <td>
                                                                        <span>
                                                                            @if($valueD->response == "" ||$valueD->response == null )
                                                                                <a style="background-color: #F89406;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('No Response')}}</a>
                                                                            @elseif($valueD->response == 1)
                                                                                <a style="background-color: #51A351;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('Accepted')}}</a>
                                                                            @elseif($valueD->response == "2")
                                                                                <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('Rejected')}}</a>
                                                                            @elseif($valueD->response == "3")
                                                                                <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('Canceled')}}</a>
                                                                            @endif
                                                                        </span>
                                                                    </td>
                                                                    <td><span> {{$valueD->dateResponse}}</span></td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">{{__('No Outgoing requests')}}</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal fade" id="financialTransactionModal" tabindex="-1"
                                 aria-labelledby="financialTransactionModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="financialTransactionModalLabel">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ...
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="others_me" role="tabpanel">
                            <div class="card-body table-responsive ">
                                <table class="table align-middle dt-responsive nowrap" id="customerTable2"
                                       style="width: 100%">
                                    <thead class="table-light">
                                    <tr class="tabHeader2earn">
                                        <th>{{ __('Request') }}</th>
                                        <th>{{ __('date') }}</th>
                                        <th>{{ __('user') }}</th>
                                        <th>{{ __('UserPhone') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    @foreach ($requestToMee as $value)
                                        <tr>
                                            <td><span>{{$value->numeroReq}}</span></td>
                                            <td><span> {{$value->date}}</span></td>
                                            <td><span> {{$value->name}}</span></td>
                                            <td><span>{{$value->mobile}}</span></td>
                                            <td><span>{{number_format((float)$value->amount, 2, '.', ' ')}} </span></td>
                                            <td><span>
                                                    @if($value->status == 0)
                                                        <a style="background-color: #51A351;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('Opened')}}</a>
                                                    @else
                                                        <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('Closed')}}</a>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @if($value->status == 0)
                                                    <i onclick="acceptRequst('{{$value->numeroReq}}')"
                                                       style="cursor:pointer; color: #51A351;font-size: 20px;margin: 5px 5px"
                                                       class="fa-regular fa-circle-check"></i>
                                                    <i onclick="rejectRequst('{{$value->numeroReq}}')"
                                                       style="cursor:pointer; color: #BD362F;font-size: 20px;margin: 5px 5px"
                                                       class="fa-regular fa-circle-xmark"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function ConfirmExchange() {
            var soldecashB = {{ $soldecashB}};
            var soldeExchange = document.getElementById('montantExchange').value
            if (Number.isNaN(soldecashB) || Number.isNaN(soldeExchange)) return;
            if (soldeExchange < 0) return;
            if (soldeExchange == 0) {
                Swal.fire({
                    title: '{{trans('Please enter the transfer amount!')}}',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '{{trans('ok')}}',
                })
                return;
            }
            var newSolde = soldecashB - soldeExchange;
            if (newSolde < 0) {
                Swal.fire({
                    title: '{{trans('Your_cash_balance')}}',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '{{trans('ok')}}',
                })
                return;
            }
            Swal.fire({
                title: '{{trans('Are you sure to exchange ?')}}' + " " + '<br>' + soldeExchange + "$ " + '{{trans('?')}}',
                text: '{{trans('operation_irreversible')}}',
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                confirmButtonText: '{{trans('ok')}}',
                denyButtonText: 'No',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.emit('PreExchange');
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }

        function ConfirmExchangeSms() {
            var soldeBFS = {{ $soldeBFS}};
            var nbSMS = $("#NSMS").val();
            var soldeExchange = $("#soldeSMS").val();
            if (Number.isNaN(nbSMS) || Number.isNaN(soldeExchange)) return;
            if (soldeExchange < 0) return;
            if (soldeExchange == 0) {
                Swal.fire({
                    title: '{{trans('Please enter the transfer amount!')}}',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '{{trans('ok')}}',
                })
                return;
            }
            var newSolde = soldeBFS - soldeExchange;
            if (newSolde < 0) {
                Swal.fire({
                    title: '{{trans('BFS_not_allow')}}',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '{{trans('ok')}}',
                })
                return;

            }
            Swal.fire({
                title: '{{trans('Are you sure to exchange ?')}}' + '<br>' + " " + soldeExchange + "$ " + '{{trans('?')}}',
                text: '{{trans('operation_irreversible')}}',
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                confirmButtonText: '{{trans('ok')}}',
                denyButtonText: 'No',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.emit('PreExchangeSMS');
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }

        function cancelRequestF(numReq) {
            Swal.fire({
                title: `{{trans('cancel_request')}}`,
                confirmButtonText: '{{trans('Yes')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('No')}}',
                denyButtonText: 'No',
                customClass: {actions: 'my-actions', confirmButton: 'order-2', denyButton: 'order-3',}
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.emit('DeleteRequest', numReq);
                }
            })
        }

        function hiddenTr(num) {
            $("#" + num).prop("hidden", !$("#" + num).prop("hidden"));
        }

        function ShowCanceledRequest() {
            if (document.getElementById('ShowCanceled').checked) {
                window.Livewire.emit('ShowCanceled', 1)
            } else {
                window.Livewire.emit('ShowCanceled', 0)
            }
        }
    </script>
    <script type="module">
        var timerInterval;
        var mnt = {{$testprop}};
        var prixSms = {{$prix_sms}};
        var soldeBFS = {{$soldeBFS}};
        var inputMontantSms = $("#soldeSMS");
        var inputSms = $("#NSMS");
        var inputsoldeBFSSMS = $("#soldeBFSSMS");
        var inputsoldeBFS = $("#soldeBFS");
        var Mymnt = {{$soldeExchange}};
        var newmntBFS = soldeBFS + Mymnt;
        inputsoldeBFS.val(newmntBFS.toFixed(2));
        $("#montantExchange").keyup(function () {
            var tt = parseFloat(soldeBFS) + parseFloat($(this).val());
            inputsoldeBFS.val(tt);
        })
        inputSms.val(mnt);
        var mntSms = mnt * prixSms;
        var newsoldeBFS = soldeBFS - mntSms
        inputMontantSms.val(mntSms.toFixed(2));
        inputsoldeBFSSMS.val(newsoldeBFS.toFixed(2));
        $("#NSMS").keyup(function () {
            var montantSms = $(this).val() * prixSms;
            inputMontantSms.val(montantSms.toFixed(2));
            var newsolde = soldeBFS - montantSms;
            newsoldeBFS = soldeBFS - montantSms;
            inputsoldeBFSSMS.val(newsolde.toFixed(2));
            if (montantSms == 0) {
                $("#submitExchangeSms").prop('disabled', true);
            } else {
                $("#submitExchangeSms").prop('disabled', false);
            }
        });

        $("#NSMS").keyup(function () {
            var montantSms = $(this).val() * prixSms;
            inputMontantSms.val(montantSms.toFixed(2));
            var newsolde = soldeBFS - montantSms;
            newsoldeBFS = soldeBFS - montantSms;
            inputsoldeBFSSMS.val(newsolde.toFixed(2));
            if (montantSms == 0) {
                $("#submitExchangeSms").prop('disabled', true);
            } else {
                $("#submitExchangeSms").prop('disabled', false);
            }
        });
        $("#soldeSMS").focusout(function () {
            var sms = $("#NSMS").val();
            var mnt = sms * prixSms;
            inputMontantSms.val(mnt.toFixed(2));
            var newsolde = soldeBFS - mnt;
            newsoldeBFS = soldeBFS - mnt;
            inputsoldeBFSSMS.val(newsolde.toFixed(2));
        });
        $("#submitExchangeSms").prop('disabled', true);
        window.addEventListener('OptExBFSCash', event => {
            Swal.fire({
                title: '{{ __('Your verification code') }}',
                html: '{{ __('We_will_send') }}<br>' + event.detail.FullNumber + '<br>' + '{{ __('Your OTP Code') }}',
                input: 'text',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT') }}',
                timerProgressBar: true,
                confirmButtonText: '{{trans('ok')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    const b = Swal.getFooter().querySelector('i');
                    Swal.getFooter().querySelector('div').classList.add("row");
                    const p22 = Swal.getFooter().querySelector('div');
                    timerInterval = setInterval(() => {
                        p22.innerHTML = '<div class="col-12">{{trans('It will close in')}}' + ' ' + (Swal.getTimerLeft() / 1000).toFixed(0) + ' ' + '{{trans('secondes')}}' + '</br> ' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a> </div>'
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },
                input: 'text',
                inputAttributes: {autocapitalize: 'off'},
                inputAttributes: {autocapitalize: 'off'},
            }).then((resultat) => {
                if (resultat.value) {
                    window.Livewire.emit('ExchangeCashToBFS', resultat.value);
                }
            })
        })


        window.addEventListener('confirmSms', event => {
            Swal.fire({
                title: '{{ __('Your verification code') }}',
                html: '{{ __('We_will_send') }}<br> ',
                html: '{{ __('We_will_send') }}<br>' + event.detail.FullNumber + '<br>' + '{{ __('Your OTP Code') }}',
                input: 'text',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT') }}',
                timerProgressBar: true,
                confirmButtonText: '{{trans('ok')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                footer: '<div class="footerOpt"></div>',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                didOpen: () => {
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    timerInterval = setInterval(() => {
                        p22.innerHTML = '{{trans('It will close in')}}' + (Swal.getTimerLeft() / 1000).toFixed(0) + '{{trans('secondes')}}' + '</br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>'
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },
            }).then((resultat) => {
                if (resultat.value) {
                    window.Livewire.emit('exchangeSms', resultat.value, $("#NSMS").val());
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })

        var theUrl = '';

        function setPaymentFormTarget(gate) {
            if (gate == 0) {
                theUrl = "paymentpaypal";
            } else if (gate == 1) {
                theUrl = "paymentcreditcard";
            } else if (gate == 2) {
                theUrl = "paymentviaadmin";
            } else if (gate == 3) {
                theUrl = "req_public_user";
            }
        }

        function acceptRequst(numeroRequest) {
            window.Livewire.emit('AcceptRequest', numeroRequest);
        }

        function rejectRequst(numeroRequest) {
            Swal.fire({
                title: `{{trans('reject_request')}}`,
                confirmButtonText: '{{trans('Yes')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('No')}}',
                customClass: {actions: 'my-actions', confirmButton: 'order-2', denyButton: 'order-3',}
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.emit('RejectRequest', numeroRequest);
                }
            })
        }

    </script>
    <script data-turbolinks-eval="false" type="module">
        $(document).on('turbolinks:load', function () {
            var triggerTabList = [].slice.call(document.querySelectorAll('#pills-tab a'))
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)
                triggerEl.addEventListener('click', function (event) {
                    var x = triggerEl.id;
                    if (triggerEl.id === "pills-contact-tab") {
                        $.ajax({
                            url: "{{ route('resetInComingNotification') }}",
                            type: 'get',
                            success: function (result) {
                                try {
                                    document.getElementById('pIn').innerHTML = "";
                                    document.getElementById('btnNotRequestInOpen').remove();
                                } catch (e) {
                                }
                                try {
                                    document.getElementById('sideNotIn').innerHTML = "";
                                    document.getElementById('sideNotIn').remove();
                                } catch (e) {
                                }
                            }
                        });
                    }
                    if (triggerEl.id === "pills-profile-tab") {
                        $.ajax({
                            url: "{{ route('resetOutGoingNotification') }}",
                            type: 'get',
                            success: function (result) {
                                try {
                                    document.getElementById('pOutAccepted').innerHTML = "";
                                    document.getElementById('btnNotRequestOutAcccepted').remove();
                                } catch (e) {
                                }
                                try {
                                    document.getElementById('pOutRefused').innerHTML = "";
                                    document.getElementById('btnNotRequestOutRefused').remove();
                                } catch (e) {
                                }
                                try {
                                    document.getElementById('sideNotOutRefused').innerHTML = "";
                                    document.getElementById('sideNotOutRefused').remove();
                                } catch (e) {
                                }
                                try {
                                    document.getElementById('sideNotOutAccepted').innerHTML = "";
                                    document.getElementById('sideNotOutAccepted').remove();
                                } catch (e) {
                                }
                            }
                        });
                    }
                })
            })
        });
        $("#pay").click(function () {
            var amount = $("#amount").val();
            if (!(amount) || (amount == 0)) {
                swal.fire({
                    title: `{{trans('the funding amount field can not be empty or 0! Please enter a valid amount!')}}`,
                    icon: "error",
                    confirmButtonText: '{{trans('Yes')}}'
                });
                return;
            }
            if ((!$("#paypal").is(':checked')) && (!$("#creditCard").is(':checked')) && (!$("#upline").is(':checked')) && (!$("#publicUser").is(':checked'))) {
                swal.fire({
                    title: `{{trans('choose_payment_option')}}`,
                    icon: "error",
                    confirmButtonText: '{{trans('Yes')}}'
                });
                return;
            }
            window.Livewire.emit('redirectPay', theUrl, amount);
        });
        var lan = "{{config('app.available_locales')[app()->getLocale()]['tabLang']}}";
        var urlLang = "https://cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
        $('#customerTable2').DataTable({
            order: [[1, 'desc']],
            "language": {
                "url": urlLang
            }
        });
    </script>
</div>
