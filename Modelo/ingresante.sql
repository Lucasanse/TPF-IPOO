-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-02-2025 a las 22:27:21
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
(11, 'Introducción a SQL', 'Explicación sobre los comandos básicos de SQL como SELECT, INSERT, UPDATE y DELETE.'),
(12, 'Taller de ciberseguridad', 'Los alumnos analizarán ataques comunes y cómo prevenirlos.'),
(13, 'Diseño en Illustrator', 'Ejercicio de diseño de logotipos usando herramientas vectoriales.');

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
(7, 'https://meet.example.com/sql', 10),
(8, 'https://meet.example.com/ciberseguridad', 15),
(9, 'https://meet.example.com/illustrator', 20);

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
('11223344', 'Pasaporte', 'Carlos', 'Fernández', 'FAI-2025003', 'carlos.fernandez@example.com'),
('12345678', 'DNI', 'Juan', 'Pérez', 'FAI-2025001', 'juan.perez@example.com'),
('22334455', 'DNI', 'Fernando', 'Torres', 'FAI-2025009', 'fernando.torres@example.com'),
('33445566', 'DNI', 'Sofía', 'Martínez', 'FAI-2025006', 'sofia.martinez@example.com'),
('44556677', 'DNI', 'Carla', 'Díaz', 'FAI-2025008', 'carla.diaz@example.com'),
('55667788', 'DNI', 'Lucía', 'Rodríguez', 'FAI-2025004', 'lucia.rodriguez@example.com'),
('66778899', 'Cédula', 'Andrea', 'Romero', 'FAI-2025010', 'andrea.romero@example.com'),
('77889900', 'Pasaporte', 'Diego', 'Sánchez', 'FAI-2025007', 'diego.sanchez@example.com'),
('87654321', 'DNI', 'María', 'Gómez', 'FAI-2025002', 'maria.gomez@example.com'),
('99887766', 'Cédula', 'Martín', 'López', 'FAI-2025005', 'martin.lopez@example.com');

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
(1, '2025-05-10', 2500.00, '12345678', 'DNI'),
(2, '2025-05-12', 3000.00, '87654321', 'DNI'),
(3, '2025-07-01', 2800.00, '11223344', 'Pasaporte'),
(4, '2025-07-05', 3200.00, '55667788', 'DNI'),
(5, '2025-09-03', 3500.00, '99887766', 'Cédula'),
(6, '2025-09-10', 4000.00, '33445566', 'DNI'),
(7, '2025-10-02', 6000.00, '77889900', 'Pasaporte'),
(8, '2025-10-05', 7200.00, '44556677', 'DNI');

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
(1, 2),
(2, 2),
(2, 3),
(3, 3),
(3, 4),
(4, 4),
(4, 5),
(5, 5),
(5, 6),
(6, 6),
(7, 1),
(7, 3),
(7, 5),
(8, 2),
(8, 4),
(8, 6);

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
(1, 'SQL Básico', '09:00 AM', '11:00 AM', '2025-05-01', '2025-05-30', 30, 2500.00, 0, 11),
(2, 'SQL Avanzado', '02:00 PM', '04:00 PM', '2025-06-01', '2025-06-30', 25, 3500.00, 0, 11),
(3, 'Fundamentos de Ciberseguridad', '10:00 AM', '12:00 PM', '2025-07-01', '2025-07-31', 35, 2800.00, 0, 12),
(4, 'Hacking Ético', '03:00 PM', '05:00 PM', '2025-08-01', '2025-08-31', 20, 4000.00, 0, 12),
(5, 'Illustrator desde Cero', '08:00 AM', '10:00 AM', '2025-09-01', '2025-09-30', 40, 3500.00, 0, 13),
(6, 'Diseño Avanzado en Illustrator', '01:00 PM', '03:00 PM', '2025-10-01', '2025-10-31', 30, 5000.00, 0, 13),
(7, 'SQL Online', '06:00 PM', '08:00 PM', '2025-05-01', '2025-05-30', 50, 2000.00, 0, 11),
(8, 'Ciberseguridad en Línea', '07:00 PM', '09:00 PM', '2025-06-01', '2025-06-30', 40, 2500.00, 0, 12),
(9, 'Workshop de Illustrator Online', '05:00 PM', '07:00 PM', '2025-07-01', '2025-07-31', 45, 3000.00, 0, 13);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
