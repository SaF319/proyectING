<?php
session_start();
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Nombre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Cambiar Nombre de Usuario</h3>
        
        <form action="procesarCambioNombre.php" method="POST">
            <div class="mb-3">
                <label for="nuevoNombre" class="form-label">Nuevo Nombre:</label>
                <input type="text" name="nuevoNombre" id="nuevoNombre" class="form-control" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Cambiar Nombre</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <a href="bienvenida.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
