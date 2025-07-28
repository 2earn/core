<div class="container-fluid">
    <div>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
        <script>

            var ErreurSoldeReqBFS2 = '{{Session::has('ErreurSoldeReqBFS2')}}';
            if (ErreurSoldeReqBFS2) {
                var someTabTriggerEl = document.querySelector('#pills-contact-tab');
                var tab = new bootstrap.Tab(someTabTriggerEl);
                tab.show();
                Swal.fire({
                    title: '{{trans('SureTransfertCashBFS')}}',
                    text: '{{trans('SoldeRequestInsuffisant')}} ',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: '{{trans('Cancel')}}',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{trans('Yes')}}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url_string = window.location.href;
                        var url = new URL(url_string);
                        var paramValue = url.searchParams.get("FinRequestN");

                        window.Livewire.dispatch('redirectToTransfertCash', ['{{Session::get('ErreurSoldeReqBFS2')}}', paramValue]);
                    }
                })
            }

            var tabRequest = '{{Session::has('tabRequest')}}';
            if (tabRequest) {
                var someTabTriggerEl = document.querySelector('#pills-profile-tab');
                var tab = new bootstrap.Tab(someTabTriggerEl);
                tab.show();
            }
        </script>

        @component('components.breadcrumb')
            @slot('title')
                {{ __('Financial transaction') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row card">
            <div class="card-body">
                <ul id="pills-tab" class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#cash_bfs" role="tab"
                           aria-selected="false" tabindex="-1">
                            {!! __('Cash >> BFS') !!}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_funding" role="tab"
                           aria-selected="false" tabindex="-1">
                            {{ __('BFS Funding') }}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_sms" role="tab"
                           aria-selected="false" tabindex="-1">
                            {!! __('BFS >> SMS') !!}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a id="pills-profile-tab" class="nav-link" data-bs-toggle="tab" href="#me_others" role="tab"
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
                    <li class="nav-item" role="presentation">
                        <a id="pills-contact-tab" class="nav-link" data-bs-toggle="tab" href="#others_me" role="tab"
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
                <div class="tab-content text-muted">
                    <livewire:cash-to-bfs/>
                    <livewire:bfs-funding/>
                    <livewire:bfs-to-sms/>
                    <livewire:outgoing-request/>
                    <livewire:incoming-request/>
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
