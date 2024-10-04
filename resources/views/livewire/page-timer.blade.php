<style>
    body {
        text-align: center;
        font-family: sans-serif;
        font-weight: 100;
    }

    h1 {
        color: #0a0c0d;
        font-weight: 100;
        font-size: 40px;
        margin: 40px 0px 20px;
    }

    #clockdiv {
        font-family: sans-serif;
        color: #fff;
        display: inline-block;
        font-weight: 100;
        text-align: center;
        font-size: 30px;
    }

    #clockdiv > div {
        padding: 10px;
        border-radius: 3px;
        background: #3c3f40;
        display: inline-block;
    }

    #clockdiv div > span {
        padding: 15px;
        border-radius: 3px;
        background: #0a53be;
        display: inline-block;
    }

    .smalltext {
        padding-top: 5px;
        font-size: 16px;
    }
</style><div>
    <h1>Coming Soon</h1>

    <div>
        <h2>Temps restant :</h2>
        <div id="timer">
            <h1>Countdown Clock</h1>
            <div id="clockdiv">
                <div>
                    <span class="days">{{ $this->days }}</span>
                    <div class="smalltext">Days</div>
                </div>
                <div>
                    <span class="hours">{{ $this->hours }}</span>
                    <div class="smalltext">Hours</div>
                </div>
                <div>
                    <span class="minutes">{{ $this->minutes }}</span>
                    <div class="smalltext">Minutes</div>
                </div>
                <div>
                    <span class="seconds">{{ $this->seconds }}</span>
                    <div class="smalltext">Seconds</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let countdownInterval;


        window.onload = function() {
            const savedDate = localStorage.getItem('futureDate');
            if (savedDate) {
                document.getElementById('futureDate').value = savedDate;
                startCountdown(new Date(savedDate));
            }
        };

        function startCountdown(futureDate) {
            const futureDateInput = document.getElementById('futureDate').value;
            if (!futureDate && futureDateInput) {
                futureDate = new Date(futureDateInput);
            }

            if (!isNaN(futureDate)) {
                localStorage.setItem('futureDate', futureDate.toISOString());
                clearInterval(countdownInterval);

                initializeClock('clockdiv', futureDate);


                countdownInterval = setInterval(() => {
                    Livewire.emit('decrementTime');
                }, 1000);
            } else {
                alert('Veuillez sélectionner une date valide.');
            }
        }

        function clearDate() {
            localStorage.removeItem('futureDate');
            document.getElementById('futureDate').value = '';
            clearInterval(countdownInterval);

            Livewire.emit('resetCountdown');
        }

        function initializeClock(id, endtime) {
            const clock = document.getElementById(id);
            const daysSpan = clock.querySelector('.days');
            const hoursSpan = clock.querySelector('.hours');
            const minutesSpan = clock.querySelector('.minutes');
            const secondsSpan = clock.querySelector('.seconds');

            function updateClock() {
                const t = Date.parse(endtime) - Date.parse(new Date());
                if (t <= 0) {
                    clearInterval(countdownInterval);
                    daysSpan.innerHTML = 0;
                    hoursSpan.innerHTML = 0;
                    minutesSpan.innerHTML = 0;
                    secondsSpan.innerHTML = 0;
                    return;
                }
                const seconds = Math.floor((t / 1000) % 60);
                const minutes = Math.floor((t / 1000 / 60) % 60);
                const hours = Math.floor((t / (1000 * 60 * 60)) % 24);
                const days = Math.floor(t / (1000 * 60 * 60 * 24));

                daysSpan.innerHTML = days;
                hoursSpan.innerHTML = hours;
                minutesSpan.innerHTML = minutes;
                secondsSpan.innerHTML = seconds;
            }

            updateClock();
            countdownInterval = setInterval(updateClock, 1000);
        }
    </script>
</div>

