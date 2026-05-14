-- mysql -u root -p -D empresa -e "SELECT * FROM usuario;"


SHOW DATABASES;

DROP DATABASE empresa;

CREATE DATABASE empresa;

USE empresa;

CREATE TABLE usuario 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    indexing_email VARCHAR(255) NOT NULL
);


CREATE TABLE usuario_status
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(255) NOT NULL,
    active VARCHAR(255) NOT NULL,
    last_activity VARCHAR(255) NOT NULL
);

CREATE TABLE usuario_info 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    fecha_nacimiento VARCHAR(100) NOT NULL,
    genero VARCHAR(20),
    nacionalidad VARCHAR(100),
    residencia VARCHAR(255),
    telefono VARCHAR(50)
);

CREATE TABLE servicio 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    precio BIGINT NOT NULL
);

CREATE TABLE image 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name_original VARCHAR(255) NOT NULL, 
    name_extension VARCHAR(255) NOT NULL,
    name_hash VARCHAR(255) NOT NULL,
    name_path VARCHAR(255) NOT NULL
);

CREATE TABLE empresa 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    telefono VARCHAR(255) NOT NULL,
    ruc VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL
);

CREATE TABLE formulario_de_compra 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    fecha_de_compra VARCHAR(100) NOT NULL,
    total_a_pagar BIGINT NOT NULL
);

-- Relacion

CREATE TABLE relacion_servicio_image
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    id_servicio BIGINT NOT NULL,
    id_image BIGINT NOT NULL,

    UNIQUE (id_servicio, id_image),

    FOREIGN KEY (id_servicio) REFERENCES servicio(id),
    FOREIGN KEY (id_image) REFERENCES image(id)
);


CREATE TABLE relacion_empresa_servicio 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    id_empresa INT NOT NULL,
    id_servicio BIGINT NOT NULL,

    UNIQUE (id_empresa, id_servicio),

    FOREIGN KEY (id_empresa) REFERENCES empresa(id),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id)
);

CREATE TABLE relacion_usuario_usuario_info 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    id_usuario BIGINT NOT NULL,
    id_usuario_info BIGINT NOT NULL,

    UNIQUE (id_usuario, id_usuario_info),

    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_usuario_info) REFERENCES usuario_info(id)
);


CREATE TABLE relacion_usuario_formulario_de_compra 
(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    id_usuario BIGINT NOT NULL,
    id_formulario_de_compra BIGINT NOT NULL,

    UNIQUE (id_usuario, id_formulario_de_compra),

    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_formulario_de_compra) REFERENCES formulario_de_compra(id)
);

-- Insert


INSERT INTO servicio
(
    name,
    precio
)
VALUES 
(
    'Mantenimiento de computadoras',
    2500
),
(
    'Instalacion de software', 
    1500
),
(
    'Respaldo de informacion', 
    1000
),
(
    'Limpieza interna de hardware',
    2000
),
(
    'Mantenimiento de computadoras',
    2500
),
(
    'Revision de red y conexion',
    3000
);

SHOW TABLES;
