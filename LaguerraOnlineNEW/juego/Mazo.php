<?php

require_once('Carta.php');

class Mazo
{
    private $mazo = [];

    public function __construct()
    {
        $this->cargarMazoDesdeBD();
    }

    private function cargarMazoDesdeBD()
    {
        $conexion = new mysqli('localhost', 'root', '', 'ProyectBD');

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $sql = "SELECT IDCarta, Palo, Numero, Imagen FROM Carta";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $carta = new Carta($fila['IDCarta'], $fila['Palo'], $fila['Numero'], $fila['Imagen']);
                array_push($this->mazo, $carta);
            }
        } else {
            throw new Exception("No hay cartas en la base de datos.");
        }

        $conexion->close();
    }

    public function getMazo(): array
    {
        return $this->mazo;
    }

    public function mostrarMazo(): void
    {
        foreach ($this->mazo as $carta) {
            echo $carta;
        }
    }

    public function contarCartasMazo(): int
    {
        return count($this->mazo);
    }

    public function getCartaAleatoria(): ?Carta
    {
        if ($this->contarCartasMazo() == 0) {
            return null;
        }
        
        $indiceAleatorio = array_rand($this->mazo);
        $carta = $this->mazo[$indiceAleatorio];
        unset($this->mazo[$indiceAleatorio]);
        $this->mazo = array_values($this->mazo);
        return $carta;
    }

    public function barajarMazo(): void
    {
        shuffle($this->mazo);
    }

    public function reiniciarMazo(): void
    {
        $this->mazo = [];
        $this->cargarMazoDesdeBD();
    }
}
