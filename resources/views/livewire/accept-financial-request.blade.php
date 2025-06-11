<div>
    <div class="col-12 card">
        <div class="card-header">
            <h5 class="card-title">{{__('Credit Transfert')}}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group mt-2">
                <li class="list-group-item">
                    <strong>{{__('Solde')}}:</strong>
                    <span class="float-end">{{$soldeUser->soldeBFS}} $</span>
                </li>
                <li class="list-group-item">
                    <strong>{{__('Opération')}}: </strong>
                    <span class="float-end">{{__('BFS to BFS')}}</span>
                </li>
                <li class="list-group-item">
                    <strong>{{__('vers')}}: </strong>
                    <span class="float-end">  {{$financialRequest->name}}</span>
                </li>
                <li class="list-group-item">
                    <strong>{{__('Mobile Number')}}:</strong>
                    <span class="float-end">   {{$financialRequest->mobile}}  </span>
                </li>
                <li class="list-group-item">
                    <strong>{{__('Montant_envoyer')}}</strong>
                    <span class="float-end">  {{$financialRequest->amount}} $</span>
                </li>
            </ul>
        </div>
        <div class="card-footer text-muted">
            <button onclick="ConfirmTransacction()"
                    class=" btn btn-primary mx-2 float-end ">{{__('Confirm transfer')}}</button>
            <a class="btn btn-danger float-end"
               href="{{route('financial_transaction',app()->getLocale())}}" class="btn-danger">{{__('Cancel')}}</a>
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch('Confirmrequest',[ 2, {{$financialRequest->numeroReq}}, result.value]);
                    }
                })
            }
        }
    </script>
</div>
