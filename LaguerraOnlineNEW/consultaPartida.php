<?php
class consultaPartida {
    private $conexion;

    // Constructor para inicializar la conexión
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Método para obtener todas las partidas de un usuario
    public function obtenerTodasLasPartidas($idUsuario) {
        $query = "
            SELECT p.idpartida, p.hora, p.fecha, p.estado
            FROM Partida p
            INNER JOIN Juegan j ON p.idpartida = j.IDPartida
            WHERE j.IDUsuario = ?
            ORDER BY p.fecha DESC, p.hora DESC";
    
        $stmt = $this->conexion->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function obtenerCartasPorPartida($idPartida) {
        $query = "
            SELECT 
                u.Nombre AS nombreJugador,
                c.IDCarta,
                c.Palo,
                c.Numero,
                c.Imagen
            FROM 
                Compone cp
            INNER JOIN 
                Mano m ON cp.IDMano = m.IDMano
            INNER JOIN 
                Carta c ON cp.IDCarta = c.IDCarta
            INNER JOIN 
                Usuario u ON cp.IDUsuario = u.IDUsuario
            WHERE 
                m.IDPartida = ?";
    
        $stmt = $this->conexion->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("i", $idPartida);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
}
?>

