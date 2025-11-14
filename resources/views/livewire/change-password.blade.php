<div>
    <form action="">
        <div class="alert alert-info border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="ri-lock-password-line fs-4 me-2"></i>
                <div>
                    <h6 class="alert-heading mb-1">{{ __('Password Security') }}</h6>
                    <small class="mb-0">{{ __('Please use a strong password with at least 8 characters') }}</small>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <label for="oldpasswordInput" class="form-label fw-semibold">
                    <i class="ri-lock-unlock-line text-primary me-1"></i>
                    {{ __('Current Password') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="position-relative auth-pass-inputgroup">
                    <input wire:model="oldPassword"
                           type="password"
                           class="form-control pe-5"
                           name="password"
                           placeholder="{{__('Old password')}}"
                           id="oldpasswordInput"
                           aria-label="{{__('Current Password')}}"
                           aria-required="true">
                    <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                        type="button"
                        id="toggleOldPassword"
                        aria-label="{{__('Toggle password visibility')}}">
                        <i class="ri-eye-fill align-middle"></i>
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <label for="newpasswordInput" class="form-label fw-semibold">
                    <i class="ri-lock-line text-primary me-1"></i>
                    {{ __('New Password') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="position-relative auth-pass-inputgroup">
                    <input wire:model="newPassword"
                           type="password"
                           class="form-control pe-5"
                           name="password"
                           placeholder="{{__('New password please')}}"
                           id="newpasswordInput"
                           aria-label="{{__('New Password')}}"
                           aria-required="true">
                    <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                        type="button"
                        id="toggleNewPassword"
                        aria-label="{{__('Toggle password visibility')}}">
                        <i class="ri-eye-fill align-middle"></i>
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <div>
                    <label for="confirmpasswordInput" class="form-label fw-semibold">
                        <i class="ri-lock-line text-primary me-1"></i>
                        {{ __('New Confirm Password') }}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative auth-pass-inputgroup">
                        <input wire:model="confirmedPassword"
                               type="password"
                               class="form-control pe-5"
                               id="confirmpasswordInput"
                               placeholder="{{__('Confirm password')}}"
                               aria-label="{{__('New Confirm Password')}}"
                               aria-required="true">
                        <button
                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                            type="button"
                            id="toggleConfirmPassword"
                            aria-label="{{__('Toggle password visibility')}}">
                            <i class="ri-eye-fill align-middle"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card bg-light border-0">
                    <div class="card-body p-3">
                        <div class="form-check form-switch">
                            <input wire:model.live="sendPassSMS"
                                   type="checkbox"
                                   id="send"
                                   class="form-check-input"
                                   role="switch"
                                   aria-checked="false">
                            <label class="form-check-label" for="send">
                                <i class="ri-message-2-line me-1"></i>
                                {{ __('I want to receive my password by SMS') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="text-end border-top pt-3">
                    <button wire:click="PreChangePass"
                            type="button"
                            class="btn btn-success px-4">
                        <i class="ri-key-2-line me-1"></i>
                        {{ __('Change password') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@script
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // SMS checkbox change handler
        $('#send').change(function () {
            if (this.checked && !{{$soldeSms}} > 0) {
                Swal.fire({
                    title: '{{ __('solde_sms_ins') }}',
                    confirmButtonText: '{{trans('ok')}}',
                });
                this.checked = false;
                return;
            }
            Swal.fire({
                title: '{{ __('upate_notification_setting') }}',
                showDenyButton: true,
                confirmButtonText: '{{trans('Yes')}}',
                denyButtonText: '{{trans('No')}}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('ParamSendChanged');
                } else if (result.isDenied) {
                    this.checked = !this.checked;
                }
            })
        });

        // Password visibility toggles
        var toggleOldPassword = document.querySelector("#toggleOldPassword");
        if (toggleOldPassword) {
            var Oldpassword = document.querySelector("#oldpasswordInput");
            toggleOldPassword.addEventListener("click", function () {
                var type = Oldpassword.getAttribute("type") === "password" ? "text" : "password";
                Oldpassword.setAttribute("type", type);
                this.classList.toggle("bi-eye");
            });
        }

        var toggleNewPassword = document.querySelector("#toggleNewPassword");
        if (toggleNewPassword) {
            var Newpassword = document.querySelector("#newpasswordInput");
            toggleNewPassword.addEventListener("click", function () {
                var type = Newpassword.getAttribute("type") === "password" ? "text" : "password";
                Newpassword.setAttribute("type", type);
                this.classList.toggle("bi-eye");
            });
        }

        var toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
        if (toggleConfirmPassword) {
            var confirmPassword = document.querySelector("#confirmpasswordInput");
            toggleConfirmPassword.addEventListener("click", function () {
                var type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
                confirmPassword.setAttribute("type", type);
                this.classList.toggle("bi-eye");
            });
        }
    });
</script>
@endscript

