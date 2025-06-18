<div class="container-fluid">
    @section('title')
        {{ __('User purchase history') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('User purchase history') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            <div class="card-body row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="row m-1 card border border-muted">
                        <div class="card-body border-info">
                            <label>{{__('Platforms')}}</label>
                            <div class="row">
                                @foreach($allPlatforms as $platform)
                                    <div class="col-auto">
                                        <div class="form-check form-switch form-check-inline" dir="ltr">
                                            <label
                                                for="platform.{{__($platform->name)}}">{{__($platform->name)}}</label>
                                            <input type="checkbox" class="form-check-input"
                                                   wire:model="selectedPlatforms"
                                                   value="{{$platform->id}}"
                                                   id="platform.{{__($platform->name)}}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="row m-1 card border border-muted">
                        <div class="card-body border-info">
                            <label>{{__('Status')}}</label>
                            <select class="form-select form-select-sm  mb-3" multiple wire:model="selectedStatuses">
                                @foreach($allStatuses as $status)
                                    <option value="{{$status->value}}">{{__($status->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary refreshOrders float-end">{{__('Search Orders')}}</button>
        </div>
    </div>


    @if($choosenOrders->count())
        <div class="row">
            <div class="col-lg-12 card">
                <div class="card-body table-responsive">
                    <table id="userPurchaseHistoryTable"
                           class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                        <thead class="table-light">
                        <tr class="head2earn tabHeader2earn">
                            <th>{{__('Details')}}</th>
                            <th>{{__('id')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('user')}}</th>
                            <th>{{__('Note')}}</th>
                            <th>{{__('Total order quantity')}}</th>
                            <th>{{__('Paid cash')}}</th>
                            <th>{{__('Actions')}}</th>

                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        @foreach($choosenOrders as $order)
                            <tr>
                                <td>
                                    <i class="fa-solid fa-circle-question text-info fa-lg dtmdbtn"></i>
                                </td>
                                <td>{{$order->id}}</td>
                                <td>
                                    <span class="badge bg-info text-end fs-14"> {{__($order->status->name)}}</span>
                                </td>
                                <td>{{getUserDisplayedName(\App\Models\User::getIdUserById($order->user_id))}}</td>
                                <td>{{$order->note}}</td>
                                <td>{{$order->total_order_quantity}}</td>
                                <td>{{$order->paid_cash}}</td>
                                <td>
                                    <a href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$order->id])}}"
                                       class=float-end">{{__('More details')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    <script type="module">

        function updateDatatable() {
            if (!$.fn.dataTable.isDataTable('#userPurchaseHistoryTable')) {
                $('#userPurchaseHistoryTable').DataTable({
                    "responsive": true,
                    "language": {"url": urlLang},
                });
            }
        }

        window.addEventListener('updateOrdersDatatable', event => {
            updateDatatable();
        });

        document.addEventListener("DOMContentLoaded", function () {
            updateDatatable();
            $('body').on('click', '.refreshOrders', function (event) {
                window.Livewire.dispatch("refreshOrders", [$(event.target).attr('data-id')]);
                $('#userPurchaseHistoryTable').DataTable().reload();
            });
        });
    </script>
</div>
