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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <!-- Encabezado de bienvenida -->
        <h3 class="mb-4">Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?> (ID: <?php echo $_SESSION['IDUsuario']; ?>)</h3>

        <!-- Botones de navegación -->
        <div class="mb-4">
            <a href="vistaPartida.php" class="btn btn-primary">Comenzar partida</a>
            <a href="cerrarSesion.php" class="btn btn-danger ml-2">Cerrar Sesión</a>
        </div>

        <!-- Opciones -->
        <div class="mb-4">
            <h5>Opciones:</h5>
            <a href="./cambiarNombre.php" class="btn btn-warning mb-2">Cambiar Nombre de Usuario</a>
            <br>
            <a href="./formContra.php" class="btn btn-warning">Cambiar Contraseña</a>
        </div>

        <hr>

        <!-- Últimas partidas -->
        <h5>Últimas 3 partidas:</h5>
        <table class="table table-striped">
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
                            <td>
                                <a href="detallePartida.php?idPartida=<?php echo urlencode($partida['idpartida']); ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($partida['idpartida']); ?>
                                </a>
                            </td>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
