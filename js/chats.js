
const chats = [{
    id: 0,
    name: "Juan Pérez",
    messages: [{
            sender: "Juan Pérez",
            text: "Hola, ¿cómo estás?"
        },
        {
            sender: "Tú",
            text: "¡Hola! Todo bien, ¿y tú?"
        },
        {
            sender: "Juan Pérez",
            text: "Todo bien, gracias. ¿Qué tal el día?"
        }
    ]
},
{
    id: 1,
    name: "Ana López",
    messages: [{
            sender: "Ana López",
            text: "¡Hola! ¿Nos vemos mañana?"
        },
        {
            sender: "Tú",
            text: "¡Claro! ¿A qué hora?"
        }
    ]
},
{
    id: 2,
    name: "Carlos Rodríguez",
    messages: [{
            sender: "Carlos Rodríguez",
            text: "¿Te gustaría salir este fin de semana?"
        },
        {
            sender: "Tú",
            text: "Sí, suena genial. ¿A dónde vamos?"
        }
    ]
},
{
    id: 3,
    name: "Maria García",
    messages: [{
            sender: "Maria García",
            text: "¿Puedo pedirte un favor?"
        },
        {
            sender: "Tú",
            text: "Claro, ¿qué necesitas?"
        }
    ]
}
];

let currentChatId = 0; 
let selectedFile = null; 
function loadChat(chatId) {
currentChatId = chatId;
const chat = chats[chatId];
const messagesContainer = document.getElementById('messages');
messagesContainer.innerHTML = ''; 
chat.messages.forEach(message => {
    const messageElement = document.createElement('p');
    if (message.text) {
        messageElement.innerHTML = `<strong>${message.sender}</strong>: ${message.text}`;
    } else if (message.file) {
        messageElement.innerHTML = `<strong>${message.sender}</strong>: <a href="${message.file}" target="_blank">Ver archivo</a>`;
    }
    messagesContainer.appendChild(messageElement);
});

highlightActiveChat(chatId);
}

function highlightActiveChat(chatId) {
const allChats = document.querySelectorAll('.chat-preview');
allChats.forEach(chat => {
    chat.classList.remove('active-chat');
});

const activeChat = document.querySelectorAll('.chat-preview')[chatId];
activeChat.classList.add('active-chat');
}

function handleFileSelect() {
const fileInput = document.getElementById('fileInput');
const filePreview = document.getElementById('filePreview');
const file = fileInput.files[0];

if (file) {
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            filePreview.innerHTML = `<img src="${e.target.result}" alt="Vista previa" />`;
        };
        reader.readAsDataURL(file);
    } else {
        filePreview.innerHTML = `<a href="#" onclick="viewFile('${file.name}')">${file.name}</a>`;
    }

    selectedFile = file; 
}
}

function viewFile(fileName) {
alert(`Viendo el archivo: ${fileName}`);
}

function sendMessage() {
const messageText = document.getElementById('message').value;
const chat = chats[currentChatId];

if (messageText.trim() !== '' || selectedFile) {
    const newMessage = {
        sender: "Tú",
        text: messageText.trim()
    };

    if (selectedFile) {
        const fileUrl = URL.createObjectURL(selectedFile); 
        newMessage.file = fileUrl;
        selectedFile = null; 
        document.getElementById('filePreview').innerHTML = ''; 
    }

    chat.messages.push(newMessage);
    document.getElementById('message').value = '';

    loadChat(currentChatId);
}
}

loadChat(currentChatId);