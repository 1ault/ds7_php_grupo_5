-- ═══════════════════════════════════════════════════════════════
--  CineMatch — Plataforma de Recomendación de Películas/Series
--  DS7 Grupo 5 — Universidad Tecnológica de Panamá
-- ═══════════════════════════════════════════════════════════════
SET NAMES 'utf8mb4';

DROP DATABASE IF EXISTS movies_db;

CREATE DATABASE movies_db CHARACTER
SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE movies_db;

-- ── Usuarios ─────────────────────────────────────────────────────────────────
CREATE TABLE usuarios (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    usuario        VARCHAR(512) NOT NULL,
    password       VARCHAR(512) NOT NULL,
    rol            ENUM('usuario','administrador') DEFAULT 'usuario' NOT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    indexing_usuario VARCHAR(512) UNIQUE NOT NULL
);

-- ── Géneros ──────────────────────────────────────────────────────────────────
CREATE TABLE generos (
    id     INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL
);

-- ── Películas / Series ───────────────────────────────────────────────────────
CREATE TABLE peliculas (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    titulo      VARCHAR(255) NOT NULL,
    descripcion TEXT,
    tipo        ENUM('pelicula','serie') DEFAULT 'pelicula' NOT NULL,
    anio        YEAR,
    poster_url  VARCHAR(500),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- ── Relación Película ↔ Género ───────────────────────────────────────────────
CREATE TABLE pelicula_genero (
    pelicula_id INT NOT NULL,
    genero_id   INT NOT NULL,
    PRIMARY KEY (pelicula_id, genero_id),
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE,
    FOREIGN KEY (genero_id)   REFERENCES generos(id)   ON DELETE CASCADE
);

-- ── Preferencias de usuario ──────────────────────────────────────────────────
CREATE TABLE preferencias (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    genero_id  INT NOT NULL,
    UNIQUE KEY uq_pref (usuario_id, genero_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (genero_id)  REFERENCES generos(id)  ON DELETE CASCADE
);

-- ── Historial de vistas ──────────────────────────────────────────────────────
CREATE TABLE historial (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id  INT NOT NULL,
    pelicula_id INT NOT NULL,
    visto_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (usuario_id)  REFERENCES usuarios(id)  ON DELETE CASCADE,
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE
);

-- ── Calificaciones ───────────────────────────────────────────────────────────
CREATE TABLE calificaciones (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id  INT NOT NULL,
    pelicula_id INT NOT NULL,
    puntuacion  TINYINT NOT NULL CHECK (puntuacion BETWEEN 1 AND 5),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    UNIQUE KEY uq_cal (usuario_id, pelicula_id),
    FOREIGN KEY (usuario_id)  REFERENCES usuarios(id)  ON DELETE CASCADE,
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE
);

-- ── Protección fuerza bruta ──────────────────────────────────────────────────
CREATE TABLE login_attempts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    fingerprint VARCHAR(255) NOT NULL,
    success     TINYINT(1)   DEFAULT 0 NOT NULL,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP NOT NULL,
    INDEX idx_fingerprint (fingerprint, created_at)
);

CREATE TABLE register_attempts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    fingerprint VARCHAR(255) NOT NULL,
    success     TINYINT(1)   DEFAULT 0 NOT NULL,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP NOT NULL,
    INDEX idx_fingerprint (fingerprint, created_at)
);

-- ══════════════════════════════════════════════════════════════════════════════
--  Datos semilla
-- ══════════════════════════════════════════════════════════════════════════════

INSERT INTO generos (nombre) VALUES
('Acción'),('Aventura'),('Animación'),('Ciencia Ficción'),
('Comedia'),('Crimen'),('Documental'),('Drama'),
('Fantasía'),('Historia'),('Horror'),('Misterio'),
('Romance'),('Thriller'),('Western');

