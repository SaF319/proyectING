<?php
session_start();
require_once 'conexion.php'; // Conexión a la base de datos
require_once 'consultaPartida.php'; // Clase consultaPartida

// Verificar si el usuario está autenticado
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}

// Verificar si se proporcionó el ID de la partida
if (!isset($_GET['idPartida'])) {
    die("ID de partida no especificado.");
}

// Obtener el ID de la partida
$idPartida = intval($_GET['idPartida']);

// Crear conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "ProyectBD");
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Crear una instancia de consultaPartida
$consulta = new consultaPartida($conexion);

// Obtener los detalles de las cartas por partida
$cartasPorPartida = $consulta->obtenerCartasPorPartida($idPartida);

// Cerrar la conexión
$conexion->close();

// Verificar si hay resultados
if (empty($cartasPorPartida)) {
    die("No se encontraron cartas para esta partida.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la partida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <h3 class="mb-4">Detalle de la partida ID: <?php echo htmlspecialchars($idPartida); ?></h3>
        <a href="bienvenida.php" class="btn btn-primary mb-3">Volver al Lobby</a>
        
        <!-- Tabla de cartas -->
        <?php if (count($cartasPorPartida) > 0): ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Jugador</th>
                        <th>ID Carta</th>
                        <th>Palo</th>
                        <th>Numero</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartasPorPartida as $carta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($carta['nombreJugador']); ?></td>
                            <td><?php echo htmlspecialchars($carta['IDCarta']); ?></td>
                            <td><?php echo htmlspecialchars($carta['Palo']); ?></td>
                            <td><?php echo htmlspecialchars($carta['Numero']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($carta['Imagen']); ?>" alt="Carta" width="100"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron cartas para esta partida.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>