
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Javascript Fullcalendar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <style>
        .fc-sun, .fc-mon, .fc-tue, .fc-wed, .fc-thu, .fc-fri, .fc-sat {
            background-color: #FFE4C4;
        }
        .fc-event {
            background-color: #FF0000;
            border-color: #FF0000;
            color: #FFF;
        }
        .fc-day-header {
            background-color: gray;
            color: white; 
        }
        .container {
            width: 100%;
            margin: 0 auto; 
        }
        #calendar {
            width: 100%; 
        }
    </style>
</head>
<body>
<div class="container">
    <h2 style="text-align: left;">Kalendar</h2>
    <div id="calendar"></div>
</div>
<br>
<script>
$(document).ready(function() {
    var events = <?php echo json_encode($events); ?>;

    $('#calendar').fullCalendar({
        defaultView: 'month',
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
        },
        events: events,
        eventRender: function(event, element) {
            element.css('background-color', '#FF0000');
            element.css('border-color', '#FF0000');
        }
    });
});
</script>
</body>
</html>
