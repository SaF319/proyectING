<?php
require_once('Conexion.php');
require_once('JugadorCRUD.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}

// Crear una instancia de JugadorCRUD
$conexion = Conexion::getInstancia()->getConexion();
$jugadorCRUD = new JugadorCRUD($conexion);

// Verificar si se pasó el ID en la URL
if (!isset($_GET['id'])) {
    echo "ID de jugador no especificado.";
    exit();
}

$idUsuario = intval($_GET['id']);

// Buscar información del jugador
$sql = "SELECT u.nombre FROM usuario u WHERE u.idusuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$jugador = $resultado->fetch_assoc();

if (!$jugador) {
    echo "Jugador no encontrado.";
    exit();
}

// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = $_POST['nombre'];
    $nuevaContra = $_POST['contra'] ?? null;

    // Actualizar el jugador
    $jugadorCRUD->actualizarJugador($idUsuario, $nuevoNombre, $nuevaContra);

    // Redirigir a admin.php después de la actualización
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Jugador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Actualizar Jugador</h2>

    <!-- Formulario de actualización -->
    <form method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($jugador['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="contra" class="form-label">Contraseña (opcional)</label>
            <input type="password" id="contra" name="contra" class="form-control" placeholder="Nueva contraseña">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="admin.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
