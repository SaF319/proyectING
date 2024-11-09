<?php
class JugadorCRUD
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }

    /**
     * Crea un nuevo jugador en la base de datos.
     *
     * @param string $nombre El nombre del jugador.
     * @param string $contra La contraseña del jugador.
     * @return void
     */
    public function crearJugador(String $nombre, String $contra): void
    {
        // Encriptar la contraseña
        $contraEncriptada = password_hash($contra, PASSWORD_DEFAULT);
        
        // Insertar el jugador en la tabla usuario
        $sqlUsuario = "insert into usuario (nombre, contra) values (?, ?)";
        $stmtUsuario = $this->conexion->prepare($sqlUsuario);
        $stmtUsuario->bind_param('ss', $nombre, $contraEncriptada);
        
        if ($stmtUsuario->execute()) {
            // Obtener el ID del nuevo usuario
            $idUsuario = $this->conexion->insert_id;

            // Insertar el ID en la tabla jugador
            $sqlJugador = "insert into jugador (idusuario) values (?)";
            $stmtJugador = $this->conexion->prepare($sqlJugador);
            $stmtJugador->bind_param('i', $idUsuario);
            
            if ($stmtJugador->execute()) {
                echo "Jugador creado con éxito.";
            } else {
                echo "Error al crear jugador: " . $stmtJugador->error;
            }
            $stmtJugador->close();
        } else {
            echo "Error al crear usuario: " . $stmtUsuario->error;
        }
        $stmtUsuario->close();
    }

    /**
     * Lee la información de un jugador por su ID.
     *
     * @param int $idUsuario El ID del jugador.
     * @return void
     */
    public function leerJugador(int $idUsuario): void
    {
        $sql = "select u.idusuario, u.nombre from jugador j join usuario u on j.idusuario = u.idusuario where j.idusuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $jugador = $resultado->fetch_assoc();
            echo "ID: " . $jugador['idusuario'] . " - Nombre: " . $jugador['nombre'] . "<br>";
        } else {
            echo "Jugador no encontrado.";
        }
        $stmt->close();
    }

    public function leerJugadores(): void 
    {
        $sql = "select u.idusuario, u.nombre from jugador j join usuario u on j.idusuario = u.idusuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            while ($jugador = $resultado->fetch_assoc()) {
                echo "ID: " . $jugador['idusuario'] . " - Nombre: " . $jugador['nombre'] . "<br>";
            }
        } else {
            echo "No hay jugadores cargados en el sistema.";
        }
    }

    public function actualizarJugador(int $idUsuario, String $nombre, String $contra = null): void
    {
        $sqlUsuario = "update usuario set nombre = ? where idusuario = ?";
        $stmtUsuario = $this->conexion->prepare($sqlUsuario);
        $stmtUsuario->bind_param('si', $nombre, $idUsuario);
        
        if ($stmtUsuario->execute()) {
            echo "Nombre actualizado con éxito.<br>";
        } else {
            echo "Error al actualizar el nombre: " . $stmtUsuario->error;
        }
        
        if ($contra !== null) {
            $contraEncriptada = password_hash($contra, PASSWORD_DEFAULT);
            $sqlContra = "update usuario set contra = ? where idusuario = ?";
            $stmtContra = $this->conexion->prepare($sqlContra);
            $stmtContra->bind_param('si', $contraEncriptada, $idUsuario);
            
            if ($stmtContra->execute()) {
                echo "Contraseña actualizada con éxito.";
            } else {
                echo "Error al actualizar la contraseña: " . $stmtContra->error;
            }
            $stmtContra->close();
        }
        $stmtUsuario->close();
    }

    public function eliminarJugador(int $idUsuario): void
    {
        $sqlJugador = "delete from jugador where idusuario = ?";
        $stmtJugador = $this->conexion->prepare($sqlJugador);
        $stmtJugador->bind_param('i', $idUsuario);
        
        if ($stmtJugador->execute()) {
            $sqlUsuario = "delete from usuario where idusuario = ?";
            $stmtUsuario = $this->conexion->prepare($sqlUsuario);
            $stmtUsuario->bind_param('i', $idUsuario);
            
            if ($stmtUsuario->execute()) {
                echo "Jugador eliminado con éxito.";
            } else {
                echo "Error al eliminar usuario: " . $stmtUsuario->error;
            }
            $stmtUsuario->close();
        } else {
            echo "Error al eliminar jugador: " . $stmtJugador->error;
        }
        $stmtJugador->close();
    }

    public function verificarContra(int $idUsuario, String $contraIngresada): bool
    {
        $existe = false;
        $sql = "select contra from usuario where idusuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $contraAlmacenada = $usuario['contra'];
            
            if (password_verify($contraIngresada, $contraAlmacenada)) {
                $existe = true; 
            }
        }
        $stmt->close();
        return $existe;
    }
}
?>
