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
                                {{$deal->current_turnover}}
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
                                    <span class="fs-14 mb-0">{{__('Objective turnover')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->objective_turnover}}
                            </span>
                        </div>
                    </div>
                </li>
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
                                {{$deal->discount}}
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
                                    <span class="fs-14 mb-0">{{__('Out provider turnover')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->out_provider_turnover}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Items profit average')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->items_profit_average}}
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
                                {{$deal->initial_commission}}
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
                                {{$deal->final_commission}}
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
                                    <span class="fs-14 mb-0">{{__('Precision')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->precision}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Progressive commission')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->progressive_commission}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Margin percentage')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->margin_percentage}}
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
                                    <span class="fs-14 mb-0">{{__('Proactive consumption margin percentage')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->proactive_consumption_margin_percentage}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Shareholder benefits margin percentage')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->shareholder_benefits_margin_percentage}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Cash back margin percentage')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->cash_back_margin_percentage}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="flex-shrink-0 ms-2">
                                    <span class="fs-14 mb-0">{{__('Tree margin percentage')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->tree_margin_percentage}}
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
