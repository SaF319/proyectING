<?php

require_once('Carta.php');
require_once('jugadorModel.php');
require_once('Mazo.php');


$carta =new Carta(1,12);
echo $carta->__toString();

echo "<br>";
$carta2 =new Carta(2,6);
echo $carta2->__toString();
/*
echo "<br>";
$carta2 =new Carta(3,8);
echo $carta2->__toString();
*/

$jugador = new jugador("yo",3);
$jugador2 = new jugador("tu",3);
$jugador->quitarVida();//rewstamos vidas para entrar l if gana un jugador destruir la session

if($jugador->getVidas() > $jugador2->getVidas()){
    echo "<br><br> gano con ".$jugador->getVidas()." es ". $jugador->getNombre();

    echo "<br>jugador enemigo tiene  ".$jugador2->getVidas();
}else if($jugador->getVidas() < $jugador2->getVidas()){
    echo "<br><br> gano con ".$jugador2->getVidas()." es ".$jugador2->getNombre();

    echo "<br>jugador enemigo tiene  ".$jugador->getVidas();
}else{
    echo"<br><br> los jugadores estan iguales vidas";
}


///implementar la cantidad de vidas
?>
