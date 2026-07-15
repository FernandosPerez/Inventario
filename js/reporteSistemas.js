let tickets = JSON.parse(localStorage.getItem('tickets')) || [];

function renderTickets() {
    const ticketsTable = document.getElementById('ticketsTable');
    ticketsTable.innerHTML = '';

    tickets.forEach((ticket, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
<td>${ticket.id}</td>
<td>${ticket.title}</td>
<td class="ticket-status">
    <span class="badge 
        ${ticket.status === 'abierto' ? 'bg-warning' : ''}
        ${ticket.status === 'en proceso' ? 'bg-info' : ''}
        ${ticket.status === 'cerrado' ? 'bg-success' : ''}">${ticket.status}
    </span>
</td>
<td class="ticket-actions">
    <a href="#" class="btn btn-info btn-sm" onclick="viewTicket(${index})">Ver</a>
    <a href="#" class="btn btn-secondary btn-sm" onclick="changeStatus(${index})">Cambiar Estado</a>
    ${ticket.status === 'cerrado' 
        ? `<a href="#" class="btn btn-warning btn-sm" onclick="openSurvey(${index})">Encuesta de Satisfacción</a>`
        : (ticket.status === 'cerrado' ? '' : `<a href="#" class="btn btn-success btn-sm" onclick="closeTicket(${index})">Cerrar Ticket</a>`)}
</td>
`;
        ticketsTable.appendChild(tr);
    });
}

function openSurvey(index) {
    const ticket = tickets[index];
    Swal.fire({
        title: 'Encuesta de Satisfacción',
        input: 'textarea',
        inputPlaceholder: 'Escriba su opinión...',
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Por favor, complete la encuesta';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            ticket.satisfactionSurvey = result.value;
            localStorage.setItem('tickets', JSON.stringify(tickets));
            renderTickets();
            Swal.fire('Gracias por su opinión', '', 'success');
        }
    });
}


function viewTicket(index) {
    const ticket = tickets[index];
    document.getElementById('modalTicketId').textContent = ticket.id;
    document.getElementById('modalTicketTitle').textContent = ticket.title;
    document.getElementById('modalTicketDescription').textContent = ticket.description;
    document.getElementById('modalTicketStatus').textContent = ticket.status;
    new bootstrap.Modal(document.getElementById('viewTicketModal')).show();
}

function changeStatus(index) {
    const ticket = tickets[index];
    if (ticket.status === 'abierto') {
        ticket.status = 'en proceso';
    } else if (ticket.status === 'en proceso') {
        ticket.status = 'cerrado';
    }
    localStorage.setItem('tickets', JSON.stringify(tickets));
    renderTickets();
}

function closeTicket(index) {
    const ticket = tickets[index];
    Swal.fire({
        title: 'Encuesta de Satisfacción',
        input: 'textarea',
        inputPlaceholder: 'Escriba su opinión...',
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Por favor, complete la encuesta';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            ticket.status = 'cerrado';
            ticket.satisfactionSurvey = result.value;
            localStorage.setItem('tickets', JSON.stringify(tickets));
            renderTickets();
            Swal.fire('Gracias por su opinión', '', 'success');
        }
    });
}

document.getElementById('ticketForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const title = document.getElementById('ticketTitle').value;
    const description = document.getElementById('ticketDescription').value;

    const newTicket = {
        id: tickets.length + 1,
        title: title,
        description: description,
        status: 'abierto',
        satisfactionSurvey: null
    };

    tickets.push(newTicket);
    localStorage.setItem('tickets', JSON.stringify(tickets));

    renderTickets();
    document.getElementById('ticketForm').reset();
    new bootstrap.Modal(document.getElementById('createTicketModal')).hide(); 
});

renderTickets();