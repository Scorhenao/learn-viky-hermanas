-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-08-2026 a las 07:24:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `learn_viky_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(10) UNSIGNED NOT NULL,
  `nombre_area` varchar(100) NOT NULL,
  `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_de_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id_area`, `nombre_area`, `fecha_de_creacion`, `fecha_de_actualizacion`) VALUES
(1, 'Matematicas', '2026-08-27 01:39:26', '2026-08-27 01:39:26'),
(2, 'Fisica', '2026-08-27 01:39:26', '2026-08-27 01:39:26'),
(3, 'Quimica', '2026-08-27 01:39:26', '2026-08-27 01:39:26'),
(4, 'Filosofia', '2026-08-27 01:39:26', '2026-08-27 01:39:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendarios`
--

CREATE TABLE `calendarios` (
  `id_calendario` int(10) UNSIGNED NOT NULL,
  `observacion` text DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_final` datetime DEFAULT NULL,
  `id_usuario_fk` int(10) UNSIGNED NOT NULL,
  `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_de_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Volcado de datos para la tabla `calendarios`
--

INSERT INTO `calendarios` (`id_calendario`, `observacion`, `fecha_inicio`, `fecha_final`, `id_usuario_fk`, `fecha_de_creacion`, `fecha_de_actualizacion`) VALUES
(5, 'cumple mateu', '2026-09-10 18:21:00', NULL, 4, '2026-08-30 23:21:59', '2026-08-30 23:21:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas`
--

CREATE TABLE `rutinas` (
  `id_rutina` int(10) UNSIGNED NOT NULL,
  `nota` text DEFAULT NULL,
  `dia_semana` enum('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo') NOT NULL,
  `tema` varchar(150) NOT NULL,
  `hora` time NOT NULL,
  `id_area_fk` int(10) UNSIGNED NOT NULL,
  `id_usuario_fk` int(10) UNSIGNED NOT NULL,
  `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_de_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rutinas`
--

INSERT INTO `rutinas` (`id_rutina`, `nota`, `dia_semana`, `tema`, `hora`, `id_area_fk`, `id_usuario_fk`, `fecha_de_creacion`, `fecha_de_actualizacion`) VALUES
(6, 'sp3', 'Lunes', 'quimica organica', '08:00:00', 3, 4, '2026-08-29 19:11:32', '2026-08-29 19:11:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tips`
--

CREATE TABLE `tips` (
  `id_tip` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `enlace` varchar(500) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `id_area_fk` int(10) UNSIGNED NOT NULL,
  `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_de_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tips`
--

INSERT INTO `tips` (`id_tip`, `titulo`, `enlace`, `imagen`, `id_area_fk`, `fecha_de_creacion`, `fecha_de_actualizacion`) VALUES
(1, 'Ingeniería Eléctrica', 'https://www.youtube.com/watch?v=Ga5rvLLeLHw&t=11s', NULL, 2, '2026-08-29 03:41:57', '2026-08-29 03:41:57'),
(2, 'matematica basica', 'https://www.youtube.com/watch?v=-RDBMu7BreE', NULL, 1, '2026-08-29 19:06:43', '2026-08-29 19:06:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `correo_electronico` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `perfil` varchar(50) NOT NULL DEFAULT 'estudiante',
  `cedula` varchar(30) DEFAULT NULL,
  `certificacion_diploma` varchar(255) DEFAULT NULL,
  `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_de_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_completo`, `correo_electronico`, `contrasena`, `perfil`, `cedula`, `certificacion_diploma`, `fecha_de_creacion`, `fecha_de_actualizacion`) VALUES
(2, 'pepito', 'pepito@gmail.com', '$2y$10$0x0cI24i.Nzy6aM.gcYBFu3oMiiY0uYJt6zZkOyA8f1zbXS.osOdO', 'estudiante', NULL, NULL, '2026-08-27 04:11:23', '2026-08-29 00:34:41'),
(4, 'valentinita', 'valencorhenao@gmail.com', '$2y$10$wz06119rdru3D4Xg4AIj3u6ny6c9ChXi2lg2tYMMJTJUcztkln1Mu', 'estudiante', NULL, NULL, '2026-08-29 19:04:26', '2026-08-29 19:04:26'),
(5, 'Administrador', 'admin@learnviky.com', '$2y$10$rOyQbmscmEK7FoCJF7dede8vyt3CA/qbpY7AVfsl1AqvXHO4vpGfK', 'administrador', NULL, NULL, '2026-08-31 05:14:00', '2026-08-31 05:14:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_por_area`
--

CREATE TABLE `usuario_por_area` (
  `id_usuario_area` int(10) UNSIGNED NOT NULL,
  `id_usuario_fk` int(10) UNSIGNED NOT NULL,
  `id_area_fk` int(10) UNSIGNED NOT NULL,
  `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_de_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`),
  ADD UNIQUE KEY `uq_areas_nombre` (`nombre_area`);

--
-- Indices de la tabla `calendarios`
--
ALTER TABLE `calendarios`
  ADD PRIMARY KEY (`id_calendario`),
  ADD KEY `fk_calendarios_usuario` (`id_usuario_fk`);

--
-- Indices de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD PRIMARY KEY (`id_rutina`),
  ADD KEY `fk_rutinas_area` (`id_area_fk`),
  ADD KEY `fk_rutinas_usuario` (`id_usuario_fk`);

--
-- Indices de la tabla `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id_tip`),
  ADD KEY `fk_tips_area` (`id_area_fk`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `uq_usuarios_correo` (`correo_electronico`);

--
-- Indices de la tabla `usuario_por_area`
--
ALTER TABLE `usuario_por_area`
  ADD PRIMARY KEY (`id_usuario_area`),
  ADD UNIQUE KEY `uq_usuario_por_area` (`id_usuario_fk`,`id_area_fk`),
  ADD KEY `fk_usuario_por_area_area` (`id_area_fk`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `calendarios`
--
ALTER TABLE `calendarios`
  MODIFY `id_calendario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  MODIFY `id_rutina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tips`
--
ALTER TABLE `tips`
  MODIFY `id_tip` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario_por_area`
--
ALTER TABLE `usuario_por_area`
  MODIFY `id_usuario_area` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calendarios`
--
ALTER TABLE `calendarios`
  ADD CONSTRAINT `fk_calendarios_usuario` FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD CONSTRAINT `fk_rutinas_area` FOREIGN KEY (`id_area_fk`) REFERENCES `areas` (`id_area`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rutinas_usuario` FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tips`
--
ALTER TABLE `tips`
  ADD CONSTRAINT `fk_tips_area` FOREIGN KEY (`id_area_fk`) REFERENCES `areas` (`id_area`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_por_area`
--
ALTER TABLE `usuario_por_area`
  ADD CONSTRAINT `fk_usuario_por_area_area` FOREIGN KEY (`id_area_fk`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_por_area_usuario` FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
