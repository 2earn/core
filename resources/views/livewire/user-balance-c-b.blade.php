<div>
    @section('title'){{ __('Cash Balance') }} @endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('Cash Balance') }}@endslot
        @endcomponent

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4">
                            <div class="col-sm-auto">
                                <div>

                               <img src=" {{asset('assets/images/qr_code.jpg')}}" class="rounded avatar-lg">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <p>{{ __('Cash Balance description') }}</p> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none ">{{ __('Ref') }}</th>
                                <th style=" border: none ">{{ __('Date') }}</th>
                                <th style=" border: none ">{{ __('Operation Designation') }}</th>
                                <th style=" border: none ">{{ __('Description') }}</th>

                                <th style=" border: none ">{{ __('Value') }}</th>
                                <th style=" border: none ">{{ __('Balance') }}</th>
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








