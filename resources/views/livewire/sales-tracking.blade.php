<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Deal sales tracking') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Deal sales tracking') }}
        @endslot
    @endcomponent
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
        </div>
        <div class="card-body row">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted">
                        <div class="flex-shrink-0 ms-2">
                            <strong class="fs-14 mb-0">{{$deal->name}}</strong>
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
        <div class="card-footer">
      <span class="text-muted float-end">
          <strong class="fs-14 mb-0">{{__('Created by')}} :</strong> {{getUserDisplayedName($deal->createdBy?->idUser)}} -   {{$deal->createdBy?->email}} / <strong>{{__('Created at')}}</strong> {{$deal->created_at}}
      </span>
        </div>
    </div>
    @if(!empty($commissions))
        @include('livewire.commission-breackdowns', ['commissions' => $commissions])
    @endif
</div>
