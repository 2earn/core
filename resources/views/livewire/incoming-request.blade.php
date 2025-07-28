<div class="tab-pane" id="others_me" role="tabpanel">
    <div class="card-body table-responsive ">
        <table class="table align-middle dt-responsive nowrap" id="customerTable2"
               style="width: 100%">
            <thead class="table-light">
            <tr class="tabHeader2earn">
                <th>{{ __('Request') }}</th>
                <th>{{ __('date') }}</th>
                <th>{{ __('user') }}</th>
                <th>{{ __('UserPhone') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody class="list form-check-all">
            @foreach ($requestToMee as $value)
                <tr>
                    <td><span>{{$value->numeroReq}}</span></td>
                    <td><span> {{$value->date}}</span></td>
                    <td><span> {{$value->name}}</span></td>
                    <td><span>{{$value->mobile}}</span></td>
                    <td><span>{{number_format((float)$value->amount, 2, '.', ' ')}} </span></td>
                    <td>
                        <span>
                            @if($value->status == 0)
                                <a style="background-color: #51A351;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('Opened')}}</a>
                            @else
                                <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px">{{__('Closed')}}</a>
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($value->status == 0)
                            <i onclick="acceptRequst('{{$value->numeroReq}}')"
                               style="cursor:pointer; color: #51A351;font-size: 20px;margin: 5px 5px"
                               class="fa-regular fa-circle-check"></i>
                            <i onclick="rejectRequst('{{$value->numeroReq}}')"
                               style="cursor:pointer; color: #BD362F;font-size: 20px;margin: 5px 5px"
                               class="fa-regular fa-circle-xmark"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script>
        function rejectRequst(numeroRequest) {
            Swal.fire({
                title: `{{trans('reject_request')}}`,
                confirmButtonText: '{{trans('Yes')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('No')}}',
                customClass: {actions: 'my-actions', confirmButton: 'order-2', denyButton: 'order-3',}
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch('RejectRequest', [numeroRequest]);
                }
            })
        }
    </script>
</div>
