<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['IDUsuario'])) {
    // Si no está logueado, redirigir al login
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
</head>
<body>
    <h2>Bienvenido, Administrador</h2>

    <!-- Aquí puedes agregar el contenido exclusivo para el administrador -->
    
    <form action="cerrarSesion.php" method="post">
        <input type="submit" value="Cerrar sesión">
    </form>
</body>
</html>
