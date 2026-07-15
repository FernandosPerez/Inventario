document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es', // Configuración en español
        dayCellClassNames: function (date) {
            // Resaltar el día actual
            if (date.dateStr === new Date().toISOString().split('T')[0]) {
                return ['highlight-today'];
            }
        },
        events: [], // Aquí podrías cargar eventos desde la base de datos si es necesario
        dateClick: function (info) {
            // Al hacer clic en una fecha, se muestra la modal para agregar evento
            $('#eventDate').val(info.dateStr); // Establece la fecha de inicio del evento al día clickeado
            $('#eventEndDate').val(info.dateStr); // Establece la fecha de fin del evento al mismo día
            $('#eventModal').modal('show');
        }
    });

    calendar.render();

    // Manejo del formulario para agregar eventos
    document.getElementById('eventForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var title = document.getElementById('eventTitle').value;
        var startDate = document.getElementById('eventDate').value;
        var endDate = document.getElementById('eventEndDate').value;
        var details = document.getElementById('eventDetails').value;

        // Agregar evento al calendario
        calendar.addEvent({
            title: title,
            start: startDate,
            end: endDate, // Agregar fecha de fin
            description: details,
            backgroundColor: '#bae3f2', // Color del fondo del evento
            textColor: 'white'
        });

        // Cerrar la modal y resetear el formulario
        $('#eventModal').modal('hide');
        document.getElementById('eventForm').reset();
    });
});