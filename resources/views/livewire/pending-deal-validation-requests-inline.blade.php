<div>
    @if($totalPending > 0)
        <div class="row g-2">
            @foreach($pendingRequests as $request)
                <div class="col-12">
                    <div class="card border-0 shadow-sm hover-shadow">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-1 col-2">
                                    <span class="badge bg-primary-subtle text-primary">#{{$request->deal_id}}</span>
                                </div>
                                <div class="col-md-4 col-10">
                                    <h6 class="mb-0">{{$request->deal->name ?? 'N/A'}}</h6>
                                </div>
                                <div class="col-md-3 col-6">
                                    @if($request->deal && $request->deal->platform)
                                        <small class="text-muted">
                                            <i class="fas fa-desktop me-1"></i>
                                            {{$request->deal->platform->name}}
                                        </small>
                                    @else
                                        <small class="text-muted">N/A</small>
                                    @endif
                                </div>
                                <div class="col-md-3 col-6">
                                    @if($request->requestedBy)
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{getUserDisplayedName($request->requestedBy->idUser)}}
                                        </small>
                                    @else
                                        <small class="text-muted">N/A</small>
                                    @endif
                                </div>
                                <div class="col-md-1 col-12 text-md-end mt-2 mt-md-0">
                                    <small class="text-muted">
                                        {{$request->created_at->format('Y-m-d H:i:s')}}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($totalPending > $limit)
            <div class="text-center mt-3 pt-2 border-top">
                <small class="text-muted">
                    {{__('Showing')}} {{$pendingRequests->count()}} {{__('of')}} {{$totalPending}} {{__('pending requests')}}
                </small>
            </div>
        @endif
    @else
        <div class="alert alert-success mb-0">
            <i class="fas fa-check-circle me-2"></i>
            {{__('No pending validation requests at the moment')}}
        </div>
    @endif
</div>

