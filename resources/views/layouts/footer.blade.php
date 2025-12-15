<div class="{{getContainerType()}}">
    <div class="row">
        <footer class="col-12 footer modern-footer {{$pageName}} mt-auto">
            <div class="footer-wave-container">
                <svg class="footer-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                     preserveAspectRatio="none">
                    <path
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                        opacity=".25" class="footer-wave-path"></path>
                    <path
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                        opacity=".5" class="footer-wave-path"></path>
                    <path
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                        class="footer-wave-path"></path>
                </svg>
            </div>

            <div class="footer-content">
                <div class="container">
                    <div class="row g-4">
                        <!-- Announcement Card -->
                        <div class="col-12">
                            <div class="footer-announcement-card">
                                <div class="announcement-icon">
                                    <i class="mdi mdi-rocket-launch"></i>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 offset-lg-1">
                                        <p class="text-center mb-0 lh-lg">
                                            <a href="{{route('home',app()->getLocale())}}"
                                               class="footer-link-highlight">
                                                2Earn.cash
                                            </a>
                                            <span class="d-inline-block mx-1">{{ __('has been accepted into') }}</span>
                                            <a href="https://www.fastercapital.com" class="footer-link-highlight"
                                               target="_blank" rel="noopener">
                                                {{ __('FasterCapital')}}
                                            </a>{{__("'s") }}
                                            <a href="https://fastercapital.com/incubation-pending/2earncash.html"
                                               class="footer-link-highlight" target="_blank" rel="noopener">
                                                {{ __('Raise Capital') }}
                                            </a>
                                            <span
                                                class="d-inline-block mx-1">{{ __('program and is seeking a capital of $2.0 million to be raised.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Navigation -->
                        <div class="col-12">
                            @include('layouts.footer-static', ['pageName' => $pageName])
                        </div>

                        <!-- Footer Bottom -->
                        <div class="col-12">
                            <div class="footer-bottom">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-4 text-center text-md-start">
                                        <div class="footer-credit">
                                            <i class="mdi mdi-code-tags"></i>
                                            <span>{{ __('Design & Develop by') }}</span>
                                            <a href="{{route('home',app()->getLocale())}}"
                                               class="footer-link-highlight">
                                                2Earn.cash
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="footer-copyright">
                                            <i class="mdi mdi-copyright"></i>
                                            {{__('Copyright')}} {{ date('Y') }}
                                            <span class="fw-semibold">2Earn.cash</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center text-md-end">
                                        <button type="button"
                                                title="{{ __('Back to Top') }}"
                                                class="btn-back-to-top"
                                                id="back-to-top"
                                                onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                                            <i class="mdi mdi-arrow-up"></i>
                                            <span class="btn-back-text d-none d-sm-inline ms-1">{{ __('Top') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
