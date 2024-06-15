-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-06-2024 a las 13:30:46
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `medicalapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('scheduled','confirmed','canceled') DEFAULT 'scheduled',
  `appointment_type` enum('in_person','online') NOT NULL DEFAULT 'in_person'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`, `appointment_type`) VALUES
(7, 1, 1, '2024-06-13 00:00:00', '13:30:00', '', 'in_person'),
(13, 1, 1, '2024-06-13 00:00:00', '13:00:00', 'scheduled', 'online'),
(14, 1, 1, '2024-06-13 00:00:00', '12:30:00', 'scheduled', 'in_person'),
(16, 1, 1, '2024-06-13 00:00:00', '15:00:00', 'scheduled', 'in_person'),
(17, 1, 2, '2024-06-13 00:00:00', '11:30:00', 'scheduled', 'online'),
(18, 1, 1, '2024-06-19 00:00:00', '13:00:00', 'scheduled', 'online'),
(19, 1, 1, '2024-06-28 00:00:00', '19:30:00', 'scheduled', 'online');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultationmessages`
--

CREATE TABLE `consultationmessages` (
  `message_id` int(11) NOT NULL,
  `consultation_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `consultationmessages`
--

INSERT INTO `consultationmessages` (`message_id`, `consultation_id`, `sender_id`, `message`, `sent_at`) VALUES
(48, 9, 2, 'hola', '2024-06-13 10:52:13'),
(49, 9, 1, 'hola', '2024-06-13 10:52:17'),
(50, 9, 2, 'hola', '2024-06-13 10:52:21'),
(51, 9, 1, 'hola', '2024-06-13 10:52:24'),
(52, 9, 1, 'soy paciente', '2024-06-13 10:52:30'),
(53, 9, 2, 'y yo doctor', '2024-06-13 10:52:35'),
(54, 9, 1, 'cuando papa nos adopto', '2024-06-13 10:52:45'),
(55, 9, 2, 'mama se desmayo', '2024-06-13 10:52:51'),
(56, 9, 1, 'hola', '2024-06-13 10:55:11'),
(57, 9, 2, 'hola', '2024-06-13 10:55:18'),
(58, 9, 1, 'como estas?', '2024-06-13 10:55:22'),
(59, 9, 2, 'muy bien y tu?', '2024-06-13 10:55:28'),
(62, 11, 1, 'hola', '2024-06-13 14:54:05'),
(63, 11, 3, 'hola', '2024-06-13 14:54:12'),
(64, 9, 2, 'prueba', '2024-06-15 11:27:46'),
(65, 13, 1, 'yup', '2024-06-15 11:29:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultations`
--

CREATE TABLE `consultations` (
  `consultation_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) NOT NULL,
  `consultation_date` datetime NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `consultations`
--

INSERT INTO `consultations` (`consultation_id`, `patient_id`, `doctor_id`, `appointment_id`, `consultation_date`, `notes`) VALUES
(9, 1, 1, 13, '2024-06-13 00:00:00', NULL),
(11, 1, 2, 17, '2024-06-13 00:00:00', NULL),
(12, 1, 1, 18, '2024-06-19 00:00:00', NULL),
(13, 1, 1, 19, '2024-06-28 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `specialties` varchar(255) DEFAULT NULL,
  `availability_days` varchar(255) DEFAULT NULL,
  `start_hour` time DEFAULT NULL,
  `end_hour` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `first_name`, `last_name`, `email`, `specialties`, `availability_days`, `start_hour`, `end_hour`) VALUES
(1, 2, 'admin', 'admin', 'admin2@gmail.com', 'Medicina General / Medicina de Familia', '1,2,3,5', '12:00:00', '20:00:00'),
(2, 3, 'rodolfo', 'lippo', 'rod@gmail.com', 'Pediatría', '1,2,3,4,5', '10:00:00', '20:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `insurance_info` varchar(255) DEFAULT NULL,
  `medical_history` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `first_name`, `last_name`, `email`, `contact_info`, `insurance_info`, `medical_history`) VALUES
(1, 1, 'admin', 'admin', 'admin@gmail.com', 'vivo en mi casa', 'ledezma', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `signals`
--

CREATE TABLE `signals` (
  `id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('patient','doctor') NOT NULL,
  `dni` varchar(20) NOT NULL,
  `birth_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `dni`, `birth_date`) VALUES
(1, 'admin1', '$2y$10$8kJ8DFZxJVQLNtXI7ZNrAOrBUEcfo9TwC/rtkZKS5ZXDc8Q19.pUC', 'patient', 'admin', '1994-05-04'),
(2, 'admin2', '$2y$10$3YABWl.cdJwbQ.fieJQ8Q.Wkt.6IQU3UXwzL4pMFgufCEou0HJHTK', 'doctor', 'admin', '1994-05-04'),
(3, 'admin3', '$2y$10$AJ9KUvF/LgW.C6/m0ilYUuSx/tj98OhICURlYgsCOzUx8e0UKyFEW', 'doctor', 'admin', '1994-01-04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `videoconsultations`
--

CREATE TABLE `videoconsultations` (
  `video_consultation_id` int(11) NOT NULL,
  `consultation_id` int(11) DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indices de la tabla `consultationmessages`
--
ALTER TABLE `consultationmessages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `consultation_id` (`consultation_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indices de la tabla `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`consultation_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indices de la tabla `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `signals`
--
ALTER TABLE `signals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consultation_id` (`consultation_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indices de la tabla `videoconsultations`
--
ALTER TABLE `videoconsultations`
  ADD PRIMARY KEY (`video_consultation_id`),
  ADD KEY `consultation_id` (`consultation_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `consultationmessages`
--
ALTER TABLE `consultationmessages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `consultations`
--
ALTER TABLE `consultations`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `signals`
--
ALTER TABLE `signals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `videoconsultations`
--
ALTER TABLE `videoconsultations`
  MODIFY `video_consultation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);

--
-- Filtros para la tabla `consultationmessages`
--
ALTER TABLE `consultationmessages`
  ADD CONSTRAINT `consultationmessages_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`consultation_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultationmessages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`),
  ADD CONSTRAINT `consultations_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `signals`
--
ALTER TABLE `signals`
  ADD CONSTRAINT `signals_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`consultation_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `videoconsultations`
--
ALTER TABLE `videoconsultations`
  ADD CONSTRAINT `videoconsultations_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`consultation_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
