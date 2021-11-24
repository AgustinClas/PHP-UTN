-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 16-11-2021 a las 00:52:26
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
-- Base de datos: `SegundoParcial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criptoMonedas`
--

CREATE TABLE `criptoMonedas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `precio` float NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `criptoMonedas`
--

INSERT INTO `criptoMonedas` (`id`, `nombre`, `nacionalidad`, `precio`, `estado`) VALUES
(1, 'agusCoin', 'argentina', 10, 'baja'),
(2, 'BocaCoin', 'brasilia', 10, 'activo'),
(3, 'BeerCoin', 'alemania', 10, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `clave` varchar(80) NOT NULL,
  `tipo` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `mail`, `clave`, `tipo`) VALUES
(1, 'agus@gmail.com', '$2y$10$mKfgk63qLw3E5y2LZ57/U.P2CXTeS/SrndOA6dRYDxp3FS7/kcH9S', 'admin'),
(2, 'agusCliente@gmail.com', '$2y$10$oCh78Bs.pGF/.pz0IFuli.ohM.8tO4wcykwleNMcGHa5aN8c2phGK', 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` int(10) NOT NULL,
  `cliente` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `nacionalidad` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `fecha`, `cantidad`, `cliente`, `nombre`, `nacionalidad`) VALUES
(1, '2021-11-15', 11, 'agus12@gmail.com', 'agusCoin', 'argentina'),
(2, '2021-06-11', 1, 'agus@gmail.com', 'BeerCoin', 'alemania');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `criptoMonedas`
--
ALTER TABLE `criptoMonedas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `criptoMonedas`
--
ALTER TABLE `criptoMonedas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
