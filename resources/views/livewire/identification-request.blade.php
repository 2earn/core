<div>
    @section('title')
        @lang('translation.team')
    @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('li_1')
                {{__('Pages')}}
            @endslot
            @slot('title')
                {{__('Team')}}
            @endslot
        @endcomponent
        <div class="container-fluid">
            <div class="row">
                @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if(Session::has('danger'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('danger') }}
                    </div>
                @endif
            </div>
            <div class="row">
                @forelse($identificationRequests as $identificationRequest)
                    <div class="col-sm-12 col-md-4">
                        <div class="card">
                            <h5 class="card-header">
                                {{$identificationRequest->enName}}
                            </h5>
                            <div class="card-body">
                                <div class="d-flex mb-4 align-items-center">
                                    <div class="flex-shrink-0">
                                        <img
                                            src="@if (file_exists('uploads/profiles/profile-image-' . $identificationRequest->idUser . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$identificationRequest->idUser.'.png') }}@else{{ URL::asset('uploads/profiles/default.png') }} @endif"
                                            class="avatar-sm rounded-circle"/>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="card-title mb-1">{{$identificationRequest->fullphone_number}}</h5>
                                    </div>
                                </div>
                                <h6 class="mb-1">{{$identificationRequest->nationalID}}</h6>
                                <p class="card-text text-muted">{{$identificationRequest->DateCreation}}</p>
                                <a href="{{route('validate_account', ['locale' => app()->getLocale(), 'paramIdUser' => $identificationRequest->id]) }}"
                                   class="btn btn-primary btn-sm">
                                    {{__('See Details')}}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info" role="alert">
                        {{__('No identification request found')}}
                    </div>
                @endforelse
            </div>
        </div>
    @endsection
</div>
