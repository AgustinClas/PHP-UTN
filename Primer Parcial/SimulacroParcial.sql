-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 19-10-2021 a las 00:56:48
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `SimulacroParcial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(50) NOT NULL,
  `mail_usuario` text NOT NULL,
  `sabor` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `cantidad` int(50) NOT NULL,
  `fecha_de_registro` date NOT NULL,
  `numero_Pedido` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `mail_usuario`, `sabor`, `tipo`, `cantidad`, `fecha_de_registro`, `numero_Pedido`) VALUES
(19, 'agus@gmail.com', 'zanahoria', 'molde', 1, '2021-10-11', 1597),
(20, 'agus@gmail.com', 'muzza', 'molde', 1, '2021-10-19', 2184),
(21, 'agus23@gmail.com', 'JamonYMorrones', 'molde', 10, '2021-10-19', 8330),
(22, 'agus@gmail.com', 'muzza', 'molde', 12, '2021-10-19', 2889),
(23, 'agus@gmail.com', 'muzza', 'molde', 12, '2021-10-11', 8774),
(24, 'agus@gmail.com', 'muzza', 'molde', 12, '2021-10-19', 8140),
(25, 'agus@gmail.com', 'muzza', 'molde', 12, '2021-10-19', 2499),
(26, 'agus@gmail.com', 'anchoa', 'piedra', 12, '2021-10-19', 2589);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
