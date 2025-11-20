<div>
    @if($totalPending > 0)
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%;">{{__('ID')}}</th>
                        <th style="width: 30%;">{{__('Deal Name')}}</th>
                        <th style="width: 25%;">{{__('Platform')}}</th>
                        <th style="width: 20%;">{{__('Requested By')}}</th>
                        <th style="width: 15%;">{{__('Date')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $request)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">#{{$request->deal_id}}</span>
                            </td>
                            <td>
                                <strong>{{$request->deal->name ?? 'N/A'}}</strong>
                            </td>
                            <td>
                                @if($request->deal && $request->deal->platform)
                                    <i class="fas fa-desktop me-1 text-muted"></i>
                                    {{$request->deal->platform->name}}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($request->requestedBy)
                                    <i class="fas fa-user me-1 text-muted"></i>
                                    {{$request->requestedBy->name}}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{$request->created_at->format('M d, Y')}}
                                </small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

