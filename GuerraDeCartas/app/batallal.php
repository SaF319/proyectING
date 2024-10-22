<?php
require_once('Mazo.php');
require_once('Carta.php');
require_once('JugadorModel.php');

session_start();

if (!isset($_SESSION['Jugador1'])) {
    $_SESSION['Jugador1'] = new Jugador("Jugador1", 3); //Inicia Jugador1 con 3 vidas
}

if (!isset($_SESSION['Jugador2'])) {
    $_SESSION['Jugador2'] = new Jugador("Jugador2", 3); //Inicia Jugador2 con 3 vidas
}

if (!isset($_SESSION['Mazo'])) {
    $_SESSION['Mazo'] = new Mazo();
}

if (!isset($_SESSION['Batalla'])) {
    $_SESSION['Batalla'] = 0;
}

if (!isset($_SESSION['Winner1'])) {
    $_SESSION['Winner1'] = 0;
}

if (!isset($_SESSION['Winner2'])) {
    $_SESSION['Winner2'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Batallar'])){
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La guerra</title>
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
<p>Vidas Jugador 1: <?php echo $_SESSION['Jugador1']->getVidas(); ?></p>
    <p>Vidas Jugador 2: <?php echo $_SESSION['Jugador2']->getVidas(); ?></p>

 <form method="post">
        <button type="submit" name="Batallar">Batallar</button>
    </form>

    <form method="post">
        <button type="submit" name="reiniciar">Volver a Jugar</button>
    </form>

</body>
</html>