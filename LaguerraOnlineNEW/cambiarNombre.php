<?php
session_start();
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Nombre</title>
</head>
<body>
    <h3>Cambiar Nombre de Usuario</h3>
    <form action="procesarCambioNombre.php" method="POST">
        <label for="nuevoNombre">Nuevo Nombre:</label>
        <input type="text" name="nuevoNombre" id="nuevoNombre" required>
        <button type="submit">Cambiar Nombre</button>
    </form>
    <a href="bienvenida.php">Volver</a>
</body>
</html>
