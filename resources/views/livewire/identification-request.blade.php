<div class="container-fluid">
    @section('title')
        {{__('Identification requests')}}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')
            {{__('Pages')}}
        @endslot
        @slot('title')
            {{__('Identification requests')}}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        @forelse($identificationRequests as $identificationRequest)
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <h5 class="card-header">
                        {{$identificationRequest->enName}}
                        <span class="float-end" title="{{$identificationRequest->status}}">
                                @if($identificationRequest->status==1)
                                <span class="badge bg-info">{{__('National')}}</span>
                            @elseif($identificationRequest->status==5)
                                <span class="badge bg-info">{{__('International')}}</span>
                            @elseif($identificationRequest->status==6)
                                <span class="badge bg-info">{{__('Global')}}</span>
                            @else
                                <span class="badge bg-warning">{{__('Old data')}}</span>
                            @endif
                   </span>
                    </h5>
                    <div class="card-body">
                        <div class="d-flex mb-4 align-items-center">
                            <div class="flex-shrink-0">
                                <img
                                    src="{{asset(\App\Models\User::getUserProfileImage($identificationRequest->idUser))}}"
                                    class="avatar-sm rounded-circle"/>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h5 class="card-title mb-1">{{$identificationRequest->fullphone_number}}</h5>
                            </div>
                        </div>
                        <h6 class="mb-1">{{$identificationRequest->nationalID}}</h6>
                        <p class="card-text text-muted float-end">{{$identificationRequest->DateCreation}}</p>
                    </div>
                    <div class="card-footer text-muted">
                        <a href="{{route('validate_account', ['locale' => app()->getLocale(), 'paramIdUser' => $identificationRequest->id]) }}"
                           class="btn btn-primary float-end">
                            {{__('See Details')}}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info material-shadow" role="alert">
                {{__('No identification request found')}}
            </div>
        @endforelse
    </div>
</div>
