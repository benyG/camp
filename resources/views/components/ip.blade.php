        <div x-data="{
            oxl() {
                fetch('https://ipapi.co/json/')
                    .then(response => response.json())
                    .then(data => {
                        var date = new Date();
                        var offsetMinutes = date.getTimezoneOffset();
                        var offsetHours = offsetMinutes / 60;
                        var sign = offsetHours >= 0 ? '-' : '+';
                        var offsetString = sign +
                            ('0' + Math.abs(offsetHours)).slice(-2) + ':' +
                            ('0' + (offsetMinutes % 60)).slice(-2);
                        $wire.ox = data;
                    })
                    .catch(error => console.error('Error', error));
            }
        }" x-init="oxl;">
        </div>