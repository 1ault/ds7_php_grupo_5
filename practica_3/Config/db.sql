--

SHOW DATABASES;

DROP DATABASE libros;

CREATE DATABASE libros;

USE libros;

CREATE TABLE libro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    fecha VARCHAR(255) NOT NULL,
    categoria VARCHAR(255) NOT NULL,
    img VARCHAR(400) NOT NULL
);
INSERT INTO libro
(
    nombre, 
    autor, 
    fecha, 
    categoria, 
    img
)
VALUES 
(
    'Moby Dick', 
    'Herman Melville', 
    '1851', 
    'novela', 
    '/Assets/img/moby_dick.jpg'
);

SHOW TABLES;

SELECT * FROM libro;
