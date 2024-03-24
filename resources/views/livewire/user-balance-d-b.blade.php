<div>
    @section('title'){{ __('Discounts Balance') }} @endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('Discounts Balance') }} @endslot
        @endcomponent

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <!--    <h5 class="card-title mb-0">Alternative Pagination</h5>-->
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="userBalanceDB_table" style="width: 100%">
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







@push('scripts')

    <script data-turbolinks-eval="false">
        $(document).on('ready ', function () {
             //   $('#page-title-box').removeClass('page-title-box');
                $('#page-title-box').addClass('page-title-box-db');
            }

        );
    </script>
@endpush

