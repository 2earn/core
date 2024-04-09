<div>
    @section('title'){{ __('history') }}@endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')
            @endslot
            @slot('title')
                {{ __('history') }}
            @endslot
        @endcomponent

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header ">
                    </div>
                    <div class="card-body table-responsive">
                        <table id="HistoryNotificationTable"
                            class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr class="head2earn  tabHeader2earn">
                                    <th>{{ __('reference') }}</th>
                                    <th>{{ __('send') }}</th>
                                    <th>{{ __('receiver') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('reponce') }}</th>
                                </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
