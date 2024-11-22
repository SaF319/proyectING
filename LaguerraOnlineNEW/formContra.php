<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <form action="cambiarContrasena.php" method="POST">
        <label for="contraActual">Contraseña Actual:</label>
        <input type="password" id="contraActual" name="contraActual" required>
        <br>
        <label for="nuevaContra">Nueva Contraseña:</label>
        <input type="password" id="nuevaContra" name="nuevaContra" required>
        <br>
        <button type="submit">Cambiar Contraseña</button>
    </form>
</body>
</html>
