<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}


// Obtener el nombre de usuario y contadores de manos ganadas
$nombreUsuario = isset($_SESSION['NombreUsuario']) ? $_SESSION['NombreUsuario'] : 'Usuario desconocido';
// Recuperar el número de manos ganadas desde la sesión
$manosGanadasHumano = isset($_SESSION['manosGanadasHumano']) ? $_SESSION['manosGanadasHumano'] : 0;
$manosGanadasPC = isset($_SESSION['manosGanadasPC']) ? $_SESSION['manosGanadasPC'] : 0;

// Inicializar manos ganadas en 0 si aún no están en la sesión
if (!isset($_SESSION['manosGanadasHumano'])) {
    $_SESSION['manosGanadasHumano'] = 0;
}
if (!isset($_SESSION['manosGanadasPC'])) {
    $_SESSION['manosGanadasPC'] = 0;
}

// Asignar los valores de manos ganadas desde la sesión a las variables

$manosGanadasHumano = $_SESSION['manosGanadasHumano'];
$manosGanadasPC = $_SESSION['manosGanadasPC'];

/* Depurar el contenido de la sesión
echo '<pre>';
print_r($_SESSION);
echo '</pre>';*/

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
    <p><a href='vistaPartida.php'>Comenzar partida</a></p>
    <a href="cerrarSesion.php">Cerrar Sesión</a>
    <hr>
    <!--
    <p>Manos Ganadas - Humano: <?php //echo $manosGanadasHumano; ?></p>
    <p>Manos Ganadas - PC: <?php //echo $manosGanadasPC; ?></p>
    -->
</body>
</html>
