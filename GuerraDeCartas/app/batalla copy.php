<?php
    require_once('Mazo.php');
    require_once('Carta.php');
    //require_once('jugador.php');

    $mazo = new Mazo();
    $mazo->barajarMazo();

    $carta1=null;
    $carta2=null;
    $resultado=null;
    $contador= null;
    $ganador1= null;
    $ganador2= null;

//clase 13 resumen ver
session_start();

    if(!isset($_SESSION['Mazo'])){
        $_SESSION['Mazo'] = new Mazo();

        if($_SESSION['Mazo']->contarCartasMazo() == 0){
            $_SESSION['Mazo'] = new Mazo();
        }
    }

    if(isset($_POST['restart'])){
        session_destroy();
    }
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_SESSION['Mazo']->contarCartasMazo() >= 2){
        $_SESSION['Mazo']->barajarMazo();   
        $carta1=$_SESSION['Mazo']->getCartaAleatoria();
        $carta2=$_SESSION['Mazo']->getCartaAleatoria();
    
    if($carta1->getNumero() > $carta2->getNumero()){
        $resultado= "Ha ganado la Carta 1";
        $ganador1 ++;

    }else if($carta1->getNumero() < $carta2->getNumero()){
        $resultado= "Ha ganado la Carta 2";
        $ganador2 ++;

    }else{
        $resultado= "EMPATEE!!";
        $ganador1 ++;
        $ganador2 ++;
    }
} else {
    $resultado="No quedan mas cartas en el mazo";

}
    $contador= $_SESSION['Mazo']->contarCartasMazo();
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
        if($carta1){
        echo $carta1->__toString();
        }else{
        echo "<p>Carta 1</p>";
        }
        ?>
        <?php
        if($carta2 ){
        echo $carta2->__toString();
        }else{
            echo"<p>Carta 2</p>";
        }
        ?>

        <?php if($resultado):?>
            <p><?php echo  $resultado;?></p>
            <p>quedan: <?php echo $contador; ?> cartas</p>
        <?php endif; ?>

        <p><?php
        if($contador == 0){
             if ($ganador1 < $ganador2){
                echo "<p>JUGADOR 1 GANA</p>";
              /*  echo "<form method='post'>
                <button type='submit' name='restart'>Volver a Jugar</button>
                        </form>";*/
            }else{
                echo "<p>JUGADOR 2 GANA </p>";
                /*echo "<form method='post'>
                <button type='submit' name='restart'>Volver a Jugar</button>
                        </form>";*/
            }
            echo print_r($ganador1);
            echo print_r($ganador2);

        }?></p>

    <form method="post">
        <button type="submit" name="batallar">Batallar</button>
    </form>
    <form method="post">
        <button type="submit" name="restart">Volver a Jugar</button>
    </form>
        
</body>
</html>