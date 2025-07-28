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
            <table class="table table-striped table-bordered tableEditAdmin"
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
                            <table class="table table-striped table-bordered table2earn "
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
    <script>

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
                    window.Livewire.dispatch('DeleteRequest', [numReq]);
                }
            })
        }

        function hiddenTr(num) {
            $("#" + num).prop("hidden", !$("#" + num).prop("hidden"));
        }
        function acceptRequst(numeroRequest) {
            window.Livewire.dispatch('AcceptRequest', [numeroRequest]);
        }


        function ShowCanceledRequest() {
            if (document.getElementById('ShowCanceled').checked) {
                window.Livewire.dispatch('ShowCanceled', [1])
            } else {
                window.Livewire.dispatch('ShowCanceled', [0])
            }
        }
    </script>
</div>
