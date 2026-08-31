USE learn_viky_db;

ALTER TABLE usuarios
  MODIFY perfil VARCHAR(50) NOT NULL DEFAULT 'estudiante';

ALTER TABLE usuarios
  ADD COLUMN IF NOT EXISTS cedula VARCHAR(30) NULL AFTER perfil;

ALTER TABLE usuarios
  ADD COLUMN IF NOT EXISTS certificacion_diploma VARCHAR(255) NULL AFTER cedula;

ALTER TABLE tips
  ADD COLUMN IF NOT EXISTS imagen VARCHAR(255) NULL AFTER enlace;

INSERT INTO usuarios (nombre_completo, correo_electronico, contrasena, perfil)
VALUES (
  'Administrador',
  'admin@learnviky.com',
  '$2y$10$rOyQbmscmEK7FoCJF7dede8vyt3CA/qbpY7AVfsl1AqvXHO4vpGfK',
  'administrador'
)
ON DUPLICATE KEY UPDATE
  nombre_completo = VALUES(nombre_completo),
  contrasena = VALUES(contrasena),
  perfil = VALUES(perfil);
