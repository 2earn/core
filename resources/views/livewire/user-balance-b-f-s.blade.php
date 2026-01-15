<div class="container">
    <div>
        @section('title')
            {{ __('Balance For Shopping') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('Balance For Shopping') }}
            @endslot
        @endcomponent
        <div class="row card">
            @if(!empty($bfss))
                <div class="card-header">
                    <h5 class="card-title mb-0">{{__('BFSs description values')}}
                        @if($type)
                            <span class="text-success">({{__($type)}})</span>
                        @endif</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @if($type!='ALL')
                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('BFS_')}}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="text-muted fs-14 mb-0">
                                                    <i class="ri-money-dollar-circle-line fs-13 align-middle"></i> {{__('ALL')}}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{config('app.currency')}}{{formatSolde(floatval($this->totalBfs),2)}}</h4>
                                                <a href="{{route('user_balance_bfs' , ['locale'=>app()->getLocale()] )}}"
                                                   class="text-decoration-underline">{{__('More details')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @foreach($bfss as $bfs)
                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{__('BFS_')}}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class=" badge
                                                    @if($type==$bfs['type'])
                                                text-success
                                                 @else
                                                text-primary
                                                 @endif
                                                 fs-14 mb-0">
                                                    <i class="ri-money-dollar-circle-line fs-13 align-middle"></i> {{$bfs['type']}}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{config('app.currency')}}{{formatSolde(floatval($bfs['value']),2)}}</h4>
                                                <a href="{{route('user_balance_bfs' , ['locale'=>app()->getLocale(),'type'=>$bfs['type']] )}}"
                                                   class="text-decoration-underline">{{__('More details')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="card-header">
                <h5 class="card-title mb-0">{{__('BFSs description title')}} </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="justify-content-sm-end">
                                <p class="text-muted">{{ __('bfs description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="bfs_container">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">{{ __('Transaction History') }}</h5>
                        <select wire:model.live="perPage" class="form-select form-select-sm per-page-width" >
                            <option value="10">10</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div wire:loading class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('Loading...') }}</span>
                        </div>
                    </div>

                    <div wire:loading.remove>
                        @if($transactions->count() > 0)
                            <div class="row g-3">
                                @foreach($transactions as $transaction)
                                    <div class="col-12">
                                        <div class="card border shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1 fw-bold">{!! $transaction['operation'] ?? '' !!}</h6>
                                                        <small class="text-muted d-block">#{{ $transaction['ranks'] ?? '' }} - {!! $transaction['reference'] ?? '' !!}</small>
                                                    </div>
                                                    @php
                                                        $value = $transaction['value'] ?? '0';
                                                        $isPositive = strpos($value, '+') !== false;
                                                    @endphp
                                                    <span class="badge {{ $isPositive ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2">{{ $value }}</span>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-6 col-md-2">
                                                        <small class="text-muted d-block mb-1">{{ __('date') }}</small>
                                                        <strong class="d-block">{{ $transaction['created_at'] ?? '-' }}</strong>
                                                    </div>
                                                    <div class="col-6 col-md-2">
                                                        <small class="text-muted d-block mb-1">{{ __('Percentage') }}</small>
                                                        <strong class="d-block">{{ $transaction['percentage'] ?? '-' }}</strong>
                                                    </div>
                                                    <div class="col-6 col-md-2">
                                                        <small class="text-muted d-block mb-1">{{ __('Balance') }}</small>
                                                        <strong class="d-block">{{ $transaction['current_balance'] ?? '-' }}</strong>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <small class="text-muted d-block mb-1">{{ __('Complementary information') }}</small>
                                                        <span class="d-block text-break">{!! $transaction['complementary_information'] ?? '-' !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info text-center">{{ __('No transactions found') }}</div>
                        @endif

                        @if($transactions->hasPages())
                            <div class="mt-4">
                                <div class="d-flex justify-content-center">
                                    {{ $transactions->links() }}
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        {{ __('Showing') }} {{ $transactions->firstItem() ?? 0 }}
                                        {{ __('to') }} {{ $transactions->lastItem() ?? 0 }}
                                        {{ __('of') }} {{ $transactions->total() }} {{ __('entries') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                $('#page-title-box').addClass('page-title-box-bfs');
            });
        </script>
    </div>
</div>
