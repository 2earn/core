<div>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-md-8">
                <div class="row">
                    <div class="card" id="funding_form" style="margin-left:1rem;">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                        </div>
                        <div class="card-body">
                            <table class=" table table-responsive tableEditAdmin">
                                <div>
                                    <div style="color: black" class="col-sm"><label
                                            class="me-sm-2">{{ __('Demande_alimentation_BFS') }}</label>
                                    </div>
                                    <thead>
                                    <tr>
                                        <th scope="Id">{{__('idUser')}}</th>
                                        <th scope="Name">{{__('Name')}}</th>
                                        <th scope="Francais">{{__('Email')}}</th>
                                        <th scope="Arabe">{{__('Mobile Number')}}</th>
                                        <th scope="Francais">{{__('idCountry')}}</th>
                                        <th scope=" "></th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    {{--                            @foreach ($translate as $s)--}}
                                    {{--                                <tr>--}}
                                    {{--                                    <td><span> {{$s->id}}</span></td>--}}
                                    {{--                                    <td><span>{{$s->name}}</span></td>--}}
                                    {{--                                    <td><input wire:model.defer="translate.{{ $key }}.value"/></td>--}}
                                    {{--                                    <td><input wire:model.defer="translate.{{ $key }}.valueFr"/></td>--}}
                                    {{--                                </tr>--}}
                                    {{--                            @endforeach--}}
                                    @foreach ($pub_users as $value)
                                        <tr>
                                            <td><span> {{$value->idUser}}</span></td>
                                            <td><span>{{$value->name}}</span></td>
                                            <td><span>{{$value->email}}</span></td>
                                            <td><span> {{$value->mobile}}</span></td>
                                            <td><span>{{$value->idCountry}}</span></td>
                                            <td>
                                                <input type="checkbox" wire:model="selectedUsers"
                                                       value="{{$value->idUser}}">

                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </div>
                            </table>

                            <div class="text-center mb-4" style="margin-top: 20px;">

                                <button type="button" onclick="sendReq()" id="pay"
                                        class="btn btn-success pl-5 pr-5">{{ __('backand.Fund')}}</button>
                            </div>
                            <div class="label">
                                <div style="color: red" class="col-sm"><label
                                        class="me-sm-2">{{ __('vous devez cocher ou mois un utilisateur') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function sendReq() {
                window.livewire.emit('sendReques');
            }
        </script>
    </div>
</div>
