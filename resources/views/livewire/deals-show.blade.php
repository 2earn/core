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
                <span class="badge btn btn-info float-end">
                    {{__(\Core\Enum\DealStatus::tryFrom($deal->status)?->name)}}
                </span>
                <span class="badge btn btn-primary float-end mx-2">
                    {{$deal->platform()->first()->name}}
                </span>
            </h4>

            @if(\App\Models\User::isSuperAdmin())
                <a class="link-dark"
                   href="{{route('sales_tracking',['locale'=>app()->getLocale(),'id'=>$deal->id])}}">{{ __('See details for Platform role') }}</a>
            @endif
        </div>
        <div class="card-body row">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted">
                        <div class="flex-shrink-0 ms-2">
                            <strong class="fs-14 font-weight-bold mb-0">{{__('Current turnover')}}
                                / {{__('Target turnover')}}</strong>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                            <span class="badge badge-success text-muted">
                                              <p class="float-end mx-1"> <span class="badge bg-success text-end fs-14"
                                                                               title="{{__('Current turnover')}}">
                                {{$deal->current_turnover}}  {{config('app.currency')}}
                            </span>
                  <i class="ri-arrow-right-fill"></i>
                    <span class="badge bg-danger text-end fs-14" title="{{__('Target turnover')}}">
                                {{$deal->target_turnover}}  {{config('app.currency')}}
                            </span>
                </p>
                            </span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted">
                        <div class="flex-shrink-0 ms-2">
                            <strong class="fs-14 font-weight-bold mb-0">{{__('Camembert value')}}</strong>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                            <span class="badge badge-success text-muted">
                                              <p class="float-end mx-1"> <span class="badge bg-success text-end fs-14"
                                                                               title="{{__('Current turnover')}}">
                                {{\App\Models\Deal::getCamombertPercentage($deal)}}  {{config('app.currency')}}
                            </span>

                </p>
                            </span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted">
                        <div class="flex-shrink-0 ms-2">
                            <strong class="fs-14 font-weight-bold mb-0">{{__('Start Date')}}
                                <i class="ri-arrow-right-fill"></i> {{__('End date')}}</strong>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                            <span class="badge badge-success text-muted">
                                              <p class="float-end mx-1"> <span class="badge bg-success text-end fs-14"
                                                                               title="{{__('Start Date')}}">
                                {{$deal->start_date}}
                            </span>
                   <i class="ri-arrow-right-fill"></i>
                    <span class="badge bg-danger text-end fs-14" title="{{__('End date')}}">
                                {{$deal->end_date}}
                            </span>
                </p>
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
            <ul class="list-group col-sm-12 col-md-6 col-lg-4">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Discount deal')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->discount}}  {{config('app.percentage')}}
                            </span>
                        </div>
                    </div>
                </li>
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
                                {{$deal->initial_commission}}  %
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
                                {{$deal->final_commission}} %
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="list-group col-sm-12 col-md-6 col-lg-4">
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
                                {{$deal->total_commission_value}}  {{config('app.currency')}}
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
                                {{$deal->total_unused_cashback_value}}  {{config('app.currency')}}
                            </span>
                        </div>
                    </div>
                </li>

            </ul>
            <ul class="list-group col-sm-12 col-md-6 col-lg-4">
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
                            <span class="mx-2 text-info">
                                {{$deal->earn_profit}} %
                            </span>
                            <span class="mx-2 text-success">
                                {{formatSolde($earn_profit,2)}} {{config('app.currency')}}
                            </span>
                            <span class="mx-2 text-primary">
                                {{formatSolde($deal->cash_company_profit,2)}} {{config('app.currency')}}
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
                                {{$deal->jackpot}} %
                            </span>
                            <span class="mx-2 text-success">
                                {{formatSolde($jackpot,2)}} {{config('app.currency')}}
                            </span>
                            <span class="mx-2 text-primary">
                                {{formatSolde($deal->cash_jackpot,2)}} {{config('app.currency')}}
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
                                {{$deal->tree_remuneration}} %
                            </span>
                            <span class="mx-2 text-success">
                                {{formatSolde($tree_remuneration,2)}} {{config('app.currency')}}
                            </span>
                            <span class="mx-2 text-primary">
                                {{formatSolde($deal->cash_tree,2)}} {{config('app.currency')}}
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
                                {{$deal->proactive_cashback}} %
                            </span>
                            <span class="mx-2 text-success">
                                {{formatSolde($proactive_cashback)}} {{config('app.currency')}}
                            </span>
                            <span class="mx-2 text-primary">
                                {{formatSolde($deal->cash_cashback,2)}} {{config('app.currency')}}
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
        @include('livewire.commission-breackdowns', ['commissions' => $commissions])
    @endif
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            $('body').on('click', '.deleteDeal', function (event) {
                Swal.fire({
                    title: '{{__('Are you sure to delete this Deal')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                    showCancelButton: true,
                    confirmButtonText: "{{__('Delete')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch("delete", [$(event.target).attr('data-id')]);
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
                        window.Livewire.dispatch("updateDeal", [id, status]);
                    }
                });
            });


        });
    </script>
</div>
