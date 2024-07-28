function convertTo24HourFormat(time) {
    var parts = time.split(' ');
    var timeParts = parts[0].split(':');
    var hours = parseInt(timeParts[0]);
    var minutes = timeParts.length > 1 ? timeParts[1] : '00';
    var period = parts[1].toLowerCase();
    
    if (period === 'pagi') {
        return (hours < 10 ? '0' + hours : hours) + ':' + minutes;
    } else if (period === 'tghr') {
        return '12:' + minutes;
    } else if (period === 'ptg' || period === 'mlm') {
        return ((hours < 12 ? hours + 12 : hours) < 10 ? '0' + (hours < 12 ? hours + 12 : hours) : (hours < 12 ? hours + 12 : hours)) + ':' + minutes;
    }
    return time; // Return original if no match
}

function combineDateAndTime(date, time) {
    var dateTimeString = date.toISOString().split('T')[0] + 'T' + time + ':00';
    return new Date(dateTimeString);
}

function validateDates() {
    var tkhmula = document.getElementById('tkhmula_input').value;
    var tkhtamat = document.getElementById('tkhtamat_input').value;
    var msmula = document.getElementById('msmula').value;
    var mstamat = document.getElementById('mstamat').value;
    var amaranpenuh = document.getElementById('amaranpenuh');
    var blockedDateRange = document.getElementById('blockedDateRange'); // Get the new div

    var startDate = new Date(tkhmula);
    var endDate = new Date(tkhtamat);
    var startTime = convertTo24HourFormat(msmula);
    var endTime = convertTo24HourFormat(mstamat);
    
    var startDateTime = combineDateAndTime(startDate, startTime);
    var endDateTime = combineDateAndTime(endDate, endTime);

    for (var i = 0; i < bookedDates.length; i++) {
        var bookedStartDate = new Date(bookedDates[i].tarikh_mula.split('T')[0]);
        var bookedEndDate = new Date(bookedDates[i].tarikh_tamat.split('T')[0]);
        var bookedStartTime = bookedDates[i].tarikh_mula.split('T')[1].substring(0, 5);
        var bookedEndTime = bookedDates[i].tarikh_tamat.split('T')[1].substring(0, 5);

        var bookedStartDateTime = combineDateAndTime(bookedStartDate, bookedStartTime);
        var bookedEndDateTime = combineDateAndTime(bookedEndDate, bookedEndTime);
        
        // Check if the booking start or end date overlaps within the same day or between days
        if ((startDateTime <= bookedEndDateTime && startDateTime >= bookedStartDateTime) ||
            (endDateTime <= bookedEndDateTime && endDateTime >= bookedStartDateTime) ||
            (startDateTime <= bookedStartDateTime && endDateTime >= bookedEndDateTime)) {
            
            console.log('Blocked date range:');
            console.log('Input start date:', startDateTime);
            console.log('Input end date:', endDateTime);
            console.log('Booked start date:', bookedStartDateTime);
            console.log('Booked end date:', bookedEndDateTime);
            
            // Set the inner HTML of the new div to show the blocked date range
           
            amaranpenuh.style.display = 'block';
            console.log('Dates overlap: showing warning');
            return false;
        }
    }

    amaranpenuh.style.display = 'none';
    console.log('Dates do not overlap: form will be submitted');
    return true;
}
