-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-03-2025 a las 16:12:39
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
-- Base de datos: `ingresante`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad`
--

CREATE TABLE `actividad` (
  `id` int(11) NOT NULL,
  `descripcionCorta` varchar(255) NOT NULL,
  `descripcionLarga` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividad`
--

INSERT INTO `actividad` (`id`, `descripcionCorta`, `descripcionLarga`) VALUES
(1, 'Introducción a Python', 'Curso básico sobre programación en Python desde cero.'),
(2, 'Fundamentos de Redes', 'Conceptos básicos de redes y configuración inicial de dispositivos.'),
(3, 'Ciberseguridad Práctica', 'Aprende sobre ataques comunes y cómo protegerte en el mundo digital.'),
(4, 'SQL Avanzado', 'Técnicas avanzadas de consultas y optimización en bases de datos SQL.'),
(5, 'Diseño Gráfico con Illustrator', 'Uso de Adobe Illustrator para diseño y creación de ilustraciones.'),
(6, 'Desarrollo Web con HTML y CSS', 'Aprende a crear páginas web con HTML y CSS desde cero.'),
(7, 'Introducción a la Inteligencia Artificial', 'Fundamentos básicos de IA y aprendizaje automático.'),
(8, 'Seguridad en Redes', 'Métodos y estrategias para proteger redes empresariales y personales.'),
(9, 'Desarrollo de Videojuegos', 'Creación de videojuegos utilizando motores gráficos como Unity.'),
(10, 'Administración de Servidores', 'Gestión y mantenimiento de servidores Linux y Windows.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlinea`
--

CREATE TABLE `enlinea` (
  `id` int(11) NOT NULL,
  `linkLlamada` varchar(255) NOT NULL,
  `bonificacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enlinea`
--

INSERT INTO `enlinea` (`id`, `linkLlamada`, `bonificacion`) VALUES
(6, 'https://meet.example.com/cyber', 10),
(7, 'https://meet.example.com/bd', 15),
(8, 'https://meet.example.com/illustrator1', 12),
(9, 'https://meet.example.com/web', 10),
(10, 'https://meet.example.com/pya', 18),
(11, 'https://meet.example.com/segRedes', 10),
(12, 'https://meet.example.com/ia', 15),
(13, 'https://meet.example.com/segRedesN', 20),
(14, 'https://meet.example.com/juegos', 12),
(15, 'https://meet.example.com/sv', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresante`
--

CREATE TABLE `ingresante` (
  `dni` varchar(20) NOT NULL,
  `tipoDni` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `legajo` varchar(20) NOT NULL,
  `correo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresante`
--

INSERT INTO `ingresante` (`dni`, `tipoDni`, `nombre`, `apellido`, `legajo`, `correo`) VALUES
('11223344', 'DNI', 'Carlos', 'Gómez', 'FAI-1057', 'carlos.gomez@example.com'),
('11224466', 'DNI', 'Javier', 'Mendoza', 'FAI-9135', 'javier.mendoza@example.com'),
('12345678', 'DNI', 'Juan', 'Pérez', 'FAI-4721', 'juan.perez@example.com'),
('22334455', 'DNI', 'Sofía', 'Díaz', 'FAI-3962', 'sofia.diaz@example.com'),
('22336644', 'DNI', 'Ezequiel', 'Vega', 'FAI-9287', 'ezequiel.vega@example.com'),
('33221100', 'DNI', 'Matías', 'Chávez', 'FAI-6874', 'matias.chavez@example.com'),
('33445566', 'DNI', 'Diego', 'Rodríguez', 'FAI-7139', 'diego.rodriguez@example.com'),
('44556677', 'DNI', 'Nicolás', 'Peralta', 'FAI-7516', 'nicolas.peralta@example.com'),
('44557788', 'DNI', 'Agustín', 'Navarro', 'FAI-1470', 'agustin.navarro@example.com'),
('55663322', 'DNI', 'Valentina', 'Ramos', 'FAI-2501', 'valentina.ramos@example.com'),
('55667788', 'DNI', 'Ana', 'Martínez', 'FAI-6312', 'ana.martinez@example.com'),
('66775544', 'DNI', 'Paula', 'Cáceres', 'FAI-8013', 'paula.caceres@example.com'),
('66778899', 'DNI', 'Lucía', 'García', 'FAI-2846', 'lucia.garcia@example.com'),
('77884433', 'DNI', 'Florencia', 'Morales', 'FAI-3625', 'florencia.morales@example.com'),
('77889900', 'DNI', 'Fernando', 'Sosa', 'FAI-8420', 'fernando.sosa@example.com'),
('87654321', 'DNI', 'María', 'López', 'FAI-8293', 'maria.lopez@example.com'),
('88776655', 'DNI', 'Martina', 'Giménez', 'FAI-4398', 'martina.gimenez@example.com'),
('99001122', 'DNI', 'Camila', 'Herrera', 'FAI-5673', 'camila.herrera@example.com'),
('99882211', 'DNI', 'Julieta', 'Torres', 'FAI-5829', 'julieta.torres@example.com'),
('99887766', 'DNI', 'Pedro', 'Fernández', 'FAI-9584', 'pedro.fernandez@example.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id` int(11) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `costoFinal` decimal(10,2) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `tipoDni` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`id`, `fecha`, `costoFinal`, `dni`, `tipoDni`) VALUES
(1, '10/03/23', 70300.00, '12345678', 'DNI'),
(2, '12/03/23', 56660.00, '87654321', 'DNI'),
(3, '14/03/23', 122030.00, '11223344', 'DNI'),
(4, '15/03/23', 33000.00, '55667788', 'DNI'),
(5, '16/03/23', 55900.00, '99887766', 'DNI'),
(6, '17/03/23', 95355.00, '66778899', 'DNI'),
(7, '18/03/23', 98200.00, '33445566', 'DNI'),
(8, '19/03/23', 42000.00, '22334455', 'DNI'),
(9, '20/03/23', 92000.00, '77889900', 'DNI'),
(10, '10/04/25', 174430.00, '99001122', 'DNI'),
(11, '22/03/23', 39000.00, '11224466', 'DNI'),
(12, '23/03/23', 0.00, '55663322', 'DNI'),
(14, '24/03/23', 18900.00, '44556677', 'DNI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion_modulo`
--

CREATE TABLE `inscripcion_modulo` (
  `inscripcion_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripcion_modulo`
--

INSERT INTO `inscripcion_modulo` (`inscripcion_id`, `modulo_id`) VALUES
(1, 1),
(1, 3),
(1, 8),
(1, 12),
(2, 6),
(2, 10),
(2, 11),
(3, 4),
(3, 5),
(3, 14),
(3, 15),
(3, 20),
(3, 23),
(4, 1),
(4, 3),
(5, 1),
(5, 8),
(5, 9),
(6, 1),
(6, 7),
(6, 14),
(6, 23),
(6, 25),
(7, 2),
(7, 13),
(7, 18),
(7, 21),
(7, 24),
(8, 1),
(8, 19),
(9, 16),
(9, 17),
(9, 18),
(9, 25),
(10, 3),
(10, 5),
(10, 8),
(10, 9),
(10, 12),
(10, 13),
(10, 14),
(10, 15),
(10, 19),
(11, 11),
(11, 22),
(14, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `horarioInicio` varchar(20) NOT NULL,
  `horarioCierre` varchar(20) NOT NULL,
  `fechaInicio` varchar(20) NOT NULL,
  `fechaFin` varchar(20) NOT NULL,
  `topeInscripciones` int(11) NOT NULL,
  `costo` decimal(10,2) NOT NULL,
  `cantidadDeInscriptos` int(11) DEFAULT 0,
  `actividad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id`, `descripcion`, `horarioInicio`, `horarioCierre`, `fechaInicio`, `fechaFin`, `topeInscripciones`, `costo`, `cantidadDeInscriptos`, `actividad_id`) VALUES
(1, 'Introducción a python- Turno Mañana', '08:00', '10:00', '10/03/25', '10/04/25', 5, 15000.00, 5, 1),
(2, 'Introducción a Python - Turno Tarde', '14:00', '16:00', '15/03/25', '15/04/25', 25, 14000.00, 1, 1),
(3, 'Taller de redes - Nivel 1', '10:00', '12:00', '20/03/25', '20/04/25', 20, 18000.00, 3, 2),
(4, 'Taller de redes - Nivel 2', '16:00', '18:00', '05/04/25', '05/05/25', 20, 19000.00, 1, 2),
(5, 'Taller en Ciberseguridad - Básico', '09:00', '11:00', '12/03/25', '12/04/25', 30, 20000.00, 2, 3),
(6, 'Taller en Ciberseguridad - Avanzado', '13:00', '15:00', '10/04/25', '10/05/25', 25, 22000.00, 1, 3),
(7, 'Bases de Datos Relacionales', '08:30', '10:30', '22/03/25', '22/04/25', 35, 17500.00, 1, 4),
(8, 'Illustrator avanzado', '17:00', '19:00', '25/03/25', '25/04/25', 20, 25000.00, 3, 5),
(9, 'Desarrollo Web con HTML y CSS', '09:30', '11:30', '02/04/25', '02/05/25', 28, 21000.00, 3, 6),
(10, 'Python Avanzado', '12:00', '14:00', '15/04/25', '15/05/25', 30, 23000.00, 1, 1),
(11, 'Seguridad en Redes', '15:30', '17:30', '20/04/25', '20/05/25', 22, 20000.00, 2, 2),
(12, 'Inteligencia artificial', '08:00', '10:00', '18/03/25', '18/04/25', 25, 18000.00, 2, 7),
(13, 'Seguridad en redes para novatos', '10:30', '12:30', '08/04/25', '08/05/25', 30, 19000.00, 2, 8),
(14, 'Introducción a la programación en videojuegos', '13:00', '15:00', '25/04/25', '25/05/25', 28, 21000.00, 3, 9),
(15, 'Guía de administración de servidores', '16:30', '18:30', '02/05/25', '02/06/25', 25, 23000.00, 2, 10),
(16, 'Aplicaciones en Python - Avanzado', '09:00', '11:00', '01/06/25', '01/07/25', 40, 25000.00, 1, 1),
(17, 'Redes y Conectividad - Intermedio', '10:00', '12:00', '05/06/25', '05/07/25', 35, 20000.00, 1, 2),
(18, 'Taller en Ciberseguridad - Online', '11:00', '13:00', '10/06/25', '10/07/25', 30, 22000.00, 2, 3),
(19, 'Curso de SQL - Online', '12:00', '14:00', '15/06/25', '15/07/25', 50, 27000.00, 2, 4),
(20, 'Introducción a Illustrator', '13:00', '15:00', '20/06/25', '20/07/25', 45, 23000.00, 1, 5),
(21, 'Desarrollo Web avanzado', '14:00', '16:00', '25/06/25', '25/07/25', 40, 24000.00, 1, 6),
(22, 'Aprendizaje automatizado', '15:00', '17:00', '30/06/25', '30/07/25', 50, 21000.00, 1, 7),
(23, 'Practica en seguridad en redes', '16:00', '18:00', '05/07/25', '05/08/25', 45, 22000.00, 2, 8),
(24, 'Videojuegos avanzado', '17:00', '19:00', '10/07/25', '10/08/25', 40, 23000.00, 1, 9),
(25, 'Servidores avanzado', '18:00', '20:00', '15/07/25', '15/08/25', 35, 25000.00, 2, 10);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `enlinea`
--
ALTER TABLE `enlinea`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingresante`
--
ALTER TABLE `ingresante`
  ADD PRIMARY KEY (`dni`,`tipoDni`),
  ADD UNIQUE KEY `legajo` (`legajo`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dni` (`dni`,`tipoDni`);

--
-- Indices de la tabla `inscripcion_modulo`
--
ALTER TABLE `inscripcion_modulo`
  ADD PRIMARY KEY (`inscripcion_id`,`modulo_id`),
  ADD KEY `modulo_id` (`modulo_id`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actividad_id` (`actividad_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad`
--
ALTER TABLE `actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `enlinea`
--
ALTER TABLE `enlinea`
  ADD CONSTRAINT `enlinea_ibfk_1` FOREIGN KEY (`id`) REFERENCES `modulo` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`dni`,`tipoDni`) REFERENCES `ingresante` (`dni`, `tipoDni`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripcion_modulo`
--
ALTER TABLE `inscripcion_modulo`
  ADD CONSTRAINT `inscripcion_modulo_ibfk_1` FOREIGN KEY (`inscripcion_id`) REFERENCES `inscripcion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripcion_modulo_ibfk_2` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD CONSTRAINT `modulo_ibfk_1` FOREIGN KEY (`actividad_id`) REFERENCES `actividad` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
