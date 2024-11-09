CREATE DATABASE ProyectBD;
USE ProyectBD;
CREATE TABLE Carta(
    IDCarta VARCHAR(50) PRIMARY KEY,
    Palo varchar(20),
    Numero int,
    Imagen varchar(255)
);
CREATE TABLE Usuario(
    IDUsuario INT PRIMARY KEY,
    Nombre VARCHAR(50)
);

CREATE TABLE Administrador(
    IDUsuario INT PRIMARY KEY,
    FOREIGN KEY (IDUsuario) REFERENCES Usuario(IDUsuario)
);

CREATE TABLE Jugador(
    IDUsuario INT PRIMARY KEY,
    FOREIGN KEY (IDUsuario) REFERENCES Usuario(IDUsuario)
);

CREATE TABLE Partida(
    IDPartida int PRIMARY KEY,
    hora TIME,
    fecha DATE,
    estado ENUM('suspendido', 'finalizado')
);
CREATE TABLE Mano(
    IDMano int PRIMARY KEY,
    IDUsuario int,
    IDPartida int,
    FOREIGN KEY (IDUsuario) REFERENCES Usuario(IDUsuario),
    FOREIGN KEY (IDPartida) REFERENCES Partida(IDPartida)
);
CREATE TABLE Juegan(
    IDUsuario int,
    IDPartida int,
    PRIMARY KEY (IDUsuario, IDPartida),
    FOREIGN KEY (IDUsuario) REFERENCES Usuario(IDUsuario),
    FOREIGN KEY (IDPartida) REFERENCES Partida(IDPartida)
);
CREATE TABLE Compone(
    IDMano INT,
    IDCarta VARCHAR(50),
    PRIMARY KEY (IDMano, IDCarta),
    FOREIGN KEY (IDMano) REFERENCES Mano(IDMano),
    FOREIGN KEY (IDCarta) REFERENCES Carta(IDCarta)
);