<?php
    require_once('Mazo.php');
    require_once('Carta.php');
    require_once('JugadorModel.php');

    session_start();

    if (!isset($_SESSION['Jugador1'])) {
        $_SESSION['Jugador1'] = new Jugador("Jugador1", 3);  // Jugador con 3 vidas
    }

    if (!isset($_SESSION['Jugador2'])) {
        $_SESSION['Jugador2'] = new Jugador("Jugador2", 3);  // Jugador con 3 vidas
    }

    if (!isset($_SESSION['Mazo'])) {
        $_SESSION['Mazo'] = new Mazo();
    }

    // Inicializar el contador de batallas
    if (!isset($_SESSION['batallas'])) {
        $_SESSION['batallas'] = 0;
    }

    // Inicializar los contadores de victorias
    if (!isset($_SESSION['ganador1'])) {
        $_SESSION['ganador1'] = 0;
    }

    if (!isset($_SESSION['ganador2'])) {
        $_SESSION['ganador2'] = 0;
    }

    // Verificar si se presionó el botón "Volver a Jugar" y reiniciar la sesión
    if (isset($_POST['restart'])) {
        session_destroy();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // Procesar el juego cuando se presiona el botón "Batallar"
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['batallar'])) {
        // Verificar si algún jugador tiene 0 vidas y reiniciar el mazo
        if (!$_SESSION['Jugador1']->quedanVidas() || !$_SESSION['Jugador2']->quedanVidas()) {
            $_SESSION['Mazo'] = new Mazo();
            $_SESSION['Jugador1'] = new Jugador("Jugador1", 3);  // Reiniciar Jugador 1
            $_SESSION['Jugador2'] = new Jugador("Jugador2", 3);  // Reiniciar Jugador 2
            $_SESSION['batallas'] = 0;
            $_SESSION['ganador1'] = 0;
            $_SESSION['ganador2'] = 0;
            $resultado = "El mazo y los jugadores han sido reiniciados.";
        } else {
            // Si hay suficientes cartas en el mazo
            if ($_SESSION['Mazo']->contarCartasMazo() >= 2) {
                $_SESSION['Mazo']->barajarMazo();
                $carta1 = $_SESSION['Mazo']->getCartaAleatoria();
                $carta2 = $_SESSION['Mazo']->getCartaAleatoria();

                // Determinar el ganador de la batalla
                if ($carta1->getNumero() > $carta2->getNumero()) {
                    $resultado = "Ha ganado la Carta 1";
                    $_SESSION['ganador1']++;
                } else if ($carta1->getNumero() < $carta2->getNumero()) {
                    $resultado = "Ha ganado la Carta 2";
                    $_SESSION['ganador2']++;
                } else {
                    $resultado = "EMPATEE!!";
                    $_SESSION['ganador1']++;
                    $_SESSION['ganador2']++;
                }

                $_SESSION['batallas']++;

                // Cada 3 batallas, verificar quién ganó más
                if ($_SESSION['batallas'] == 3) {
                    if ($_SESSION['ganador1'] > $_SESSION['ganador2']) {
                        $_SESSION['Jugador2']->quitarVida();
                        $resultado .= " Jugador 1 ganó las últimas 3 batallas. Jugador 2 pierde una vida.";
                    } else if ($_SESSION['ganador2'] > $_SESSION['ganador1']) {
                        $_SESSION['Jugador1']->quitarVida();
                        $resultado .= " Jugador 2 ganó las últimas 3 batallas. Jugador 1 pierde una vida.";
                    } else {
                        $resultado .= " Empate en las últimas 3 batallas. Ninguno pierde vida.";
                    }

                    // Reiniciar el contador de batallas y las victorias
                    $_SESSION['batallas'] = 0;
                    $_SESSION['ganador1'] = 0;
                    $_SESSION['ganador2'] = 0;
                }

                // Verificar si alguno de los jugadores perdió todas las vidas
                if (!$_SESSION['Jugador1']->quedanVidas()) {
                    $resultado = "Jugador 2 gana, Jugador 1 se quedó sin vidas.";
                } else if (!$_SESSION['Jugador2']->quedanVidas()) {
                    $resultado = "Jugador 1 gana, Jugador 2 se quedó sin vidas.";
                }
            } else {
                $resultado = "No quedan más cartas en el mazo.";
            }
        }

        $contador = $_SESSION['Mazo']->contarCartasMazo();
    }
?>
    
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batalla</title>
</head>
<body>
<?php
        if(isset($carta1)){
        echo $carta1->__toString();
        }else{
        echo "<p>Carta 1</p>";
        }
        ?>
        <?php
        if(isset($carta2)){
        echo $carta2->__toString();
        }else{
            echo"<p>Carta 2</p>";
        }
        ?>

    <?php if (isset($resultado)) { ?>
        <p><?php echo $resultado; ?></p>
        <p>Quedan: <?php echo $contador; ?> cartas</p>
    <?php } ?>

    <p>Vidas Jugador 1: <?php echo $_SESSION['Jugador1']->getVidas(); ?></p>
    <p>Vidas Jugador 2: <?php echo $_SESSION['Jugador2']->getVidas(); ?></p>

    <?php if (!$_SESSION['Jugador1']->quedanVidas() || !$_SESSION['Jugador2']->quedanVidas()) { ?>
        <p><?php echo $resultado; ?></p>
    <?php } ?>

    <form method="post">
        <button type="submit" name="batallar">Batallar</button>
    </form>

    <form method="post">
        <button type="submit" name="restart">Volver a Jugar</button>
    </form>
</body>
</html>
