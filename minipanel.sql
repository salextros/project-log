CREATE DATABASE IF NOT EXISTS minipanel_escolar;
USE minipanel_escolar;

CREATE TABLE tbl_usuarios (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    edad VARCHAR (50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(30) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1
);

INSERT INTO tbl_usuarios (nombre_completo, usuario,edad, password, rol, activo)
VALUES
('Administrador General', 'admin','35',  '$2y$10$wHhJ1Rz7p4xM4fD9vP9M2.6mM7zQb5cQv6uM7w4l5tM2sXQ8L1v1K', 'administrador', 1);