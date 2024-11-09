<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}

// Verificar que el nombre de usuario esté en la sesión
$nombreUsuario = isset($_SESSION['NombreUsuario']) ? $_SESSION['NombreUsuario'] : 'Usuario desconocido';

// Mostrar un mensaje de bienvenida
echo "<h3>Bienvenido, " . htmlspecialchars($nombreUsuario) . " (ID: " . $_SESSION['IDUsuario'] . ")</h3>";
echo "<p>Comenzar partida</p>";
?>
<a href="cerrarSesion.php">Cerrar Sesión</a>

