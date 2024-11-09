<?php

class Carta
{
    private const PALOS = ['o', 'c', 'e', 'b'];
    private $idCarta;
    private $palo;
    private $num;
    private $imagen;

    public function __construct($idCarta, $palo = null, $num = null, $imagen = null)
    {
        if ($palo === null || $num === null || $imagen === null) {
            // Si no se proporcionan datos, cargar de la base de datos
            $conexion = new mysqli('localhost', 'tu_usuario', 'tu_contraseña', 'ProyectBD');
            
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            $sql = "SELECT Palo, Numero, Imagen FROM Carta WHERE IDCarta = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $idCarta);
            $stmt->execute();
            $stmt->bind_result($palo, $num, $imagen);

            if ($stmt->fetch()) {
                $this->idCarta = $idCarta;
                $this->palo = $palo;
                $this->num = $num;
                $this->imagen = $imagen;
            } else {
                throw new Exception("No se encontró la carta con el ID especificado.");
            }

            $stmt->close();
            $conexion->close();
        } else {
            // Si se proporcionan datos, asignarlos directamente
            $this->idCarta = $idCarta;
            $this->palo = $palo;
            $this->num = $num;
            $this->imagen = $imagen;
        }
    }

    public function getNumero()
    {
        return $this->num;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getPalo()
    {
        return $this->palo;
    }

    public function __toString()
    {
        return "<img src='" . $this->imagen . "' alt='Carta " . $this->palo . " " . $this->num . "'>";
    }

    // Método estático para obtener todas las cartas
    public static function obtenerTodasLasCartas()
    {
        $conexion = new mysqli('localhost', 'root', '', 'ProyectBD');
        
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $sql = "SELECT IDCarta, Palo, Numero, Imagen FROM Carta";
        $resultado = $conexion->query($sql);

        $cartas = [];

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $carta = new Carta($fila['IDCarta'], $fila['Palo'], $fila['Numero'], $fila['Imagen']);
                $cartas[] = $carta;
            }
        }

        $conexion->close();
        return $cartas;
    }
}
