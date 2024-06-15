$(document).ready(function() {
    let doctorAvailability = {};

    $('#specialties').change(function() {
        var specialty = $(this).val();
        console.log("Specialty selected: " + specialty);
        $.ajax({
            url: 'get_doctors.php',
            method: 'POST',
            data: {specialty: specialty},
            success: function(response) {
                console.log("Response received: " + response);
                $('#doctor_id').html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + ": " + error);
            }
        });
    });

    $('#doctor_id').change(function() {
        var doctor_id = $(this).val();
        if (doctor_id) {
            $.ajax({
                url: 'get_doctor_availability.php',
                method: 'POST',
                data: {doctor_id: doctor_id},
                success: function(response) {
                    doctorAvailability = JSON.parse(response);
                    console.log("Doctor availability: " + JSON.stringify(doctorAvailability));
                    initializeFlatpickr();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + ": " + error);
                }
            });
        }
    });

    function initializeFlatpickr() {
        flatpickr("#appointment_date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: [
                function(date) {
                    return !doctorAvailability.days.includes(date.getDay().toString());
                }
            ],
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                if (doctorAvailability.days.includes(dayElem.dateObj.getDay().toString())) {
                    dayElem.classList.add('available');
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                var selectedDate = new Date(dateStr);
                var dayOfWeek = selectedDate.getDay();
                var times = [];

                if (doctorAvailability.days.includes(dayOfWeek.toString())) {
                    var startHour = parseInt(doctorAvailability.start_hour.split(':')[0], 10);
                    var endHour = parseInt(doctorAvailability.end_hour.split(':')[0], 10);
                    for (var hour = startHour; hour < endHour; hour++) {
                        times.push(hour + ":00");
                        times.push(hour + ":30");
                    }
                }

                $.ajax({
                    url: 'get_available_times.php',
                    method: 'POST',
                    data: {doctor_id: $('#doctor_id').val(), appointment_date: dateStr},
                    success: function(response) {
                        var bookedTimes = JSON.parse(response);
                        console.log("Booked times: " + JSON.stringify(bookedTimes));
                        var options = "<option value=''>Select a Time</option>";
                        times.forEach(function(time) {
                            if (bookedTimes.includes(time)) {
                                options += "<option value='" + time + "' class='bg-red-500 text-white' disabled>" + time + "</option>";
                            } else {
                                options += "<option value='" + time + "' class='bg-green-500 text-white'>" + time + "</option>";
                            }
                        });
                        $('#appointment_time').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                    }
                });
            }
        });
    }
});