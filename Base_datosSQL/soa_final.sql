-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-01-2025 a las 23:01:03
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `soa_final`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `paginaweb` varchar(100) DEFAULT NULL,
  `datos_interes` text DEFAULT NULL,
  `imagen_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`id`, `cv_id`, `telefono`, `correo_electronico`, `paginaweb`, `datos_interes`, `imagen_path`) VALUES
(11, 7, '00000000', 'aaaaaaa@usal.es', 'ayer.es', NULL, NULL),
(12, 10, '123456789', 'usuario@example.com', 'https://usuario.com', NULL, NULL),
(13, 11, '123456789', 'usuario@example.com', NULL, NULL, NULL),
(14, 12, '123456789', 'usuario@example.com', 'https://usuario.com', NULL, NULL),
(15, 13, '00000000', 'aaaaaaa@usal.es', 'ayer.es', NULL, NULL),
(16, 14, '00000000', 'aaaaaaa@usal.es', 'ayer.es', NULL, NULL),
(17, 15, '00000000', 'aaaaaaa@usal.es', 'ayer.es', NULL, NULL),
(18, 16, '00000000', 'aaaaaaa@usal.es', 'ayer.es', 'Hola buenas noches', '../Imagenes/OIP.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curriculums`
--

CREATE TABLE `curriculums` (
  `usuario_id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `curriculums`
--

INSERT INTO `curriculums` (`usuario_id`, `cv_id`) VALUES
(6, 7),
(6, 10),
(6, 11),
(6, 12),
(6, 13),
(6, 14),
(6, 15),
(6, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `educacion`
--

CREATE TABLE `educacion` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `institucion` varchar(100) DEFAULT NULL,
  `fecha` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `educacion`
--

INSERT INTO `educacion` (`id`, `cv_id`, `titulo`, `institucion`, `fecha`) VALUES
(14, 7, 'Estupìdez', 'asdfasdfsadf', 'asdfsafdsf'),
(15, 10, 'Licenciatura en Informática', 'Universidad Nacional', '2015-06-15'),
(16, 10, 'Maestría en Ciencia de Datos', 'Universidad de Ciencias Aplicadas', '2018-11-20'),
(17, 11, 'Licenciatura en Informática', 'Universidad Nacional', '2015-06-15'),
(18, 11, 'Maestría en Ciencia de Datos', 'Universidad de Ciencias Aplicadas', '2018-11-20'),
(19, 13, 'Estupìdez', 'asdfasdfsadf', 'asdfsafdsf'),
(20, 14, 'Estupìdez', 'asdfasdfsadf', 'asdfsafdsf'),
(21, 15, 'Estupìdez', 'asdfasdfsadf', 'asdfsafdsf'),
(22, 16, 'Estupìdez', 'asdfasdfsadf', 'asdfsafdsf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencia_laboral`
--

CREATE TABLE `experiencia_laboral` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `puesto` varchar(100) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `experiencia_laboral`
--

INSERT INTO `experiencia_laboral` (`id`, `cv_id`, `puesto`, `empresa`, `fecha_inicio`, `fecha_fin`, `descripcion`) VALUES
(13, 7, 'asdfdsafasdf', 'asdfasdfsdaf', '0000-00-00', '0000-00-00', ''),
(14, 10, 'Desarrollador Backend', 'Tech Solutions', '2016-01-01', '2018-12-31', 'Desarrollo de APIs REST y mantenimiento de bases de datos.'),
(15, 10, 'Ingeniero de Software', 'Innovative Tech', '2019-01-01', '2023-06-30', 'Liderazgo de proyectos de desarrollo de software y mentoría de equipos.'),
(16, 11, 'Desarrollador Backend', 'Tech Solutions', '2016-01-01', '2018-12-31', 'Desarrollo de APIs REST y mantenimiento de bases de datos.'),
(17, 11, 'Ingeniero de Software', 'Innovative Tech', '2019-01-01', '2023-06-30', 'Liderazgo de proyectos de desarrollo de software y mentoría de equipos.'),
(18, 13, 'asdfdsafasdf', 'asdfasdfsdaf', '0000-00-00', '0000-00-00', ''),
(19, 14, 'asdfdsafasdf', 'asdfasdfsdaf', '0000-00-00', '0000-00-00', ''),
(20, 15, 'asdfdsafasdf', 'asdfasdfsdaf', '0000-00-00', '0000-00-00', ''),
(21, 16, 'asdfdsafasdf', 'asdfasdfsdaf', '0000-00-00', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `habilidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habilidades`
--

INSERT INTO `habilidades` (`id`, `cv_id`, `habilidad`) VALUES
(19, 7, 'Ninguno'),
(20, 10, 'PHP'),
(21, 10, 'MySQL'),
(22, 10, 'JavaScript'),
(23, 10, 'Python'),
(24, 10, 'Gestión de Proyectos'),
(25, 11, 'PHP'),
(26, 11, 'MySQL'),
(27, 11, 'JavaScript'),
(28, 11, 'Python'),
(29, 11, 'Gestión de Proyectos'),
(30, 13, 'Ninguno'),
(31, 14, 'Ninguno'),
(32, 15, 'Ninguno'),
(33, 16, 'Ninguno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomas`
--

CREATE TABLE `idiomas` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `idioma` varchar(50) DEFAULT NULL,
  `nivel` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `idiomas`
--

INSERT INTO `idiomas` (`id`, `cv_id`, `idioma`, `nivel`) VALUES
(13, 7, 'Español', 'Medio'),
(14, 10, 'Inglés', 'Avanzado'),
(15, 10, 'Francés', 'Intermedio'),
(16, 11, NULL, 'Avanzado'),
(17, 11, NULL, 'Intermedio'),
(18, 13, 'Español', 'Medio'),
(19, 14, 'Español', 'Medio'),
(20, 15, 'Español', 'Medio'),
(21, 16, 'Español', 'Medio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `fecha_nacimiento`, `direccion`, `correo_electronico`, `telefono`, `usuario`, `contrasena`, `token`, `role`) VALUES
(1, 'admin', 'admin', '2024-12-10', 'admin', 'admin@gmail.com', '123123123', 'admin', '$2y$10$bOEnPL9d9W7cluyBDFELie7xaa3s254pjIPMCC8J30KnXPIUH0e5y', 'fe1ebcd59b8c6dc531d7599713e557e4', 'admin'),
(2, 'Miguel', 'Redonet Conde', '2002-03-06', 'Calle Pablo Morillo', 'id00810911@usal.com', '123456789', 'miguel', '$2y$10$YtfyR/TmJsLf5/KZ1q5baOmJrpW6ZeM/9fT26AMViZ3ISXmxP.hmq', 'a490853490b1d9e51f7ad88d8f4eb009', 'user'),
(5, 'david', 'González', '2025-01-09', 'Avenida de Juan Pablo II', 'ad@ad.es', '606461129', 'david', '$2y$10$Q2anot00G.koixGjceBo2OwG5.lNUDZ6T81xsXS/J5qhmDJJ.KcHm', '643894e4628efdb0fd7dbe2ac0059f5f', 'user'),
(6, 'Carlos', 'Hernández', '2002-09-16', 'Quemasda', 'carlos_hernandez@usal.es', '123123123', 'carlos', '$2y$10$m6Svw56bRI9QDDFFAFSWkOCiKH/nTMyouteT6zjYKni5DAWTjnwo.', '69f21a2b2716981f0605c8ba36f72aad', 'user');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`cv_id`);

--
-- Indices de la tabla `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`cv_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `educacion`
--
ALTER TABLE `educacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`cv_id`);

--
-- Indices de la tabla `experiencia_laboral`
--
ALTER TABLE `experiencia_laboral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`cv_id`);

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`cv_id`);

--
-- Indices de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`cv_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `curriculums`
--
ALTER TABLE `curriculums`
  MODIFY `cv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `educacion`
--
ALTER TABLE `educacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `experiencia_laboral`
--
ALTER TABLE `experiencia_laboral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `curriculums`
--
ALTER TABLE `curriculums`
  ADD CONSTRAINT `curriculums_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