INSERT INTO peliculas (titulo, descripcion, tipo, anio, poster_url) VALUES
('Inception','Un ladrón que roba secretos de los sueños recibe la misión inversa: plantar una idea.','pelicula',2010,'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg'),
('Interstellar','Un equipo de exploradores viaja a través de un agujero de gusano en busca de un nuevo hogar.','pelicula',2014,'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg'),
('The Dark Knight','Batman enfrenta al Joker, un criminal que quiere sumir Gotham en el caos.','pelicula',2008,'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg'),
('Parasite','Una familia pobre se infiltra en la vida de una familia adinerada con consecuencias inesperadas.','pelicula',2019,'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg'),
('The Matrix','Un programador descubre que la realidad es una simulación controlada por máquinas.','pelicula',1999,'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg'),
('Spirited Away','Una niña queda atrapada en un mundo de espíritus y debe trabajar para liberar a sus padres.','pelicula',2001,'https://image.tmdb.org/t/p/w500/39wmItIWsg5sZMyRUHLkWBcuVCM.jpg'),
('The Godfather','La historia del poderoso clan criminal Corleone y su heredero involuntario.','pelicula',1972,'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsLeBHka9Xq0.jpg'),
('Forrest Gump','La extraordinaria vida de un hombre con baja inteligencia que logra grandes cosas.','pelicula',1994,'https://image.tmdb.org/t/p/w500/arw2vcBveWOVZr6pxd9XTd1TdQa.jpg'),
('Breaking Bad','Un profesor de química se convierte en fabricante de metanfetamina tras un diagnóstico terminal.','serie',2008,'https://image.tmdb.org/t/p/w500/ggFHVNu6YYI5L9pCfOacjizRGt.jpg'),
('Stranger Things','Un grupo de niños enfrenta fuerzas sobrenaturales en su pequeño pueblo.','serie',2016,'https://image.tmdb.org/t/p/w500/49WJfeN0moxb9IPfGn8AIqMGskD.jpg'),
('The Crown','La historia de la Reina Isabel II y la familia real británica.','serie',2016,'https://image.tmdb.org/t/p/w500/1M876KPjulVwppEpldhdc8V4o68.jpg'),
('Dark','Una historia de viajes en el tiempo que conecta a cuatro familias alemanas.','serie',2017,'https://image.tmdb.org/t/p/w500/apbrbWs8M9lyOpJYU5WXrpFbk1Z.jpg'),
('Joker','El origen del icónico villano de Batman en una ciudad corrupta y desigual.','pelicula',2019,'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg'),
('Avatar','Un marine paralítico viaja a Pandora y se une a los Na''vi en su lucha por sobrevivir.','pelicula',2009,'https://image.tmdb.org/t/p/w500/jRXYjXNq0Cs2TcJjLkki24MLp7u.jpg'),
('The Witcher','Un cazador de monstruos lucha para encontrar su lugar en un mundo corrupto.','serie',2019,'https://image.tmdb.org/t/p/w500/7VS3sJpnElnWnOmsCzEPTFR3Noe.jpg');

-- Relaciones película ↔ género
INSERT INTO pelicula_genero (pelicula_id, genero_id) VALUES
(1,4),(1,15),(1,14),  -- Inception: Sci-Fi, Thriller
(2,4),(2,8),(2,2),    -- Interstellar: Sci-Fi, Drama, Aventura
(3,1),(3,6),(3,14),   -- The Dark Knight: Acción, Crimen, Thriller
(4,8),(4,6),(4,14),   -- Parasite: Drama, Crimen, Thriller
(5,4),(5,1),(5,14),   -- The Matrix: Sci-Fi, Acción, Thriller
(6,3),(6,9),(6,2),    -- Spirited Away: Animación, Fantasía, Aventura
(7,8),(7,6),(7,14),   -- The Godfather: Drama, Crimen, Thriller
(8,8),(8,5),(8,13),   -- Forrest Gump: Drama, Comedia, Romance
(9,6),(9,8),(9,14),   -- Breaking Bad: Crimen, Drama, Thriller
(10,4),(10,14),(10,9),-- Stranger Things: Sci-Fi, Thriller, Fantasía
(11,8),(11,10),       -- The Crown: Drama, Historia
(12,4),(12,8),(12,14),-- Dark: Sci-Fi, Drama, Thriller
(13,14),(13,8),(13,6),-- Joker: Thriller, Drama, Crimen
(14,4),(14,1),(14,2), -- Avatar: Sci-Fi, Acción, Aventura
(15,9),(15,1),(15,2); -- The Witcher: Fantasía, Acción, Aventura

-- Usuario administrador por defecto (password: Admin@DS7Semestral1)
-- Hash generado con password_hash + Argon2id. Cambiar en producción.
-- Para insertar un admin manual, usar el script de registro y luego UPDATE:
-- UPDATE usuarios SET rol='administrador' WHERE id=1;
