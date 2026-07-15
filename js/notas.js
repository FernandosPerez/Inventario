const addNoteButton = document.getElementById('add-note');
const saveNoteButton = document.getElementById('save-note');
const noteContent = document.getElementById('note-content');
const noteList = document.getElementById('note-list');
const noteModal = document.getElementById('noteModal');
let currentNoteId = null;

function loadNotes() {
    const notes = JSON.parse(localStorage.getItem('notes')) || [];
    noteList.innerHTML = '';
    notes.forEach((note, index) => {
        const noteElement = document.createElement('div');
        noteElement.classList.add('note');
        noteElement.classList.add('card');
        noteElement.classList.add('mb-3');
        noteElement.innerHTML = `
            <div class="card-body post-it" data-note-id="${index}">
                <h5 class="card-title" style="color:white">Nota ${index + 1}</h5>
                <p class="card-text" style="color:white">${note}</p>
                <button class="btn btn-warning edit-note">Editar</button>
                <button class="btn btn-danger delete-note">Eliminar</button>
            </div>
        `;
        noteList.appendChild(noteElement);
    });
}

function saveNote() {
    const noteText = noteContent.value.trim();
    if (noteText === '') {
        alert('La nota no puede estar vacía.');
        return;
    }

    let notes = JSON.parse(localStorage.getItem('notes')) || [];

    if (currentNoteId === null) {
        notes.push(noteText); 
    } else {
        notes[currentNoteId] = noteText; 
    }

    localStorage.setItem('notes', JSON.stringify(notes));
    loadNotes();
    $('#noteModal').modal('hide');
    noteContent.value = ''; 
    currentNoteId = null; 
}

addNoteButton.addEventListener('click', function() {
    currentNoteId = null;
    noteContent.value = ''; 
    $('#noteModal').modal('show');
});

saveNoteButton.addEventListener('click', saveNote);

noteList.addEventListener('click', function(event) {
    if (event.target.classList.contains('edit-note')) {
        currentNoteId = event.target.parentElement.getAttribute('data-note-id');
        const notes = JSON.parse(localStorage.getItem('notes')) || [];
        noteContent.value = notes[currentNoteId]; 
        $('#noteModal').modal('show'); 
    }

    if (event.target.classList.contains('delete-note')) {
        const noteId = event.target.parentElement.getAttribute('data-note-id');
        const notes = JSON.parse(localStorage.getItem('notes')) || [];
        notes.splice(noteId, 1); 
        localStorage.setItem('notes', JSON.stringify(notes));
        loadNotes();
    }
});
window.onload = loadNotes;