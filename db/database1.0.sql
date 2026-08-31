CREATE DATABASE IF NOT EXISTS learn_viky_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE learn_viky_db;

CREATE TABLE usuarios (
  id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(120) NOT NULL,
  correo_electronico VARCHAR(150) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  perfil VARCHAR(50) NOT NULL DEFAULT 'estudiante',
  cedula VARCHAR(30) NULL,
  certificacion_diploma VARCHAR(255) NULL,
  fecha_de_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_de_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_usuarios_correo UNIQUE (correo_electronico)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE areas (
  id_area INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_area VARCHAR(100) NOT NULL,
  fecha_de_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_de_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_areas_nombre UNIQUE (nombre_area)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuario_por_area (
  id_usuario_area INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_usuario_fk INT UNSIGNED NOT NULL,
  id_area_fk INT UNSIGNED NOT NULL,
  fecha_de_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_de_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_usuario_por_area UNIQUE (id_usuario_fk, id_area_fk),
  CONSTRAINT fk_usuario_por_area_usuario
    FOREIGN KEY (id_usuario_fk) REFERENCES usuarios (id_usuario)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_usuario_por_area_area
    FOREIGN KEY (id_area_fk) REFERENCES areas (id_area)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tips (
  id_tip INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  enlace VARCHAR(500) NOT NULL,
  imagen VARCHAR(255) NULL,
  id_area_fk INT UNSIGNED NOT NULL,
  fecha_de_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_de_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_tips_area
    FOREIGN KEY (id_area_fk) REFERENCES areas (id_area)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rutinas (
  id_rutina INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nota TEXT NULL,
  dia_semana ENUM('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo') NOT NULL,
  tema VARCHAR(150) NOT NULL,
  hora TIME NOT NULL,
  id_area_fk INT UNSIGNED NOT NULL,
  id_usuario_fk INT UNSIGNED NOT NULL,
  fecha_de_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_de_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_rutinas_area
    FOREIGN KEY (id_area_fk) REFERENCES areas (id_area)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_rutinas_usuario
    FOREIGN KEY (id_usuario_fk) REFERENCES usuarios (id_usuario)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE calendarios (
  id_calendario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  observacion TEXT NULL,
  fecha_inicio DATETIME NOT NULL,
  fecha_final DATETIME NULL,
  id_usuario_fk INT UNSIGNED NOT NULL,
  fecha_de_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_de_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_calendarios_usuario
    FOREIGN KEY (id_usuario_fk) REFERENCES usuarios (id_usuario)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT chk_calendarios_fechas
    CHECK (fecha_final IS NULL OR fecha_final >= fecha_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO areas (nombre_area) VALUES
  ('Matematicas'),
  ('Fisica'),
  ('Quimica'),
  ('Filosofia');

INSERT INTO usuarios (nombre_completo, correo_electronico, contrasena, perfil)
VALUES (
  'Administrador',
  'admin@learnviky.com',
  '$2y$10$rOyQbmscmEK7FoCJF7dede8vyt3CA/qbpY7AVfsl1AqvXHO4vpGfK',
  'administrador'
);
