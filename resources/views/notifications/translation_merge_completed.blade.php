<div
</div>
    </div>
        </div>
            </div>
                </div>
                    @endif
                        </div>
                            <i class="ri-mail-check-line text-success fs-18"></i>
                             title="{{__('Read')}}">
                             style="width: 36px; height: 36px; background: rgba(var(--bs-success-rgb), 0.1);"
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                    @else
                        </button>
                            </span>
                                <span class="spinner-border spinner-border-sm text-info" role="status"></span>
                            <span wire:loading wire:target="markAsRead('{{$notification->id}}')" class="position-absolute">
                            <i class="ri-mail-line fs-18"></i>
                                onmouseout="this.style.background=''; this.style.color=''">
                                onmouseover="this.style.background='rgba(var(--bs-info-rgb), 0.1)'; this.style.color='var(--bs-info)'"
                                title="{{__('Mark as read')}}"
                                id="all-notification-check{{$notification->id}}"
                                wire:click="markAsRead('{{$notification->id}}')"
                                style="width: 36px; height: 36px; transition: all 0.3s;"
                                class="btn btn-sm btn-light border-0 d-inline-flex align-items-center justify-content-center rounded-circle"
                        <button type="button"
                    @if ($notification->read_at === null)
                    </span>
                        {{$notification->created_at->diffForHumans()}}
                        <i class="ri-time-line"></i>
                    <span class="text-muted fs-12 d-sm-none d-inline-flex align-items-center gap-1">
                <div class="d-flex align-items-center gap-2">
                </a>
                    <i class="ri-arrow-right-line"></i>
                    <span class="fw-semibold">{{ __('View Translations') }}</span>
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(var(--bs-info-rgb), 0.25)'">
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(var(--bs-info-rgb), 0.35)'"
                   style="transition: all 0.3s; box-shadow: 0 2px 6px rgba(var(--bs-info-rgb), 0.25);"
                   class="btn btn-sm btn-info px-4 py-2 d-inline-flex align-items-center gap-2 rounded-pill"
                <a href="{{ \App\Helpers\NotificationHelper::localizeUrl($notification->data['url'] ?? route('translate', app()->getLocale()), app()->getLocale()) }}"
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            @endif
                </div>
                    </span>
                        {{ __('Execution time') }}: {{ $notification->data['execution_time'] }} {{ __('seconds') }}
                        <i class="ri-time-line me-1"></i>
                    <span class="badge bg-soft-info text-info fs-12 px-2 py-1">
                <div class="mb-2">
            @if(isset($notification->data['execution_time']))
            </p>
                {{ $notification->data['message'] ?? __('Translation merge operation completed successfully') }}
            <p class="text-muted mb-1 fs-13" style="line-height: 1.4;">
            </div>
                </span>
                    {{$notification->created_at->diffForHumans()}}
                    <i class="ri-time-line"></i>
                <span class="text-muted fs-12 d-none d-sm-inline-flex align-items-center gap-1">
                </h6>
                    @endif
                        </span>
                            {{__('New')}}
                            <i class="ri-notification-3-line me-1" style="font-size: 0.7rem;"></i>
                                     box-shadow: 0 2px 4px rgba(79, 172, 254, 0.3);">
                                     font-size: 0.65rem; padding: 0.2rem 0.5rem;
                              style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                        <span class="badge rounded-pill"
                    @if ($notification->read_at === null)
                    {{ __('Translation Merge Completed') }}
                <h6 class="fs-15 fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <div class="d-flex justify-content-between align-items-center mb-1 gap-1">
        <div class="flex-grow-1 overflow-hidden">
        </div>
            </div>
                <i class="ri-translate-2 fs-20 text-info"></i>
                 style="width: 48px; height: 48px; background: linear-gradient(135deg, rgba(var(--bs-info-rgb), 0.15) 0%, rgba(var(--bs-info-rgb), 0.25) 100%); box-shadow: 0 4px 8px rgba(var(--bs-info-rgb), 0.2);">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
        <div class="flex-shrink-0">
         style="background: @if ($notification->read_at === null) linear-gradient(135deg, rgba(var(--bs-info-rgb), 0.05) 0%, rgba(var(--bs-info-rgb), 0.02) 100%) @else #ffffff @endif;">
    <div class="d-flex align-items-start p-3 gap-3 position-relative"
    @endif
        <div class="position-absolute top-0 start-0 h-100 bg-info" style="width: 4px;"></div>
    @if ($notification->read_at === null)
    style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 12px; margin-bottom: 8px; overflow: hidden;">
    data-notification-id="{{$notification->id}}"
    id="{{$notification->id}}"
    class="notification-item position-relative border-0 @if ($notification->read_at === null) unread @endif"

