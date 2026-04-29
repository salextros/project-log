CREATE TABLE tbl_bitacora_sala1 (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    nombre_responsable VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_salida TIME NOT NULL,
    curso VARCHAR(80) NOT NULL,
    actividad_realizar VARCHAR(255) NOT NULL,
    sala_organizada ENUM('si','no') NOT NULL,
    luces_apagadas ENUM('si','no') NOT NULL,
    computadores_apagados ENUM('si','no') NOT NULL,
    sin_problemas ENUM('si','no') NOT NULL,
    sala_cerrada ENUM('si','no') NOT NULL,
    aa_apagado ENUM('si','no') NOT NULL,
    observaciones TEXT NULL,
    creado_por INT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);