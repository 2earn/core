<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Accept financial request') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Accept financial request') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card">
            <div class="card-header">
                <h5 class="card-title">{{__('Credit Transfer')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group mt-2">
                    <li class="list-group-item">
                        <strong>{{__('Solde')}}:</strong>
                        <span class="float-end">{{$soldeUser}} {{config('app.currency')}}</span>
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
                        <span
                            class="float-end    @if($soldeUser<$financialRequest->amount) text-danger @else text-success @endif">  {{$financialRequest->amount}} {{config('app.currency')}}</span>
                        @if($soldeUser<$financialRequest->amount)
                            <hr>
                            <div class="alert alert-warning m-2 float-end" role="alert">
                                {{ __('Insufficient sold') }}
                            </div>
                        @endif
                    </li>
                </ul>

            </div>
            <div class="card-footer text-muted">
                <button onclick="ConfirmTransacction()"
                        @if($soldeUser < $financialRequest->amount) disabled @endif
                        class=" btn btn-primary mx-2 float-end ">
                    {{__('Confirm transfer of')}} {{$financialRequest->amount}} {{config('app.currency')}}
                </button>


                <a class="btn btn-danger float-end"
                   href="{{route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 5])}}"
                   class="btn-danger">{{__('Cancel')}}</a>
            </div>
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
                    cancelButtonText: '{{trans('cancel')}}',
                    confirmButtonText: '{{trans('ok')}}',
                    denyButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch('ConfirmRequest', [2, {{$financialRequest->numeroReq}}, result.value]);
                    }
                })
            }
        }
    </script>
</div>
