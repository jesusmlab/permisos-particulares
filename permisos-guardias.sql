-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-08-2023 a las 20:21:42
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `guardias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_gesprof`
--

CREATE TABLE `configuracion_gesprof` (
  `curso` varchar(5) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `topeConcesionesD` int(11) NOT NULL DEFAULT 0,
  `topeConcesionesV` int(11) NOT NULL DEFAULT 0,
  `codCausa` int(11) NOT NULL,
  `email_secretaria` varchar(255) NOT NULL,
  `email_direccion` varchar(255) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion_gesprof`
--

INSERT INTO `configuracion_gesprof` (`curso`, `fecha_inicio`, `fecha_fin`, `topeConcesionesD`, `topeConcesionesV`, `codCausa`, `email_secretaria`, `email_direccion`, `id`) VALUES
('23-24', '2023-09-01', '2024-08-30', 3, 3, 14, 'secretaria@tucentro.es', 'director@tucentro.es', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_gesprof`
--

CREATE TABLE `permisos_gesprof` (
  `usuario` varchar(20) NOT NULL,
  `horario` varchar(1) NOT NULL COMMENT '[D]iurno,[V]espertino',
  `npeticion` int(11) NOT NULL DEFAULT 0,
  `fecha_peticion` datetime NOT NULL,
  `fecha_pedida` date NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `nregistro` varchar(10) NOT NULL,
  `dia_lectivo` smallint(6) NOT NULL DEFAULT 0,
  `estado` varchar(1) NOT NULL COMMENT '[S]olicitado,[C]oncedido,[D]enegado,[R]egistrado',
  `falta_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_gesprof`
--

CREATE TABLE `usuarios_gesprof` (
  `usuario` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `apenom` varchar(35) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `rol` varchar(1) NOT NULL COMMENT '[D]irector,[J]efe de estudios,[P]rofesor',
  `horario` varchar(1) NOT NULL COMMENT '[D]iurno,[V]espertino',
  `email` varchar(255) NOT NULL,
  `codigoprof` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios_gesprof`
--

INSERT INTO `usuarios_gesprof` (`usuario`, `password`, `apenom`, `departamento`, `rol`, `horario`, `email`, `codigoprof`) VALUES
('admin', '$2y$10$jwrCCRwZpEesmckNTa9gVugEMQqKgcHa9PWNNtCPOJSDWSjJmBGSu', 'Usuario Administrador', '-', 'D', 'D', 'nada@nada.es', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion_gesprof`
--
ALTER TABLE `configuracion_gesprof`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos_gesprof`
--
ALTER TABLE `permisos_gesprof`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_gesprof`
--
ALTER TABLE `usuarios_gesprof`
  ADD PRIMARY KEY (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion_gesprof`
--
ALTER TABLE `configuracion_gesprof`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permisos_gesprof`
--
ALTER TABLE `permisos_gesprof`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
