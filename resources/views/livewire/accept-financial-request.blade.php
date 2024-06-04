<div>

    <script>
        var ErrorSecurityCodeRequest= '{{Session::has('ErrorSecurityCodeRequest')}}';
        if (ErrorSecurityCodeRequest) {
            Swal.fire({
                icon: 'error',

                title:'{{trans('désolé')}}',
                text: '{{Session::get('ErrorSecurityCodeRequest')}}',
                confirmButtonText: '{{trans('Yes')}}',
                // footer: '<a href="">Why do I have this issue?</a>'
            })
        }

    </script>
    <div class="col-3 card">
        <div class="card-header">
            {{__('fiche_crédit')}}
        </div>
        <div class="card-body">
            <h5 class="card-title">{{__('Transfert_crédit')}}</h5>
            <p><strong>{{__('Solde')}}:</strong> {{$soldeUser->soldeBFS}} $</p>
            <div class="col-12">
                <div><strong><span>{{__('Opération')}}:</span> </strong><span>{{__('BFS to BFS')}}</span></div>
                <div><strong><span>{{__('vers')}}:</span> </strong><span>  {{$financialRequest->name}}  </span></div>
                <div><strong><span>{{__('Mobile Number')}}:</span></strong> <span>  {{$financialRequest->mobile}}  </span></div>
            </div>
            <p class="card-text"><strong>{{__('Montant_envoyer')}}</strong> {{$financialRequest->amount}} $</p>
            <button  onclick="ConfirmTransacction()" class=" btn btn-primary btn2earn  ">{{__('Confirmer')}}</button>
            <a style="padding: 5px;border-radius: 5px;text-decoration: none!important;color: #f02602!important;"
               href="{{route('financial_transaction',app()->getLocale())}}" class="btn-danger">{{__('Cancel')}}</a>
        </div>
        <div class="card-footer text-muted">

        </div>
    </div>
    <script>
        function ConfirmTransacction() {
            if ($('#RequstCompte').val() != 0) {
                Swal.fire({
                    title: '{{__('Submit your security code')}}',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    cancelButtonText: '{{trans('canceled !')}}',
                    confirmButtonText: '{{trans('ok')}}',
                    denyButtonText: 'No',
                    // preConfirm: (login) => {
                    //     return fetch(`//api.github.com/users/${login}`)
                    //         .then(response => {
                    //             if (!response.ok) {
                    //                 throw new Error(response.statusText)
                    //             }
                    //             return response.json()
                    //         })
                    //         .catch(error => {
                    //             Swal.showValidationMessage(
                    //                 `Request failed: ${error}`
                    //             )
                    //         })
                    // },
                    // allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('Confirmrequest', 2, {{$financialRequest->numeroReq}}, result.value);
                    }
                })
                {{--window.livewire.emit('Confirmrequest',2,{{$financialRequest->numeroReq}})--}}
            }
        }
    </script>
</div>
