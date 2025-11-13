<div class="{{getContainerType()}}">
    <div>
        @section('title')
            {{ __('Tree Balance') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('Tree Balance') }}
            @endslot
        @endcomponent
        <div class="row card">
            <div class="card-body">
                <div id="tree_container">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">{{ __('Transaction History') }}</h5>
                        <select wire:model.live="perPage" class="form-select form-select-sm"
                                style="width: auto;">
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
                                @foreach($transactions as $tr)
                                    @php
                                        $value = $tr['value'] ?? '0';
                                    @endphp
                                    <div class="col-12">
                                        <div class="card border shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1 fw-bold">{!! $tr['operation'] ?? '' !!}</h6>
                                                        <small class="text-muted d-block">{{ $tr['reference'] ?? '' }}</small>
                                                    </div>
                                                    <span class="badge bg-danger fs-6 px-3 py-2">{{ $value }}</span>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-6 col-md-3">
                                                        <small class="text-muted d-block mb-1">{{ __('Created at') }}</small>
                                                        <strong class="d-block">{{ $tr['created_at'] ?? '-' }}</strong>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <small class="text-muted d-block mb-1">{{ __('Current balance') }}</small>
                                                        <strong class="d-block">{{ $tr['current_balance'] ?? '-' }}</strong>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <small class="text-muted d-block mb-1">{{ __('Complementary information') }}</small>
                                                        <span class="d-block text-break">{!! $tr['complementary_information'] ?? '-' !!}</span>
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
            document.addEventListener('DOMContentLoaded', function () {
                $('#page-title-box').addClass('page-title-box-bfs');
            });
        </script>
    </div>
</div>
