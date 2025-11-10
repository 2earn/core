
<div class="{{getContainerType()}}">
    @section('title')
        {{ __('SMS Management') }}
    @endsection
@component('components.breadcrumb')
        @slot('li_1')
            {{ __('SMS') }}
        @endslot
        @slot('title')
            {{ __('SMS Management') }}
        @endslot
    @endcomponent
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Total SMS') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-message-2-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2" id="total-sms">
                                <span class="counter-value" data-target="0">0</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Today') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-info fs-14 mb-0">
                                <i class="ri-calendar-todo-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2" id="today-sms">
                                <span class="counter-value" data-target="0">0</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('This Week') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-warning fs-14 mb-0">
                                <i class="ri-calendar-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2" id="week-sms">
                                <span class="counter-value" data-target="0">0</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('This Month') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-danger fs-14 mb-0">
                                <i class="ri-calendar-2-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2" id="month-sms">
                                <span class="counter-value" data-target="0">0</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ri-filter-3-line align-middle me-1"></i> {{ __('Filters') }}
            </h5>
            <button class="btn btn-sm btn-soft-danger" id="resetFilters">
                <i class="ri-refresh-line align-middle me-1"></i> {{ __('Reset Filters') }}
            </button>
        </div>
        <div class="card-body filter-card">
            <form id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">{{ __('Date From') }}</label>
                        <input type="date" class="form-control" id="date_from" name="date_from">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">{{ __('Date To') }}</label>
                        <input type="date" class="form-control" id="date_to" name="date_to">
                    </div>
                    <div class="col-md-3">
                        <label for="destination_number" class="form-label">{{ __('Phone Number') }}</label>
                        <input type="text" class="form-control" id="destination_number" name="destination_number"
                               placeholder="{{ __('Enter phone number') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="message" class="form-label">{{ __('Message Content') }}</label>
                        <input type="text" class="form-control" id="message" name="message"
                               placeholder="{{ __('Search in message') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">{{ __('User ID') }}</label>
                        <input type="number" class="form-control" id="user_id" name="user_id"
                               placeholder="{{ __('Enter user ID') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" id="applyFilters">
                            <i class="ri-search-line align-middle me-1"></i> {{ __('Apply Filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-mail-line align-middle me-1"></i> {{ __('SMS List') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sms-table"
                               class="table table-bordered table-striped dt-responsive nowrap align-middle"
                               style="width:100%">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Phone Number') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="smsDetailModal" tabindex="-1" aria-labelledby="smsDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-soft-primary">
                    <h5 class="modal-title" id="smsDetailModalLabel">
                        <i class="ri-message-2-line align-middle me-1"></i> {{ __('SMS Details') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="smsDetailContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">{{ __('Loading...') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
 <div>
     <script type="module">
         $(document).ready(function () {
             var table = $('#sms-table').DataTable({
                 processing: true,
                 serverSide: true,
                 responsive: true,
                 ajax: {
                     url: "{{ route('sms_data', ['locale' => app()->getLocale()]) }}",
                     type: 'GET',
                     data: function (d) {
                         d.date_from = $('#date_from').val();
                         d.date_to = $('#date_to').val();
                         d.destination_number = $('#destination_number').val();
                         d.message = $('#message').val();
                         d.user_id = $('#user_id').val();
                     },
                     error: function (xhr, error, thrown) {
                         console.error('DataTable error:', error);
                     }
                 },
                 columns: [
                     {data: 'id', name: 'id'},
                     {data: 'user_info', name: 'user_name', orderable: false},
                     {data: 'phone_info', name: 'destination_number', orderable: false},
                     {data: 'message_preview', name: 'message'},
                     {data: 'created_at', name: 'created_at'},
                 ],
                 order: [[4, 'desc']],
                 pageLength: 25,
                 lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                 language: {
                     search: "{{ __('Search') }}:",
                     lengthMenu: "{{ __('Show') }} _MENU_ {{ __('entries') }}",
                     info: "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
                     infoEmpty: "{{ __('No entries found') }}",
                     infoFiltered: "({{ __('filtered from') }} _MAX_ {{ __('total entries') }})",
                     paginate: {
                         first: "{{ __('First') }}",
                         last: "{{ __('Last') }}",
                         next: "{{ __('Next') }}",
                         previous: "{{ __('Previous') }}"
                     },
                     processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">{{ __("Loading...") }}</span></div>'
                 },
                 drawCallback: function (settings) {
                     updateStatistics(settings.json);
                 }
             });

             // Apply filters
             $('#applyFilters').on('click', function () {
                 table.ajax.reload();
             });

             // Reset filters
             $('#resetFilters').on('click', function () {
                 $('#filterForm')[0].reset();
                 table.ajax.reload();
             });

             // Enter key to apply filters
             $('#filterForm input').on('keypress', function (e) {
                 if (e.which === 13) {
                     e.preventDefault();
                     table.ajax.reload();
                 }
             });

             // View SMS details
             $('#sms-table').on('click', '.view-sms', function (e) {
                 e.preventDefault();
                 var smsId = $(this).data('id');

                 $('#smsDetailContent').html(`
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">{{ __('Loading...') }}</span>
                            </div>
                        </div>
                    `);

                 $('#smsDetailModal').modal('show');

                 $.ajax({
                     url: "{{ url(app()->getLocale() . '/sms') }}/" + smsId,
                     type: 'GET',
                     success: function (response) {
                         var html = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('SMS ID') }}</label>
                                            <p class="form-control-static">${response.sms.id}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('Created At') }}</label>
                                            <p class="form-control-static">${response.sms.created_at}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('Destination Number') }}</label>
                                            <p class="form-control-static">${response.sms.destination_number || 'N/A'}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('Source Number') }}</label>
                                            <p class="form-control-static">${response.sms.source_number || 'N/A'}</p>
                                        </div>
                                    </div>
                                    ${response.user ? `
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('User') }}</label>
                                            <p class="form-control-static">${response.user.enFirstName || ''} ${response.user.enLastName || ''} (ID: ${response.user.id})</p>
                                        </div>
                                    </div>
                                    ` : ''}
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('Message') }}</label>
                                            <div class="alert alert-info">
                                                ${response.sms.message || 'N/A'}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                         $('#smsDetailContent').html(html);
                     },
                     error: function (xhr, error, thrown) {
                         $('#smsDetailContent').html(`
                                <div class="alert alert-danger">
                                    {{ __('Error loading SMS details. Please try again.') }}
                         </div>
`);
                     }
                 });
             });

             // Update statistics (mock function - replace with actual API call if needed)
             function updateStatistics(data) {
                 // Load statistics from API
                 loadStatistics();
             }

             // Load statistics on page load
             loadStatistics();

             function loadStatistics() {
                 $.ajax({
                     url: "{{ route('sms_statistics', ['locale' => app()->getLocale()]) }}",
                     type: 'GET',
                     success: function (response) {
                         if (response.total !== undefined) {
                             $('#total-sms .counter-value').text(response.total);
                         }
                         if (response.today !== undefined) {
                             $('#today-sms .counter-value').text(response.today);
                         }
                         if (response.week !== undefined) {
                             $('#week-sms .counter-value').text(response.week);
                         }
                         if (response.month !== undefined) {
                             $('#month-sms .counter-value').text(response.month);
                         }
                     },
                     error: function (xhr, error, thrown) {
                         console.error('Statistics error:', error);
                     }
                 });
             }
         });
     </script>
 </div>

</div>
