<?php
require_once 'Conexion.php';
require_once 'JugadorCRUD.php';

if (isset($_POST['nombre'], $_POST['contra'])) {
    $nombre = $_POST['nombre'];
    $contra = $_POST['contra'];

    // Crear instancia de JugadorCRUD
    $jugadorCRUD = new JugadorCRUD();

    // Llamar a la función crearJugador para registrar el nuevo jugador
    $jugadorCRUD->crearJugador($nombre, $contra);

    // Redireccionar al usuario a la página de inicio de sesión
    header("Location: index.html");
    exit();
} else {
    echo "Por favor, complete todos los campos.";
}
?>
