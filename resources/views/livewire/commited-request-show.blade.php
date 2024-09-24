<div>
    @section('title')
        {{ __('Committed investor request examination') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Committed investor request examination') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-header">
            {{getUserDisplayedName($commitedInvestorsRequest->user->idUser)}}
        </div>
        <div class="card-body">
            <div class="d-flex mb-4 align-items-center">
                <div class="flex-shrink-0">
                    <img
                        src="@if (file_exists('uploads/profiles/profile-image-' . $commitedInvestorsRequest->user->idUser . '.png')) {{ URL::asset('uploads/profiles/profile-image-'. $commitedInvestorsRequest->user->idUser.'.png') }}@else{{ URL::asset('uploads/profiles/default.png') }} @endif"
                        class="avatar-sm rounded-circle"/>
                </div>
                <div class="flex-grow-1 ms-2">
                    <h5 class="card-title mb-1">{{$commitedInvestorsRequest->fullphone_number}}</h5>
                </div>
            </div>
            <p class="card-text text-muted float-end">{{$commitedInvestorsRequest->request_date}}</p>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-soft-success float-end mx-2"
                    wire:click="validateRequest()">{{__('Validate')}}</button>
            <button type="button" class="btn btn-soft-danger float-end mx-2"
                    wire:click="rejectRequest()">{{__('Reject')}}</button>
        </div>
    </div>
</div>
