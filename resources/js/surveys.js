$(document).on('turbolinks:load', function () {

    function setupCountdown(campaignSelector, startTimeMillis, endTimeMillis) {
        var second = 1000;
        var minute = second * 60;
        var hour = minute * 60;
        var day = hour * 24;

        function calculateRemaining() {
            var now = new Date().getTime();
            return now >= startTimeMillis && now < endTimeMillis ? endTimeMillis - now : 0;
        }

        var didRefresh = false;
        var previousGap = calculateRemaining();

        function countdown() {
            var gap = calculateRemaining();

            var shouldRefresh = previousGap > day && gap <= day || previousGap > 0 && gap === 0;

            previousGap = gap;

            var textDay = Math.floor(gap / day);
            var textHour = Math.floor((gap % day) / hour);
            var textMinute = Math.floor((gap % hour) / minute);
            var textSecond = Math.floor((gap % minute) / second);

            if (document.querySelector(campaignSelector + ' .timer')) {
                document.querySelector(campaignSelector + ' .day').innerText = textDay;
                document.querySelector(campaignSelector + ' .hour').innerText = textHour;
                document.querySelector(campaignSelector + ' .minute').innerText = textMinute;
                document.querySelector(campaignSelector + ' .second').innerText = textSecond;
            }

            if (shouldRefresh && !didRefresh) {
                didRefresh = true;
                setTimeout(function () {
                    window.location.reload();
                }, 30000 + Math.random() * 90000);
            }
        }

        countdown();
        setInterval(countdown, 1000);
    }

    document.addEventListener("DOMContentLoaded", function (event) {
        if (!document.querySelectorAll || !document.body.classList) {
            return;
        }

    });

    function isElement(obj) {
        try {
            return obj instanceof HTMLElement;
        } catch (e) {
            return (typeof obj === "object") &&
                (obj.nodeType === 1) && (typeof obj.style === "object") &&
                (typeof obj.ownerDocument === "object");
        }
    }

    var elements = document.getElementsByClassName('survey-cd');
    for (var i in elements) {
        var item = elements[i];
        if (isElement(item)) {
            setupCountdown(
                "." + item.getAttribute("id"),
                new Date(document.getElementById(item.getAttribute("id")).getAttribute('data-start')).getTime(),
                new Date(document.getElementById(item.getAttribute("id")).getAttribute('data-end')).getTime())
        }
    }
});
