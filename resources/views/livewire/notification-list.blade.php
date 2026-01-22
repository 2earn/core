<div class="container">
    @section('title')
        {{ __('Notifications list') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Notifications list') }}
        @endslot
    @endcomponent

  <div class="row">
      <div class="col-12 card border-0">
          <div class="card-header">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                  <div class="btn-group" role="group" aria-label="Notification Filter" style="border-radius: 12px; overflow: hidden;">
                      <input type="radio" class="btn-check" name="filter" id="filter-all" value="all"
                             wire:model.live="filter" autocomplete="off" checked>
                      <label class="btn btn-primary border-end-0 p-2 mx-1 d-inline-flex align-items-center gap-2 fw-semibold"
                             for="filter-all" style="border-radius: 12px 0 0 12px;">
                          <i class="ri-inbox-line fs-16"></i>
                          <span>{{ __('All') }}</span>
                      </label>

                      <input type="radio" class="btn-check" name="filter" id="filter-unread" value="unread"
                             wire:model.live="filter" autocomplete="off">
                      <label class="btn btn-outline-primary border-start-0 border-end-0  p-2 mx-1  d-inline-flex align-items-center gap-2 fw-semibold"
                             for="filter-unread">
                          <i class="ri-mail-unread-line fs-16"></i>
                          <span>{{ __('Unread') }}</span>
                      </label>

                      <input type="radio" class="btn-check" name="filter" id="filter-read" value="read"
                             wire:model.live="filter" autocomplete="off">
                      <label class="btn btn-outline-primary border-start-0  p-2 mx-1  d-inline-flex align-items-center gap-2 fw-semibold"
                             for="filter-read" style="border-radius: 0 12px 12px 0;">
                          <i class="ri-mail-check-line fs-16"></i>
                          <span>{{ __('Read') }}</span>
                      </label>
                  </div>

                  <button type="button"
                          wire:click="markThemAllRead"
                          class="btn btn-primary p-1 d-inline-flex align-items-center gap-2 shadow-sm fw-semibold"
                          style="border-radius: 10px;">
                      <span>{{ __('Mark them all as read') }}</span>
                      <span wire:loading wire:target="markThemAllRead">
                        <span class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>
                    </span>
                  </button>
              </div>
          </div>

          <div class="card-body p-0 bg-light" style="min-height: 300px;">
              @forelse($notifications as $notification)
                  <div class="p-1">
                      @include(\App\Helpers\NotificationHelper::getTemplate($notification) , ['notification' => $notification])
                  </div>
              @empty
                  <div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 300px;">
                      <div class="mb-4 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                          <i class="ri-notification-off-line text-primary fs-1"></i>
                      </div>
                      <h5 class="text-dark fw-bold mb-2">{{ __('No notifications found.') }}</h5>
                      <p class="text-muted mb-0">{{ __('You\'re all caught up! Check back later.') }}</p>
                  </div>
              @endforelse
          </div>

          @if($notifications->hasPages())
              <div class="card-footer bg-white border-top py-3">
                  <div class="d-flex justify-content-center">
                      {{ $notifications->links() }}
                  </div>
              </div>
          @endif
      </div>
  </div>
</div>
