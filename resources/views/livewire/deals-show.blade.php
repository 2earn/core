<div>
    @section('title')
        {{ __('Deals') }} > {{$deal->name}}
    @endsection
    @if(!in_array($currentRouteName,["deals_archive"]))
        @component('components.breadcrumb')
                @slot('title')
                    <a class="link-light"
                       href="{{route('deals_index',['locale'=>app()->getLocale()])}}">{{ __('Deals') }}</a>
                    <i class="ri-arrow-right-s-line"></i>
                {{$deal->name}}
            @endslot
        @endcomponent
    @endif
    <div class="card">
        <div class="card-header">
            <h4 class="text-info">{{__('Details')}}
                <span
                    class="badge btn btn-info float-end">{{__(\Core\Enum\DealStatus::tryFrom($deal->status)?->name)}}</span>

                <p class="float-end mx-1"> <span class="badge bg-success text-end fs-14" title="{{__('Current turnover')}}">
                                {{$deal->current_turnover}} {{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                    /
                    <span class="badge bg-danger text-end fs-14" title="{{__('Target turnover')}}">
                                {{$deal->target_turnover}} {{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                </p>

            </h4>
        </div>
        <div class="card-body row">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted">
                        <div class="flex-shrink-0 ms-2">
                            <strong class="fs-14 font-weight-bold mb-0">{{__('Current turnover')}}</strong>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                            <span class="badge badge-success text-muted">
                                {{$deal->current_turnover}} {{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                </div>
            </div>
        </div>
        <div class="card-body row">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted">
                        <div class="flex-shrink-0 ms-2">
                            <strong class="fs-14 mb-0">{{__('Description')}}</strong>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                            <span class="text-muted">
                                {{$deal->description}}
                            </span>
                </div>
            </div>
        </div>
        <div class="card-body row">
            <ul class="list-group col-sm-12 col-md-6 col-lg-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Start date')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->start_date}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('End date')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->end_date}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Discount')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->discount}} {{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="list-group col-sm-12 col-md-6 col-lg-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Initial commission')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->initial_commission}} {{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Final commission')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->final_commission}}{{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="list-group col-sm-12 col-md-6 col-lg-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Min percentage cashback')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->min_percentage_cashback}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Max percentage cashback')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->max_percentage_cashback}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Total commission value')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->total_commission_value}} {{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Total unused cashback value')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->total_unused_cashback_value}} {{\App\Http\Livewire\DealsShow::CURRENCY}}
                            </span>
                        </div>
                    </div>
                </li>

            </ul>
            <ul class="list-group col-sm-12 col-md-6 col-lg-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('2 Earn profit')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->earn_profit}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Jackpot')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->jackpot}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Tree remuneration')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->tree_remuneration}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Proactive cashback')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->proactive_cashback}}
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-footer">
      <span class="text-muted float-end">
          <strong class="fs-14 mb-0">{{__('Created by')}} :</strong> {{getUserDisplayedName($deal->createdBy?->idUser)}} -   {{$deal->createdBy?->email}} / <strong>{{__('Created at')}}</strong> {{$deal->created_at}}
      </span>
        </div>
    </div>
        @if(!empty($commissions))
            <div class="card mt-2">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{__('Commission break down')}}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-border table-card table-nowrap">
                        <thead>
                        <tr>
                            <th scope="col">{{__('Order')}}</th>
                            <th scope="col">{{__('Trigger')}}</th>
                            <th scope="col">{{__('Amount')}}</th>
                            <th scope="col">{{__('Percentage')}}</th>
                            <th scope="col">{{__('Value')}}</th>
                            <th scope="col">{{__('Created at')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commissions as $key => $commission)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$commission->trigger}}</td>
                                <td> <span class="badge bg-success text-end fs-14">{{$commission->amount}} {{\App\Http\Livewire\DealsShow::CURRENCY}}</span></td>
                                <td>{{$commission->percentage}} %</td>
                                <td><span class="badge bg-success text-end fs-14">{{$commission->value}} {{\App\Http\Livewire\DealsShow::CURRENCY}}</span></td>
                                <td>{{$commission->created_at}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        <script type="module">
        $(document).on('turbolinks:load', function () {
            $('body').on('click', '.deleteDeal', function (event) {
                Swal.fire({
                    title: '{{__('Are you sure to delete this Deal')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                    showCancelButton: true,
                    confirmButtonText: "{{__('Delete')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.emit("delete", $(event.target).attr('data-id'));
                    }
                });
            });

            $('body').on('click', '.updateDeal', function (event) {
                var status = $(event.target).attr('data-status');
                var id = $(event.target).attr('data-id');
                var name = $(event.target).attr('data-status-name');
                var title = '{{__('Are you sure to')}} ' + name + ' ?';
                var confirmButtonText = name;
                Swal.fire({
                    title: title,
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.emit("updateDeal", id, status);
                    }
                });
            });


        });
    </script>
</div>
