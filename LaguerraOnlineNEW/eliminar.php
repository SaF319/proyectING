<?php
require_once('Conexion.php');
require_once('JugadorCRUD.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}

// Verificar si se recibió un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de jugador no especificado.";
    exit();
}

$idUsuario = intval($_GET['id']); // Convertir a entero para mayor seguridad

try {
    // Obtener la instancia de conexión
    $conexion = Conexion::getInstancia()->getConexion();

    // Crear instancia de JugadorCRUD
    $jugadorCRUD = new JugadorCRUD($conexion);

    // Eliminar el jugador
    $jugadorCRUD->eliminarJugador($idUsuario);

    // Redirigir de vuelta al admin.php
    header("Location: admin.php");
    exit();

} catch (Exception $e) {
    echo "Error al eliminar el jugador: " . $e->getMessage();
}
