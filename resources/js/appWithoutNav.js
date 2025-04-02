import jQuery from 'jquery';

Object.assign(window, {
    $: jQuery,
    jQuery
})

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import '@popperjs/core';

import Swal from 'sweetalert2';

window.Swal = Swal;

import intlTelInput from 'intl-tel-input';
window.intlTelInput = intlTelInput;

import.meta.glob([ '../images/**', ]);
