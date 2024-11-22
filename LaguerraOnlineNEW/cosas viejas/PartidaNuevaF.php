<?php
require_once('Jugador.php');
require_once('Carta.php');
require_once('Mazo.php');
require_once('./Conexion.php');

class Partida {
    private Jugador $jugadorHumano;
    private Jugador $jugadorPC;
    private int $manosGanadasHumano = 0;
    private int $manosGanadasPC = 0;    
    private int $rondaActual;
    private int $manoActual;
    private ?Carta $cartaHumanoActual;  // Con esta variable imprimimos en el tablero
    private ?Carta $cartaPCActual;      // Con esta variable imprimimos en el tablero
    private int $idPartida;  // ID de la partida en la base de datos

    public function __construct() {
        session_start();
    
         // Comprobar si existe una partida activa en la sesión
        if (!isset($_SESSION['idPartida'])) {
            $this->guardarPartida(); // Guarda la partida en la base de datos
        } else {
            $this->idPartida = $_SESSION['idPartida'];
        }

        // Asegurarse de que las variables de sesión estén definidas
        if (!isset($_SESSION['manosGanadasHumano'])) {
            $_SESSION['manosGanadasHumano'] = 0;
        }
        if (!isset($_SESSION['manosGanadasPC'])) {
            $_SESSION['manosGanadasPC'] = 0;
        }
    
        $this->manosGanadasHumano = $_SESSION['manosGanadasHumano'];
        $this->manosGanadasPC = $_SESSION['manosGanadasPC'];
    
        // Usar el usuario autenticado de la sesión como jugador humano
        if (!isset($_SESSION['IDUsuario'])) {
            throw new Exception("Usuario no autenticado. Redirigir a la página de inicio de sesión.");
        }
    
        if (!isset($_SESSION['jugadorHumano'])) {
            // ID del usuario actual de la sesión para el jugador humano
            $idJugadorHumano = $_SESSION['IDUsuario']; 
            // ID fijo para el jugador PC en la base de datos
            $idJugadorPC = 2;  
    
            // Crear los objetos Jugador para el humano y la PC
            $this->jugadorHumano = new Jugador('Jugador Humano', 3, $idJugadorHumano);
            $this->jugadorPC = new Jugador('PC', 3, $idJugadorPC);
    
            // Guardar los jugadores en la sesión
            $_SESSION['jugadorHumano'] = $this->jugadorHumano;
            $_SESSION['jugadorPC'] = $this->jugadorPC;
    
            // Inicializar ronda y mano actual
            $this->rondaActual = 1;
            $this->manoActual = 1;
            $_SESSION['rondaActual'] = $this->rondaActual;
            $_SESSION['manoActual'] = $this->manoActual;
        } else {
            // Recuperar los jugadores y la ronda desde la sesión si ya están inicializados
            $this->jugadorHumano = $_SESSION['jugadorHumano'];
            $this->jugadorPC = $_SESSION['jugadorPC'];
            $this->rondaActual = $_SESSION['rondaActual'];
            $this->manoActual = $_SESSION['manoActual'];
        }
    
        $this->cartaHumanoActual = null;  // Inicialización
        $this->cartaPCActual = null;      // Inicialización
    }
    
    private function guardarPartida(): int {
        $conexion = Conexion::getInstancia()->getConexion();
        $hora = date("H:i:s");
        $fecha = date("Y-m-d");
        $estado = 'suspendido';
    
        // Query SQL para insertar una nueva partida
        $sql = "INSERT INTO Partida (hora, fecha, estado) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $hora, $fecha, $estado);
    
        // Ejecutar el statement
        if ($stmt->execute()) {
            // Obtener el ID generado automáticamente
            $this->idPartida = $stmt->insert_id;
    
            // Guardar el ID en la sesión
            $_SESSION['idPartida'] = $this->idPartida;
    
            // Llamar a la función para insertar en la tabla `Juegan` u otra acción relacionada
            $this->insertarJuegan();
    
            // Devolver el ID de la partida
            $stmt->close();
            return $this->idPartida;
        } else {
            // Manejo de errores
            $error = "Error al guardar la partida: " . $stmt->error;
            $stmt->close();
            die($error);
        }
    }
    
    public function insertarJuegan() {
        // Declarar la variable $count
        $count = 0;
    
        // Verificar si la sesión tiene los datos necesarios
        if (!isset($_SESSION['idPartida'], $_SESSION['IDUsuario'])) {
            die("Error: Datos de partida o usuario no encontrados en la sesión.");
        }
    
        $idPartida = $_SESSION['idPartida'];
        $idJugadorHumano = $_SESSION['IDUsuario'];
    
        // Conectar a la base de datos
        $conexion = Conexion::getInstancia()->getConexion();
        if (!$conexion) {
            die("Error al conectar a la base de datos.");
        }
    
        // Verificar que el IDUsuario existe en la tabla Usuario
        $sql = "SELECT COUNT(*) FROM Usuario WHERE IDUsuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idJugadorHumano);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        if ($count == 0) {
            die("Error: El IDUsuario no existe en la tabla Usuario.");
        }
        $stmt->close();
    
