<div>
    @if($pendingAssignments->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">{{__('ID')}}</th>
                        <th>{{__('User')}}</th>
                        <th>{{__('Platform')}}</th>
                        <th>{{__('Role')}}</th>
                        <th class="text-center">{{__('Date')}}</th>
                        <th class="text-end">{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingAssignments as $assignment)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-soft-secondary text-secondary">#{{$assignment->id}}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar-xs">
                                            <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                {{strtoupper(substr($assignment->user->name ?? 'U', 0, 1))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fs-6">{{$assignment->user->name ?? 'N/A'}}</h6>
                                        <small class="text-muted">{{$assignment->user->email ?? ''}}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">{{$assignment->platform->name ?? 'N/A'}}</span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info">
                                    {{ucfirst(str_replace('_', ' ', $assignment->role))}}
                                </span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">{{$assignment->created_at->format('M d, Y')}}</small>
                            </td>
                            <td class="text-end">
                                <a href="{{route('platform_role_assignments', ['locale' => app()->getLocale()])}}"
                                   class="btn btn-sm btn-soft-info">
                                    <i class="ri-eye-line me-1"></i>{{__('Review')}}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(\App\Models\AssignPlatformRole::where('status', \App\Models\AssignPlatformRole::STATUS_PENDING)->count() > 5)
            <div class="text-center mt-3 pt-3 border-top">
                <a href="{{route('platform_role_assignments', ['locale' => app()->getLocale()])}}"
                   class="btn btn-sm btn-info">
                    <i class="ri-list-check me-1"></i>
                    {{__('View All')}} ({{\App\Models\AssignPlatformRole::where('status', \App\Models\AssignPlatformRole::STATUS_PENDING)->count()}} {{__('total')}})
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-4">
            <div class="avatar-md mx-auto mb-3">
                <div class="avatar-title rounded-circle bg-soft-success text-success">
                    <i class="ri-check-line fs-3"></i>
                </div>
            </div>
            <h6 class="text-muted mb-0">{{__('No pending role assignments')}}</h6>
            <p class="text-muted small mb-0">{{__('All role assignments have been processed')}}</p>
        </div>
    @endif
</div>

