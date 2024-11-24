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
        // Verificar si el nombre ya existe
        $sqlVerificarNombre = "SELECT COUNT(*) AS count FROM usuario WHERE nombre = ?";
        $stmtVerificar = $this->conexion->prepare($sqlVerificarNombre);
        $stmtVerificar->bind_param('s', $nombre);
        $stmtVerificar->execute();
        $resultado = $stmtVerificar->get_result();
        $data = $resultado->fetch_assoc();
        
        if ($data['count'] > 0) {
            // El nombre ya existe, mostrar mensaje de error
            echo "<script>alert('El nombre de jugador ya está registrado. Por favor, elige otro nombre.');</script>";
            $stmtVerificar->close();
            return; // Salir del método si el nombre ya está en uso
        }

        // Encriptar la contraseña
        $contraEncriptada = password_hash($contra, PASSWORD_DEFAULT);
        
        // Insertar el jugador en la tabla usuario
        $sqlUsuario = "INSERT INTO usuario (nombre, contra) VALUES (?, ?)";
        $stmtUsuario = $this->conexion->prepare($sqlUsuario);
        $stmtUsuario->bind_param('ss', $nombre, $contraEncriptada);
        
        if ($stmtUsuario->execute()) {
            // Obtener el ID del nuevo usuario
            $idUsuario = $this->conexion->insert_id;

            // Insertar el ID en la tabla jugador
            $sqlJugador = "INSERT INTO jugador (idusuario) VALUES (?)";
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
        $sql = "SELECT u.idusuario, u.nombre FROM jugador j JOIN usuario u ON j.idusuario = u.idusuario WHERE j.idusuario = ?";
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

    /**
     * Lee todos los jugadores.
     *
     * @return void
     */
    public function leerJugadores(): void 
    {
        $sql = "SELECT u.idusuario, u.nombre FROM jugador j JOIN usuario u ON j.idusuario = u.idusuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        if ($resultado->num_rows > 0) {
            // Recorre los resultados y genera las filas de la tabla
            while ($jugador = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>" . $jugador['idusuario'] . "</td>
                        <td>" . $jugador['nombre'] . "</td>
                        <td>
                            <!-- Agregar botones u opciones aquí para acciones como editar o eliminar -->
                            <a href='editarJugador.php?id=" . $jugador['idusuario'] . "'>Editar</a> |
                            <a href='eliminarJugador.php?id=" . $jugador['idusuario'] . "'>Eliminar</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay jugadores cargados en el sistema.</td></tr>";
        }
    }
    

    /**
     * Actualiza la información de un jugador (nombre y/o contraseña).
     *
     * @param int $idUsuario El ID del jugador.
     * @param string $nombre El nuevo nombre del jugador.
     * @param string|null $contra La nueva contraseña del jugador.
     * @return void
     */
    public function actualizarJugador(int $idUsuario, String $nombre, String $contra = null): void
    {
        $sqlUsuario = "UPDATE usuario SET nombre = ? WHERE idusuario = ?";
        $stmtUsuario = $this->conexion->prepare($sqlUsuario);
        $stmtUsuario->bind_param('si', $nombre, $idUsuario);
        
        if ($stmtUsuario->execute()) {
            echo "Nombre actualizado con éxito.<br>";
        } else {
            echo "Error al actualizar el nombre: " . $stmtUsuario->error;
        }
        
        if ($contra !== null) {
            $contraEncriptada = password_hash($contra, PASSWORD_DEFAULT);
            $sqlContra = "UPDATE usuario SET contra = ? WHERE idusuario = ?";
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

    /**
     * Elimina un jugador de la base de datos.
     *
     * @param int $idUsuario El ID del jugador a eliminar.
     * @return void
     */
    public function eliminarJugador(int $idUsuario): void
    {
        $sqlJugador = "DELETE FROM jugador WHERE idusuario = ?";
        $stmtJugador = $this->conexion->prepare($sqlJugador);
        $stmtJugador->bind_param('i', $idUsuario);
        
        if ($stmtJugador->execute()) {
            $sqlUsuario = "DELETE FROM usuario WHERE idusuario = ?";
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

    ///verifica si nombre ya existe en la base de datos para no repetir nombres de usuarios
        public function verificarNombreExistente(String $nombre): bool
    {
        // Consultar si el nombre ya existe en la base de datos
        $sql = "SELECT COUNT(*) AS count FROM usuario WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $data = $resultado->fetch_assoc();

        return $data['count'] > 0; // Retorna true si el nombre ya existe, false si no
    }

            //
    /**
     * Verifica si la contraseña ingresada coincide con la almacenada.
     *
     * @param int $idUsuario El ID del usuario.
     * @param string $contraIngresada La contraseña ingresada por el usuario.
     * @return bool Retorna true si la contraseña es correcta, false en caso contrario.
     */
    public function verificarContra(int $idUsuario, String $contraIngresada): bool
    {
        $existe = false;
        $sql = "SELECT contra FROM usuario WHERE idusuario = ?";
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
