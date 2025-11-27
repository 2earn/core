<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            <i class="ri-exchange-dollar-line me-2"></i>{{ __('Financial transaction') }}
        @endslot
    @endcomponent
    <div class="row">
            @include('layouts.flash-messages')
    </div>
    <div class="col-12 card shadow-sm">
        <div class="card-header">
            <nav class="mt-3">
                <ul id="pills-tab" class="nav nav-pills nav-justified gap-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($filter=="1" or $filter=="") active @endif d-flex align-items-center justify-content-center"
                           data-bs-toggle="tab"
                           href="#cash_bfs"
                           role="tab"
                           aria-selected="@if($filter=="1" or $filter=="") true @else false @endif">
                            <i class="ri-money-dollar-circle-line me-2"></i>
                            <span class="d-none d-sm-inline">{!! __('Cash >> BFS') !!}</span>
                            <span class="d-inline d-sm-none">Cash</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($filter=="2") active @endif d-flex align-items-center justify-content-center"
                           data-bs-toggle="tab"
                           href="#bfs_funding"
                           role="tab"
                           aria-selected="@if($filter=="2") true @else false @endif">
                            <i class="ri-wallet-3-line me-2"></i>
                            <span class="d-none d-sm-inline">{{ __('BFS Funding') }}</span>
                            <span class="d-inline d-sm-none">BFS</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($filter=="3") active @endif d-flex align-items-center justify-content-center"
                           data-bs-toggle="tab"
                           href="#bfs_sms"
                           role="tab"
                           aria-selected="@if($filter=="3") true @else false @endif">
                            <i class="ri-message-3-line me-2"></i>
                            <span class="d-none d-sm-inline">{!! __('BFS >> SMS') !!}</span>
                            <span class="d-inline d-sm-none">SMS</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a id="pills-profile-tab"
                           class="nav-link @if($filter=="4") active @endif d-flex align-items-center justify-content-center position-relative"
                           data-bs-toggle="tab"
                           href="#me_others"
                           role="tab"
                           aria-selected="@if($filter=="4") true @else false @endif">
                            <i class="ri-arrow-right-circle-line me-2"></i>
                            <span class="d-none d-md-inline">{!! __('Requests: Me >> Others') !!}</span>
                            <span class="d-inline d-md-none">Out</span>
                            @if($requestOutAccepted>0)
                                <span id="pOutAccepted"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    {{$requestOutAccepted}}
                                    <span class="visually-hidden">accepted requests</span>
                                </span>
                            @endif
                            @if($requestOutRefused>0)
                                <span id="pOutRefused"
                                      class="position-absolute top-100 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{$requestOutRefused}}
                                    <span class="visually-hidden">refused requests</span>
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a id="pills-contact-tab"
                           class="nav-link @if($filter=="5") active @endif d-flex align-items-center justify-content-center position-relative"
                           data-bs-toggle="tab"
                           href="#others_me"
                           role="tab"
                           aria-selected="@if($filter=="5") true @else false @endif">
                            <i class="ri-arrow-left-circle-line me-2"></i>
                            <span class="d-none d-md-inline">{!! __('Requests: Others >> Me') !!}</span>
                            <span class="d-inline d-md-none">In</span>
                            @if($requestInOpen>0)
                                <span id="pIn"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                    {{$requestInOpen}}
                                    <span class="visually-hidden">open requests</span>
                                </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="card-body p-4">
            <div class="tab-content">
                <livewire:cash-to-bfs :filter="$filter"/>
                <livewire:bfs-funding :filter="$filter"/>
                <livewire:bfs-to-sms :filter="$filter"/>
                <livewire:outgoing-request :filter="$filter"/>
                <livewire:incoming-request :filter="$filter"/>
            </div>
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            var triggerTabList = [].slice.call(document.querySelectorAll('#pills-tab a'))
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)
                triggerEl.addEventListener('click', function (event) {
                    if (triggerEl.id === "pills-contact-tab") {
                        $.ajax({
                            url: "{{ route('reset_incoming_notification') }}",
                            type: 'get',
                            success: function (result) {
                                try {
                                    var pIn = document.getElementById('pIn');
                                    if (pIn) pIn.remove();
                                } catch (e) {
                                }
                                try {
                                    var sideNotIn = document.getElementById('sideNotIn');
                                    if (sideNotIn) sideNotIn.remove();
                                } catch (e) {
                                }
                            }
                        });
                    }
                    if (triggerEl.id === "pills-profile-tab") {
                        $.ajax({
                            url: "{{ route('reset_out_going_notification') }}",
                            type: 'get',
                            success: function (result) {
                                try {
                                    var pOutAccepted = document.getElementById('pOutAccepted');
                                    if (pOutAccepted) pOutAccepted.remove();
                                } catch (e) {
                                }
                                try {
                                    var pOutRefused = document.getElementById('pOutRefused');
                                    if (pOutRefused) pOutRefused.remove();
                                } catch (e) {
                                }
                                try {
                                    var sideNotOutRefused = document.getElementById('sideNotOutRefused');
                                    if (sideNotOutRefused) sideNotOutRefused.remove();
                                } catch (e) {
                                }
                                try {
                                    var sideNotOutAccepted = document.getElementById('sideNotOutAccepted');
                                    if (sideNotOutAccepted) sideNotOutAccepted.remove();
                                } catch (e) {
                                }
                            }
                        });
                    }
                })
            })
        });
    </script>
</div>



