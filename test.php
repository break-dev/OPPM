<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popovers Dinámicos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-popover {
            max-width: 200px; /* Ajusta el ancho del popover */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Imágenes con Popovers Dinámicos</h2>
    <img src="https://via.placeholder.com/150" alt="Imagen 1" class="img-thumbnail" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Contenido de la imagen 1" data-bs-html="true">
    <img src="https://via.placeholder.com/150" alt="Imagen 2" class="img-thumbnail" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Contenido de la imagen 2" data-bs-html="true">
    <img src="https://via.placeholder.com/150" alt="Imagen 3" class="img-thumbnail" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Contenido de la imagen 3" data-bs-html="true">
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Esperar a que el documento esté listo
    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionar todas las imágenes con el atributo data-bs-toggle="popover"
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            // Crear un nuevo popover para cada imagen
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true, // Permitir HTML en el contenido
                content: function () {
                    // Aquí puedes personalizar el contenido del popover
                    return '<strong>Información:</strong> ' + popoverTriggerEl.getAttribute('data-bs-content');
                }
            });
        });
    });
</script>

</body>
</html>
