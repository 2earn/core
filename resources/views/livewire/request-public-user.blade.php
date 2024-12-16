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
    <div class="col">
        <div class="card" id="funding_form">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Demande_alimentation_BFS') }}</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered  tableEditAdmin">
                                    <thead>
                                    <tr>
                                        <th>{{__('idUser')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Email')}}</th>
                                        <th>{{__('Mobile Number')}}</th>
                                        <th>{{__('idCountry')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($pub_users as $value)
                                        <tr>
                                            <td>{{$value->idUser}}</td>
                                            <td>{{$value->name}}</td>
                                            <td>{{$value->email}}</td>
                                            <td>{{$value->mobile}}</td>
                                            <td>{{$value->idCountry}}</td>
                                            <td><input type="checkbox" wire:model="selectedUsers"
                                                       value="{{$value->idUser}}"></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                            </table>
                            <div class="text-center mb-4 mt-3 float-end">
                                <button type="button" onclick="sendReq()" id="pay"
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
            function sendReq() {
                window.Livewire.emit('sendFinancialRequest');
            }
        </script>
</div>
