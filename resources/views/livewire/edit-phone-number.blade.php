<div data-turbolinks="false">
    <div class="row justify-content-center ">
        <div class="col-12 col-md-4">
            <div class="card cardPhone " id="phone-form">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Update Phone Number') }}</h4>
                </div>
                <div wire:ignore class="card-body">
                    {{--            @foreach ($errors->all() as $error)--}}
                    {{--                <p class="text-danger">{{ $error }}</p>--}}
                    {{--            @endforeach--}}
                    {{--            @if(!empty($email_verified))--}}
                    {{--                @foreach ($email_verified as $error_email)--}}
                    {{--                    <p class="text-danger">{{ $error_email }}</p>--}}
                    {{--                @endforeach--}}
                    {{--            @endif--}}
                    {{--            <input type="hidden" name="id_user" value="{{ $user->idUser }}">--}}
                    {{--                    <input type=" " name="id_user" value=" ">--}}
                    <div class="text-center mb-3" dir="ltr">
                        <label>{{ __('Your new phone number') }}</label>
                        <div id="inputPhoneUpdate" data-turbolinks-permanent class="input-group signup mb-3"
                             style="justify-content:center;">

                            {{--                            @php--}}
                            {{--                                $ip = ip2long(request()->ip());--}}
                            {{--                                $ip = long2ip($ip);--}}

                            {{--                                if($ip == "127.0.0.1")--}}
                            {{--                                    {--}}
                            {{--                                         $ip = "41.228.16.1";--}}

                            {{--                                    }--}}

                            {{--                                if(env('APP_LIEN')== "http://2earn.test"){--}}
                            {{--                                    $ip = "41.228.16.1";--}}
                            {{--                                }--}}

                            {{--                                $json = file_get_contents("http://ipinfo.io/{$ip}/geo");--}}

                            {{--                                $details = json_decode($json, true);--}}

                            {{--                                // dd($details);--}}
                            {{--                                $country_code = $details['country'];--}}
                            {{--                            @endphp--}}

                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        <button type="submit" id="submit_phone" class="btn btn-success ps-5 pe-5"
                                onclick="ConfirmChangePhone()"
                        >{{ __('send') }}</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function ConfirmChangePhone() {
            if ($('#phoneUpPhone').val() == "") {
                Swal.fire({
                    title: '{{__('Required_phone_number')}}',

                    text: '',
                    icon: "warning",
                    // showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    denyButtonText: 'No',
                    customClass: {
                        actions: 'my-actions',
                        cancelButton: 'order-1 right-gap',
                        confirmButton: 'order-2',
                        denyButton: 'order-3',
                    }
                });
                return;
            }
            Swal.fire({
                title: '{{ __('Are you sure to change Phone') }}',
                text: '',
                icon: "warning",
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '{{trans('ok')}}',
                cancelButtonText: '{{trans('canceled !')}}',
                denyButtonText: '{{trans('No')}}',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('PreChangePhone', $('#phoneUpPhone').val(), $('#outputUpPhone').val(), 'mail');
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        }

        window.addEventListener('PreChPhone', event => {
            var textHtmlSend = '{{ __('We_will_send') }}';
            if (event.detail.methodeVerification === 'mail'){
                textHtmlSend = '{{ __('We_will_send_mail') }}'
            }
            Swal.fire({
                title: '{{ __('Your verification code') }}',
                html: textHtmlSend + '<br> ' + event.detail.FullNumber + '<br>' + '{{__('Your OTP Code')}}',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT') }}',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                confirmButtonText: '{{trans('ok')}}',
                footer: '<div class="footerOpt"></div></br> <i></i>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')

                    timerInterval = setInterval(() => {
                        p22.innerHTML = '{{trans('It will close in')}}' + ' ' + (Swal.getTimerLeft() / 1000).toFixed(0) + ' '  + '{{trans('secondes')}}' + '</br>' +  '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a> '
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
                    if (event.detail.methodeVerification === 'mail')
                    {
                        window.livewire.emit('PreChangePhone', $('#phoneUpPhone').val(), $('#outputUpPhone').val(),'phone');
                    }
                    else
                    {
                        window.livewire.emit('UpdatePhoneNumber', resultat.value, $('#phoneUpPhone').val(), $('#outputUpPhone').val(), $('#ccodeUpPhone').val(),$('#isoUpPhone').val());
                    }
                }
                if (resultat.isDismissed) {
                }
            })
        })

    </script>
</div>
