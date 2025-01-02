-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-12-2024 a las 15:53:29
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
-- Estructura de tabla para la tabla `curriculums`
--

CREATE TABLE `curriculums` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_curriculum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_experiencias`
--

CREATE TABLE `cv_experiencias` (
  `id_curriculum` int(11) NOT NULL,
  `id_experiencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_formacion`
--

CREATE TABLE `cv_formacion` (
  `id_curriculum` int(11) NOT NULL,
  `id_formacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_habilidades`
--

CREATE TABLE `cv_habilidades` (
  `id_curriculum` int(11) NOT NULL,
  `id_habilidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_idiomas`
--

CREATE TABLE `cv_idiomas` (
  `id_curriculum` int(11) NOT NULL,
  `id_idioma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_intereses`
--

CREATE TABLE `cv_intereses` (
  `id_curriculum` int(11) NOT NULL,
  `id_interes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencias`
--

CREATE TABLE `experiencias` (
  `id` int(11) NOT NULL,
  `puesto` varchar(100) NOT NULL,
  `empresa` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencia_responsabilidades`
--

CREATE TABLE `experiencia_responsabilidades` (
  `id_experiencia` int(11) NOT NULL,
  `id_responsabilidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formacion`
--

CREATE TABLE `formacion` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `centro` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `id` int(11) NOT NULL,
  `habilidad` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomas`
--

CREATE TABLE `idiomas` (
  `id` int(11) NOT NULL,
  `idioma` varchar(100) NOT NULL,
  `nivel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intereses`
--

CREATE TABLE `intereses` (
  `id` int(11) NOT NULL,
  `interes` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsabilidades`
--

CREATE TABLE `responsabilidades` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `contraseña` varchar(255) NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `fecha_nacimiento`, `direccion`, `correo_electronico`, `telefono`, `usuario`, `contraseña`, `token`, `role`) VALUES
(0, 'admin', 'admin', '2024-12-10', 'admin', 'admin@gmail.com', '123123123', 'admin', '$2y$10$XmkRm7/JKcDNHHkYz2yhG.Ja/l19i1bPIKUPwv8wrCh5IC4GTfy9.', 'd50bbc79c1e858db21745fe3cefacfa7', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `cv_experiencias`
--
ALTER TABLE `cv_experiencias`
  ADD PRIMARY KEY (`id_curriculum`,`id_experiencia`),
  ADD KEY `id_experiencia` (`id_experiencia`);

--
-- Indices de la tabla `cv_formacion`
--
ALTER TABLE `cv_formacion`
  ADD PRIMARY KEY (`id_curriculum`,`id_formacion`),
  ADD KEY `id_formacion` (`id_formacion`);

--
-- Indices de la tabla `cv_habilidades`
--
ALTER TABLE `cv_habilidades`
  ADD PRIMARY KEY (`id_curriculum`,`id_habilidad`),
  ADD KEY `id_habilidad` (`id_habilidad`);

--
-- Indices de la tabla `cv_idiomas`
--
ALTER TABLE `cv_idiomas`
  ADD PRIMARY KEY (`id_curriculum`,`id_idioma`),
  ADD KEY `id_idioma` (`id_idioma`);

--
-- Indices de la tabla `cv_intereses`
--
ALTER TABLE `cv_intereses`
  ADD PRIMARY KEY (`id_curriculum`,`id_interes`),
  ADD KEY `id_interes` (`id_interes`);

--
-- Indices de la tabla `experiencias`
--
ALTER TABLE `experiencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `experiencia_responsabilidades`
--
ALTER TABLE `experiencia_responsabilidades`
  ADD PRIMARY KEY (`id_experiencia`,`id_responsabilidad`),
  ADD KEY `id_responsabilidad` (`id_responsabilidad`);

--
-- Indices de la tabla `formacion`
--
ALTER TABLE `formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `intereses`
--
ALTER TABLE `intereses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `responsabilidades`
--
ALTER TABLE `responsabilidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `curriculums`
--
ALTER TABLE `curriculums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `experiencias`
--
ALTER TABLE `experiencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formacion`
--
ALTER TABLE `formacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `intereses`
--
ALTER TABLE `intereses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `responsabilidades`
--
ALTER TABLE `responsabilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `curriculums`
--
ALTER TABLE `curriculums`
  ADD CONSTRAINT `curriculums_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cv_experiencias`
--
ALTER TABLE `cv_experiencias`
  ADD CONSTRAINT `cv_experiencias_ibfk_1` FOREIGN KEY (`id_curriculum`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cv_experiencias_ibfk_2` FOREIGN KEY (`id_experiencia`) REFERENCES `experiencias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cv_formacion`
--
ALTER TABLE `cv_formacion`
  ADD CONSTRAINT `cv_formacion_ibfk_1` FOREIGN KEY (`id_curriculum`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cv_formacion_ibfk_2` FOREIGN KEY (`id_formacion`) REFERENCES `formacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cv_habilidades`
--
ALTER TABLE `cv_habilidades`
  ADD CONSTRAINT `cv_habilidades_ibfk_1` FOREIGN KEY (`id_curriculum`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cv_habilidades_ibfk_2` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cv_idiomas`
--
ALTER TABLE `cv_idiomas`
  ADD CONSTRAINT `cv_idiomas_ibfk_1` FOREIGN KEY (`id_curriculum`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cv_idiomas_ibfk_2` FOREIGN KEY (`id_idioma`) REFERENCES `idiomas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cv_intereses`
--
ALTER TABLE `cv_intereses`
  ADD CONSTRAINT `cv_intereses_ibfk_1` FOREIGN KEY (`id_curriculum`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cv_intereses_ibfk_2` FOREIGN KEY (`id_interes`) REFERENCES `intereses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `experiencia_responsabilidades`
--
ALTER TABLE `experiencia_responsabilidades`
  ADD CONSTRAINT `experiencia_responsabilidades_ibfk_1` FOREIGN KEY (`id_experiencia`) REFERENCES `experiencias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `experiencia_responsabilidades_ibfk_2` FOREIGN KEY (`id_responsabilidad`) REFERENCES `responsabilidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
