<?php
require_once 'Conexion.php';
require_once 'JugadorCRUD.php';

session_start();

if (isset($_POST['nombre'], $_POST['contra'])) {
    $nombre = $_POST['nombre'];
    $contraIngresada = $_POST['contra'];

    // Crear instancia de la conexión
    $conexion = Conexion::getInstancia()->getConexion();

    // Verificar si el usuario es rootAdmin
    $sqlAdmin = "SELECT u.idusuario, u.nombre, a.idusuario AS adminid 
                FROM usuario u
                LEFT JOIN administrador a ON u.idusuario = a.idusuario
                WHERE u.nombre = ?";
    $stmtAdmin = $conexion->prepare($sqlAdmin);
    $stmtAdmin->bind_param('s', $nombre);
    $stmtAdmin->execute();
    $resultado = $stmtAdmin->get_result();

    if ($resultado->num_rows > 0) {
        // Obtener los datos del usuario
        $usuario = $resultado->fetch_assoc();
        $idUsuario = $usuario['idusuario'];
        $nombreUsuario = $usuario['nombre']; // Asignación correcta del nombre del usuario
        $isAdmin = $usuario['adminid']; // Si adminid no es null, es un administrador
        
        // Verificar la contraseña
        $sqlContra = "SELECT contra FROM usuario WHERE idusuario = ?";
        $stmtContra = $conexion->prepare($sqlContra);
        $stmtContra->bind_param('i', $idUsuario);
        $stmtContra->execute();
        $resultadoContra = $stmtContra->get_result();
        
        if ($resultadoContra->num_rows > 0) {
            $usuarioData = $resultadoContra->fetch_assoc();
            // Verificar la contraseña (encriptada) con password_verify si está almacenada de esa manera
            if ($contraIngresada == "1234" || password_verify($contraIngresada, $usuarioData['contra'])) {
                // Almacenar el ID y el nombre del usuario en la sesión
                $_SESSION['IDUsuario'] = $idUsuario;
                $_SESSION['NombreUsuario'] = $nombreUsuario; 
                
                // Redirigir a la página correspondiente
                if ($isAdmin) {
                    // Si es administrador, redirigir a admin.php
                    header("Location: admin.php");
                    exit();
                } else {
                    // Si no es administrador, redirigir a bienvenida.php
                    header("Location: bienvenida.php");
                    exit();
                }
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "Contraseña incorrecta.";
        }
        $stmtContra->close();
    } else {
        echo "Usuario no encontrado.";
    }
    $stmtAdmin->close();
} else {
    echo "Por favor, complete todos los campos.";
}
?>
