
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('addPostForm');
    const postTitle = document.getElementById('postTitle');
    const postImage = document.getElementById('postImage');
    const postContent = document.getElementById('postContent');
    const blogPosts = document.getElementById('blogPosts');

    // Función para crear una nueva tarjeta de blog
    function createPostCard(title, imageSrc, content) {
        const postCard = document.createElement('div');
        postCard.classList.add('col-md-4');
        postCard.classList.add('mb-4');
        postCard.innerHTML = `
            <div class="card">
                <img src="${imageSrc}" class="card-img-top" alt="${title}">
                <div class="card-body">
                    <h5 class="card-title">${title}</h5>
                    <p class="card-text">${content}</p>
                </div>
            </div>
        `;
        blogPosts.appendChild(postCard);
    }

    // Manejo del formulario de agregar noticia
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const title = postTitle.value;
        const content = postContent.value;

        // Cargar imagen (puedes cambiar esto para usar imágenes de un servidor o base de datos)
        const image = URL.createObjectURL(postImage.files[0]);

        // Crear una nueva tarjeta de noticia
        createPostCard(title, image, content);

        // Mostrar una alerta de éxito
        alert('¡Noticia creada exitosamente!');

        // Cerrar el modal y resetear el formulario
        $('#addPostModal').modal('hide');
        form.reset();
    });
});