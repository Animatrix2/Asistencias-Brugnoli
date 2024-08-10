-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 10-08-2024 a las 22:32:15
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `curso` varchar(10) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1236 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `curso`, `nombre`, `apellido`, `dni`, `sexo`) VALUES
(16, '1ro 1ra CB', 'Josi', 'Almirón', '24865355', 'Femenino'),
(12, '1ro 2da CB', 'Sahira', 'Bolívar', '12365452', 'Otro'),
(10, '1ro 1ra CB', 'Don', 'Ramón', '12468325', 'Masculino'),
(9, '1ro 1ra CB', 'Mamaría', 'Tablón', '41453146', 'Femenino'),
(13, '2do 1ra CB', 'Hernán', 'Esmeralda', '12345678', 'Masculino'),
(14, '2do 1ra CB', 'Jorge', 'Esmeralda', '12345679', 'Masculino'),
(15, '1ro 1ra CB', 'José', 'Altamirano', '12344568', 'Masculino'),
(17, '1ro 1ra CB', 'Sarp', 'Sánchez', '48500623', 'Masculino'),
(18, '1ro 1ra CB', 'Julio', 'Roca', '45821658', 'Masculino'),
(19, '1ro 1ra CB', 'Estefana', 'García', '47986515', 'Femenino'),
(20, '1ro 1ra CB', 'Pepe', 'Chavez', '42658565', 'Masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE IF NOT EXISTS `asistencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `alumno_id` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `estado` enum('asistió','faltó','tardanza') NOT NULL,
  `justificada` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `alumno_id` (`alumno_id`)
) ENGINE=MyISAM AUTO_INCREMENT=309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `alumno_id`, `fecha`, `estado`, `justificada`) VALUES
(180, 2, '2024-05-29', 'faltó', 0),
(179, 5, '2024-05-29', 'asistió', 0),
(178, 4, '2024-05-29', 'faltó', 0),
(177, 3, '2024-05-29', 'tardanza', 0),
(176, 1, '2024-05-29', 'asistió', 0),
(110, 7, '2024-05-24', 'faltó', 0),
(109, 6, '2024-05-24', 'tardanza', 0),
(99, 6, '2024-05-22', 'asistió', 0),
(100, 7, '2024-05-22', 'asistió', 0),
(181, 1, '2024-06-09', 'tardanza', 0),
(182, 4, '2024-06-09', 'faltó', 0),
(183, 5, '2024-06-09', 'faltó', 1),
(184, 10, '2024-06-14', 'asistió', 0),
(185, 9, '2024-06-14', 'asistió', 0),
(186, 10, '2024-06-15', 'faltó', 1),
(187, 9, '2024-06-15', 'faltó', 1),
(188, 15, '2024-06-15', 'faltó', 0),
(195, 10, '2024-06-23', 'asistió', 0),
(196, 9, '2024-06-23', 'faltó', 0),
(197, 15, '2024-06-23', 'faltó', 0),
(210, 10, '2024-06-27', 'asistió', 0),
(211, 9, '2024-06-27', 'tardanza', 0),
(212, 15, '2024-06-27', 'asistió', 0),
(261, 16, '2024-07-27', 'asistió', 0),
(262, 10, '2024-07-27', 'tardanza', 0),
(263, 9, '2024-07-27', 'tardanza', 0),
(264, 15, '2024-07-27', 'tardanza', 0),
(265, 17, '2024-07-27', 'faltó', 0),
(266, 18, '2024-07-27', 'faltó', 0),
(267, 19, '2024-07-27', 'faltó', 0),
(268, 20, '2024-07-27', 'asistió', 0),
(301, 16, '2024-07-30', 'asistió', 0),
(302, 10, '2024-07-30', 'asistió', 0),
(303, 9, '2024-07-30', 'asistió', 0),
(304, 15, '2024-07-30', 'asistió', 0),
(305, 17, '2024-07-30', 'asistió', 0),
(306, 18, '2024-07-30', 'asistió', 0),
(307, 19, '2024-07-30', 'asistió', 0),
(308, 20, '2024-07-30', 'asistió', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `permisos` set('1ro 1ra CB','1ro 2da CB','1ro 3ra CB','1ro 4ta CB','1ro 5ta CB','1ro 6ta CB','1ro 7ma CB','2do 1ra CB','2do 2da CB','2do 3ra CB','2do 4ta CB','2do 5ta CB','1ro 1ra IPP','1ro 2da IPP','2do 1ra IPP','2do 2da IPP','3ro 1ra IPP','3ro 2da IPP','4to 1ra IPP','4to 2da IPP','1ro 1ra GAO','1ro 2da GAO','1ro 3ra GAO','1ro 4ta GAO','2do 1ra GAO','2do 2da GAO','2do 3ra GAO','2do 4ta GAO','3ro 1ra GAO','3ro 2da GAO','3ro 3ra GAO','3ro 4ta GAO','4to 1ra GAO','4to 2da GAO','4to 3ra GAO','4to 4ta GAO','1ro 1ra TEP','2do 1ra TEP','3ro 1ra TEP','4to 1ra TEP') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `contraseña`, `permisos`) VALUES
(5, 'gonzalo', '$2y$10$pU2.Bs6MDZta7PyCLZDdjOQq9g.ccLsb4U0SUnj1HeEwGZaKN/Dly', '1ro 1ra GAO');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
