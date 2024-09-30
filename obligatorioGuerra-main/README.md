# obligatorioGuerra
Juego de Cartas mutijugaror - La Guerra
# Juego de Cartas en PHP

Este proyecto implementa un juego de cartas basado en la baraja española, desarrollado en PHP con una arquitectura MVC. El objetivo del juego es simple: los jugadores compiten para obtener la carta más alta en tres repartidas.

## Requerimientos del Juego

### **Actores Principales**
- **Jugadores:** Participan en el juego, compitiendo para obtener la carta más alta.
- **Crupier:** Se encarga de barajar y repartir las cartas a los jugadores.
- **Administrador:** Gestiona el estado del juego, incluyendo la reinicialización y la visualización de estadísticas.

### **Clases Principales**
- **Mazo:** Representa el conjunto de cartas disponibles para repartir. Se encarga de barajar y distribuir las cartas entre los jugadores.
- **Jugador:** Recibe las cartas y compite en el juego.
- **Crupier:** Gestiona el mazo, baraja las cartas y las reparte a los jugadores.
- **Administrador:** Controla el flujo del juego y realiza tareas administrativas.

### **Objetivo del Juego**
Cada jugador recibe tres cartas, y gana el que tenga la carta de mayor valor. El juego utiliza la baraja española tradicional, que consta de 40 o 48 cartas (según la versión).

### **Características Principales**
- **Arquitectura MVC:** Separa la lógica del negocio (Modelo), la interfaz de usuario (Vista) y el control de flujo (Controlador).
- **Persistencia de Datos:** Utiliza una base de datos para almacenar el estado del juego y la información de los jugadores.
- **Pruebas:** Incluye pruebas unitarias y de integración para asegurar la funcionalidad del juego.
- **Gestión Ágil:** Se emplean metodologías ágiles para el desarrollo, utilizando GitHub para el control de versiones y Slack para la comunicación del equipo.

## Estructura del Repositorio

- **`/app`** - Código fuente del juego, siguiendo el patrón MVC.
- **`/tests`** - Pruebas que verifican la correcta funcionalidad del sistema.
- **`/docs`** - Documentación del diseño y la arquitectura del juego.

## Cómo Empezar

1. Clona el repositorio: `git clone https://github.com/tu-usuario/juego-cartas-php.git`
2. Configura la base de datos y ejecuta las migraciones.
3. Inicia el servidor local y accede al juego a través del navegador.

## Contribuciones

Las contribuciones son bienvenidas. Consulta la guía de contribución en el repositorio y asegúrate de que todas las pruebas pasen antes de enviar un pull request.
