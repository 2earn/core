<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Financial transaction') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row card">
            <div class="card-header align-items-xl-center d-xl-flex">
                <ul id="pills-tab" class="nav nav-pills nav-info" role="tablist">
                    <li class="nav-item waves-effect waves-light mx-1 p-1" role="presentation">
                        <a class="nav-link @if($filter=="1" or $filter=="") active @endif " data-bs-toggle="tab"
                           href="#cash_bfs" role="tab"
                           aria-selected="false" tabindex="-1">
                            {!! __('Cash >> BFS') !!}
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light mx-1 p-1" role="presentation">
                        <a class="nav-link align-middle @if($filter=="2" ) active @endif " data-bs-toggle="tab"
                           href="#bfs_funding" role="tab"
                           aria-selected="false" tabindex="-1">
                            {{ __('BFS Funding') }}
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light mx-1 p-1" role="presentation">
                        <a class="nav-link align-middle  @if($filter=="3" ) active @endif " data-bs-toggle="tab"
                           href="#bfs_sms" role="tab"
                           aria-selected="false" tabindex="-1">
                            {!! __('BFS >> SMS') !!}
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light mx-1 p-1" role="presentation">
                        <a id="pills-profile-tab" class="nav-link  @if($filter=="4" ) active @endif "
                           data-bs-toggle="tab" href="#me_others" role="tab"
                           aria-selected="true">
                            {!! __('Requests: Me >> Others') !!}
                            @if($requestOutAccepted>0)
                                <button id="btnNotRequestOutAcccepted" type="button"
                                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                    <i class=" ri-user-follow-line text-success fs-22"></i>
                                    <span id="pOutAccepted"
                                          class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success">
                                            {{$requestOutAccepted}}
                                        </span>
                                </button>
                            @endif
                            @if($requestOutRefused>0)
                                <button id="btnNotRequestOutRefused" type="button"
                                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                    <i class=" ri-user-unfollow-line text-danger fs-22"></i>
                                    <span id="pOutRefused"
                                          class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">{{$requestOutRefused}}</span>
                                </button>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light mx-1 p-1" role="presentation">
                        <a id="pills-contact-tab" class="nav-link  @if($filter=="5" ) active @endif "
                           data-bs-toggle="tab" href="#others_me" role="tab"
                           aria-selected="true">
                            {!! __('Requests: Others >> Me') !!}
                            @if($requestInOpen>0)
                                <button id="btnNotRequestInOpen" type="button"
                                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                    <i class="  ri-user-received-2-line text-primary fs-22"></i>
                                    <span id="pIn"
                                          class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-primary">  {{$requestInOpen}}</span>
                                </button>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content text-muted">
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
                        var x = triggerEl.id;
                        if (triggerEl.id === "pills-contact-tab") {
                            $.ajax({
                                url: "{{ route('reset_incoming_notification') }}",
                                type: 'get',
                                success: function (result) {
                                    try {
                                        document.getElementById('pIn').innerHTML = "";
                                        document.getElementById('btnNotRequestInOpen').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('sideNotIn').innerHTML = "";
                                        document.getElementById('sideNotIn').remove();
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
                                        document.getElementById('pOutAccepted').innerHTML = "";
                                        document.getElementById('btnNotRequestOutAcccepted').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('pOutRefused').innerHTML = "";
                                        document.getElementById('btnNotRequestOutRefused').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('sideNotOutRefused').innerHTML = "";
                                        document.getElementById('sideNotOutRefused').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('sideNotOutAccepted').innerHTML = "";
                                        document.getElementById('sideNotOutAccepted').remove();
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
</div>
