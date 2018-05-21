-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2018 a las 21:51:45
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `asesoriaspar`
--
CREATE DATABASE IF NOT EXISTS `asesoriaspar` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `asesoriaspar`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `advisory_request`
--

CREATE TABLE `advisory_request` (
  `advisory_id` bigint(20) NOT NULL,
  `date_register` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `fk_adviser` bigint(20) DEFAULT NULL,
  `fk_student` bigint(20) NOT NULL,
  `fk_subject` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `career`
--

CREATE TABLE `career` (
  `career_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `date_register` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `day_and_hour`
--

CREATE TABLE `day_and_hour` (
  `day_hour_id` int(11) NOT NULL,
  `hour` time NOT NULL,
  `day` varchar(20) NOT NULL,
  `day_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `day_and_hour`
--

INSERT INTO `day_and_hour` (`day_hour_id`, `hour`, `day`, `day_number`) VALUES
(1, '08:00:00', 'Lunes', 1),
(2, '09:00:00', 'Lunes', 1),
(3, '10:00:00', 'Lunes', 1),
(4, '11:00:00', 'Lunes', 1),
(5, '12:00:00', 'Lunes', 1),
(6, '13:00:00', 'Lunes', 1),
(7, '14:00:00', 'Lunes', 1),
(8, '15:00:00', 'Lunes', 1),
(9, '16:00:00', 'Lunes', 1),
(10, '17:00:00', 'Lunes', 1),
(11, '18:00:00', 'Lunes', 1),
(12, '08:00:00', 'Martes', 2),
(13, '09:00:00', 'Martes', 2),
(14, '10:00:00', 'Martes', 2),
(15, '11:00:00', 'Martes', 2),
(16, '12:00:00', 'Martes', 2),
(17, '13:00:00', 'Martes', 2),
(18, '14:00:00', 'Martes', 2),
(19, '15:00:00', 'Martes', 2),
(20, '16:00:00', 'Martes', 2),
(21, '17:00:00', 'Martes', 2),
(22, '18:00:00', 'Martes', 2),
(23, '08:00:00', 'Miercoles', 3),
(24, '09:00:00', 'Miercoles', 3),
(25, '10:00:00', 'Miercoles', 3),
(26, '11:00:00', 'Miercoles', 3),
(27, '12:00:00', 'Miercoles', 3),
(28, '13:00:00', 'Miercoles', 3),
(29, '14:00:00', 'Miercoles', 3),
(30, '15:00:00', 'Miercoles', 3),
(31, '16:00:00', 'Miercoles', 3),
(32, '17:00:00', 'Miercoles', 3),
(33, '18:00:00', 'Miercoles', 3),
(34, '08:00:00', 'Jueves', 4),
(35, '09:00:00', 'Jueves', 4),
(36, '10:00:00', 'Jueves', 4),
(37, '11:00:00', 'Jueves', 4),
(38, '12:00:00', 'Jueves', 4),
(39, '13:00:00', 'Jueves', 4),
(40, '14:00:00', 'Jueves', 4),
(41, '15:00:00', 'Jueves', 4),
(42, '16:00:00', 'Jueves', 4),
(43, '17:00:00', 'Jueves', 4),
(44, '18:00:00', 'Jueves', 4),
(45, '08:00:00', 'Viernes', 5),
(46, '09:00:00', 'Viernes', 5),
(47, '10:00:00', 'Viernes', 5),
(48, '11:00:00', 'Viernes', 5),
(49, '12:00:00', 'Viernes', 5),
(50, '13:00:00', 'Viernes', 5),
(51, '14:00:00', 'Viernes', 5),
(52, '15:00:00', 'Viernes', 5),
(53, '16:00:00', 'Viernes', 5),
(54, '17:00:00', 'Viernes', 5),
(55, '18:00:00', 'Viernes', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `period`
--

CREATE TABLE `period` (
  `period_id` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `date_register` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `plan_id` bigint(20) NOT NULL,
  `year` varchar(4) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `register_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE `role` (
  `name` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`name`, `description`) VALUES
('admin', 'Control de asesorias, usuarios, registros, etc.'),
('basic', 'Solo el control de su perfil de usuario y solicitu de asesorias'),
('moderator', 'Control de asesorias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedule`
--

CREATE TABLE `schedule` (
  `schedule_id` bigint(20) NOT NULL,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `fk_student` bigint(20) NOT NULL,
  `fk_period` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedule_days_hours`
--

CREATE TABLE `schedule_days_hours` (
  `schedule_dh_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `fk_day_hour` int(11) NOT NULL,
  `fk_schedule` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedule_subjects`
--

CREATE TABLE `schedule_subjects` (
  `schedule_subject_id` bigint(20) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = NO, 1 = SI',
  `date_register` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `fk_schedule` bigint(20) NOT NULL,
  `fk_subject` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student`
--

CREATE TABLE `student` (
  `student_id` bigint(20) NOT NULL,
  `itson_id` varchar(10) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `date_register` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `fk_user` bigint(20) NOT NULL,
  `fk_career` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject`
--

CREATE TABLE `subject` (
  `subject_id` bigint(20) NOT NULL,
  `semester` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_register` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `fk_career` bigint(20) NOT NULL,
  `fk_plan` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject_similary`
--

CREATE TABLE `subject_similary` (
  `pk_similary` bigint(20) NOT NULL,
  `fk_subject_1` bigint(20) NOT NULL,
  `fk_subject_2` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `user_id` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `fk_role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `register_date`, `status`, `fk_role`) VALUES
(1, 'c_01_12@gmail.com', 'd5aa1729c8c253e5d917a5264855eab8', '2018-05-21 19:47:44', 1, 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `advisory_request`
--
ALTER TABLE `advisory_request`
  ADD PRIMARY KEY (`advisory_id`),
  ADD KEY `fk_adviser` (`fk_adviser`),
  ADD KEY `fk_student` (`fk_student`),
  ADD KEY `fk_subject` (`fk_subject`);

--
-- Indices de la tabla `career`
--
ALTER TABLE `career`
  ADD PRIMARY KEY (`career_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `short_name` (`short_name`);

--
-- Indices de la tabla `day_and_hour`
--
ALTER TABLE `day_and_hour`
  ADD PRIMARY KEY (`day_hour_id`);

--
-- Indices de la tabla `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`period_id`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`name`);

--
-- Indices de la tabla `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `fk_student` (`fk_student`),
  ADD KEY `fk_period` (`fk_period`);

--
-- Indices de la tabla `schedule_days_hours`
--
ALTER TABLE `schedule_days_hours`
  ADD PRIMARY KEY (`schedule_dh_id`),
  ADD KEY `fk_day_hour` (`fk_day_hour`),
  ADD KEY `fk_schedule` (`fk_schedule`);

--
-- Indices de la tabla `schedule_subjects`
--
ALTER TABLE `schedule_subjects`
  ADD PRIMARY KEY (`schedule_subject_id`),
  ADD KEY `fk_schedule` (`fk_schedule`),
  ADD KEY `fk_subject` (`fk_subject`);

--
-- Indices de la tabla `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `fk_user` (`fk_user`),
  ADD KEY `fk_career` (`fk_career`);

--
-- Indices de la tabla `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `fk_career` (`fk_career`),
  ADD KEY `fk_plan` (`fk_plan`);

--
-- Indices de la tabla `subject_similary`
--
ALTER TABLE `subject_similary`
  ADD PRIMARY KEY (`pk_similary`),
  ADD KEY `fk_subject_1` (`fk_subject_1`),
  ADD KEY `fk_subject_2` (`fk_subject_2`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_role` (`fk_role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `advisory_request`
--
ALTER TABLE `advisory_request`
  MODIFY `advisory_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `career`
--
ALTER TABLE `career`
  MODIFY `career_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `day_and_hour`
--
ALTER TABLE `day_and_hour`
  MODIFY `day_hour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `period`
--
ALTER TABLE `period`
  MODIFY `period_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `plan_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `schedule_days_hours`
--
ALTER TABLE `schedule_days_hours`
  MODIFY `schedule_dh_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `schedule_subjects`
--
ALTER TABLE `schedule_subjects`
  MODIFY `schedule_subject_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `student`
--
ALTER TABLE `student`
  MODIFY `student_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subject_similary`
--
ALTER TABLE `subject_similary`
  MODIFY `pk_similary` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `advisory_request`
--
ALTER TABLE `advisory_request`
  ADD CONSTRAINT `advisory_request_ibfk_1` FOREIGN KEY (`fk_adviser`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `advisory_request_ibfk_2` FOREIGN KEY (`fk_student`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `advisory_request_ibfk_3` FOREIGN KEY (`fk_subject`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`fk_student`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`fk_period`) REFERENCES `period` (`period_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `schedule_days_hours`
--
ALTER TABLE `schedule_days_hours`
  ADD CONSTRAINT `schedule_days_hours_ibfk_1` FOREIGN KEY (`fk_day_hour`) REFERENCES `day_and_hour` (`day_hour_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_days_hours_ibfk_2` FOREIGN KEY (`fk_schedule`) REFERENCES `schedule` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `schedule_subjects`
--
ALTER TABLE `schedule_subjects`
  ADD CONSTRAINT `schedule_subjects_ibfk_1` FOREIGN KEY (`fk_schedule`) REFERENCES `schedule` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_subjects_ibfk_2` FOREIGN KEY (`fk_subject`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`fk_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`fk_career`) REFERENCES `career` (`career_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`fk_career`) REFERENCES `career` (`career_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_ibfk_2` FOREIGN KEY (`fk_plan`) REFERENCES `plan` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `subject_similary`
--
ALTER TABLE `subject_similary`
  ADD CONSTRAINT `subject_similary_ibfk_1` FOREIGN KEY (`fk_subject_1`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_similary_ibfk_2` FOREIGN KEY (`fk_subject_2`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`fk_role`) REFERENCES `role` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
