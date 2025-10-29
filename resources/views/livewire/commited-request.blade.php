<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Commited investors requests') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Commited investors requests') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        @forelse($commitedInvestorsRequests as $commitedRequest)
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <h5 class="card-header">
                        {{getUserDisplayedName($commitedRequest->user->idUser)}}
                    </h5>
                    <div class="card-body">
                        <div class="d-flex mb-4 align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{\App\Models\User::getUserProfileImage($commitedRequest->user->idUser)}}"
                                     class="avatar-sm rounded-circle"/>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h5 class="card-title mb-1">{{$commitedRequest->fullphone_number}}</h5>
                            </div>
                        </div>
                        <p class="card-text text-muted float-end">{{$commitedRequest->request_date}}</p>
                    </div>
                    <div class="card-footer text-muted">
                        <a href="{{route('requests_commited_investors_show', ['locale' => app()->getLocale(), 'id' => $commitedRequest->id]) }}"
                           class="btn btn-soft-primary float-end">
                            {{__('See Details')}}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info material-shadow" role="alert">
                        {{__('No Commited investors requests found')}}
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
