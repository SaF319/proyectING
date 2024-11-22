
<?php
class Usuario
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para cambiar el nombre de usuario
    public function cambiarNombre($idUsuario, $nuevoNombre)
    {
        $query = "UPDATE Usuario SET Nombre = ? WHERE IDUsuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("si", $nuevoNombre, $idUsuario);

        if ($stmt->execute()) {
            return true; // Cambio exitoso
        } else {
            return false; // Error al actualizar
        }
    }

    // Método para obtener el nombre actual (opcional)
    public function obtenerNombre($idUsuario)
    {
        $query = "SELECT Nombre FROM Usuario WHERE IDUsuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            return $fila['Nombre'];
        } else {
            return null; // Usuario no encontrado
        }
    }

    public function cambiarContrasena($idUsuario, $nuevaContraHash) {
        $query = "UPDATE Usuario SET contra = ? WHERE IDUsuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("si", $nuevaContraHash, $idUsuario);
        return $stmt->execute();
    }
    
}
?>
