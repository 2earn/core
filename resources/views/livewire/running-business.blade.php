<div>
    @section('title')
        {{ __('Running business') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Running business') }}
        @endslot
    @endcomponent


    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-4">
                    <img src="{{ Vite::asset('resources/images/icon-learn.png') }}" alt="logo2earn"
                         class="img-thumbnail d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-auto col-md-6 col-lg-7 mt-2 m-auto">
                    <div class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business">
                        <input type="checkbox" class="form-check-input" id="be_commited_investor" checked="">
                        <label class="form-check-label  "
                               for="be_commited_investor">{{__('Be commited Investor')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-4">
                    <img src="{{ Vite::asset('resources/images/icon-learn.png') }}" alt="logo2earn"
                         class="img-thumbnail d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-auto col-md-6 col-lg-7 mt-2 m-auto">
                    <div class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business">
                        <input type="checkbox" class="form-check-input" id="be_instructor" checked="">
                        <label class="form-check-label  "
                               for="be_instructor">{{__('Be Instructor')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-4">
                    <img src="{{ Vite::asset('resources/images/logo2earn.png') }}" alt="logo2earn"
                         class="img-thumbnail d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-auto col-md-6 col-lg-7 mt-2 m-auto">
                    <div class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business">
                        <input type="checkbox" class="form-check-input" disabled id="be_private_hire_vehicle">
                        <label class="form-check-label  "
                               for="be_private_hire_vehicle">{{__('Be Private Hire Vehicle')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-4">
                    <img src="{{ Vite::asset('resources/images/icon-shop.png') }}" alt="icon-shop"
                         class="img-thumbnail d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-auto col-md-6 col-lg-7 mt-2 m-auto">
                    <div class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business">
                        <input type="checkbox" class="form-check-input" disabled id="be_seller" >
                        <label class="form-check-label  "
                               for="be_seller">{{__('Be Seller')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
