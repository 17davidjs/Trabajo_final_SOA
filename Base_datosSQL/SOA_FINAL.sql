-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2024 a las 19:16:29
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
  `ID` int(11) NOT NULL, 
  `usuario_id` int(11) NOT NULL, 
  `nombre` varchar(100) NOT NULL, 
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_curriculum` date NOT NULL, 
  `formacion_academica` text DEFAULT NULL,
  `experiencia_laboral` text DEFAULT NULL,
  `idiomas` text DEFAULT NULL,
  `habilidades` text DEFAULT NULL,
  `datos_interes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL, 
  `nombre` varchar(100) NOT NULL, 
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL, 
  `contraseña` varchar(255) NOT NULL,
  `token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `curriculums`
--
ALTER TABLE `curriculums`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `curriculums`
--
ALTER TABLE `curriculums`
  ADD CONSTRAINT `curriculums_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
