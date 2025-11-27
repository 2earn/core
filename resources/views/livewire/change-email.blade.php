<div class="{{getContainerType()}}">
    <div class="row">
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Change Email Address') }}
            @endslot
        @endcomponent

        <div class="row">
            @include('layouts.flash-messages')
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="ri-mail-send-line fs-4 text-primary me-2"></i>
                            <h5 class="card-title mb-0 text-primary">{{ __('Update Your Email Address') }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info border-0 mb-4" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="ri-information-line fs-4 me-2 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">{{ __('Email Verification Required') }}</h6>
                                    <p class="mb-0">
                                        {{ __('To change your email address, we will send a verification code to your phone number') }}
                                        :
                                        <strong>{{ $numberActif }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form wire:submit.prevent="sendVerificationMail">
                            <div class="mb-3">
                                <label for="currentEmail" class="form-label fw-semibold">
                                    <i class="ri-mail-line text-muted me-1"></i>
                                    {{ __('Current Email') }}
                                </label>
                                <input type="email"
                                       class="form-control bg-light"
                                       id="currentEmail"
                                       value="{{ $user->email ?? __('No email set') }}"
                                       disabled
                                       aria-label="{{ __('Current Email') }}">
                            </div>

                            <div class="mb-4">
                                <label for="newEmail" class="form-label fw-semibold">
                                    <i class="ri-mail-add-line text-primary me-1"></i>
                                    {{ __('New Email Address') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                       wire:model="newEmail"
                                       class="form-control @error('newEmail') is-invalid @enderror"
                                       id="newEmail"
                                       placeholder="{{ __('your@email.com') }}"
                                       required
                                       aria-label="{{ __('New Email Address') }}"
                                       aria-required="true">
                                @error('newEmail')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="ri-shield-check-line"></i>
                                    {{ __('You will receive a verification code via SMS') }}
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit"
                                        wire:loading.attr="disabled"
                                        class="btn btn-primary px-4">
                                    <span wire:loading.remove wire:target="sendVerificationMail">
                                        <i class="ri-send-plane-line me-1"></i>{{ __('Send Verification Code') }}
                                    </span>
                                    <span wire:loading wire:target="sendVerificationMail">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"
                                              aria-hidden="true"></span>
                                        {{ __('Sending') }}...
                                    </span>
                                </button>
                                <a href="{{ route('user_form', app()->getLocale()) }}"
                                   class="btn btn-light px-4">
                                    <i class="ri-arrow-left-line me-1"></i>{{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start text-muted">
                            <i class="ri-information-line fs-5 me-2 flex-shrink-0"></i>
                            <small>
                                {{ __('After entering your new email address, you will receive two verification codes') }}
                                :
                                <ul class="mb-0 mt-2">
                                    <li>{{ __('First code via SMS to your phone number') }}</li>
                                    <li>{{ __('Second code via email to your new email address') }}</li>
                                </ul>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        var timerInterval;

        window.addEventListener('confirmOPTVerifMail', event => {
            Swal.fire({
                title: '{{trans('Your verification code by phone number')}}',
                html: '{{ __('We_will_send') }}' + '<br>' + event.detail[0].numberActif + '<br>' + '{{__('Your OTP Code')}}',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT',180000) }}',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '{{trans('cancel')}}',
                confirmButtonText: '{{trans('confirm OPT')}}',
                footer: '<i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    const b = Swal.getFooter().querySelector('i');
                    const p22 = Swal.getFooter().querySelector('div');
                    p22.innerHTML = '<br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';
                    timerInterval = setInterval(() => {
                        let timerLeft = Swal.getTimerLeft();
                        if (timerLeft !== null) {
                            b.innerHTML = '{{trans('It will close in')}}' + (timerLeft / 1000).toFixed(0) + '{{trans('secondes')}}';
                        } else {
                            clearInterval(timerInterval);
                        }
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                },
                input: 'text',
                inputAttributes: {autocapitalize: 'off'},
            }).then((resultat) => {
                if (resultat.isConfirmed) {
                    window.Livewire.dispatch('checkUserEmail', [resultat.value]);
                } else if (resultat.isDismissed && resultat.dismiss == 'cancel') {
                    window.Livewire.dispatch('cancelProcess', ["{{__('confirm OPT Verif Mail canceled')}}"]);
                }
            }).catch((error) => {
                console.error('SweetAlert Error:', error);
            });
        });

        window.addEventListener('EmailCheckUser', event => {
            if (event.detail[0].emailValidation) {
                Swal.fire({
                    title: event.detail[0].title,
                    html: event.detail[0].html,
                    allowOutsideClick: false,
                    timer: '{{ env('timeOPT',180000) }}',
                    timerProgressBar: true,
                    showCancelButton: true,
                    cancelButtonText: '{{trans('Cancel')}}',
                    confirmButtonText: '{{trans('Confirm OPT')}}',
                    footer: '<i></i><div class="footerOpt"></div>',
                    didOpen: () => {
                        const b = Swal.getFooter().querySelector('i');
                        const p22 = Swal.getFooter().querySelector('div');
                        p22.innerHTML = '<br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';
                        var timerInterval = setInterval(() => {
                            let timerLeft = Swal.getTimerLeft();
                            if (timerLeft !== null) {
                                b.innerHTML = '{{trans('It will close in')}}' + (timerLeft / 1000).toFixed(0) + '{{trans('secondes')}}';
                            } else {
                                clearInterval(timerInterval);
                            }
                        }, 1000);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    },
                    input: 'text',
                    inputAttributes: {autocapitalize: 'off'},
                }).then((resultat) => {
                    if (resultat.isConfirmed) {
                        window.Livewire.dispatch('saveVerifiedMail', [resultat.value]);
                    } else if (resultat.isDismissed && resultat.dismiss == 'cancel') {
                        window.Livewire.dispatch('cancelProcess', ["{{__('confirm Email Check User canceled')}}"]);
                    }
                }).catch((error) => {
                    console.error('SweetAlert Error:', error);
                });
            } else {
                Swal.fire({
                    title: event.detail[0].title,
                    text: event.detail[0].text,
                    icon: 'error',
                    confirmButtonText: "{{__('ok')}}"
                });
            }
        });
    </script>
@endpush

