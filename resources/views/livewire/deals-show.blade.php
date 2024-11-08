<div>
    @section('title')
        {{ __('Deals') }} > {{$deal->name}}
    @endsection
    @if(!in_array($currentRouteName,["deals_archive"]))
        @component('components.breadcrumb')
            @slot('li_1')@endslot
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
            <h6 class="text-info">{{__('Inputs')}}</h6>
        </div>
        <div class="card-body row">
            <ul class="list-group col-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Description')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->description}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Status')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->status}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Objective turnover')}}</h6>
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
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Start date')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->start_date}}
                            </span>
                        </div>
                    </div>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('End date')}}</h6>
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
            </ul>
            <ul class="list-group col-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Out provider turnover')}}</h6>
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
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Items profit average')}}</h6>
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
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Initial commission')}}</h6>
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
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Final commission')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->final_commission}}
                            </span>
                        </div>
                    </div>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Precision')}}</h6>
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
            </ul>
            <ul class="list-group col-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Progressive commission')}}</h6>
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
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Margin percentage')}}</h6>
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
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Cash back margin percentage')}}</h6>
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
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Proactive consumption margin percentage')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->proactive_consumption_margin_percentage}}
                            </span>
                        </div>
                    </div>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Shareholder benefits margin percentage')}}</h6>
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
            </ul>
            <ul class="list-group col-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Tree margin percentage')}}</h6>
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
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Current turnover')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->current_turnover}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Item price')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->item_price}}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-shrink-0 ms-2">
                                    <h6 class="fs-14 mb-0">{{__('Current turnover index')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-info">
                                {{$deal->current_turnover_index}}
                            </span>
                        </div>
                    </div>
            </ul>
        </div>
        <div class="card-footer">
      <span class="text-muted float-end">
          <strong class="fs-14 mb-0">{{__('Created by')}} :</strong> {{getUserDisplayedName($deal->createdBy?->idUser)}}
          {{$deal->createdBy?->email}}
      </span>
        </div>
        <div class="card-header">
            <h6 class="text-info">{{__('Calculated')}}</h6>
        </div>
        <div class="card-body">
        </div>
        <div class="card-header">
            <h6 class="text-info">{{__('Action')}}</h6>
        </div>
        <div class="card-body">
        </div>
    </div>

</div>
