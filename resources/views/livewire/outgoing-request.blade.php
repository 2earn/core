<div class="tab-pane @if($filter=="4" ) active show @endif" id="me_others" role="tabpanel">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ __('Outgoming request') }}</h4>
        </div>
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
            @forelse($requestFromMee as $value)
                <div class="border rounded mb-3 overflow-hidden">
                    <!-- Main Request Card -->
                    <div class="p-3 cursor-pointer"
                         style="background-color: #f8f9fa; cursor: pointer;"
                         onclick="hiddenTr('{{$value->numeroReq}}')">
                        <div class="row align-items-center">
                            <!-- Expand Icon -->
                            <div class="col-md-1 col-2 text-center">
                                <i class="fas fa-plus-circle text-success" style="font-size: 1.2rem;" id="icon-{{$value->numeroReq}}"></i>
                            </div>

                            <!-- Request Number -->
                            <div class="col-md-2 col-10 col-lg-2 mb-2 mb-md-0">
                                <div class="text-muted small">{{__('numeroReq')}}</div>
                                <div class="fw-semibold">{{$value->numeroReq}}</div>
                            </div>

                            <!-- Date -->
                            <div class="col-md-2 col-6 mb-2 mb-md-0">
                                <div class="text-muted small">{{__('date')}}</div>
                                <div>{{$value->date}}</div>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-2 col-6 mb-2 mb-md-0">
                                <div class="text-muted small">{{__('Amount')}}</div>
                                <div class="fw-bold text-primary">{{formatSolde($value->amount,2)}}</div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-5 col-12">
                                <div class="text-muted small">{{__('Status')}}</div>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @if($value->FStatus == 0)
                                        <span class="badge bg-warning text-dark">{{__('Opened')}}</span>
                                        <button onclick="event.stopPropagation(); cancelRequestF('{{$value->numeroReq}}')"
                                                class="btn btn-info btn-sm">
                                            <i class="fas fa-ban me-1"></i>{{__('Cancel')}}
                                        </button>
                                    @elseif($value->FStatus == 1)
                                        <span class="badge bg-success">{{__('Accepted')}}</span>
                                    @elseif($value->FStatus == 3)
                                        <span class="badge bg-danger">{{__('Canceled')}}</span>
                                    @else
                                        <span class="badge bg-danger">{{__('Rejected')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details Section (Hidden by default) -->
                    <div id="{{$value->numeroReq}}" class="border-top" style="display: none;">
                        <div class="p-3 bg-light">
                            <h6 class="mb-3">{{__('Request Details')}}</h6>

                            @if(count($value->details) > 0)
                                <div class="row g-3">
                                    @foreach ($value->details as $valueD)
                                        @if($valueD->user != null)
                                            <div class="col-12">
                                                <div class="card border">
                                                    <div class="card-body p-3">
                                                        <div class="row align-items-center">
                                                            <!-- User Name -->
                                                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                                                <div class="text-muted small">{{__('user')}}</div>
                                                                <div class="fw-semibold">{{$valueD->User->name}}</div>
                                                            </div>

                                                            <!-- Mobile Number -->
                                                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                                                <div class="text-muted small">{{__('Mobile Number')}}</div>
                                                                <div>{{$valueD->User->mobile}}</div>
                                                            </div>

                                                            <!-- Response -->
                                                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                                                <div class="text-muted small">{{__('response')}}</div>
                                                                <div>
                                                                    @if($valueD->response == "" || $valueD->response == null)
                                                                        <span class="badge bg-warning text-dark">{{__('No Response')}}</span>
                                                                    @elseif($valueD->response == 1)
                                                                        <span class="badge bg-success">{{__('Accepted')}}</span>
                                                                    @elseif($valueD->response == "2")
                                                                        <span class="badge bg-danger">{{__('Rejected')}}</span>
                                                                    @elseif($valueD->response == "3")
                                                                        <span class="badge bg-danger">{{__('Canceled')}}</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Date Response -->
                                                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                                                <div class="text-muted small">{{__('dateResponse')}}</div>
                                                                <div>{{$valueD->dateResponse ?? '-'}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle me-2"></i>{{__('No details available')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fa-regular fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">{{__('No Outgoing requests')}}</p>
                </div>
            @endforelse
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
                title: `{{trans('Do you want to cancel this request')}}`,
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
            const element = $("#" + num);
            const icon = $("#icon-" + num);

            if (element.is(':visible')) {
                element.slideUp(300);
                icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
            } else {
                element.slideDown(300);
                icon.removeClass('fa-plus-circle').addClass('fa-minus-circle');
            }
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
