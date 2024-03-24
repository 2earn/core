<div>

    @section('title')
        @lang('translation.team')
    @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('li_1')
                Pages
            @endslot
            @slot('title')
                Team
            @endslot
        @endcomponent

        <div class="row">
            <div class="col-12">
                <div class="justify-content-between d-flex align-items-center mt-3 mb-4">
                </div>
                <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-1">
                    @foreach($requestIdentif as $req)
                        <div class="col">
                            <div class="card card-body">
                                <div class="d-flex mb-4 align-items-center">
                                    <div class="flex-shrink-0">
                                        <img
                                            src="@if (file_exists('uploads/profiles/profile-image-' . $req->idUser . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$req->idUser.'.png') }}@else{{ URL::asset('uploads/profiles/default.png') }} @endif"
                                            alt=""
                                            class="avatar-sm rounded-circle"/>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="card-title mb-1">{{$req->fullphone_number}}</h5>
                                        <p class="text-muted mb-0">{{$req->enName}}</p>
                                    </div>
                                </div>
                                <h6 class="mb-1">{{$req->nationalID}}</h6>
                                <p class="card-text text-muted">{{$req->DateCreation}}</p>
                                <a href=" {{route('validateaccount', ['locale' => app()->getLocale(), 'paramIdUser' => $req->id]) }}"
                                   class="btn btn-primary btn-sm">See Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @endsection


</div>
