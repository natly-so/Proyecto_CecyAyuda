-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-06-2025 a las 08:25:30
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
-- Base de datos: `cecyayuda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncia`
--

CREATE TABLE `denuncia` (
  `codigo` int(11) NOT NULL,
  `correo_d` varchar(200) NOT NULL,
  `tipo_v` text NOT NULL,
  `tipo_p` text NOT NULL,
  `archivo` blob NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denunciante`
--

CREATE TABLE `denunciante` (
  `control` text NOT NULL,
  `sexo` text NOT NULL,
  `semestre` text NOT NULL,
  `nombre` text NOT NULL,
  `edad` int(2) NOT NULL,
  `correo` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `denunciante`
--

INSERT INTO `denunciante` (`control`, `sexo`, `semestre`, `nombre`, `edad`, `correo`) VALUES
('23415082010906', 'Masculino', '3° Semestre', 'Daniela Diaz', 13, 'daniela@gmail.com'),
('23415082010360', 'Femenino', '2° Semestre', 'Daniela Vazquez', 14, 'daniela@gmail.com'),
('23415082010362', 'Femenino', '4', 'Daniela Vazquez', 14, 'nataly12@gmail.com'),
('23415082010905', 'Masculino', '3° Semestre', 'Daniela Diaz', 13, 'daniela@gmail.com'),
('23415082010367', 'Femenino', '4', 'Daniela Vazquez', 19, 'nataly12@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `denuncia`
--
ALTER TABLE `denuncia`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `denunciante`
--
ALTER TABLE `denunciante`
  ADD PRIMARY KEY (`control`(20));

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `denuncia`
--
ALTER TABLE `denuncia`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
