<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Cambiar Contraseña</h3>
        
        <form action="cambiarContrasena.php" method="POST">
            <div class="mb-3">
                <label for="contraActual" class="form-label">Contraseña Actual:</label>
                <input type="password" id="contraActual" name="contraActual" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nuevaContra" class="form-label">Nueva Contraseña:</label>
                <input type="password" id="nuevaContra" name="nuevaContra" class="form-control" required>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
            </div>
        </form>

        <!-- Botón Volver -->
        <div class="text-center mt-4">
            <a href="bienvenida.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
