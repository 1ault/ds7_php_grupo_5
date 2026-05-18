CREATE DATABASE rh_system;
USE rh_system;

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('aspirante','rh') DEFAULT 'aspirante' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE aspirantes (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    cedula_pasaporte VARCHAR(30) NOT NULL,

    nombre VARCHAR(100) NOT NULL,

    apellido VARCHAR(100) NOT NULL,

    estado_civil VARCHAR(50),

    genero VARCHAR(20) NOT NULL,

    tipo_sangre VARCHAR(10),

    fecha_nacimiento DATE NOT NULL,

    nacionalidad VARCHAR(50) NOT NULL,

    telefono VARCHAR(20) NOT NULL,

    residencia TEXT NOT NULL,

    correo VARCHAR(100) NOT NULL,

    estado_solicitud ENUM(
        'no revisado',
        'considerado',
        'no considerado'
    ) DEFAULT 'no revisado',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id)
);
