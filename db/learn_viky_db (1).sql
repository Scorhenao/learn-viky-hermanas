-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2026 a las 23:37:51
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
(4, 'Filosofia', '2026-08-27 01:39:26', '2026-08-27 01:39:26'),
(5, 'Ingles', '2026-09-17 00:00:00', '2026-09-17 00:00:00'),
(6, 'Español', '2026-09-17 00:00:00', '2026-09-17 00:00:00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calendarios`
--

INSERT INTO `calendarios` (`id_calendario`, `observacion`, `fecha_inicio`, `fecha_final`, `id_usuario_fk`, `fecha_de_creacion`, `fecha_de_actualizacion`) VALUES
(5, 'cumple mateu', '2026-09-10 18:21:00', NULL, 4, '2026-08-30 23:21:59', '2026-08-30 23:21:59'),
(6, 'cumple abu', '2026-09-21 15:06:00', '2026-09-22 20:06:00', 4, '2026-08-31 19:06:46', '2026-08-31 19:06:46'),
(7, 'deuda rafa', '2026-09-07 13:00:00', '2026-09-07 17:00:00', 4, '2026-08-31 19:37:33', '2026-08-31 19:37:33'),
(8, 'cumple alvarez', '2027-02-18 17:36:00', '2027-02-18 23:36:00', 7, '2026-09-07 21:36:55', '2026-09-07 21:36:55'),
(9, 'holi', '2026-09-10 15:50:00', '2026-09-14 15:51:00', 8, '2026-09-09 20:51:06', '2026-09-09 20:51:06');

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
(6, 'sp3', 'Lunes', 'quimica organica', '08:00:00', 3, 4, '2026-08-29 19:11:32', '2026-08-29 19:11:32'),
(7, 'la purga', 'Martes', 'pelicula', '06:20:00', 4, 4, '2026-08-31 19:07:29', '2026-08-31 19:07:29'),
(8, 'estuydiar popo', 'Miercoles', 'popo', '08:00:00', 2, 5, '2026-09-02 21:31:38', '2026-09-02 21:31:38'),
(9, 'examen importante', 'Lunes', 'algebra', '08:00:00', 1, 7, '2026-09-07 21:35:56', '2026-09-07 21:35:56');

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
(2, 'matematica basica', 'https://www.youtube.com/watch?v=-RDBMu7BreE', 'uploads/tips/tip_6a9879c89ee303.16804600.jpg', 1, '2026-08-29 19:06:43', '2026-09-02 19:32:24'),
(3, 'funciones trigonometricas', 'https://www.youtube.com/watch?v=8zVW0U2jn8U', 'uploads/tips/tip_6a95d21a5ed501.01377981.jpg', 1, '2026-08-31 19:10:16', '2026-08-31 19:12:26'),
(4, 'movimiento rectilíneo unif', 'https://www.youtube.com/watch?v=7X2A7wq8Zow', 'uploads/tips/tip_6a987964744c81.55854899.jpg', 2, '2026-08-31 19:21:28', '2026-09-02 19:30:44'),
(5, 'filosofía medieval', 'https://www.youtube.com/watch?v=d7wuiGCyl0M', 'uploads/tips/tip_6a987a71457609.26627864.jpg', 4, '2026-09-02 19:35:13', '2026-09-02 19:35:13'),
(6, 'que es la química orgánica?', 'https://www.youtube.com/watch?v=FUjHB7S8L4w', 'uploads/tips/tip_6aaaf3431b4580.65658090.jpg', 3, '2026-09-16 19:51:31', '2026-09-16 19:51:31'),
(7, 'leyes de newton', 'https://www.youtube.com/watch?v=86ZNmoAdlNg', 'uploads/tips/tip_6aaaf3dcca8513.81311884.jpg', 2, '2026-09-16 19:54:04', '2026-09-16 19:54:04');

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
(5, 'Administrador', 'admin@learnviky.com', '$2y$10$rOyQbmscmEK7FoCJF7dede8vyt3CA/qbpY7AVfsl1AqvXHO4vpGfK', 'administrador', NULL, NULL, '2026-08-31 05:14:00', '2026-08-31 05:14:00'),
(6, 'isa', 'isacorhenao@gmail.com', '$2y$10$aHC6aWg7Fwh2ypHrcKurMuxF62XdEq38oXyllRJcangmRHbbycI3u', 'estudiante', NULL, NULL, '2026-09-02 18:32:47', '2026-09-02 18:32:47'),
(7, 'alvarez', 'alvarez@gmail.com', '$2y$10$PnTx3da8FeeyG7JxywePRud4c03imjUWkUbYyTWZENGTawMgI29Ye', 'estudiante', NULL, NULL, '2026-09-07 21:34:17', '2026-09-07 21:34:17'),
(8, 'arley florez', 'arleyflorez@iejorgerobledo.edu.co', '$2y$10$tAGotsl9OutFl9QtfP14fe587hcc/fbD8.XdDpOzCOILe1yYOdsSG', 'profesor', '1036650589', 'uploads/certificaciones/diploma_6aa1aae4b76ee2.44015556.pdf', '2026-09-09 18:52:20', '2026-09-09 18:52:20');

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
  MODIFY `id_calendario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  MODIFY `id_rutina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tips`
--
ALTER TABLE `tips`
  MODIFY `id_tip` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
