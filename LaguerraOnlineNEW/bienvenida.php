<?php
session_start();
require_once 'conexion.php'; // Archivo para conectar a la base de datos
require_once 'consultaPartida.php'; // Clase consultaPartida

// Verificar si el usuario está autenticado
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}

// Obtener el nombre de usuario
$nombreUsuario = isset($_SESSION['NombreUsuario']) ? $_SESSION['NombreUsuario'] : 'Usuario desconocido';

// Crear conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "ProyectBD");
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Crear una instancia de la clase consultaPartida
$consulta = new consultaPartida($conexion);

// Obtener el ID del usuario desde la sesión
$idUsuario = $_SESSION['IDUsuario'];

// Obtener las últimas 3 partidas del usuario
$ultimasPartidas = $consulta->obtenerUltimasPartidas($idUsuario); // Pasamos el IDUsuario aquí

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
</head>
<body>
    <h3>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?> (ID: <?php echo $_SESSION['IDUsuario']; ?>)</h3>
    <button onclick="window.location.href='vistaPartida.php'">Comenzar partida</button>
    <br>
    <br>
    <button onclick="window.location.href='cerrarSesion.php'">Cerrar Sesión</button>
    <hr>
    <h3>Opciones:</h3>
    <button onclick="window.location.href='./cambiarNombre.php'">Cambiar Nombre de Usuario</button>
    <br>
    <br>
    <button onclick="window.location.href='./formContra.php'">Cambiar Contraseña</button>
    <hr>
    <h3>Últimas 3 partidas:</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID Partida</th>
                <th>Hora</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ultimasPartidas)): ?>
                <?php foreach ($ultimasPartidas as $partida): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($partida['idpartida']); ?></td>
                        <td><?php echo htmlspecialchars($partida['hora']); ?></td>
                        <td><?php echo htmlspecialchars($partida['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($partida['estado']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No se encontraron partidas recientes.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
