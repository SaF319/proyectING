<?php

require_once 'juego/newPartida.php';


//session_start(); // Asegúrate de iniciar la sesión

/* Verifica si el ID y nombre están almacenados en la sesión
if (isset($_SESSION['IDUsuario']) && isset($_SESSION['NombreUsuario'])) {
    echo "ID Usuario: " . $_SESSION['IDUsuario'];
    echo "Nombre Usuario: " . $_SESSION['NombreUsuario'];
} else {
    echo "No se ha iniciado sesión correctamente.";
}
    */


// Inicializa la partida solo si no está ya en la sesión
if (!isset($_SESSION['partida'])) {
    $_SESSION['partida'] = new Partida();
}
$partida = $_SESSION['partida'];

// Si se pulsa el botón de "Batallar", realiza la batalla
$resultadoBatalla = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batallar'])) {
    $resultadoBatalla = $partida->batallar();
}

// Verifica el estado del juego después de la batalla
$ganador = $partida->verificarEstadoJuego();
if ($ganador) {
    $resultadoBatalla .= "<br><strong>¡El ganador del juego es: $ganador!</strong>";
}

// Obtiene las cartas actuales de cada jugador
$cartaHumanoActual = $partida->getCartaHumanoActual();
$cartaPCActual = $partida->getCartaPCActual();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Partida de Cartas</title>
</head>
<body>
    <h1 style="text-align: center;">Partida de Cartas</h1>
    
<!-- Aquí empieza el contenedor de las cartas -->
<div style="display: flex; justify-content: space-around; align-items: center;">
    <div style="text-align: center;">
    <h2>Jugador Humano</h2>
    <p>Nombre: <?php echo isset($_SESSION['NombreUsuario']) ? $_SESSION['NombreUsuario'] : "Desconocido"; ?></p>
    <p>Vidas: <?php echo $partida->getJugadores()[0]->getVidas(); ?></p>
    <p>Carta Actual: <?php echo $cartaHumanoActual ? $cartaHumanoActual->getNumero() : "N/A"; ?></p>

    <?php if ($cartaHumanoActual): ?>
        <p><img src="<?php echo $cartaHumanoActual->getImagen(); ?>" alt="Carta Humano" width="150"></p>
    <?php endif; ?>

    </div>

    <div style="text-align: center;">
    <h2>Jugador PC</h2>
        <p>Vidas: <?php echo $partida->getJugadores()[1]->getVidas(); ?></p>
        <p>Carta Actual: <?php echo $cartaPCActual ? $cartaPCActual->getNumero() : "N/A"; ?></p>

        <?php if ($cartaPCActual): ?>
            <img src="<?php echo $cartaPCActual->getImagen(); ?>" alt="Carta PC" width="150">
        <?php endif; ?>
    </div>
</div>

    <!-- Resultado de la batalla -->
<div style="display: flex; justify-content: space-evenly; align-items: center; gap: 20px;">
    <div style="text-align: center;">
        <?php if ($resultadoBatalla): ?>
            <div>
                <h3>Resultado:</h3>
                <p><?php echo $resultadoBatalla; ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center;">
        <!-- Botón para realizar la batalla -->
        <form method="post">
            <button type="submit" name="batallar">Batallar</button>
        </form>
        <br>
        <a href="cerrarSesion.php">Cerrar Sesión</a>
    </div>

    
    <div style="text-align: center;">
        <!-- Estado actual de la partida -->
        <h3>Estado de la Partida</h3>
        <p>Ronda Actual: <?php echo isset($_SESSION['rondaActual']) ? $_SESSION['rondaActual'] : '0'; ?></p>
        
        <!-- Verificar si las claves existen antes de mostrarlas -->
        <p>Manos Ganadas - Humano: <?php echo isset($_SESSION['manosGanadasHumano']) ? $_SESSION['manosGanadasHumano'] : '0'; ?></p>
        <p>Manos Ganadas - PC: <?php echo isset($_SESSION['manosGanadasPC']) ? $_SESSION['manosGanadasPC'] : '0'; ?></p>
    </div>
</div>

    </div>
</div>

</body>
</html>
