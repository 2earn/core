<div class="tab-pane   @if($filter=="5" ) active show @endif" id="others_me" role="tabpanel">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ __('Incoming request') }}</h4>
        </div>
        <div class="card-body">
            @if(count($requestToMee) > 0)
                <div class="row g-3">
                    @foreach ($requestToMee as $value)
                        <div class="col-12">
                            <div class="border rounded p-3 {{ $value->status == 0 ? 'border-success' : 'border-danger' }}" style="background-color: #f8f9fa;">
                                <div class="row align-items-center">
                                    <!-- Request Number -->
                                    <div class="col-md-2 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">{{ __('Request') }}</div>
                                        <div class="fw-semibold">{{$value->numeroReq}}</div>
                                    </div>

                                    <!-- Date -->
                                    <div class="col-md-2 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">{{ __('date') }}</div>
                                        <div>{{$value->date}}</div>
                                    </div>

                                    <!-- User Phone -->
                                    <div class="col-md-2 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">{{ __('User phone') }}</div>
                                        <div>{{$value->mobile}}</div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="col-md-2 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">{{ __('Amount') }}</div>
                                        <div class="fw-bold text-primary">{{ formatSolde($value->amount,2) }}</div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-2 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">{{ __('Status') }}</div>
                                        <div>
                                            @if($value->status == 0)
                                                <span class="badge bg-success">{{__('Opened')}}</span>
                                            @else
                                                <span class="badge bg-danger">{{__('Closed')}}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-2 col-12 text-md-end">
                                        <div class="text-muted small d-none d-md-block">{{ __('Actions') }}</div>
                                        <div class="d-flex justify-content-md-end justify-content-start gap-2 mt-1">
                                            @if($value->status == 0)
                                                <button onclick="acceptRequest('{{$value->numeroReq}}')"
                                                        class="btn btn-success btn-sm"
                                                        title="{{__('Accept request')}}">
                                                    <i class="fa-regular fa-circle-check"></i>
                                                    <span class="d-none d-sm-inline ms-1">{{__('Accept')}}</span>
                                                </button>
                                                <button onclick="rejectRequest('{{$value->numeroReq}}')"
                                                        class="btn btn-danger btn-sm"
                                                        title="{{__('reject_request')}}">
                                                    <i class="fa-regular fa-circle-xmark"></i>
                                                    <span class="d-none d-sm-inline ms-1">{{__('Reject')}}</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa-regular fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('No incoming requests found') }}</p>
                </div>
            @endif
        </div>
    </div>
    <script>
        function rejectRequest(numeroRequest) {
            Swal.fire({
                title: `{{trans('reject_request')}}`,
                confirmButtonText: '{{trans('Yes')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('No')}}',
                customClass: {actions: 'my-actions', confirmButton: 'order-2', denyButton: 'order-3',}
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch('RejectRequest', [numeroRequest]);
                }
            })
        }

        function acceptRequest(numeroRequest) {
            Swal.fire({
                title: `{{trans('Accept request')}}`,
                confirmButtonText: '{{trans('Yes')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('No')}}',
                customClass: {actions: 'my-actions', confirmButton: 'order-2', denyButton: 'order-3',}
            }).then((result) => {
                console.log(result)
                if (result.isConfirmed) {
                    window.Livewire.dispatch('AcceptRequest', [numeroRequest]);
                }
            })

        }
    </script>
</div>
