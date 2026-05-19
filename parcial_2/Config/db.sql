DROP DATABASE IF EXISTS rh_system;

CREATE DATABASE rh_system;
USE rh_system;

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario VARCHAR(512) UNIQUE NOT NULL,
    password VARCHAR(512) NOT NULL,
    rol ENUM('aspirante','rh') DEFAULT 'aspirante' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    indexing_usuario VARCHAR(512) UNIQUE NOT NULL
);

CREATE TABLE aspirantes (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    cedula_pasaporte VARCHAR(512) NOT NULL,

    nombre VARCHAR(512) NOT NULL,

    apellido VARCHAR(512) NOT NULL,

    estado_civil VARCHAR(512) NOT NULL,

    genero VARCHAR(512) NOT NULL,

    tipo_sangre VARCHAR(512) NOT NULL,

    fecha_nacimiento DATE NOT NULL,

    nacionalidad VARCHAR(512) NOT NULL,

    telefono VARCHAR(512) NOT NULL,

    residencia VARCHAR(512) NOT NULL,

    correo VARCHAR(512) NOT NULL,

    estado_solicitud ENUM(
        'no revisado',
        'considerado',
        'no considerado'
    ) DEFAULT 'no revisado',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id)
);

CREATE TABLE login_attempts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    fingerprint VARCHAR(255) NOT NULL,
    success     TINYINT(1)  DEFAULT 0 NOT NULL,
    created_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP NOT NULL,

    INDEX idx_fingerprint (fingerprint, created_at)
);

CREATE TABLE register_attempts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    fingerprint VARCHAR(255) NOT NULL,
    success     TINYINT(1)  DEFAULT 0 NOT NULL,
    created_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP NOT NULL,

    INDEX idx_fingerprint (fingerprint, created_at)
);
