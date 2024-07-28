document.addEventListener('DOMContentLoaded', function() {
    // Get the date input elements
    var startDateInput = document.getElementById('tkhmula_input');
    var endDateInput = document.getElementById('tkhtamat_input');
    var bilanganHariInput = document.getElementById('bil_hari');
    var minStartDateDisplay = document.getElementById('minStartDateDisplay');

    // Set the minimum date for tkhmula (7 days from today)
    var today = new Date();
    var minStartDate = new Date();
    minStartDate.setDate(today.getDate() + 7);
    startDateInput.setAttribute('min', formatDateISO(minStartDate));
    minStartDateDisplay.innerText = formatDate(minStartDate); // Set the formatted date in the span

    // Update the minimum date for tkhtamat based on tkhmula selection
    startDateInput.addEventListener('change', function() {
        var selectedStartDate = new Date(startDateInput.value);
        endDateInput.setAttribute('min', formatDateISO(selectedStartDate));
        calculateDays();
    });

    // Calculate the number of days between tkhmula and tkhtamat
    endDateInput.addEventListener('change', calculateDays);

    function calculateDays() {
        var startDate = new Date(startDateInput.value);
        var endDate = new Date(endDateInput.value);
        if (startDate && endDate) {
            var timeDiff = endDate - startDate;
            var dayDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            bilanganHariInput.value = dayDiff;
            console.log('Bilangan Hari:', dayDiff); // For debugging purposes
        }
    }

    // Helper function to format date as YYYY-MM-DD for input element
    function formatDateISO(date) {
        var yyyy = date.getFullYear();
        var mm = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        var dd = String(date.getDate()).padStart(2, '0');
        return yyyy + '-' + mm + '-' + dd;
    }

    // Helper function to format date as "25 April 2024"
    function formatDate(date) {
        var day = date.getDate();
        var monthNames = ["Januari", "Februari", "Mac", "April", "Mei", "Jun", "Julai", "Ogos", "September", "Oktober", "November", "Disember"];
        var month = monthNames[date.getMonth()];
        var year = date.getFullYear();
        return day + ' ' + month + ' ' + year;
    }
});

// JavaScript to generate time options pagi
document.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('msmula');
    var start = new Date();
    start.setHours(0, 0, 0, 0); // Start at 12:00 AM

    var timePeriods = [
        { start: 0, end: 11, label: 'Pagi' },
        { start: 12, end: 12, label: 'Tghr' },
        { start: 13, end: 18, label: 'Ptg' },
        { start: 19, end: 23, label: 'Mlm' }
    ];

    for (var i = 0; i < 96; i++) { // 96 intervals of 15 minutes in 24 hours
        var hours = start.getHours();
        var minutes = start.getMinutes();
        var period = '';

        // Determine the period label based on the hours
        for (var j = 0; j < timePeriods.length; j++) {
            if (hours >= timePeriods[j].start && hours <= timePeriods[j].end) {
                period = timePeriods[j].label;
                break;
            }
        }

        // Convert hours from 24-hour to 12-hour format
        var displayHours = hours % 12;
        displayHours = displayHours ? displayHours : 12; // the hour '0' should be '12'

        // Pad minutes with leading zero
        var displayMinutes = minutes < 10 ? '0' + minutes : minutes;

        // Format time
        var timeDisplay = displayHours + ':' + displayMinutes + ' ' + period;
        var timeValue = hours.toString().padStart(2, '0') + ':' + displayMinutes;

        // Create option element
        var option = document.createElement('option');
        option.setAttribute('data-value', timeValue); // Use the 24-hour format as data-value
        option.textContent = timeDisplay;

        // Append option to select
        select.appendChild(option);

        // Increment by 15 minutes
        start.setMinutes(start.getMinutes() + 15);
    }
});

// JavaScript to generate time options malam
document.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('mstamat');
    var start = new Date();
    start.setHours(0, 14, 0, 0); // Start at 12:00 AM

    var timePeriods = [
        { start: 0, end: 11, label: 'Pagi' },
        { start: 12, end: 12, label: 'Tghr' },
        { start: 13, end: 18, label: 'Ptg' },
        { start: 19, end: 23, label: 'Mlm' }
    ];

    for (var i = 0; i < 96; i++) { // 96 intervals of 15 minutes in 24 hours
        var hours = start.getHours();
        var minutes = start.getMinutes();
        var period = '';

        // Determine the period label based on the hours
        for (var j = 0; j < timePeriods.length; j++) {
            if (hours >= timePeriods[j].start && hours <= timePeriods[j].end) {
                period = timePeriods[j].label;
                break;
            }
        }

        // Convert hours from 24-hour to 12-hour format
        var displayHours = hours % 12;
        displayHours = displayHours ? displayHours : 12; // the hour '0' should be '12'

        // Pad minutes with leading zero
        var displayMinutes = minutes < 10 ? '0' + minutes : minutes;

        // Format time
        var timeDisplay = displayHours + ':' + displayMinutes + ' ' + period;
        var timeValue = hours.toString().padStart(2, '0') + ':' + displayMinutes;

        // Create option element
        var option = document.createElement('option');
        option.setAttribute('data-value', timeValue); // Use the 24-hour format as data-value
        option.textContent = timeDisplay;

        // Append option to select
        select.appendChild(option);

        // Increment by 15 minutes
        start.setMinutes(start.getMinutes() + 15 );
    }
});
