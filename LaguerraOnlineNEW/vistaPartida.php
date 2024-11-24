<?php
//session_start();
require_once 'juego/newPartida.php';

// Inicializar partida
if (!isset($_SESSION['partida'])) {
    $_SESSION['partida'] = new Partida();
}
$partida = $_SESSION['partida'];

// Realizar batalla si se pulsa el botón
$resultadoBatalla = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batallar'])) {
    $resultadoBatalla = $partida->batallar();
}

// Verificar ganador de la partida
$ganador = $partida->verificarEstadoJuego();
if ($ganador) {
    $resultadoBatalla .= "<br><strong>¡El ganador del juego es: $ganador!</strong>";
}

// Obtener cartas actuales de cada jugador
$cartaHumanoActual = $partida->getCartaHumanoActual();
$cartaPCActual = $partida->getCartaPCActual();

// Reiniciar partida si se pulsa "Finalizar"
if (isset($_POST['Finalizar'])) {
    $partida->Finalizar();
    unset($_SESSION['idPartida']); // Elimina el ID de la partida actual
    header("Location: bienvenida.php");
    exit();
}

// Inicializar los contadores de manos si no existen
if (!isset($_SESSION['manosGanadasHumano'])) {
    $_SESSION['manosGanadasHumano'] = 0;
}
if (!isset($_SESSION['manosGanadasPC'])) {
    $_SESSION['manosGanadasPC'] = 0;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partida de Cartas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Partida de Cartas</h1>

        <!-- Fila para las cartas y botones -->
        <div class="row justify-content-center mt-4">
            <!-- Carta Jugador Humano -->
            <div class="col-12 col-md-4 text-center">
                <h2>Jugador Humano</h2>
                <p>Nombre: <?php echo isset($_SESSION['NombreUsuario']) ? $_SESSION['NombreUsuario'] : "Desconocido"; ?></p>
                <p>Vidas: <?php echo $partida->getJugadores()[0]->getVidas(); ?></p>
                <p>Carta Actual: <?php echo $cartaHumanoActual ? $cartaHumanoActual->getNumero() : "N/A"; ?></p>
                <?php if ($cartaHumanoActual): ?>
                    <img src="<?php echo $cartaHumanoActual->getImagen(); ?>" alt="Carta Humano" class="img-fluid" style="max-width: 150px;">
                <?php endif; ?>
            </div>

            <!-- Espacio entre las cartas y los botones -->
            <div class="col-12 col-md-1 d-flex justify-content-center align-items-center">
                <!-- Espacio para alinear los botones con las cartas -->
            </div>

            <!-- Botones: Batallar y Finalizar -->
            <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-start">
                <form method="post" class="d-flex flex-column align-items-start">
                    <?php if ($partida->getJugadores()[0]->getVidas() <= 0 || $partida->getJugadores()[1]->getVidas() <= 0): ?>
                        <button type="submit" name="Finalizar" class="btn btn-danger mb-2">Finalizar</button>
                    <?php else: ?>
                        <button type="submit" name="batallar" class="btn btn-primary mb-2">Batallar</button>
                    <?php endif; ?>
                </form>
                <a href="cerrarSesion.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>

            <!-- Carta Jugador PC -->
            <div class="col-12 col-md-4 text-center">
                <h2>Jugador PC</h2>
                <p>Vidas: <?php echo $partida->getJugadores()[1]->getVidas(); ?></p>
                <p>Carta Actual: <?php echo $cartaPCActual ? $cartaPCActual->getNumero() : "N/A"; ?></p>
                <?php if ($cartaPCActual): ?>
                    <img src="<?php echo $cartaPCActual->getImagen(); ?>" alt="Carta PC" class="img-fluid" style="max-width: 150px;">
                <?php endif; ?>
            </div>
        </div>

        <!-- Resultado de la batalla -->
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-5 text-center">
                <?php if ($resultadoBatalla): ?>
                    <h3>Resultado:</h3>
                    <p><?php echo $resultadoBatalla; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estado de la partida -->
        <div class="row justify-content-center mt-4">
            <div class="col-12 text-center">
                <h3>Estado de la Partida</h3>
                <p>Ronda Actual: <?php echo $_SESSION['rondaActual'] ?? '0'; ?></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
