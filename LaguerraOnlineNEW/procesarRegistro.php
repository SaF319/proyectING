<?php
// Incluir el archivo de conexión y la clase JugadorCRUD
require_once 'Conexion.php';
require_once 'JugadorCRUD.php';

// Inicializar una variable para mostrar el mensaje de error
$mensajeError = "";

// Verificar si se recibieron los datos del formulario
if (isset($_POST['nombre'], $_POST['contra'])) {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $contra = $_POST['contra'];

    // Crear una instancia de JugadorCRUD
    $jugadorCRUD = new JugadorCRUD();

    // Verificar si el nombre de jugador ya existe en la base de datos
    $nombreExistente = $jugadorCRUD->verificarNombreExistente($nombre);

    if ($nombreExistente) {
        // Si el nombre ya existe, mostrar el mensaje de error
        $mensajeError = "El nombre de jugador ya está registrado. Por favor, elige otro nombre.";
    } else {
        // Si el nombre no existe, proceder con el registro
        $jugadorCRUD->crearJugador($nombre, $contra);
        // Redirigir al usuario al inicio o a la página de inicio de sesión
        header("Location: index.html");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Jugador</title>
</head>
<body>
    <!-- Mostrar mensaje de error si existe -->
    <?php if (!empty($mensajeError)): ?>
        <p style="color: red;"><?php echo $mensajeError; ?></p>
    <?php endif; ?>

    <!-- Volver al formulario de registro -->
    <a href="index.html">Volver al formulario</a>
</body>
</html>
