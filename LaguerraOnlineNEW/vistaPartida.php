<?php
//session_start();
require_once 'juego/newPartida.php';

/*
function mostrarCartasDelMazo($jugador) {
    $mazo = $jugador->getMazo();
    $cartas = $mazo->obtenerTodasLasCartas();

    echo "<h3>Mazo de " . $jugador->getNombre() . " (" . count($cartas) . " cartas)</h3>";
    echo "<div style='display: flex; flex-wrap: wrap; justify-content: flex-start;'>";  // contenedor flexible
    $contador = 0;
    foreach ($cartas as $carta) {
        // Mostrar hasta 6 cartas por fila
        echo "<div style='width: 16%; text-align: center; margin: 5px;'>";
        echo "<p>" . $carta->getNumero() . " de " . $carta->getPalo() . "</p>";
        echo "<img src='" . $carta->getImagen() . "' alt='Carta' width='100'>";  // ajusta el tamaño si es necesario
        echo "</div>";

        $contador++;
        if ($contador % 5 == 0) {
            echo "<div style='flex-basis: 100%;'></div>";  // salto de línea después de cada 6 cartas
        }
    }
    echo "</div>";
}
*/
// Definir el ganador de la partida (esto dependerá de tu lógica del juego)
$ganador = "";  // Aquí deberías asignar el valor correcto según la lógica de tu juego
// Suponiendo que $ganador es 'humano' o 'pc' según el caso

// Supón que ya tienes la lógica para determinar el ganador de la partida
if ($ganador === 'humano') {
    $_SESSION['manosGanadasHumano']++;
    $_SESSION['partidasGanadasHumano']++;  // Incrementa partidas ganadas para el humano
} elseif ($ganador === 'pc') {
    $_SESSION['manosGanadasPC']++;
    $_SESSION['partidasGanadasPC']++;  // Incrementa partidas ganadas para el PC
}


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
// Aquí agregar la lógica para determinar el ganador de la mano
// Simulación de ganador: puedes adaptar esto a tu lógica de juego
$ganador = rand(0, 1); // 0 para PC, 1 para Humano

if ($ganador === 1) {
    $_SESSION['manosGanadasHumano']++;
} else {
    $_SESSION['manosGanadasPC']++;
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Partida de Cartas</title>
</head>
<body>
    <h1 style="text-align: center;">Partida de Cartas</h1>

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

    <div style="display: flex; justify-content: space-evenly; align-items: center; gap: 20px;">
        <div style="text-align: center;">
            <?php if ($resultadoBatalla): ?>
                <h3>Resultado:</h3>
                <p><?php echo $resultadoBatalla; ?></p>
            <?php endif; ?>
        </div>

        <div style="text-align: center;">
            <form method="post">
                <?php if ($partida->getJugadores()[0]->getVidas() <= 0 || $partida->getJugadores()[1]->getVidas() <= 0): ?>
                    <button type="submit" name="Finalizar">Finalizar</button>
                <?php else: ?>
                    <button type="submit" name="batallar">Batallar</button>
                <?php endif; ?>
            </form>
            <br>
            <a href="cerrarSesion.php">Cerrar Sesión</a>
        </div>

        <div style="text-align: center;">
            <h3>Estado de la Partida</h3>
            <p>Ronda Actual: <?php echo $_SESSION['rondaActual'] ?? '0'; ?></p>
            <!--
            <p>Manos Ganadas - Humano: <?php // echo $_SESSION['manosGanadasHumano'] ?? '0'; ?></p>
            <p>Manos Ganadas - PC: <?php // echo $_SESSION['manosGanadasPC'] ?? '0'; ?></p>
            -->
        </div>
    </div>

    <?php /*
        mostrarCartasDelMazo($partida->getJugadores()[0]);
        mostrarCartasDelMazo($partida->getJugadores()[1]);
        */
    ?>

</body>
</html>
