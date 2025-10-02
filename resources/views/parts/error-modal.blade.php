<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content  shadow">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> @lang('Datatable loading error')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('datatable.close_button')"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-0 fs-6 text-muted">@lang('The datatable does not load correctly')</p>
                <p class="mb-0 fs-6 text-muted">@lang('AJAX error suppressed')</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> @lang('Close')
                </button>
            </div>
        </div>
    </div>
</div>
