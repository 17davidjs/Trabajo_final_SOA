-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-01-2025 a las 19:21:44
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
-- Base de datos: `soa_final`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `paginaweb` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`id`, `usuario_id`, `telefono`, `correo_electronico`, `paginaweb`) VALUES
(1, 1, '', '', ''),
(2, 2, '606461129', 'gsdfgsd@gdfg.gsdf', 'hdfgh.vom'),
(3, 3, '606461129', 'gsdfgsd@gdfg.gsdf', 'hdfgh.vom'),
(4, 4, '606461129', 'gsdfgsd@gdfg.gsdf', 'hdfgh.vom');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `educacion`
--

CREATE TABLE `educacion` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `institucion` varchar(100) DEFAULT NULL,
  `fecha` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `educacion`
--

INSERT INTO `educacion` (`id`, `usuario_id`, `titulo`, `institucion`, `fecha`) VALUES
(1, 1, '', '', ''),
(2, 2, 'ingeniero ', 'usal', '2120'),
(3, 3, 'ingeniero ', 'usal', '2120'),
(4, 4, 'ingeniero ', 'usal', '2120'),
(5, 4, 'dsf', 'usal', '2120gf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencia_laboral`
--

CREATE TABLE `experiencia_laboral` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `puesto` varchar(100) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `experiencia_laboral`
--

INSERT INTO `experiencia_laboral` (`id`, `usuario_id`, `puesto`, `empresa`, `fecha_inicio`, `fecha_fin`, `descripcion`) VALUES
(1, 1, '', '', '0000-00-00', '0000-00-00', ''),
(2, 2, 'ceo', 'danode', '2025-01-10', '2025-02-05', 'rewterwtwert'),
(3, 3, 'ceo', 'danode', '2025-01-10', '2025-02-05', 'rewterwtwert'),
(4, 4, 'ceo', 'danode', '2025-01-16', '2025-02-07', 'fdsgdfsgds');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `habilidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habilidades`
--

INSERT INTO `habilidades` (`id`, `usuario_id`, `habilidad`) VALUES
(1, 1, ''),
(2, 2, 'rewtretwertwe'),
(3, 3, 'rewtretwertwe'),
(4, 4, 'se muchas cosas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomas`
--

CREATE TABLE `idiomas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `idioma` varchar(50) DEFAULT NULL,
  `nivel` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `idiomas`
--

INSERT INTO `idiomas` (`id`, `usuario_id`, `idioma`, `nivel`) VALUES
(1, 1, '', ''),
(2, 2, 'espanil', 'bajo'),
(3, 3, 'espanil', 'bajo'),
(4, 4, 'espanil', 'bajo');

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
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `datos_interes` text DEFAULT NULL,
  `imagen_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `fecha_nacimiento`, `direccion`, `correo_electronico`, `telefono`, `usuario`, `contrasena`, `token`, `role`, `datos_interes`, `imagen_path`) VALUES
(1, 'admin', 'admin', '2024-12-10', 'admin', 'admin@gmail.com', '123123123', 'admin', '$2y$10$bOEnPL9d9W7cluyBDFELie7xaa3s254pjIPMCC8J30KnXPIUH0e5y', '6bc3eb7fa3ec20061b8647b5f4996b9a', 'admin', NULL, NULL),
(2, 'Miguel', 'Redonet Conde', '2002-03-06', 'Calle Pablo Morillo', 'id00810911@usal.com', '123456789', 'miguel', '$2y$10$YtfyR/TmJsLf5/KZ1q5baOmJrpW6ZeM/9fT26AMViZ3ISXmxP.hmq', '955f1895e577eb2d921939282fbe7459', 'user', NULL, NULL),
(5, 'david', 'González', '2025-01-09', 'Avenida de Juan Pablo II', 'ad@ad.es', '606461129', 'david', '$2y$10$Q2anot00G.koixGjceBo2OwG5.lNUDZ6T81xsXS/J5qhmDJJ.KcHm', '643894e4628efdb0fd7dbe2ac0059f5f', 'user', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `educacion`
--
ALTER TABLE `educacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `experiencia_laboral`
--
ALTER TABLE `experiencia_laboral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `educacion`
--
ALTER TABLE `educacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `experiencia_laboral`
--
ALTER TABLE `experiencia_laboral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
