<div id="page-title-box" class="row page-title-box m-1 p-1">
    <nav class="col d-flex align-items-center text-white" aria-label="{{ __('Breadcrumb') }}">
        <ol class="breadcrumb m-0 flex-grow-1">
            <li class="breadcrumb-item">
                <a href="{{ route('home', app()->getLocale()) }}" title="{{ __('To Home') }}" class="text-muted">
                    <i class="ri-home-7-line" aria-hidden="true"></i>
                    <span class="visually-hidden">{{ __('Home') }}</span>
                </a>
            </li>

            @php
                // Normalize provided items: expect an array of ['label' => string, 'url' => string|null]
                $crumbs = null;
                if (isset($items) && is_array($items) && count($items)) {
                    $crumbs = [];
                    foreach ($items as $it) {
                        if (is_array($it)) {
                            $crumbs[] = [
                                'label' => $it['label'] ?? ($it['title'] ?? ''),
                                'url' => $it['url'] ?? ($it['href'] ?? null),
                            ];
                        } else {
                            // If a plain string is provided, treat it as the current page label
                            $crumbs[] = ['label' => (string)$it, 'url' => null];
                        }
                    }
                }
            @endphp

            @if($crumbs)
                @foreach($crumbs as $index => $crumb)
                    @php $isLast = $index === count($crumbs) - 1; @endphp
                    <li class="breadcrumb-item @if($isLast) active @endif" @if($isLast) aria-current="page" @endif>
                        @if(!$isLast && !empty($crumb['url']) && $crumb['url'] !== '#')
                            <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                        @else
                            {!! $crumb['label'] !!}
                        @endif
                    </li>
                @endforeach
            @else
                @if(($pageTitle ?? '') !== 'Home' && !empty($pageTitle))
                    <li class="breadcrumb-item active" aria-current="page">{!! $pageTitle !!}</li>
                @endif
            @endif
        </ol>

        @if(!empty($helpUrl ?? null) && $helpUrl !== '#')
            <a href="{{ $helpUrl }}" title="{{ __('Check the help page') }}"
               class="ms-auto float-end m2 badge badge-outline-light">
                <i class="ri-question-line text-muted" aria-hidden="true"></i>
                <span class="visually-hidden">{{ __('Help') }}</span>
            </a>
        @endif

        <a href="{{ route('site_menu',['locale'=>app()->getLocale()]) }}" title="{{ __('Site menu') }}"
           class="ms-auto float-end m2 badge badge-outline-light">
            <i class="ri-menu-line text-muted" aria-hidden="true"></i>
            <span class="visually-hidden">{{ __('Menu') }}</span>
        </a>
    </nav>
</div>
