<?php
session_start();
require_once 'conexion.php'; // Archivo para conectar a la base de datos
require_once 'Usuario.php'; // Clase Usuario

if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}

$idUsuario = $_SESSION['IDUsuario'];
$nuevaContra = $_POST['nuevaContra'];
$contraActual = $_POST['contraActual'];

// Crear conexión y la instancia de Usuario
$conexion = new mysqli("localhost", "root", "", "ProyectBD");
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$usuario = new Usuario($conexion);

// Verificar la contraseña actual
$query = "SELECT contra FROM Usuario WHERE IDUsuario = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && password_verify($contraActual, $row['contra'])) {
    // Si la contraseña actual es correcta, proceder con el cambio
    $hashNuevaContra = password_hash($nuevaContra, PASSWORD_DEFAULT);

    if ($usuario->cambiarContrasena($idUsuario, $hashNuevaContra)) {
        echo "Contraseña cambiada exitosamente. <a href='bienvenida.php'>Volver</a>";
    } else {
        echo "Error al cambiar la contraseña. <a href='bienvenida.php'>Volver</a>";
    }
} else {
    // La contraseña actual no es correcta
    echo "La contraseña actual es incorrecta. <a href='cambiarContrasena.php'>Volver</a>";
}

$conexion->close();
?>
