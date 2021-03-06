-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2022 a las 01:21:27
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cantidadhijos`
--

CREATE TABLE `cantidadhijos` (
  `id_cant_hijos` int(11) NOT NULL,
  `cant_hijos` varchar(20) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cantidadhijos`
--

INSERT INTO `cantidadhijos` (`id_cant_hijos`, `cant_hijos`) VALUES
(1, 'Ninguno'),
(2, 'Uno'),
(3, 'Dos'),
(4, 'Entre tres y cinco'),
(5, 'Más de cinco');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadocivil`
--

CREATE TABLE `estadocivil` (
  `id_est_civil` int(11) NOT NULL,
  `est_civil` varchar(250) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estadocivil`
--

INSERT INTO `estadocivil` (`id_est_civil`, `est_civil`) VALUES
(2, 'Soltero'),
(3, 'Casado'),
(6, 'Viudo'),
(7, 'Divorciado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `Id` int(11) NOT NULL,
  `Usuario_origen` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Usuario_destino` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Texto` varchar(10000) COLLATE utf8_spanish_ci NOT NULL,
  `FechaEnvio` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `ArchivoAdjunto` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipdoc`
--

CREATE TABLE `tipdoc` (
  `id_tipdoc` int(11) NOT NULL,
  `tip_doc` varchar(250) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipdoc`
--

INSERT INTO `tipdoc` (`id_tipdoc`, `tip_doc`) VALUES
(1, 'CEDULA DE CIUDADANIA'),
(2, 'CEDULA DE EXTRANJERIA'),
(5, 'TARJETA DE IDENTIDAD'),
(6, 'PASAPORTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tuits`
--

CREATE TABLE `tuits` (
  `Id_tuit` int(11) NOT NULL,
  `mensaje_tuit` varchar(140) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_tuit` datetime NOT NULL DEFAULT current_timestamp(),
  `id_usuario_tuit` int(11) NOT NULL,
  `Estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `nombres` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `id_tip_doc` int(11) NOT NULL,
  `num_doc` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `correo` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `id_num_hijos` int(11) NOT NULL,
  `id_est_civil` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `foto` varchar(250) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cantidadhijos`
--
ALTER TABLE `cantidadhijos`
  ADD PRIMARY KEY (`id_cant_hijos`);

--
-- Indices de la tabla `estadocivil`
--
ALTER TABLE `estadocivil`
  ADD PRIMARY KEY (`id_est_civil`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tipdoc`
--
ALTER TABLE `tipdoc`
  ADD PRIMARY KEY (`id_tipdoc`);

--
-- Indices de la tabla `tuits`
--
ALTER TABLE `tuits`
  ADD PRIMARY KEY (`Id_tuit`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cantidadhijos`
--
ALTER TABLE `cantidadhijos`
  MODIFY `id_cant_hijos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estadocivil`
--
ALTER TABLE `estadocivil`
  MODIFY `id_est_civil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `tipdoc`
--
ALTER TABLE `tipdoc`
  MODIFY `id_tipdoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tuits`
--
ALTER TABLE `tuits`
  MODIFY `Id_tuit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
