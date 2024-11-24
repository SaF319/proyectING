<?php
require_once('Conexion.php');
require_once('JugadorCRUD.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['IDUsuario'])) {
    // Si no está logueado, redirigir al login
    header("Location: index.html");
    exit();
}

// Crear una instancia de JugadorCRUD
$jugadorCRUD = new JugadorCRUD();

// Obtener todos los jugadores
//$jugadores = $jugadorCRUD->leerJugadores();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <!-- Incluir Bootstrap para diseño responsivo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
        <h2 class="text-center">Bienvenido, Administrador</h2>

        <!-- Tabla de jugadores -->
        <h4 class="mt-4">Jugadores Registrados</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los jugadores en la tabla
                $jugadorCRUD->leerJugadores();
                ?>
            </tbody>
        </table>

        <!-- Botón para cerrar sesión -->
        <form action="cerrarSesion.php" method="post">
            <input type="submit" value="Cerrar sesión" class="btn btn-danger mt-4">
        </form>
    </div>

    <!-- Incluir script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

</html>
