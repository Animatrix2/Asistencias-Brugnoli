-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-09-2024 a las 04:40:50
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

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
) ENGINE=MyISAM AUTO_INCREMENT=1242 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(20, '1ro 1ra CB', 'Pepe', 'Chavez', '42658565', 'Masculino'),
(1236, '1ro 1ra CB', 'caco', 'quico', '12345670', 'Masculino'),
(1237, '1ro 1ra CB', 'pepito', 'ituna', '43434343', 'Masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE IF NOT EXISTS `asistencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `alumno_id` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `estado` enum('asistencia','inasistencia','tardanza') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `justificada` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `alumno_id` (`alumno_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `alumno_id`, `fecha`, `estado`, `justificada`) VALUES
(412, 20, '2024-08-21', 'inasistencia', 0),
(411, 19, '2024-08-21', 'inasistencia', 0),
(410, 18, '2024-08-21', 'asistencia', 0),
(409, 17, '2024-08-21', 'asistencia', 0),
(408, 15, '2024-08-21', 'inasistencia', 0),
(407, 9, '2024-08-21', 'tardanza', 0),
(406, 10, '2024-08-21', 'tardanza', 0),
(405, 16, '2024-08-21', 'tardanza', 0),
(372, 20, '2024-08-19', 'inasistencia', 0),
(371, 19, '2024-08-19', 'inasistencia', 0),
(370, 18, '2024-08-19', 'inasistencia', 0),
(369, 17, '2024-08-19', 'inasistencia', 0),
(368, 15, '2024-08-19', 'inasistencia', 0),
(367, 9, '2024-08-19', 'inasistencia', 0),
(366, 10, '2024-08-19', 'inasistencia', 0),
(365, 16, '2024-08-19', 'inasistencia', 0),
(566, 16, '2024-09-25', 'inasistencia', 0),
(567, 10, '2024-09-25', 'inasistencia', 0),
(568, 9, '2024-09-25', 'inasistencia', 0),
(569, 15, '2024-09-25', 'inasistencia', 0),
(570, 17, '2024-09-25', 'inasistencia', 0),
(571, 18, '2024-09-25', 'inasistencia', 0),
(572, 19, '2024-09-25', 'inasistencia', 0),
(573, 20, '2024-09-25', 'inasistencia', 0),
(574, 1236, '2024-09-25', 'inasistencia', 0),
(666, 16, '2024-08-02', 'inasistencia', 0),
(777, 16, '2024-08-05', 'inasistencia', 0),
(888, 16, '2024-09-11', 'inasistencia', 0),
(999, 16, '2024-09-05', 'inasistencia', 0),
(878, 16, '2024-08-21', 'inasistencia', 0),
(989, 16, '2024-09-13', 'inasistencia', 0),
(678, 16, '2024-08-26', 'inasistencia', 0),
(987, 16, '2024-07-23', 'inasistencia', 0),
(789, 16, '2024-08-08', 'inasistencia', 0),
(778, 16, '2024-09-05', 'inasistencia', 0),
(771, 16, '2024-09-12', 'inasistencia', 0),
(772, 16, '2024-07-19', 'inasistencia', 0),
(773, 16, '2024-08-15', 'inasistencia', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `permisos` set('1ro 1ra CB','1ro 2da CB','1ro 3ra CB','1ro 4ta CB','1ro 5ta CB','1ro 6ta CB','1ro 7ma CB','2do 1ra CB','2do 2da CB','2do 3ra CB','2do 4ta CB','2do 5ta CB','1ro 1ra IPP','1ro 2da IPP','2do 1ra IPP','2do 2da IPP','3ro 1ra IPP','3ro 2da IPP','4to 1ra IPP','4to 2da IPP','1ro 1ra GAO','1ro 2da GAO','1ro 3ra GAO','1ro 4ta GAO','2do 1ra GAO','2do 2da GAO','2do 3ra GAO','2do 4ta GAO','3ro 1ra GAO','3ro 2da GAO','3ro 3ra GAO','3ro 4ta GAO','4to 1ra GAO','4to 2da GAO','4to 3ra GAO','4to 4ta GAO','1ro 1ra TEP','2do 1ra TEP','3ro 1ra TEP','4to 1ra TEP','Administrador') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `contraseña`, `permisos`) VALUES
(1, 'admin', '$2y$10$olWiN01dNLm6Y3bvACEuAuYlE5x7QQgKqFisxhPHI0mu7JR5ZzLha', 'Administrador'),
(11, 'sosa', '$2y$10$IT7JZzUll94g2triJ54klutAeT8kYnxiw3nVxxZkr5I74MxULp2lW', '3ro 1ra IPP,3ro 2da IPP,4to 1ra IPP,4to 2da IPP,1ro 1ra TEP,2do 1ra TEP,3ro 1ra TEP,4to 1ra TEP'),
(15, 'carla', '$2y$10$dTF6KKoPjJfeQf2HJgObhOPPezwTyAdkpjPtx51Ql6dG65J0IC0vi', '1ro 1ra CB,1ro 2da CB,1ro 3ra CB,1ro 4ta CB,1ro 5ta CB,1ro 6ta CB,1ro 7ma CB,2do 1ra CB,2do 2da CB,2do 3ra CB,2do 4ta CB,2do 5ta CB'),
(19, 'Maria Desza', '$2y$10$OPgvDYDXt6p4sVHlVVmQse9Bwj7DoHohnrbeTDjddNOlP6ETO/Y7q', '1ro 1ra CB'),
(18, 'p', '$2y$10$6r6QBo07K9gfh41erFuUdOrgVjvza0hh9yEx4IWZ2yDIpwlwSsaPy', '1ro 1ra CB,1ro 2da CB,1ro 3ra CB,1ro 4ta CB,1ro 5ta CB,1ro 6ta CB,1ro 7ma CB,2do 1ra CB,2do 2da CB,2do 3ra CB,2do 4ta CB,2do 5ta CB');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
