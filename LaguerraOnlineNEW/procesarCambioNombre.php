<?php
session_start();
require_once 'conexion.php'; // Archivo para conectar a la base de datos
require_once 'Usuario.php'; // Clase Usuario

if (!isset($_SESSION['IDUsuario'])) {
    header("Location: index.html");
    exit();
}

$idUsuario = $_SESSION['IDUsuario'];
$nuevoNombre = $_POST['nuevoNombre'];

// Crear conexión y la instancia de Usuario
$conexion = new mysqli("localhost", "root", "", "ProyectBD");
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$usuario = new Usuario($conexion);

// Verificar si el nombre ya existe
$query = "SELECT COUNT(*) as cantidad FROM Usuario WHERE Nombre = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $nuevoNombre);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['cantidad'] > 0) {
    // El nombre ya existe
    echo "El nombre de usuario ya existe. Por favor, elige otro. <a href='cambiarNombre.php'>Volver</a>";
} else {
    // Intentar cambiar el nombre
    if ($usuario->cambiarNombre($idUsuario, $nuevoNombre)) {
        $_SESSION['NombreUsuario'] = $nuevoNombre; // Actualizar el nombre en la sesión
        echo "Nombre cambiado exitosamente. <a href='bienvenida.php'>Volver</a>";
    } else {
        echo "Error al cambiar el nombre. <a href='bienvenida.php'>Volver</a>";
    }
}

$conexion->close();
?>
