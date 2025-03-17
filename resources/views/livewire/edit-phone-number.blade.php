<div class="container-fluid">
    <div data-turbolinks="false">
    <div class="row">
        <div class="col-12">
            <div class="card cardPhone" id="phone-form">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Update Phone Number') }}</h4>
                </div>
                <div wire:ignore class="card-body">
                    <div class="mb-3" dir="ltr">
                        <label>
                            {{ __('Your new phone number') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div id="inputPhoneUpdate" data-turbolinks-permanent class="input-group signup mb-3">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="submit_phone" class="btn btn-secondary"
                                onclick="ConfirmChangePhone()">
                            {{ __('Change phone number') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        var timerInterval

        function ConfirmChangePhone() {
            if ($('#phoneUpPhone').val() == "") {
                Swal.fire({
                    title: '{{__('Required_phone_number')}}',
                    text: '',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    denyButtonText: 'No',
                    customClass: {
                        actions: 'my-actions',
                        cancelButton: 'order-1 right-gap',
                        confirmButton: 'order-2',
                        denyButton: 'order-3'
                    }
                });
                return;
            }
            Swal.fire({
                title: '{{ __('Are you sure to change Phone') }}',
                text: '',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: '{{trans('ok')}}',
                cancelButtonText: '{{trans('canceled !')}}',
                denyButtonText: '{{trans('No')}}',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch('PreChangePhone', [$('#phoneUpPhone').val(), $('#outputUpPhone').val(), 'mail']);
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        }

        window.addEventListener('PreChPhone', event => {
            var textHtmlSend = '{{ __('We will send') }}';
            if (event.detail[0].methodeVerification === 'mail') {
                textHtmlSend = '{{ __('We will send mail') }}'
            }
            Swal.fire({
                title: '{{ __('Your verification code') }}',
                html: textHtmlSend + '<br> ' + event.detail[0].FullNumber + '<br>' + '{{__('Your OTP Code')}}',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT',180000) }}',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                confirmButtonText: '{{trans('ok')}}',
                footer: '<div class="footerOpt"></div></br> <i></i>',
                didOpen: () => {
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    timerInterval = setInterval(() => {
                        p22.innerHTML = '{{trans('It will close in')}}' + ' ' + (Swal.getTimerLeft() / 1000).toFixed(0) + ' ' + '{{trans('secondes')}}' + '</br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a> '
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
            }).then((resultat) => {
                if (resultat.value) {
                    if (event.detail[0].methodeVerification === 'mail') {
                        window.Livewire.dispatch('PreChangePhone', [$('#phoneUpPhone').val(), $('#outputUpPhone').val(), 'phone']);
                    } else {
                        window.Livewire.dispatch('UpdatePhoneNumber', [resultat.value, $('#phoneUpPhone').val(), $('#outputUpPhone').val(), $('#ccodeUpPhone').val(), $('#isoUpPhone').val()]);
                    }
                }
                if (resultat.isDismissed) {
                }
            })
        })
    </script>
</div>
</div>
