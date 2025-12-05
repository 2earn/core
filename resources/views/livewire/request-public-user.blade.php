<div class="{{getContainerType()}}">
    @section('title')
        {{ __('BFS funding request') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('BFS funding request') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12 card" id="funding_form">
            <div class="card-header">
                <h4 class="card-title">{{ __('Demande_alimentation_BFS') }}</h4>
            </div>
            <div class="card-body">
                <!-- Header Row -->
                <div class="row fw-bold border-bottom pb-2 mb-3 d-none d-md-flex">
                    <div class="col-md-1">{{__('idUser')}}</div>
                    <div class="col-md-2">{{__('Name')}}</div>
                    <div class="col-md-3">{{__('Email')}}</div>
                    <div class="col-md-2">{{__('Mobile Number')}}</div>
                    <div class="col-md-2">{{__('idCountry')}}</div>
                    <div class="col-md-2 text-center">{{__('Select')}}</div>
                </div>

                <!-- Data Rows -->
                @foreach ($pub_users as $value)
                    <div class="row border-bottom py-3 align-items-center">
                        <div class="col-md-1 col-6">
                            <span class="d-md-none fw-bold">{{__('idUser')}}: </span>
                            <span>{{$value->idUser}}</span>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="d-md-none fw-bold">{{__('Name')}}: </span>
                            <span>{{getUserDisplayedName($value->idUser)}}</span>
                        </div>
                        <div class="col-md-3 col-12">
                            <span class="d-md-none fw-bold">{{__('Email')}}: </span>
                            <span>{{$value->email}}</span>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="d-md-none fw-bold">{{__('Mobile Number')}}: </span>
                            <span>{{$value->mobile}}</span>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="d-md-none fw-bold">{{__('idCountry')}}: </span>
                            <span>{{$value->idCountry}}</span>
                        </div>
                        <div class="col-md-2 col-12 text-center mt-2 mt-md-0">
                            <input type="checkbox" wire:model.live="selectedUsers"
                                   value="{{$value->idUser}}" class="form-check-input">
                        </div>
                    </div>
                @endforeach

                <div class="text-center mb-4 mt-3 float-end">
                    <button type="button" onclick="sendBFSFoundingRequest()" id="pay"
                            class="btn btn-success pl-5 pr-5">{{ __('backand.Fund')}}</button>
                </div>
                <div class="label">
                    <div class="col-sm text-danger"><strong>{{__('Note')}}
                            :</strong>{{ __('You must check or less a user') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function sendBFSFoundingRequest() {
        window.Livewire.dispatch('sendFinancialRequest');
    }
</script>
