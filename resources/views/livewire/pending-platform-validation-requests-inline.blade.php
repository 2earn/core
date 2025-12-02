<div>
    @if($totalPending > 0)
        <div class="row g-2">
            @foreach($pendingRequests as $request)
                <div class="col-12">
                    <div class="card border-0 shadow-sm hover-shadow">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-1 col-2">
                                    <span class="badge bg-primary-subtle text-primary">#{{$request->platform_id}}</span>
                                </div>
                                <div class="col-md-5 col-10">
                                    <h6 class="mb-0">{{$request->platform->name ?? 'N/A'}}</h6>
                                </div>
                                <div class="col-md-3 col-6">
                                    <small class="text-muted">
                                        <i class="ri-shield-check-line me-1"></i>
                                        {{__('Validation Required')}}
                                    </small>
                                </div>
                                <div class="col-md-3 col-6 text-md-end mt-2 mt-md-0">
                                    <small class="text-muted">
                                        {{$request->created_at->format(config('app.date_format'))}}
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
            <i class="ri-checkbox-circle-line me-2"></i>
            {{__('No pending validation requests at the moment')}}
        </div>
    @endif
</div>