        // Verificar que el IDPartida existe en la tabla Partida
        $sql = "SELECT COUNT(*) FROM Partida WHERE IDPartida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idPartida);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        if ($count == 0) {
            die("Error: El IDPartida no existe en la tabla Partida.");
        }
        $stmt->close();
    
        // Verificar si el jugador humano ya está en la partida (duplicados de IDUsuario e IDPartida)
        $sql = "SELECT COUNT(*) FROM Juegan WHERE IDUsuario = ? AND IDPartida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idJugadorHumano, $idPartida);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        if ($count > 0) {
            //die("Error: El jugador humano ya está en esta partida.");
            header("Location: bienvenida.php");
        }
        $stmt->close();
    
        // ID de jugador PC (siempre es el mismo jugador, no cambia)
        $idJugadorPC = 2; // El jugador PC es siempre el ID 2 o el que corresponda
    
        // Verificar si el jugador PC ya está en la partida (duplicados de IDUsuario e IDPartida para el jugador PC)
        $sql = "SELECT COUNT(*) FROM Juegan WHERE IDUsuario = ? AND IDPartida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idJugadorPC, $idPartida);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        // Si el jugador PC no está en la tabla, lo insertamos
        if ($count == 0) {
            $sql = "INSERT INTO Juegan (IDUsuario, IDPartida) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $idJugadorPC, $idPartida);
            if (!$stmt->execute()) {
                die("Error al insertar al jugador PC en la tabla Juegan: " . $stmt->error);
            }
            $stmt->close();
        }
    
        // Insertar al jugador humano en la tabla Juegan
        $sql = "INSERT INTO Juegan (IDUsuario, IDPartida) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idJugadorHumano, $idPartida);
        if (!$stmt->execute()) {
            die("Error al insertar al jugador humano en la tabla Juegan: " . $stmt->error);
        }
    
        $stmt->close();
    }
    

    public function actualizarEstadoPartida(string $nuevoEstado): void {
        $conexion = Conexion::getInstancia()->getConexion();

        $sql = "UPDATE Partida SET estado = ? WHERE idpartida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $nuevoEstado, $this->idPartida);

        if (!$stmt->execute()) {
            die("Error al actualizar el estado de la partida: " . $stmt->error);
        }

        $stmt->close();
    }

    public function finalizarPartida(): void {
        $this->actualizarEstadoPartida('finalizado');

    }

    public function batallar(): string {
        $resultado = '';
    
        // Obtener cartas aleatorias de cada jugador
        $this->cartaHumanoActual = $this->jugadorHumano->getCartaMazoAleatoria();
        $this->cartaPCActual = $this->jugadorPC->getCartaMazoAleatoria();
    
        // Comparar las cartas
        if ($this->cartaHumanoActual->getNumero() > $this->cartaPCActual->getNumero()) {
            $resultado .= $this->jugadorHumano->getNombre() . " gana la mano " . $this->manoActual . "!<br>";
            $this->manosGanadasHumano++;
            $_SESSION['manosGanadasHumano']++;
        } elseif ($this->cartaHumanoActual->getNumero() < $this->cartaPCActual->getNumero()) {
            $resultado .= $this->jugadorPC->getNombre() . " gana la mano " . $this->manoActual . "!<br>";
            $this->manosGanadasPC++;
            $_SESSION['manosGanadasPC']++;
        } else {
            $resultado .= "Es un empate en la mano " . $this->manoActual . "<br>";
        }
    
        // Incrementar el contador de manos
        $this->manoActual++;
    
        // **Asegurarse de actualizar la sesión**
        $_SESSION['manosGanadasHumano'] = $this->manosGanadasHumano;
        $_SESSION['manosGanadasPC'] = $this->manosGanadasPC;
    
        // Comprobar si se ha completado la ronda de tres manos
        if ($this->manoActual > 3) {
            if ($this->manosGanadasHumano > $this->manosGanadasPC) {
                $this->jugadorPC->reducirVidas();
                $resultado .= $this->jugadorHumano->getNombre() . " gana la ronda y le quita una vida a " . $this->jugadorPC->getNombre() . "!";
            } elseif ($this->manosGanadasPC > $this->manosGanadasHumano) {
                $this->jugadorHumano->reducirVidas();
                $resultado .= $this->jugadorPC->getNombre() . " gana la ronda y le quita una vida a " . $this->jugadorHumano->getNombre() . "!";
            } else {
                $resultado .= "La ronda termina en empate.";
            }
    
            // Reiniciar las manos para la próxima ronda
            $this->manoActual = 1;
            $this->manosGanadasHumano = 0;
            $this->manosGanadasPC = 0;
            $this->rondaActual++;
        }
    
        // Actualizar las variables de sesión al finalizar la ronda
        $_SESSION['rondaActual'] = $this->rondaActual;
        $_SESSION['manoActual'] = $this->manoActual;
        $_SESSION['manosGanadasHumano'] = $this->manosGanadasHumano;
        $_SESSION['manosGanadasPC'] = $this->manosGanadasPC;
    
        return $resultado;
    }
    
    

    // Método para verificar el estado del juego (si hay ganador)
    public function verificarEstadoJuego(): ?string {
        if ($this->jugadorHumano->getVidas() <= 0) {
            $this->finalizarPartida(); // Llamar a finalizarPartida si el jugador humano pierde todas las vidas
            $this->registrarMano('perdedor');
            return $this->jugadorPC->getNombre();
        } elseif ($this->jugadorPC->getVidas() <= 0) {
            $this->finalizarPartida(); // Llamar a finalizarPartida si la PC pierde todas las vidas
            $this->registrarMano('ganador');
            return $this->jugadorHumano->getNombre();
        }
        return null;
    }

    // Método para contar las cartas restantes de cada jugador
    public function contarCartasRestantes(): array {
        return [
            'contadorHumano' => $this->jugadorHumano->getMazo()->contarCartasMazo(),
            'contadorPC' => $this->jugadorPC->getMazo()->contarCartasMazo()
        ];
    }

    // Métodos para obtener las cartas actuales
    public function getCartaHumanoActual(): ?Carta {
        return $this->cartaHumanoActual;
    }

    public function getCartaPCActual(): ?Carta {
        return $this->cartaPCActual;
    }

    // Obtener los jugadores
    public function getJugadores(): array {
        return [$this->jugadorHumano, $this->jugadorPC];
    }
    
    

    public function registrarMano(string $resultado): void {
        // Obtener la conexión a la base de datos
        $conexion = Conexion::getInstancia()->getConexion();
    
        // Verificar si la sesión está iniciada
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $idPartida=0;
        // Verificar si el ID de la partida está en la sesión, si no, consultar desde la base de datos
        if (!isset($_SESSION['idPartida'])) {
            // Consultar el ID de la última partida insertada
            $sql = "SELECT MAX(IDPartida) AS ultimaPartida FROM Partida";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($idPartida);
            $stmt->fetch();
            $stmt->close();
    
            if (!$idPartida) {
                die("Error: No se ha encontrado ninguna partida registrada.");
            }
    
            // Guardar el ID de la última partida en la sesión
            $_SESSION['idPartida'] = $idPartida;
        } else {
            $idPartida = $_SESSION['idPartida'];
        }
        $count = 0;
        // Verificar si ya existen manos registradas para la partida
        $sql = "SELECT COUNT(*) FROM Mano WHERE IDPartida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idPartida);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        /*if ($count > 0) {
            die("Error: Ya se ha registrado una mano para esta partida.");
        }*/
    
        // Obtener los IDs de los jugadores
        $idJugadorHumano = $_SESSION['IDUsuario']; // ID del jugador humano desde la sesión
        $idJugadorPC = 2; // ID fijo para el jugador PC
    
        // Insertar las manos
        $sql = "INSERT INTO Mano (IDUsuario, IDPartida, Resultado) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
    
        $resultadoHumano = ($resultado === 'ganador') ? 'ganador' : 'perdedor';
        $resultadoPC = ($resultado === 'ganador') ? 'perdedor' : 'ganador';
    
        // Registrar el resultado del jugador humano
        $stmt->bind_param("iis", $idJugadorHumano, $idPartida, $resultadoHumano);
        $stmt->execute();
    
        // Registrar el resultado del jugador PC
        $stmt->bind_param("iis", $idJugadorPC, $idPartida, $resultadoPC);
        $stmt->execute();
    
        $stmt->close();
    }
    
    
    

    //intento de reiniciar partida sin perder el usuario de la session
    public function Finalizar() {
        // Guardar el ID del usuario actual antes de restablecer los datos
        $idUsuario = $_SESSION['IDUsuario'];
    
        // Reiniciar los jugadores con vidas y mazos iniciales
        $this->jugadorHumano = new Jugador('Jugador Humano', 3, $idUsuario); // Restauramos las 3 vidas y el IDUsuario
        $this->jugadorPC = new Jugador('PC', 3, 2); // ID fijo para el jugador PC con 3 vidas iniciales
    
        // Reiniciar los mazos de los jugadores a su estado original
        $this->jugadorHumano->getMazo()->reiniciarMazo();
        $this->jugadorPC->getMazo()->reiniciarMazo();
    
        // Restablecer los contadores y las variables de estado de la partida
        //$this->manosGanadasHumano = 0;
        //$this->manosGanadasPC = 0;
        $this->rondaActual = 1;
        $this->manoActual = 1;
        $this->cartaHumanoActual = null;
        $this->cartaPCActual = null;
    
        // Guardar el estado inicial de la partida en la sesión
        $_SESSION['jugadorHumano'] = $this->jugadorHumano;
        $_SESSION['jugadorPC'] = $this->jugadorPC;
        $_SESSION['rondaActual'] = $this->rondaActual;
        $_SESSION['manoActual'] = $this->manoActual;
        $_SESSION['manosGanadasHumano'] = $this->manosGanadasHumano;
        $_SESSION['manosGanadasPC'] = $this->manosGanadasPC;
    
        // Restaurar el ID del usuario en la sesión para asegurar que sigue autenticado
        $_SESSION['IDUsuario'] = $idUsuario;

        $this-> insertarJuegan();
        $this->finalizarPartida();
    }
    
    
}