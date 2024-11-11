<?php
require_once('juego/Carta.php');

$cartas = Carta::obtenerTodasLasCartas();

foreach ($cartas as $carta) {
    echo $carta; // Muestra la imagen de cada carta
}

