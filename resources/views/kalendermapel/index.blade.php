@extends('layout.layout')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar</title>
  <!-- FullCalendar JS -->
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

  <!-- Custom CSS -->
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
      font-size: 14px;
    }

    #calendar {
      max-width: 1100px;
      margin: 40px auto;
    }
  </style>
</head>

<body>
  <h1>Jadwal Mata Pelajaran</h1>
  <div id='calendar'></div>

  <!-- Custom JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        initialDate: '2024-10-07',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          //   right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [{
            title: 'Fisika 7A',
            start: '2024-10-12T10:30:00',
            end: '2024-10-12T12:30:00'
          },
          {
            title: 'Matematika 7B',
            start: '2024-10-12T12:00:00'
          },
          {
            title: 'Kimia 7C',
            start: '2024-10-12T14:30:00'
          },
          {
            title: 'Kimia 9A',
            start: '2024-10-13T07:00:00'
          }
        ]
      });

      calendar.render();
    });
  </script>
</body>

</html>
@endsection