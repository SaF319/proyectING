<?php
require_once('Jugador.php');
require_once('Carta.php');

class Partida {
    private Jugador $jugadorHumano;
    private Jugador $jugadorPC;
    private int $manosGanadasHumano;
    private int $manosGanadasPC;
    private int $rondaActual;
    private int $manoActual;
    private ?Carta $cartaHumanoActual;  // Con esta variable imprimimos en el tablero
    private ?Carta $cartaPCActual;      // Con esta variable imprimimos en el tablero

    public function __construct() {
        session_start();

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

        $this->manosGanadasHumano = 0;
        $this->manosGanadasPC = 0;
        $this->cartaHumanoActual = null;  // Inicialización
        $this->cartaPCActual = null;      // Inicialización
    }

    public function batallar(): string {
        $resultado = '';
    
        // Obtener cartas aleatorias de cada jugador
        $this->cartaHumanoActual = $this->jugadorHumano->getCartaMazoAleatoria();
        $this->cartaPCActual = $this->jugadorPC->getCartaMazoAleatoria();
    
        // Obtener la ruta de la imagen de las cartas
        $imagenHumano = $this->cartaHumanoActual->getImagen();
        $imagenPC = $this->cartaPCActual->getImagen();
    
        // Comparar las cartas
        if ($this->cartaHumanoActual->getNumero() > $this->cartaPCActual->getNumero()) {
            $resultado .= $this->jugadorHumano->getNombre() . " gana la mano " . $this->manoActual . "!<br>";
            $this->manosGanadasHumano++;
        } elseif ($this->cartaHumanoActual->getNumero() < $this->cartaPCActual->getNumero()) {
            $resultado .= $this->jugadorPC->getNombre() . " gana la mano " . $this->manoActual . "!<br>";
            $this->manosGanadasPC++;
        } else {
            $resultado .= "Es un empate en la mano " . $this->manoActual . "<br>";
        }
    
        // Mostrar las cartas jugadas con imagen
        //$resultado .= "Carta " . $this->jugadorHumano->getNombre() . ": <img src='" . $imagenHumano . "' alt='Carta Humano'><br>";
        //$resultado .= "Carta " . $this->jugadorPC->getNombre() . ": <img src='" . $imagenPC . "' alt='Carta PC'><br>";
    
        // Incrementar el contador de manos
        $this->manoActual++;
    
        // Verificar si se han jugado las 3 manos
        if ($this->manoActual > 3) {
            // Determinar el ganador de la ronda
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
    
        // Actualizar las variables de sesión
        $_SESSION['rondaActual'] = $this->rondaActual;
        $_SESSION['manoActual'] = $this->manoActual;
        $_SESSION['manosGanadasHumano'] = $this->manosGanadasHumano;
        $_SESSION['manosGanadasPC'] = $this->manosGanadasPC;
    
        return $resultado;
    }
    

    // Método para verificar el estado del juego (si hay ganador)
    public function verificarEstadoJuego(): ?string {
        if ($this->jugadorHumano->getVidas() <= 0) {
            return $this->jugadorPC->getNombre();
        } elseif ($this->jugadorPC->getVidas() <= 0) {
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
}
