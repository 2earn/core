@if($user_name)
@endif
    <span class="badge bg-secondary">{{ __('System') }}</span>
@else
    </div>
        </div>
            <p class="text-muted mb-0">ID: {{ $created_by ?? 'N/A' }}</p>
            <h5 class="fs-13 mb-0">{{ $user_name }}</h5>
        <div class="flex-grow-1">
    <div class="d-flex align-items-center">

