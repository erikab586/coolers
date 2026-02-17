-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-10-2025 a las 20:16:27
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
-- Base de datos: `bd_cooleroficial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camara`
--

CREATE TABLE `camara` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idcooler` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `capacidadminima` int(11) NOT NULL,
  `capacidadmaxima` int(11) NOT NULL,
  `tipo` enum('PRE ENFRIADO','CONSERVACIÓN','CRUCE DE ANDÉN') NOT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `camara`
--

INSERT INTO `camara` (`id`, `idcooler`, `codigo`, `capacidadminima`, `capacidadmaxima`, `tipo`, `estatus`, `created_at`, `updated_at`) VALUES
(3, 2, '3', 4, 14, 'PRE ENFRIADO', 'activo', '2025-07-18 06:18:34', '2025-07-18 06:18:34'),
(4, 2, '4', 4, 14, 'PRE ENFRIADO', 'activo', '2025-07-18 06:18:35', '2025-07-18 06:18:35'),
(5, 3, '1', 4, 14, 'PRE ENFRIADO', 'activo', '2025-07-18 07:12:46', '2025-07-18 07:12:46'),
(8, 3, '2', 4, 14, 'PRE ENFRIADO', 'activo', '2025-07-18 07:21:12', '2025-07-18 07:21:12'),
(9, 3, '3', 4, 14, 'PRE ENFRIADO', 'activo', '2025-07-18 07:21:12', '2025-07-18 07:21:12'),
(10, 3, '4', 4, 14, 'CONSERVACIÓN', 'activo', '2025-07-18 07:21:12', '2025-07-18 07:21:12'),
(11, 2, '1', 4, 14, 'CONSERVACIÓN', 'activo', '2025-07-21 19:37:16', '2025-07-21 19:37:16'),
(18, 5, 'Cámara 1', 4, 14, 'PRE ENFRIADO', 'activo', '2025-10-11 12:08:05', '2025-10-11 12:08:05'),
(19, 5, 'Cámara 2', 4, 14, 'PRE ENFRIADO', 'activo', '2025-10-11 12:08:05', '2025-10-11 12:08:05'),
(20, 5, 'Cámara 3', 4, 14, 'CONSERVACIÓN', 'activo', '2025-10-11 12:08:05', '2025-10-11 12:08:05'),
(21, 5, 'Cámara 4', 4, 14, 'CONSERVACIÓN', 'activo', '2025-10-11 12:08:05', '2025-10-11 12:08:05'),
(22, 7, '01', 4, 14, 'PRE ENFRIADO', 'activo', '2025-10-17 16:36:34', '2025-10-17 16:36:34'),
(23, 7, '02', 4, 14, 'PRE ENFRIADO', 'activo', '2025-10-17 16:36:34', '2025-10-17 16:36:34'),
(24, 7, '03', 4, 14, 'CONSERVACIÓN', 'activo', '2025-10-17 16:36:34', '2025-10-17 16:36:34'),
(28, 2, 'Cámara 1', 4, 14, 'CRUCE DE ANDÉN', 'activo', '2025-10-17 21:13:11', '2025-10-17 21:13:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobranzas`
--

CREATE TABLE `cobranzas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idrecepcion` bigint(20) UNSIGNED NOT NULL,
  `iddetallerecepcion` bigint(20) UNSIGNED NOT NULL,
  `folio` varchar(255) NOT NULL,
  `fruta` varchar(255) NOT NULL,
  `presentacion` varchar(255) NOT NULL,
  `variedad` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `monto_preenfriado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_conservacion` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_anden` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_conservacion` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_preenfriado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tiempo_preenfriado` int(11) NOT NULL DEFAULT 0,
  `tiempo_conservacion` int(11) NOT NULL DEFAULT 0,
  `tiempo_anden` int(11) NOT NULL DEFAULT 0,
  `moneda` varchar(255) NOT NULL DEFAULT 'MXN',
  `dia_recepcion` varchar(255) DEFAULT NULL,
  `fecha_recepcion` date DEFAULT NULL,
  `estatus` enum('PAGADA','PENDIENTE') NOT NULL DEFAULT 'PENDIENTE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cobranzas`
--

INSERT INTO `cobranzas` (`id`, `idrecepcion`, `iddetallerecepcion`, `folio`, `fruta`, `presentacion`, `variedad`, `cantidad`, `monto_preenfriado`, `monto_conservacion`, `monto_anden`, `total_conservacion`, `total_preenfriado`, `tiempo_preenfriado`, `tiempo_conservacion`, `tiempo_anden`, `moneda`, `dia_recepcion`, `fecha_recepcion`, `estatus`, `created_at`, `updated_at`) VALUES
(1, 19, 36, 'N°-A00004', 'Berries', '1LB', 'Orgánica', 120, 0.00, 36.00, 0.00, 36.00, 0.00, 0, 60, 0, 'MXN', 'miércoles', '2025-10-15', 'PAGADA', '2025-10-17 02:29:15', '2025-10-17 16:16:14'),
(2, 18, 35, 'N°-A00003', 'Fresa', '1LB', 'Orgánica', 140, 0.00, 42.00, 0.00, 42.00, 0.00, 0, 60, 0, 'MXN', 'miércoles', '2025-10-15', 'PAGADA', '2025-10-17 02:29:15', '2025-10-17 14:08:20'),
(3, 19, 36, 'N°-A00004', 'Berries', '1LB', 'Orgánica', 120, 0.00, 36.00, 0.00, 36.00, 0.00, 0, 60, 0, 'MXN', 'miércoles', '2025-10-15', 'PAGADA', '2025-10-17 02:29:15', '2025-10-17 14:18:12'),
(4, 18, 35, 'N°-A00003', 'Fresa', '1LB', 'Orgánica', 140, 0.00, 42.00, 0.00, 42.00, 0.00, 0, 60, 0, 'MXN', 'miércoles', '2025-10-15', 'PENDIENTE', '2025-10-17 02:29:15', '2025-10-17 02:29:15'),
(5, 22, 39, 'N°-A00007', 'Fresa', '2LB', 'Orgánica', 30, 333.50, -26.55, 0.00, -26.55, 333.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 16:59:04', '2025-10-17 17:12:45'),
(6, 21, 38, 'N°-A00006', 'Fresa', '2LB', 'Orgánica', 210, 2334.50, -185.85, 0.00, -185.85, 2334.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 16:59:04', '2025-10-17 16:59:04'),
(7, 22, 39, 'N°-A00007', 'Fresa', '2LB', 'Orgánica', 30, 333.50, -26.55, 0.00, -26.55, 333.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 16:59:04', '2025-10-17 16:59:04'),
(8, 21, 38, 'N°-A00006', 'Fresa', '2LB', 'Orgánica', 210, 2334.50, -185.85, 0.00, -185.85, 2334.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 16:59:04', '2025-10-17 16:59:04'),
(9, 22, 39, 'N°-A00007', 'Fresa', '2LB', 'Orgánica', 30, 333.50, -26.55, 0.00, -26.55, 333.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 17:00:47', '2025-10-17 17:00:47'),
(10, 21, 38, 'N°-A00006', 'Fresa', '2LB', 'Orgánica', 210, 2334.50, -185.85, 0.00, -185.85, 2334.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 17:00:47', '2025-10-17 17:00:47'),
(11, 22, 39, 'N°-A00007', 'Fresa', '2LB', 'Orgánica', 30, 333.50, -26.55, 0.00, -26.55, 333.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 17:00:47', '2025-10-17 17:00:47'),
(12, 21, 38, 'N°-A00006', 'Fresa', '2LB', 'Orgánica', 210, 2334.50, -185.85, 0.00, -185.85, 2334.50, 1334, -177, 0, 'MXN', 'viernes', '2025-10-17', 'PENDIENTE', '2025-10-17 17:00:47', '2025-10-17 17:00:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comercializadora`
--

CREATE TABLE `comercializadora` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rfc` varchar(255) NOT NULL,
  `nombrerepresentante` varchar(255) NOT NULL,
  `numtelefono` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `banco` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `abreviatura` varchar(255) NOT NULL,
  `imgcomercializadora` varchar(255) DEFAULT NULL,
  `nombrecomercializadora` varchar(255) NOT NULL,
  `estatus` varchar(255) NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `comercializadora`
--

INSERT INTO `comercializadora` (`id`, `rfc`, `nombrerepresentante`, `numtelefono`, `correo`, `banco`, `clave`, `abreviatura`, `imgcomercializadora`, `nombrecomercializadora`, `estatus`, `created_at`, `updated_at`) VALUES
(12, 'GBF131024LX3', 'LGUTIERREZ, JBERNAL, RFERNANDEZ', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'CALGIANT', 'imagenes/comercializadoras/1760384947_68ed57b34a3a3.png', 'CALGIANT', 'activo', '2025-10-11 20:14:56', '2025-10-13 16:49:07'),
(13, 'XEXX010101000', 'CESAR@GOODFARMS, LOGISTCA AWP', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'AW', 'imagenes/comercializadoras/1760384956_68ed57bc188e6.png', 'AW', 'activo', '2025-10-11 20:18:54', '2025-10-13 16:49:16'),
(14, 'BAAG821016I20', 'ADMONCAMPOVERDE', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'CAMPO VERDE', 'imagenes/comercializadoras/1760384965_68ed57c5c7f86.png', 'CAMPO VERDE', 'activo', '2025-10-11 20:21:27', '2025-10-13 16:49:25'),
(15, 'CFR220309GC2', 'MANUEL CASTRO', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'CASHER', 'imagenes/comercializadoras/1760392358_68ed74a6007c6.png', 'CASHER FRUITS', 'activo', '2025-10-13 18:52:38', '2025-10-13 18:52:38'),
(16, 'BSE221228GB6', 'prueba', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'SEASONS', 'imagenes/comercializadoras/1760392446_68ed74fe245c5.png', 'BERRY SEASONS', 'activo', '2025-10-13 18:54:06', '2025-10-13 18:54:06'),
(17, 'RFR1607266R5', 'FERNANDO.GARIBAY, RCG FACTURACION', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'RGC', 'imagenes/comercializadoras/1760392727_68ed7617f0ea7.png', 'RCG FRUITS', 'activo', '2025-10-13 18:58:47', '2025-10-13 18:58:47'),
(18, 'CAPF930905RP7', 'FERCHOSBERRYS', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'FER CAMPOS', 'imagenes/comercializadoras/1760398623_68ed8d1f74daf.png', 'FERNANDO CAMPOS PEREZ', 'activo', '2025-10-13 20:37:03', '2025-10-13 20:37:03'),
(19, 'CMF2005041V7', 'AGRICOLAMC', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'MECER', 'imagenes/comercializadoras/1760401616_68ed98d004516.png', 'COMERCIALIZADORA MECER FRESH', 'activo', '2025-10-13 21:26:56', '2025-10-13 21:26:56'),
(20, 'DBF220315LD4', 'NACHO CERVNANTES', '123456789', 'comercializadora@ejemplo.com', 'Prueba', 'Prueba123', 'DRAGON', 'imagenes/comercializadoras/1760401735_68ed99475685f.png', 'DRAGON BERRIES FARMS', 'activo', '2025-10-13 21:28:55', '2025-10-13 21:28:55'),
(22, 'GABO654213GGLAGD04', 'juan manuel', '3516458913548', 'manuelito@gmail.com', 'bancomer', '1254687459654123', 'MAD', 'imagenes/comercializadoras/1760726130_68f28c7224a5e.png', 'MADARI', 'activo', '2025-10-17 16:35:30', '2025-10-17 16:35:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conservacion`
--

CREATE TABLE `conservacion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idcamara` bigint(20) UNSIGNED NOT NULL,
  `idtarima` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conservacion`
--

INSERT INTO `conservacion` (`id`, `idcamara`, `idtarima`, `created_at`, `updated_at`) VALUES
(9, 10, 1063, '2025-10-15 23:17:42', '2025-10-15 23:17:42'),
(10, 10, 1062, '2025-10-17 02:27:43', '2025-10-17 02:27:43'),
(11, 10, 1064, '2025-10-17 16:28:35', '2025-10-17 16:28:35'),
(12, 10, 1068, '2025-10-17 16:41:23', '2025-10-17 16:41:23'),
(13, 10, 1068, '2025-10-17 16:48:18', '2025-10-17 16:48:18'),
(14, 11, 1070, '2025-10-17 23:07:31', '2025-10-17 23:07:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato`
--

CREATE TABLE `contrato` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipocliente` enum('EXPORTACIÓN','IMPORTACIÓN') NOT NULL,
  `tipocontrato` enum('TEMPORADA','ANUAL') NOT NULL,
  `idcomercializadora` bigint(20) UNSIGNED NOT NULL,
  `idusuario` bigint(20) UNSIGNED NOT NULL,
  `idcooler` bigint(20) UNSIGNED NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estatus` varchar(255) NOT NULL DEFAULT 'activo',
  `fechacontrato` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contrato`
--

INSERT INTO `contrato` (`id`, `tipocliente`, `tipocontrato`, `idcomercializadora`, `idusuario`, `idcooler`, `imagen`, `estatus`, `fechacontrato`, `created_at`, `updated_at`) VALUES
(9, 'EXPORTACIÓN', 'TEMPORADA', 12, 8, 3, NULL, 'activo', '2025-10-13', '2025-10-13 16:30:04', '2025-10-13 23:46:51'),
(10, 'EXPORTACIÓN', 'TEMPORADA', 12, 8, 5, NULL, 'activo', '2025-10-13', '2025-10-13 23:41:02', '2025-10-13 23:41:02'),
(11, 'EXPORTACIÓN', 'TEMPORADA', 12, 8, 6, NULL, 'activo', '2025-10-13', '2025-10-13 23:42:03', '2025-10-13 23:42:03'),
(12, 'EXPORTACIÓN', 'TEMPORADA', 13, 8, 5, NULL, 'activo', '2025-10-13', '2025-10-13 23:48:27', '2025-10-13 23:48:27'),
(13, 'EXPORTACIÓN', 'TEMPORADA', 14, 8, 3, NULL, 'activo', '2025-10-13', '2025-10-13 23:49:26', '2025-10-13 23:49:26'),
(14, 'EXPORTACIÓN', 'TEMPORADA', 15, 8, 2, NULL, 'activo', '2025-10-13', '2025-10-13 23:50:18', '2025-10-13 23:50:18'),
(15, 'EXPORTACIÓN', 'TEMPORADA', 16, 8, 5, NULL, 'activo', '2025-10-13', '2025-10-13 23:52:07', '2025-10-13 23:52:07'),
(16, 'EXPORTACIÓN', 'TEMPORADA', 17, 8, 2, NULL, 'activo', '2025-10-13', '2025-10-13 23:52:56', '2025-10-13 23:52:56'),
(17, 'EXPORTACIÓN', 'TEMPORADA', 18, 8, 3, NULL, 'activo', '2025-10-13', '2025-10-13 23:53:38', '2025-10-13 23:53:38'),
(18, 'EXPORTACIÓN', 'TEMPORADA', 19, 8, 3, NULL, 'activo', '2025-10-13', '2025-10-13 23:54:42', '2025-10-13 23:54:42'),
(19, 'EXPORTACIÓN', 'TEMPORADA', 20, 8, 3, NULL, 'activo', '2025-10-13', '2025-10-13 23:56:33', '2025-10-13 23:56:33'),
(20, 'EXPORTACIÓN', 'TEMPORADA', 20, 8, 5, NULL, 'activo', '2025-10-13', '2025-10-13 23:57:02', '2025-10-13 23:57:02'),
(21, 'IMPORTACIÓN', 'ANUAL', 22, 1, 7, NULL, 'activo', '2025-10-17', '2025-10-17 16:37:40', '2025-10-17 16:37:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cooler`
--

CREATE TABLE `cooler` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombrecooler` varchar(255) NOT NULL,
  `codigoidentificador` varchar(255) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cooler`
--

INSERT INTO `cooler` (`id`, `nombrecooler`, `codigoidentificador`, `ubicacion`, `estatus`, `created_at`, `updated_at`) VALUES
(2, 'MEGABASTOS', '8050', 'La Rinconada Dirección Melones esq. Jitomates S/N  Col La Rinconada', 'activo', '2025-07-18 04:08:18', '2025-07-18 04:08:18'),
(3, 'MEGABASTOS II', '8051', 'La Rinconada Dirección Melones esq. Jitomates S/N  Col La Rinconada II', 'activo', '2025-07-18 07:00:37', '2025-07-18 07:00:37'),
(5, 'TANGANCÍCUARO', '0007', 'Tangancícuaro Dirección Carretera Zamora – Morelia #1010 Col. Camécuaro Rio adentro', 'activo', '2025-10-11 12:02:55', '2025-10-11 12:02:55'),
(6, 'La Luz', '01', 'La Luz', 'activo', '2025-10-11 12:03:32', '2025-10-11 12:03:32'),
(7, 'PRUEBA 1', '002', 'LA ESTANCIA DE AMEZCUA', 'activo', '2025-10-17 16:36:07', '2025-10-17 16:36:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruce_anden`
--

CREATE TABLE `cruce_anden` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idtarima` bigint(20) UNSIGNED NOT NULL,
  `idcamara` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cruce_anden`
--

INSERT INTO `cruce_anden` (`id`, `idtarima`, `idcamara`, `created_at`, `updated_at`) VALUES
(1, 1071, 28, '2025-10-17 23:13:46', '2025-10-17 23:13:46'),
(2, 1071, 28, '2025-10-17 23:15:42', '2025-10-17 23:15:42'),
(3, 1071, 28, '2025-10-17 23:16:58', '2025-10-17 23:16:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_conservacion`
--

CREATE TABLE `detalle_conservacion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idconservacion` bigint(20) UNSIGNED NOT NULL,
  `iddetalle` bigint(20) UNSIGNED NOT NULL,
  `hora_entrada` time NOT NULL,
  `temperatura_entrada` decimal(5,2) NOT NULL,
  `hora_salida` time DEFAULT NULL,
  `temperatura_salida` decimal(5,2) DEFAULT NULL,
  `tiempototal` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_conservacion`
--

INSERT INTO `detalle_conservacion` (`id`, `idconservacion`, `iddetalle`, `hora_entrada`, `temperatura_entrada`, `hora_salida`, `temperatura_salida`, `tiempototal`, `created_at`, `updated_at`) VALUES
(14, 9, 37, '23:17:00', 0.00, '00:17:00', 0.00, 1380, '2025-10-15 23:17:58', '2025-10-15 23:17:58'),
(15, 10, 36, '02:27:00', 0.00, '03:27:00', NULL, 60, '2025-10-17 02:27:57', '2025-10-17 02:27:57'),
(16, 10, 35, '02:27:00', 0.00, '03:27:00', NULL, 60, '2025-10-17 02:27:57', '2025-10-17 02:27:57'),
(17, 12, 39, '23:47:00', 0.00, '21:41:00', NULL, 126, '2025-10-17 16:50:20', '2025-10-17 20:41:51'),
(18, 12, 38, '23:47:00', 0.00, '20:41:00', NULL, 186, '2025-10-17 16:50:20', '2025-10-17 20:41:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_contrato`
--

CREATE TABLE `detalle_contrato` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idcontrato` bigint(20) UNSIGNED NOT NULL,
  `idfruta` bigint(20) UNSIGNED NOT NULL,
  `idvariedad` bigint(20) UNSIGNED NOT NULL,
  `idpresentacion` bigint(20) UNSIGNED DEFAULT NULL,
  `tiposervicio` varchar(15) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `moneda` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_contrato`
--

INSERT INTO `detalle_contrato` (`id`, `idcontrato`, `idfruta`, `idvariedad`, `idpresentacion`, `tiposervicio`, `monto`, `moneda`, `created_at`, `updated_at`) VALUES
(34, 9, 11, 2, 2, 'preenfrio', 11.00, 'PESO', '2025-10-13 16:30:04', '2025-10-13 16:30:04'),
(35, 9, 17, 2, 2, 'preenfrio', 0.45, 'DOLAR', '2025-10-13 18:45:24', '2025-10-13 18:45:24'),
(36, 10, 17, 2, 2, 'preenfrio', 11.00, 'PESO', '2025-10-13 23:41:02', '2025-10-13 23:41:02'),
(37, 10, 11, 2, 2, 'preenfrio', 0.45, 'DOLAR', '2025-10-13 23:41:02', '2025-10-13 23:41:02'),
(38, 11, 17, 2, 2, 'preenfrio', 11.00, 'PESO', '2025-10-13 23:42:03', '2025-10-13 23:42:03'),
(39, 11, 11, 2, 2, 'preenfrio', 0.45, 'DOLAR', '2025-10-13 23:42:03', '2025-10-13 23:42:03'),
(40, 12, 10, 2, 2, 'preenfrio', 0.43, 'DOLAR', '2025-10-13 23:48:27', '2025-10-13 23:48:27'),
(41, 13, 10, 2, 2, 'preenfrio', 8.00, 'PESO', '2025-10-13 23:49:26', '2025-10-13 23:49:26'),
(42, 14, 10, 2, 2, 'preenfrio', 0.43, 'DOLAR', '2025-10-13 23:50:18', '2025-10-13 23:50:18'),
(43, 15, 10, 2, 2, 'preenfrio', 0.43, 'DOLAR', '2025-10-13 23:52:07', '2025-10-13 23:52:07'),
(44, 16, 10, 2, 2, 'preenfrio', 0.43, 'DOLAR', '2025-10-13 23:52:56', '2025-10-13 23:52:56'),
(45, 17, 11, 2, 2, 'preenfrio', 7.00, 'PESO', '2025-10-13 23:53:38', '2025-10-13 23:53:38'),
(46, 18, 10, 2, 2, 'preenfrio', 0.43, 'DOLAR', '2025-10-13 23:54:42', '2025-10-13 23:54:42'),
(47, 19, 11, 2, 2, 'preenfrio', 0.43, 'DOLAR', '2025-10-13 23:56:33', '2025-10-13 23:56:33'),
(48, 20, 10, 2, 2, 'preenfrio', 0.43, 'DOLAR', '2025-10-13 23:57:02', '2025-10-13 23:57:02'),
(49, 21, 11, 2, 3, 'preenfrio', 8.00, 'PESO', '2025-10-17 16:37:40', '2025-10-17 16:37:40'),
(50, 21, 11, 2, 3, 'conservacion', 6.00, 'PESO', '2025-10-17 16:37:40', '2025-10-17 16:37:40'),
(51, 21, 11, 2, 3, 'anden', 4.00, 'PESO', '2025-10-17 16:37:40', '2025-10-17 16:37:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cruce_anden`
--

CREATE TABLE `detalle_cruce_anden` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idcruce_anden` bigint(20) UNSIGNED NOT NULL,
  `iddetalle` bigint(20) UNSIGNED NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `temperatura_entrada` decimal(5,2) DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `temperatura_salida` decimal(5,2) DEFAULT NULL,
  `tiempototal` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_cruce_anden`
--

INSERT INTO `detalle_cruce_anden` (`id`, `idcruce_anden`, `iddetalle`, `hora_entrada`, `temperatura_entrada`, `hora_salida`, `temperatura_salida`, `tiempototal`, `created_at`, `updated_at`) VALUES
(1, 3, 45, '23:17:00', 0.00, '23:19:00', 0.00, 2, '2025-10-17 23:18:03', '2025-10-17 23:18:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_embarcaciones`
--

CREATE TABLE `detalle_embarcaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idembarcacion` bigint(20) UNSIGNED NOT NULL,
  `idconservacion` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_embarcaciones`
--

INSERT INTO `detalle_embarcaciones` (`id`, `idembarcacion`, `idconservacion`, `created_at`, `updated_at`) VALUES
(13, 5, 9, '2025-10-15 23:19:06', '2025-10-15 23:19:06'),
(14, 6, 10, '2025-10-17 02:29:15', '2025-10-17 02:29:15'),
(15, 6, 10, '2025-10-17 02:29:15', '2025-10-17 02:29:15'),
(16, 7, 12, '2025-10-17 16:59:04', '2025-10-17 16:59:04'),
(17, 7, 12, '2025-10-17 16:59:04', '2025-10-17 16:59:04'),
(18, 8, 12, '2025-10-17 17:00:47', '2025-10-17 17:00:47'),
(19, 8, 12, '2025-10-17 17:00:47', '2025-10-17 17:00:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_preenfriado`
--

CREATE TABLE `detalle_preenfriado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idpreenfrio` bigint(20) UNSIGNED NOT NULL,
  `iddetalle` bigint(20) UNSIGNED NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `temperatura_entrada` decimal(5,2) DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `temperatura_salida` decimal(5,2) DEFAULT NULL,
  `tiempototal` int(11) DEFAULT NULL COMMENT 'Tiempo total en minutos',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_preenfriado`
--

INSERT INTO `detalle_preenfriado` (`id`, `idpreenfrio`, `iddetalle`, `hora_entrada`, `temperatura_entrada`, `hora_salida`, `temperatura_salida`, `tiempototal`, `created_at`, `updated_at`) VALUES
(30, 11, 37, '20:17:00', 25.00, '23:17:00', 0.00, 180, '2025-10-15 23:17:19', '2025-10-15 23:17:41'),
(31, 12, 36, '23:27:00', 25.00, '02:27:00', 0.00, 1260, '2025-10-17 02:27:28', '2025-10-17 02:27:43'),
(32, 12, 35, '23:27:00', 25.00, '02:27:00', 0.00, 1260, '2025-10-17 02:27:28', '2025-10-17 02:27:43'),
(33, 13, 32, '12:27:00', 25.00, '10:28:00', 0.00, 119, '2025-10-17 16:27:50', '2025-10-17 16:28:35'),
(34, 13, 32, '12:27:00', 25.00, NULL, NULL, NULL, '2025-10-17 16:27:50', '2025-10-17 16:27:50'),
(35, 14, 39, '12:40:00', 25.00, '23:47:00', 0.00, 667, '2025-10-17 16:40:48', '2025-10-17 16:48:18'),
(36, 14, 38, '12:40:00', 25.00, '23:47:00', 0.00, 667, '2025-10-17 16:40:48', '2025-10-17 16:48:18'),
(37, 15, 44, '20:06:00', 25.00, '23:06:00', 0.00, 180, '2025-10-17 23:06:38', '2025-10-17 23:06:59'),
(38, 15, 43, '20:06:00', 25.00, '23:06:00', 0.00, 180, '2025-10-17 23:06:38', '2025-10-17 23:06:59'),
(39, 16, 45, '20:13:00', 25.00, '23:13:00', 0.00, 180, '2025-10-17 23:13:22', '2025-10-17 23:13:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_recepcion`
--

CREATE TABLE `detalle_recepcion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idrecepcion` bigint(20) UNSIGNED NOT NULL,
  `idfruta` bigint(20) UNSIGNED NOT NULL,
  `idvariedad` bigint(20) UNSIGNED NOT NULL,
  `idpresentacion` bigint(20) UNSIGNED NOT NULL,
  `hora` time DEFAULT NULL,
  `temperatura` decimal(5,2) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `folio` varchar(255) DEFAULT NULL,
  `estatus` enum('recepcion','tarima','preenfriado','conservacion','cruce_anden','embarcacion','salida') DEFAULT 'recepcion',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_recepcion`
--

INSERT INTO `detalle_recepcion` (`id`, `idrecepcion`, `idfruta`, `idvariedad`, `idpresentacion`, `hora`, `temperatura`, `tipo`, `cantidad`, `folio`, `estatus`, `created_at`, `updated_at`) VALUES
(32, 16, 17, 2, 2, '19:26:00', 25.00, '°C', 100, 'N°-A00001', 'conservacion', '2025-10-15 22:28:09', '2025-10-17 16:28:35'),
(33, 16, 11, 2, 2, '22:27:00', 25.00, '°C', 100, 'N°-A00001', 'tarima', '2025-10-15 22:28:09', '2025-10-17 17:21:43'),
(34, 17, 11, 2, 2, '19:28:00', 25.00, '°C', 120, 'N°-A00002', 'tarima', '2025-10-15 22:29:06', '2025-10-17 17:21:43'),
(35, 18, 11, 2, 2, '19:29:00', 25.00, '°C', 140, 'N°-A00003', 'embarcacion', '2025-10-15 22:29:37', '2025-10-17 02:27:57'),
(36, 19, 17, 2, 2, '19:37:00', 25.00, '°C', 120, 'N°-A00004', 'embarcacion', '2025-10-15 22:38:11', '2025-10-17 02:27:57'),
(37, 20, 10, 2, 2, '20:15:00', 25.00, '°C', 78, 'N°-A00005', 'embarcacion', '2025-10-15 23:15:40', '2025-10-15 23:17:58'),
(38, 21, 11, 2, 3, '12:38:00', 25.00, '°C', 210, 'N°-A00006', 'embarcacion', '2025-10-17 16:38:29', '2025-10-17 16:50:20'),
(39, 22, 11, 2, 3, '12:38:00', 25.00, '°C', 30, 'N°-A00007', 'embarcacion', '2025-10-17 16:38:53', '2025-10-17 16:50:20'),
(42, 25, 11, 2, 3, '13:17:00', 25.00, '°C', 100, 'N°-A00010', 'tarima', '2025-10-17 17:17:39', '2025-10-17 17:17:58'),
(43, 26, 10, 2, 2, '18:10:00', 25.00, '°C', 120, 'N°-A00011', 'conservacion', '2025-10-17 21:10:46', '2025-10-17 23:07:31'),
(44, 27, 10, 2, 2, '20:04:00', 25.00, '°C', 120, 'N°-A00012', 'conservacion', '2025-10-17 23:04:55', '2025-10-17 23:07:31'),
(45, 28, 10, 2, 2, '20:08:00', 25.00, '°C', 143, 'N°-A00013', 'cruce_anden', '2025-10-17 23:08:21', '2025-10-17 23:16:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `embarcaciones`
--

CREATE TABLE `embarcaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trans_placa` varchar(100) NOT NULL,
  `trans_placacaja` varchar(100) NOT NULL,
  `trans_temperaturacaja` varchar(100) NOT NULL,
  `condtrans_estado` tinyint(1) NOT NULL,
  `condtrans_higiene` tinyint(1) NOT NULL,
  `condtrans_plagas` tinyint(1) NOT NULL,
  `condtar_desmontado` tinyint(1) NOT NULL,
  `condtar_flejado` tinyint(1) NOT NULL,
  `condtar_distribucion` tinyint(1) NOT NULL,
  `infcarga_hrallegada` time NOT NULL,
  `infcarga_hracarga` time NOT NULL,
  `infcarga_hrasalida` time NOT NULL,
  `infcarga_nsello` varchar(100) NOT NULL,
  `infcarga_nchismografo` varchar(100) NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `firma_usuario` text NOT NULL,
  `nombre_responsblecliente` varchar(100) NOT NULL,
  `apellido_responsablecliente` varchar(100) NOT NULL,
  `firma_cliente` text NOT NULL,
  `nombre_responsblechofer` varchar(100) NOT NULL,
  `apellido_responsablechofer` varchar(100) NOT NULL,
  `firma_chofer` text NOT NULL,
  `linea_transporte` varchar(100) NOT NULL,
  `total1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total4` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total5` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total6` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `embarcaciones`
--

INSERT INTO `embarcaciones` (`id`, `trans_placa`, `trans_placacaja`, `trans_temperaturacaja`, `condtrans_estado`, `condtrans_higiene`, `condtrans_plagas`, `condtar_desmontado`, `condtar_flejado`, `condtar_distribucion`, `infcarga_hrallegada`, `infcarga_hracarga`, `infcarga_hrasalida`, `infcarga_nsello`, `infcarga_nchismografo`, `id_usuario`, `firma_usuario`, `nombre_responsblecliente`, `apellido_responsablecliente`, `firma_cliente`, `nombre_responsblechofer`, `apellido_responsablechofer`, `firma_chofer`, `linea_transporte`, `total1`, `total2`, `total3`, `total4`, `total5`, `total6`, `created_at`, `updated_at`) VALUES
(5, '148625-jhds', '146546', '0', 1, 0, 0, 1, 0, 0, '23:18:00', '23:22:00', '23:24:00', '1556', '15156dhbfdsu', 8, 'EB6', 'RBECA', 'CROSBY', 'FTM', 'RBECA', 'CROSBY', 'JJ', 'Transportista', 'Arandanos - Orgánica - 1LB: 78', '0', '0', '0', '0', '0', '2025-10-15 23:19:06', '2025-10-15 23:19:06'),
(6, '15165', '146546', '0', 1, 0, 0, 1, 0, 0, '03:28:00', '03:33:00', '04:29:00', '1556', '15156dhbfdsu', 8, 'EB6', 'RBECA', 'CROSBY', 'FTM', 'RBECA', 'CROSBY', 'JJ', 'Transportista', 'Berries - Orgánica - 1LB: 120', 'Fresa - Orgánica - 1LB: 140', '0', '0', '0', '0', '2025-10-17 02:29:15', '2025-10-17 02:29:15'),
(7, '125468', '35345rrdjm', '0', 1, 1, 1, 1, 1, 1, '19:58:00', '20:08:00', '21:20:00', '654654', 'la mercancía se cargó sin problema', 1, 'fgfg', 'francisco javier', 'montejano', 'fgfg', 'francisco javier', 'montejano', 'fgfg', 'betos truking', 'Fresa - Orgánica - 2LB: 240', '0', '0', '0', '0', '0', '2025-10-17 16:59:04', '2025-10-17 16:59:04'),
(8, '125468', '35345rrdjm', '0', 1, 1, 0, 1, 1, 1, '15:00:00', '13:39:00', '22:00:00', '654654', 'la mercancía se cargó sin problema', 1, 'fgfg', 'francisco javier', 'montejano', 'fgfg', 'francisco javier', 'montejano', 'fgfg', 'betos truking', 'Fresa - Orgánica - 2LB: 240', '0', '0', '0', '0', '0', '2025-10-17 17:00:47', '2025-10-17 17:00:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fruta`
--

CREATE TABLE `fruta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombrefruta` varchar(255) NOT NULL,
  `imgfruta` varchar(255) DEFAULT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fruta`
--

INSERT INTO `fruta` (`id`, `nombrefruta`, `imgfruta`, `estatus`, `created_at`, `updated_at`) VALUES
(10, 'Arandanos', NULL, 'activo', '2025-07-21 20:49:52', '2025-08-30 04:56:03'),
(11, 'Fresa', 'imagenes/frutas/1753116870_687e70c64a797.svg', 'activo', '2025-07-21 20:54:30', '2025-07-21 20:54:30'),
(12, 'Jitomate', 'imagenes/frutas/1753116899_687e70e356635.svg', 'activo', '2025-07-21 20:54:59', '2025-07-21 20:54:59'),
(13, 'Brócoli', 'imagenes/frutas/1753116913_687e70f168ec1.svg', 'activo', '2025-07-21 20:55:13', '2025-07-21 20:55:13'),
(14, 'Zarzamora', 'imagenes/frutas/1753116945_687e71112dbc0.svg', 'activo', '2025-07-21 20:55:45', '2025-07-21 20:55:45'),
(16, 'Frambuesa', 'imagenes/frutas/1753117071_687e718f930bb.svg', 'activo', '2025-07-21 20:57:51', '2025-07-21 20:57:51'),
(17, 'Berries', 'imagenes/frutas/1760391847_68ed72a76ef64.png', 'activo', '2025-08-30 05:06:10', '2025-10-13 18:44:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(21, '0001_01_01_000000_create_users_table', 1),
(22, '0001_01_01_000001_create_cache_table', 1),
(23, '0001_01_01_000002_create_jobs_table', 1),
(24, '2025_07_17_015237_create_fruta_table', 1),
(25, '2025_07_17_020639_create_presentacion_table', 1),
(26, '2025_07_17_020822_create_variedad_table', 1),
(27, '2025_07_17_021153_create_rol_usuario_table', 1),
(28, '2025_07_17_021342_create_cooler_table', 1),
(29, '2025_07_17_022013_create_camara_table', 1),
(30, '2025_07_17_022406_create_tipopallet_table', 1),
(31, '2025_07_17_023943_add_fields_to_users_table', 1),
(32, '2025_07_17_025015_create_comercializadora_table', 2),
(33, '2025_07_17_025703_create_contrato_table', 3),
(34, '2025_07_17_030321_create_detalle_contrato_table', 4),
(35, '2025_07_18_041758_modify_unique_index_on_camara_table', 5),
(36, '2025_07_22_012940_create_recepcion_table', 6),
(37, '2025_07_22_013535_create_detalle_recepcion_table', 7),
(38, '2025_07_25_005909_create_tarimas_table', 8),
(39, '2025_07_25_222645_update_tarimas_table', 9),
(41, '2025_07_25_223557_create_tarima_detarec_table', 10),
(42, '2025_07_25_230019_create_tarima_detarecs_table', 10),
(43, '2025_07_30_001341_update_foreign_keys_on_camara_table', 10),
(44, '2025_07_30_002231_update_foreign_keys_on_users_table', 11),
(45, '2025_07_30_002640_update_foreign_keys_on_contrato_table', 12),
(46, '2025_07_30_003037_update_foreign_keys_on_detalle_contrato_table', 13),
(47, '2025_07_30_110845_update_foreign_keys_on_recepcion_table', 14),
(48, '2025_07_30_111701_update_estatus_enum_on_recepcion_table', 15),
(49, '2025_07_30_112426_update_detalle_recepcion_table', 16),
(50, '2025_08_06_014753_create_preenfriados_table', 17),
(51, '2025_08_13_221629_create_preenfriado_table', 18),
(52, '2025_08_13_222552_create_detalle_preenfriado_table', 19),
(53, '2025_08_14_215544_create_conservacion_table', 20),
(54, '2025_08_14_215815_create_detalle_conservacion_table', 21),
(55, '2025_08_15_032955_add_ubicacion_to_tarimas_table', 22),
(56, '2025_08_18_200118_create_embarcaciones_table', 23),
(57, '2025_08_18_200405_create_detalle_embarcaciones_table', 24),
(58, '2025_08_18_204429_create_embarcacions_table', 25),
(59, '2025_08_18_204434_create_detalle_embarcacions_table', 25),
(60, '2025_08_29_165416_add_idpresentacion_and_tiposervicio_to_detalle_contrato_table', 26),
(61, '2025_08_30_001545_add_estatus_to_rol_usuario_table', 27),
(62, '2025_08_30_001735_add_estatus_to_cooler_table', 28),
(63, '2025_08_30_001950_add_estatus_to_camara_table', 29),
(64, '2025_08_30_002303_remove_idcooler_from_users_table', 30),
(65, '2025_08_30_002940_create_usuario_cooler_table', 31),
(66, '2025_08_30_005420_add_estatus_to_fruta_table', 32),
(67, '2025_08_30_005846_add_estatus_to_presentacion_table', 33),
(68, '2025_08_30_010655_add_estatus_to_variedad_table', 34),
(69, '2025_08_30_011017_add_estatus_to_tipopallet_table', 35),
(70, '2025_09_01_093531_add_estatuseliminar_to_recepcion_table', 36),
(71, '2025_09_01_100831_add_estatuseliminar_to_tarimas_table', 37),
(72, '2025_10_11_220200_create_permisos_table', 38),
(73, '2025_10_11_220300_create_rol_permiso_table', 38),
(74, '2025_10_11_000001_create_permissions_table', 39),
(75, '2025_10_11_000002_create_permission_rol_table', 39),
(76, '2025_10_11_000003_create_permission_user_table', 40),
(77, '2025_10_13_211300_add_moneda_to_detalle_contrato_table', 41),
(78, '2025_10_13_211350_migrate_tipomoneda_to_detalle_contrato', 41),
(79, '2025_10_13_211400_remove_tipomoneda_from_contrato_table', 41),
(80, '2025_10_16_020000_update_estatus_enum_recepcion_table', 42),
(81, '2025_10_16_020100_update_existing_estatus_values', 43),
(82, '2025_10_16_231203_create_cobranzas_table', 44),
(83, '2025_10_17_123353_add_cruce_tipo_to_camara_table', 45),
(84, '2025_10_17_181500_add_cancelada_to_recepcion_estatus', 46),
(85, '2025_10_17_224800_create_cruce_anden_table', 47),
(86, '2025_10_17_224900_create_detalle_cruce_anden_table', 47),
(87, '2025_10_17_225000_add_cruce_anden_to_recepcion_estatus', 47),
(88, '2025_10_17_231400_add_cruce_anden_to_tarimas_ubicacion', 48),
(89, '2025_10_17_231500_add_cruce_anden_to_detalle_recepcion_estatus', 49);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `modulo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `descripcion`, `modulo`, `created_at`, `updated_at`) VALUES
(1, 'ver_usuarios', 'Ver listado de usuarios', 'usuarios', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(2, 'crear_usuarios', 'Crear nuevos usuarios', 'usuarios', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(3, 'editar_usuarios', 'Editar usuarios existentes', 'usuarios', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(4, 'eliminar_usuarios', 'Eliminar usuarios', 'usuarios', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(5, 'ver_roles', 'Ver listado de roles', 'roles', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(6, 'crear_roles', 'Crear nuevos roles', 'roles', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(7, 'editar_roles', 'Editar roles existentes', 'roles', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(8, 'eliminar_roles', 'Eliminar roles', 'roles', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(9, 'ver_contratos', 'Ver listado de contratos', 'contratos', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(10, 'crear_contratos', 'Crear nuevos contratos', 'contratos', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(11, 'editar_contratos', 'Editar contratos existentes', 'contratos', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(12, 'eliminar_contratos', 'Eliminar contratos', 'contratos', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(13, 'generar_factura', 'Generar facturas de contratos', 'contratos', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(14, 'ver_recepciones', 'Ver listado de recepciones', 'recepciones', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(15, 'crear_recepciones', 'Crear nuevas recepciones', 'recepciones', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(16, 'editar_recepciones', 'Editar recepciones existentes', 'recepciones', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(17, 'eliminar_recepciones', 'Eliminar recepciones', 'recepciones', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(18, 'ver_tarimas', 'Ver listado de tarimas', 'tarimas', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(19, 'crear_tarimas', 'Crear nuevas tarimas', 'tarimas', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(20, 'editar_tarimas', 'Editar tarimas existentes', 'tarimas', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(21, 'eliminar_tarimas', 'Eliminar tarimas', 'tarimas', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(22, 'ver_preenfriado', 'Ver listado de preenfriado', 'preenfriado', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(23, 'crear_preenfriado', 'Crear registros de preenfriado', 'preenfriado', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(24, 'editar_preenfriado', 'Editar registros de preenfriado', 'preenfriado', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(25, 'eliminar_preenfriado', 'Eliminar registros de preenfriado', 'preenfriado', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(26, 'ver_conservacion', 'Ver listado de conservación', 'conservacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(27, 'crear_conservacion', 'Crear registros de conservación', 'conservacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(28, 'editar_conservacion', 'Editar registros de conservación', 'conservacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(29, 'eliminar_conservacion', 'Eliminar registros de conservación', 'conservacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(30, 'ver_embarcacion', 'Ver listado de embarcaciones', 'embarcacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(31, 'crear_embarcacion', 'Crear registros de embarcación', 'embarcacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(32, 'editar_embarcacion', 'Editar registros de embarcación', 'embarcacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(33, 'eliminar_embarcacion', 'Eliminar registros de embarcación', 'embarcacion', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(34, 'ver_coolers', 'Ver listado de coolers', 'coolers', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(35, 'crear_coolers', 'Crear nuevos coolers', 'coolers', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(36, 'editar_coolers', 'Editar coolers existentes', 'coolers', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(37, 'eliminar_coolers', 'Eliminar coolers', 'coolers', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(38, 'ver_comercializadoras', 'Ver listado de comercializadoras', 'comercializadoras', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(39, 'crear_comercializadoras', 'Crear nuevas comercializadoras', 'comercializadoras', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(40, 'editar_comercializadoras', 'Editar comercializadoras existentes', 'comercializadoras', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(41, 'eliminar_comercializadoras', 'Eliminar comercializadoras', 'comercializadoras', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(42, 'gestionar_catalogos', 'Gestionar catálogos del sistema', 'catalogos', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(43, 'ver_dashboard', 'Acceder al dashboard', 'dashboard', '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(44, 'ver_cobranza', 'Ver módulo de cobranza', 'cobranza', '2025-10-11 21:29:14', '2025-10-11 21:29:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `module`, `created_at`, `updated_at`) VALUES
(1, 'ver_usuarios', 'Ver Usuarios', 'Permite ver la lista de usuarios', 'usuarios', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(2, 'crear_usuarios', 'Crear Usuarios', 'Permite crear nuevos usuarios', 'usuarios', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(3, 'editar_usuarios', 'Editar Usuarios', 'Permite editar usuarios existentes', 'usuarios', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(4, 'eliminar_usuarios', 'Eliminar Usuarios', 'Permite eliminar usuarios', 'usuarios', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(5, 'ver_coolers', 'Ver Coolers', 'Permite ver la lista de coolers', 'coolers', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(6, 'crear_coolers', 'Crear Coolers', 'Permite crear nuevos coolers', 'coolers', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(7, 'editar_coolers', 'Editar Coolers', 'Permite editar coolers existentes', 'coolers', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(8, 'eliminar_coolers', 'Eliminar Coolers', 'Permite eliminar coolers', 'coolers', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(9, 'ver_comercializadoras', 'Ver Comercializadoras', 'Permite ver la lista de comercializadoras', 'comercializadoras', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(10, 'crear_comercializadoras', 'Crear Comercializadoras', 'Permite crear nuevas comercializadoras', 'comercializadoras', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(11, 'editar_comercializadoras', 'Editar Comercializadoras', 'Permite editar comercializadoras existentes', 'comercializadoras', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(12, 'eliminar_comercializadoras', 'Eliminar Comercializadoras', 'Permite eliminar comercializadoras', 'comercializadoras', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(13, 'ver_contratos', 'Ver Contratos', 'Permite ver la lista de contratos', 'contratos', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(14, 'crear_contratos', 'Crear Contratos', 'Permite crear nuevos contratos', 'contratos', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(15, 'editar_contratos', 'Editar Contratos', 'Permite editar contratos existentes', 'contratos', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(16, 'eliminar_contratos', 'Eliminar Contratos', 'Permite eliminar contratos', 'contratos', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(17, 'ver_recepciones', 'Ver Recepciones', 'Permite ver la lista de recepciones', 'recepciones', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(18, 'crear_recepciones', 'Crear Recepciones', 'Permite crear nuevas recepciones', 'recepciones', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(19, 'editar_recepciones', 'Editar Recepciones', 'Permite editar recepciones existentes', 'recepciones', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(20, 'eliminar_recepciones', 'Eliminar Recepciones', 'Permite eliminar recepciones', 'recepciones', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(21, 'ver_reportes', 'Ver Reportes', 'Permite ver reportes y estadísticas', 'reportes', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(22, 'exportar_reportes', 'Exportar Reportes', 'Permite exportar reportes a Excel/PDF', 'reportes', '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(23, 'ver_dashboard', 'Ver Dashboard', 'Permite acceder al dashboard principal', 'dashboard', '2025-10-11 23:16:06', '2025-10-11 23:16:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_rol`
--

CREATE TABLE `permission_rol` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `rol_usuario_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permission_rol`
--

INSERT INTO `permission_rol` (`id`, `permission_id`, `rol_usuario_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(3, 3, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(4, 4, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(5, 5, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(6, 6, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(7, 7, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(8, 8, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(9, 9, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(10, 10, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(11, 11, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(12, 12, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(13, 13, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(14, 14, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(15, 15, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(16, 16, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(17, 17, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(18, 18, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(19, 19, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(20, 20, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(21, 21, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(22, 22, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(23, 23, 1, '2025-10-11 23:16:06', '2025-10-11 23:16:06'),
(24, 10, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(25, 11, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(26, 12, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(27, 9, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(28, 6, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(29, 7, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(30, 8, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(31, 5, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(32, 23, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(33, 18, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(34, 19, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(35, 20, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(36, 17, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(37, 2, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(38, 3, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(39, 4, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(40, 1, 3, '2025-10-13 15:27:40', '2025-10-13 15:27:40'),
(41, 2, 1, '2025-10-13 15:33:00', '2025-10-13 15:33:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_user`
--

CREATE TABLE `permission_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preenfriado`
--

CREATE TABLE `preenfriado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idcamara` bigint(20) UNSIGNED NOT NULL,
  `idtarima` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `preenfriado`
--

INSERT INTO `preenfriado` (`id`, `idcamara`, `idtarima`, `created_at`, `updated_at`) VALUES
(11, 18, 1063, '2025-10-15 23:17:19', '2025-10-15 23:17:19'),
(12, 5, 1062, '2025-10-17 02:27:28', '2025-10-17 02:27:28'),
(13, 3, 1064, '2025-10-17 16:27:50', '2025-10-17 16:27:50'),
(14, 22, 1068, '2025-10-17 16:40:48', '2025-10-17 16:40:48'),
(15, 19, 1070, '2025-10-17 23:06:38', '2025-10-17 23:06:38'),
(16, 3, 1071, '2025-10-17 23:13:22', '2025-10-17 23:13:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombrepresentacion` varchar(255) NOT NULL,
  `descripcionpresentacion` text DEFAULT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`id`, `nombrepresentacion`, `descripcionpresentacion`, `estatus`, `created_at`, `updated_at`) VALUES
(2, '1LB', 'Esta presentación corresponde a una (1) Libra (LB)', 'activo', '2025-07-18 00:21:33', '2025-08-30 19:59:05'),
(3, '2LB', 'Esta presentación corresponde a una (2) Libra (LB)', 'activo', '2025-07-18 06:57:15', '2025-07-18 06:57:15'),
(4, '6 oz', 'variedad de frambuesa de seis (6) onzas (Oz)', 'activo', '2025-07-21 20:29:49', '2025-07-21 20:29:49'),
(5, '4.4 Oz', 'variedad de frambuesa de cuatro punto cuatro (4.4) onzas (Oz)', 'activo', '2025-07-21 20:30:16', '2025-07-21 20:30:16'),
(6, '11 Oz', 'Esta presentación equivale a once (11) onzas (Oz)', 'activo', '2025-07-21 20:31:58', '2025-07-21 20:31:58'),
(7, '12Oz', 'Esta presentación equivale a once (12) onzas (Oz)', 'activo', '2025-07-21 20:32:10', '2025-07-21 20:32:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcion`
--

CREATE TABLE `recepcion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `datosclave` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `revision` varchar(255) DEFAULT NULL,
  `fechaemision` date DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `folio` varchar(255) DEFAULT NULL,
  `estatuseliminar` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `estatus` enum('CON DETALLE','TARIMA','EN PREENFRIADO','EN CONSERVACIÓN','EN CRUCE DE ANDÉN','EN EMBARQUE','FINALIZADO','CANCELADA') DEFAULT 'CON DETALLE',
  `idusuario` bigint(20) UNSIGNED NOT NULL,
  `idcontrato` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recepcion`
--

INSERT INTO `recepcion` (`id`, `datosclave`, `area`, `revision`, `fechaemision`, `imagen`, `folio`, `estatuseliminar`, `estatus`, `idusuario`, `idcontrato`, `created_at`, `updated_at`) VALUES
(16, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-15', NULL, 'N°-A00001', 'activo', 'TARIMA', 8, 10, '2025-10-15 22:28:09', '2025-10-17 17:21:43'),
(17, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-15', NULL, 'N°-A00002', 'activo', 'TARIMA', 8, 10, '2025-10-15 22:29:06', '2025-10-17 17:21:43'),
(18, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-15', NULL, 'N°-A00003', 'activo', 'FINALIZADO', 1, 19, '2025-10-15 22:29:37', '2025-10-17 02:29:15'),
(19, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-15', NULL, 'N°-A00004', 'activo', 'FINALIZADO', 8, 9, '2025-10-15 22:38:11', '2025-10-17 02:29:15'),
(20, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-15', NULL, 'N°-A00005', 'activo', 'CON DETALLE', 8, 15, '2025-10-15 23:15:40', '2025-10-15 23:15:40'),
(21, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-17', NULL, 'N°-A00006', 'activo', 'FINALIZADO', 1, 21, '2025-10-17 16:38:29', '2025-10-17 16:59:04'),
(22, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-17', NULL, 'N°-A00007', 'activo', 'FINALIZADO', 1, 21, '2025-10-17 16:38:53', '2025-10-17 16:59:04'),
(25, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-17', NULL, 'N°-A00010', 'inactivo', 'CANCELADA', 1, 21, '2025-10-17 17:17:39', '2025-10-17 22:28:31'),
(26, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-17', NULL, 'N°-A00011', 'activo', 'EN CONSERVACIÓN', 8, 20, '2025-10-17 21:10:46', '2025-10-17 23:07:31'),
(27, 'F-BCM-PRO-02', 'Recepción', '01', '2025-10-17', NULL, 'N°-A00012', 'activo', 'EN CONSERVACIÓN', 8, 20, '2025-10-17 23:04:55', '2025-10-17 23:07:31'),
(28, 'F-BCM-PRO-01', 'Recepción', '01', '2025-10-17', NULL, 'N°-A00013', 'activo', 'EN CRUCE DE ANDÉN', 8, 14, '2025-10-17 23:08:21', '2025-10-17 23:16:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idrol` bigint(20) UNSIGNED NOT NULL,
  `idpermiso` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id`, `idrol`, `idpermiso`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(2, 1, 2, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(3, 1, 3, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(4, 1, 4, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(5, 1, 5, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(6, 1, 6, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(7, 1, 7, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(8, 1, 8, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(9, 1, 9, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(10, 1, 10, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(11, 1, 11, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(12, 1, 12, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(13, 1, 13, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(14, 1, 14, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(15, 1, 15, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(16, 1, 16, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(17, 1, 17, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(18, 1, 18, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(19, 1, 19, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(20, 1, 20, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(21, 1, 21, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(22, 1, 22, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(23, 1, 23, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(24, 1, 24, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(25, 1, 25, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(26, 1, 26, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(27, 1, 27, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(28, 1, 28, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(29, 1, 29, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(30, 1, 30, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(31, 1, 31, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(32, 1, 32, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(33, 1, 33, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(34, 1, 34, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(35, 1, 35, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(36, 1, 36, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(37, 1, 37, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(38, 1, 38, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(39, 1, 39, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(40, 1, 40, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(41, 1, 41, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(42, 1, 42, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(43, 1, 43, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(44, 1, 44, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(45, 3, 1, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(46, 3, 2, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(47, 3, 3, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(48, 3, 5, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(49, 3, 9, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(50, 3, 14, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(51, 3, 15, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(52, 3, 16, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(53, 3, 17, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(54, 3, 18, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(55, 3, 19, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(56, 3, 20, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(57, 3, 21, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(58, 3, 22, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(59, 3, 23, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(60, 3, 24, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(61, 3, 25, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(62, 3, 26, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(63, 3, 27, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(64, 3, 28, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(65, 3, 29, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(66, 3, 30, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(67, 3, 31, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(68, 3, 32, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(69, 3, 33, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(70, 3, 34, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(71, 3, 36, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(72, 3, 38, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(73, 3, 40, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(74, 3, 42, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(75, 3, 43, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(76, 5, 14, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(77, 5, 15, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(78, 5, 16, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(79, 5, 18, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(80, 5, 19, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(81, 5, 20, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(82, 5, 22, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(83, 5, 23, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(84, 5, 24, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(85, 5, 26, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(86, 5, 27, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(87, 5, 28, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(88, 5, 30, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(89, 5, 31, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(90, 5, 32, '2025-10-11 21:29:14', '2025-10-11 21:29:14'),
(91, 5, 43, '2025-10-11 21:29:14', '2025-10-11 21:29:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_usuario`
--

CREATE TABLE `rol_usuario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombrerol` varchar(255) NOT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol_usuario`
--

INSERT INTO `rol_usuario` (`id`, `nombrerol`, `estatus`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'activo', '2025-07-19 09:19:08', '2025-10-08 22:54:28'),
(3, 'Supervisor', 'activo', '2025-07-21 21:02:10', '2025-10-08 22:54:18'),
(5, 'Operativo', 'activo', '2025-08-24 23:06:30', '2025-08-24 23:06:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('GlC4n7TEFaz0jjcMq9Uhv5z41eTPGvFibcgO0hAF', 8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiODBWTWpyNWQ2b2hrWnU5aGY3VnpIcm5vYUlLQWxsdFpvWXpGTlNtMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovL2xvY2FsaG9zdC9jb29sZXJzL3B1YmxpYy9yZWNlcGNpb25lcyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vbG9jYWxob3N0L2Nvb2xlcnMvcHVibGljL2NydWNlLWFuZGVuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODt9', 1760754964),
('Iye6Oeu820UdGZKTTOY4CXCdjIWSHvFfLe2cH4rF', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSU0yWFNMd0J4VThhdGpKVW5zd2MxeTlDd0UzZDh1WVVFV3ZUWlBYRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3QvY29vbGVycy9wdWJsaWMvY3J1Y2UtYW5kZW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1760811267);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarimas`
--

CREATE TABLE `tarimas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `capacidad` int(11) NOT NULL DEFAULT 240,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estatus` enum('disponible','completo') NOT NULL DEFAULT 'disponible',
  `ubicacion` enum('tarima','preenfriado','conservacion','cruce_anden','embarque') DEFAULT 'tarima',
  `estatuseliminar` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tarimas`
--

INSERT INTO `tarimas` (`id`, `codigo`, `cantidad`, `capacidad`, `created_at`, `updated_at`, `estatus`, `ubicacion`, `estatuseliminar`) VALUES
(1060, 'T1-010925-10:21', 10, 35, '2025-09-01 13:26:13', '2025-10-17 01:31:18', 'completo', 'tarima', 'inactivo'),
(1061, 'T1061-010925-10:33', 100, 92, '2025-09-01 13:33:18', '2025-10-17 01:31:09', 'completo', 'embarque', 'inactivo'),
(1062, 'T1062-010925-10:33', 100, 42, '2025-09-01 13:33:18', '2025-10-17 02:27:57', 'completo', 'embarque', 'activo'),
(1063, 'T1063-010925-10:33', 100, 162, '2025-09-01 13:33:18', '2025-10-15 23:17:58', 'completo', 'embarque', 'activo'),
(1064, 'T1064-010925-10:33', 100, 120, '2025-09-01 13:33:18', '2025-10-17 16:28:35', 'completo', 'conservacion', 'activo'),
(1065, 'T1065-010925-10:33', 100, 0, '2025-09-01 13:33:18', '2025-10-17 17:13:39', 'completo', 'tarima', 'inactivo'),
(1066, 'T1066-151025-17:16', 10, 0, '2025-10-15 20:16:17', '2025-10-17 17:17:58', 'completo', 'tarima', 'inactivo'),
(1067, 'T1067-151025-17:16', 10, 140, '2025-10-15 20:16:17', '2025-10-17 01:31:05', 'completo', 'conservacion', 'inactivo'),
(1068, 'T1068-171025-12:37', 10, 0, '2025-10-17 16:37:55', '2025-10-17 20:41:51', 'completo', 'embarque', 'activo'),
(1069, 'T1069-171025-13:21', 10, 20, '2025-10-17 17:21:27', '2025-10-17 17:21:43', 'completo', 'tarima', 'activo'),
(1070, 'T1070-171025-20:05', 10, 0, '2025-10-17 23:05:13', '2025-10-17 23:07:31', 'completo', 'conservacion', 'activo'),
(1071, 'T1071-171025-20:05', 10, 97, '2025-10-17 23:05:13', '2025-10-17 23:16:58', 'completo', 'cruce_anden', 'activo'),
(1072, 'T1072-171025-20:05', 10, 240, '2025-10-17 23:05:13', '2025-10-17 23:05:13', 'disponible', 'tarima', 'activo'),
(1073, 'T1073-171025-20:05', 10, 240, '2025-10-17 23:05:13', '2025-10-17 23:05:13', 'disponible', 'tarima', 'activo'),
(1074, 'T1074-171025-20:05', 10, 240, '2025-10-17 23:05:13', '2025-10-17 23:05:13', 'disponible', 'tarima', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarima_detarec`
--

CREATE TABLE `tarima_detarec` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iddetalle` bigint(20) UNSIGNED DEFAULT NULL,
  `idtarima` bigint(20) UNSIGNED DEFAULT NULL,
  `idtipopallet` bigint(20) UNSIGNED DEFAULT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `cantidad_usada` int(11) DEFAULT NULL,
  `estatus` enum('vacio','disponible','completo') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tarima_detarec`
--

INSERT INTO `tarima_detarec` (`id`, `iddetalle`, `idtarima`, `idtipopallet`, `codigo`, `cantidad`, `cantidad_usada`, `estatus`, `created_at`, `updated_at`) VALUES
(59, 36, 1062, 1, 'Lote:3601', 120, 120, 'completo', '2025-10-15 23:12:51', '2025-10-15 23:12:51'),
(60, 35, 1062, 1, 'Lote:3502', 78, 42, 'completo', '2025-10-15 23:12:51', '2025-10-15 23:12:51'),
(61, 37, 1063, 1, 'Lote:3701', 78, 162, 'completo', '2025-10-15 23:16:21', '2025-10-15 23:16:21'),
(62, 32, 1064, 1, 'Lote:3201', 120, 120, 'completo', '2025-10-16 02:01:25', '2025-10-16 02:01:25'),
(63, 32, 1064, 1, 'Lote:3201', 120, 120, 'completo', '2025-10-16 02:03:33', '2025-10-16 02:03:33'),
(64, 39, 1068, 1, 'Lote:3901', 30, 210, 'completo', '2025-10-17 16:39:31', '2025-10-17 16:39:31'),
(65, 38, 1068, 1, 'Lote:3802', 210, 0, 'completo', '2025-10-17 16:39:31', '2025-10-17 16:39:31'),
(66, NULL, 1065, 1, 'Lote:4001', 240, 0, 'completo', '2025-10-17 17:13:39', '2025-10-17 17:13:39'),
(67, 42, 1066, 1, 'Lote:4201', 100, 140, 'completo', '2025-10-17 17:17:58', '2025-10-17 17:17:58'),
(68, NULL, 1066, 1, 'Lote:4102', 140, 0, 'completo', '2025-10-17 17:17:58', '2025-10-17 17:17:58'),
(69, 34, 1069, 1, 'Lote:3401', 120, 120, 'completo', '2025-10-17 17:21:43', '2025-10-17 17:21:43'),
(70, 33, 1069, 1, 'Lote:3302', 100, 20, 'completo', '2025-10-17 17:21:43', '2025-10-17 17:21:43'),
(71, 44, 1070, 1, 'Lote:4401', 120, 120, 'completo', '2025-10-17 23:06:03', '2025-10-17 23:06:03'),
(72, 43, 1070, 1, 'Lote:4302', 120, 0, 'completo', '2025-10-17 23:06:03', '2025-10-17 23:06:03'),
(73, 45, 1071, 1, 'Lote:4501', 143, 97, 'disponible', '2025-10-17 23:08:46', '2025-10-17 23:08:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopallet`
--

CREATE TABLE `tipopallet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipopallet` varchar(255) NOT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipopallet`
--

INSERT INTO `tipopallet` (`id`, `tipopallet`, `estatus`, `created_at`, `updated_at`) VALUES
(1, 'Cajas', 'activo', '2025-07-18 04:33:50', '2025-07-18 04:33:50'),
(3, 'Cubetas', 'activo', '2025-07-18 07:02:39', '2025-08-30 20:10:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idrol` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `estatus` varchar(255) NOT NULL DEFAULT 'activo',
  `fechaconexion` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `idrol`, `name`, `apellidos`, `telefono`, `email`, `email_verified_at`, `password`, `estatus`, `fechaconexion`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Erika', 'Brito', '51981296129', 'erika@gmail.com', NULL, '$2y$12$d5oU.h/w.YdYV962Xm8IYO7lKd1GOd/bxIa5bGBDbzdkfYV5hMjwC', 'activo', '2025-10-18 11:44:48', NULL, '2025-07-20 19:02:20', '2025-10-18 14:44:48'),
(2, 1, 'Ana', 'Brito', '159785632', 'ana@gmail.com', NULL, '$2y$12$1Wi.1Qgp51T/A3W3D7V.beU4H4U97n8/U4zhfBnpGjg28kbfx56wm', 'activo', NULL, NULL, '2025-07-21 04:13:22', '2025-07-21 04:13:22'),
(4, 3, 'Pancho', 'Chavez', '3512548795', 'pancho@gmail.com', NULL, '$2y$12$/w05v.BeZCp5Esom2wuZHOcn4BLlDAoF9qGGk4ZWaebHL6N0ZeNC.', 'inactivo', NULL, NULL, '2025-07-21 21:03:32', '2025-08-30 05:52:50'),
(6, 3, 'RBECA', 'CROSBY', '51981239456', 'rbeca@gmail.com', NULL, '$2y$12$2Px3tRXjD3Bro96SsVAj7efSyeLs27RmOV/wKRwJTJ2d3ZZNjpzHq', 'activo', '2025-08-31 20:59:13', NULL, '2025-08-30 19:14:05', '2025-08-31 23:59:13'),
(7, 5, 'jorge', 'shasse', '51981296129', 'admin@example.com', NULL, '$2y$12$JcW7VdXRW3peFFzxclaQOejs6IlffZKmN8XImPEnQzKLv5hTtCEV6', 'activo', '2025-10-13 19:21:05', NULL, '2025-08-30 19:24:25', '2025-10-13 23:21:05'),
(8, 1, 'Administrador', 'Administrador', '4421234567', 'admin@cooler.com', NULL, '$2y$12$q74dwtQtKgKtYokRxs11JuFJaM8fXdScnSOxCdCIjD7LCmJe0Txt.', 'activo', '2025-10-17 19:25:30', NULL, '2025-10-11 12:23:07', '2025-10-17 22:25:30'),
(9, 3, 'Supervisor', 'Supervisor', '4421234567', 'supervisor@cooler.com', NULL, '$2y$12$52cINmGQP6gQzlHQWz5LluIbDee3zXC7t5C5qymLPTjm0pjLwo5iu', 'activo', '2025-10-16 12:02:34', NULL, '2025-10-11 12:24:59', '2025-10-16 15:02:34'),
(10, 5, 'Operativo', 'Operativo', '4421234567', 'operativo@cooler.com', NULL, '$2y$12$GHU/kpEa3Qal2sOL7BVKxeqerm19R/5BllGCVoAzWUtIYS9Feh0x6', 'activo', '2025-10-11 22:01:51', NULL, '2025-10-11 12:26:10', '2025-10-12 01:01:51'),
(11, 1, 'IRMA ALEJANDRA', 'SUAREZ DEL REAL VALERIO', '123456789', 'alejandra.suarezdelreal@bonumcoolers.com', NULL, '$2y$12$8bRYPnE7fWXsRYJl6tyPUuRdxRT/1ZX7wgIaTdpWmFld0mU8eq7U2', 'activo', NULL, NULL, '2025-10-14 00:43:47', '2025-10-14 00:43:47'),
(12, 1, 'Guillermo Alejandro', 'Gomez Suarez del Real', '123456789', 'guillermo.gomez@bonumcoolers.com', NULL, '$2y$12$47Ne7etdoH9m0Oz/PliaZuTWZZQw2OYc7XbVK8bAXiKWizjsXQnnq', 'activo', NULL, NULL, '2025-10-14 00:45:16', '2025-10-14 00:45:16'),
(13, 1, 'Mario Alberto Rodriguez Torres', 'Mario Alberto Rodriguez Torres', '123456789', 'mario.rodriguez@bonumcoolers.com', NULL, '$2y$12$We9OU.QLQb9o5lVe.JkEwO6uTK4mdSgI/rFzIwZD0begDQqF9w1/G', 'activo', NULL, NULL, '2025-10-14 00:46:05', '2025-10-14 00:46:05'),
(14, 3, 'Guillermo', 'Magaña Zavala', '123456789', 'guillermo.magana@bonumcoolers.com', NULL, '$2y$12$nFzln3.FddXh29L5ipzLFuDojHtBy6NAaSbgAxzD5FrfwqjlwPjTy', 'activo', NULL, NULL, '2025-10-14 00:48:11', '2025-10-14 00:48:11'),
(15, 3, 'Diana', 'Avila Barrera', '123456789', 'diana.avila@bonumcoolers.com', NULL, '$2y$12$Kw3s3OKd0sCqrMrYQBB2mutFd8HyAMP4KZ6bev9EbBUUGEZDt/B/a', 'activo', NULL, NULL, '2025-10-14 00:50:06', '2025-10-14 00:50:06'),
(16, 5, 'Rodolfo', 'Chavez Garcia', '123456789', 'rodolfo.chavez@bonumcoolers.com', NULL, '$2y$12$K6UBUK6hQ.K2qT5Z0TIkPuCN0qDvLZCQPuoBpMRycRTwPpmkslpTS', 'activo', NULL, NULL, '2025-10-14 00:51:30', '2025-10-14 00:51:30'),
(17, 5, 'Efrain', 'Gutierrez Perez', '123456789', 'efrain.gutierrez@bonumcoolers.com', NULL, '$2y$12$uLhBF1I/4XCRbMEgdQsVce2L8lfcfia8WMhdVS6MhBKuFyB6ik6g2', 'activo', NULL, NULL, '2025-10-14 00:52:47', '2025-10-14 00:52:47'),
(18, 5, 'Juan Carlos', 'Pulido Espinoza', '123456789', 'juan.pulido@bonumcoolers.com', NULL, '$2y$12$Q8dIf085LcmkX.TKp2K2y.VhxpoaIelrnnBS8SZ3d1Yw/AV9Ly66.', 'activo', NULL, NULL, '2025-10-14 00:54:14', '2025-10-14 00:54:14'),
(19, 5, 'Juan', 'Ortega Ramirez', '123456789', 'juan.ortega@bonumcoolers.com', NULL, '$2y$12$HUl1dC2Kp0V0fhaPrExLiOG/X/tajiu1v23uHz0fCyeJKQ46WLDwa', 'activo', NULL, NULL, '2025-10-14 00:55:22', '2025-10-14 00:55:22'),
(20, 5, 'Jesús', 'Gutiérrez García', '123456789', 'jesus.gutierrez@bonumcoolers.com', NULL, '$2y$12$olND.xhdB2E3YXwPRfy1yuV8uBt2p446aKct6Fo7eaTu0GNgEoQJy', 'activo', NULL, NULL, '2025-10-14 00:58:36', '2025-10-14 00:58:36'),
(21, 5, 'Diego Armando', 'Garcia Garcia', '123456789', 'diego.garcia@bonumcoolers.com', NULL, '$2y$12$RrxhiL/wBRFdOCU4VuyFwezV4ILc77jy0FsH3ANAjQYUFDoyFz74S', 'activo', NULL, NULL, '2025-10-14 00:59:59', '2025-10-14 00:59:59'),
(22, 5, 'Jose Israel', 'Godinez Mendez', '123456789', 'jose.godinez@bonumcoolers.com', NULL, '$2y$12$299166hMFvtE7rTSgx1CrOzMldN.1j0SMkO5jvrmeBCPlSB4jMS8q', 'activo', NULL, NULL, '2025-10-14 01:00:57', '2025-10-14 00:24:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_cooler`
--

CREATE TABLE `usuario_cooler` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idusuario` bigint(20) UNSIGNED NOT NULL,
  `idcooler` bigint(20) UNSIGNED NOT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_cooler`
--

INSERT INTO `usuario_cooler` (`id`, `idusuario`, `idcooler`, `estatus`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 'activo', '2025-08-30 19:24:25', '2025-08-31 20:44:45'),
(3, 6, 2, 'activo', '2025-08-30 19:40:59', '2025-08-30 19:40:59'),
(4, 1, 2, 'activo', '2025-10-09 02:31:14', NULL),
(5, 1, 3, 'activo', '2025-10-09 02:31:14', NULL),
(6, 9, 2, 'activo', '2025-10-11 12:24:59', '2025-10-11 12:24:59'),
(7, 10, 2, 'activo', '2025-10-11 12:26:10', '2025-10-11 12:26:10'),
(8, 10, 3, 'activo', '2025-10-11 12:26:10', '2025-10-11 12:26:10'),
(9, 10, 5, 'activo', '2025-10-11 12:26:10', '2025-10-11 12:26:10'),
(10, 14, 2, 'activo', '2025-10-14 00:48:11', '2025-10-14 00:48:11'),
(11, 15, 5, 'activo', '2025-10-14 00:50:06', '2025-10-14 00:50:06'),
(12, 16, 2, 'activo', '2025-10-14 00:51:30', '2025-10-14 00:51:30'),
(13, 17, 3, 'activo', '2025-10-14 00:52:47', '2025-10-14 00:52:47'),
(14, 18, 5, 'activo', '2025-10-14 00:54:14', '2025-10-14 00:54:14'),
(15, 19, 6, 'activo', '2025-10-14 00:55:22', '2025-10-14 00:55:22'),
(16, 20, 2, 'activo', '2025-10-14 00:58:36', '2025-10-14 00:58:36'),
(17, 21, 3, 'activo', '2025-10-14 00:59:59', '2025-10-14 00:59:59'),
(18, 22, 5, 'activo', '2025-10-14 01:00:57', '2025-10-14 00:24:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variedad`
--

CREATE TABLE `variedad` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipofruta` varchar(255) NOT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `variedad`
--

INSERT INTO `variedad` (`id`, `tipofruta`, `estatus`, `created_at`, `updated_at`) VALUES
(2, 'Orgánica', 'activo', '2025-07-18 00:35:19', '2025-08-30 19:52:37'),
(3, 'Convencional', 'activo', '2025-07-18 06:58:05', '2025-07-18 06:58:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `camara`
--
ALTER TABLE `camara`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_idcooler_codigo` (`idcooler`,`codigo`);

--
-- Indices de la tabla `cobranzas`
--
ALTER TABLE `cobranzas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cobranzas_idrecepcion_foreign` (`idrecepcion`),
  ADD KEY `cobranzas_iddetallerecepcion_foreign` (`iddetallerecepcion`);

--
-- Indices de la tabla `comercializadora`
--
ALTER TABLE `comercializadora`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comercializadora_rfc_unique` (`rfc`);

--
-- Indices de la tabla `conservacion`
--
ALTER TABLE `conservacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conservacion_idcamara_foreign` (`idcamara`),
  ADD KEY `conservacion_idtarima_foreign` (`idtarima`);

--
-- Indices de la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contrato_idcomercializadora_foreign` (`idcomercializadora`),
  ADD KEY `contrato_idusuario_foreign` (`idusuario`),
  ADD KEY `contrato_idcooler_foreign` (`idcooler`);

--
-- Indices de la tabla `cooler`
--
ALTER TABLE `cooler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cooler_codigoidentificador_unique` (`codigoidentificador`);

--
-- Indices de la tabla `cruce_anden`
--
ALTER TABLE `cruce_anden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cruce_anden_idtarima_foreign` (`idtarima`),
  ADD KEY `cruce_anden_idcamara_foreign` (`idcamara`);

--
-- Indices de la tabla `detalle_conservacion`
--
ALTER TABLE `detalle_conservacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_conservacion_idconservacion_foreign` (`idconservacion`),
  ADD KEY `detalle_conservacion_iddetalle_foreign` (`iddetalle`);

--
-- Indices de la tabla `detalle_contrato`
--
ALTER TABLE `detalle_contrato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_contrato_idcontrato_foreign` (`idcontrato`),
  ADD KEY `detalle_contrato_idfruta_foreign` (`idfruta`),
  ADD KEY `detalle_contrato_idvariedad_foreign` (`idvariedad`),
  ADD KEY `detalle_contrato_idpresentacion_foreign` (`idpresentacion`);

--
-- Indices de la tabla `detalle_cruce_anden`
--
ALTER TABLE `detalle_cruce_anden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_cruce_anden_idcruce_anden_foreign` (`idcruce_anden`),
  ADD KEY `detalle_cruce_anden_iddetalle_foreign` (`iddetalle`);

--
-- Indices de la tabla `detalle_embarcaciones`
--
ALTER TABLE `detalle_embarcaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_embarcaciones_idembarcacion_foreign` (`idembarcacion`),
  ADD KEY `detalle_embarcaciones_idconservacion_foreign` (`idconservacion`);

--
-- Indices de la tabla `detalle_preenfriado`
--
ALTER TABLE `detalle_preenfriado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_preenfriado_idpreenfrio_foreign` (`idpreenfrio`),
  ADD KEY `detalle_preenfriado_iddetalle_foreign` (`iddetalle`);

--
-- Indices de la tabla `detalle_recepcion`
--
ALTER TABLE `detalle_recepcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_recepcion_idrecepcion_foreign` (`idrecepcion`),
  ADD KEY `detalle_recepcion_idfruta_foreign` (`idfruta`),
  ADD KEY `detalle_recepcion_idvariedad_foreign` (`idvariedad`),
  ADD KEY `detalle_recepcion_idpresentacion_foreign` (`idpresentacion`);

--
-- Indices de la tabla `embarcaciones`
--
ALTER TABLE `embarcaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `embarcaciones_id_usuario_foreign` (`id_usuario`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `fruta`
--
ALTER TABLE `fruta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permisos_nombre_unique` (`nombre`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indices de la tabla `permission_rol`
--
ALTER TABLE `permission_rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_rol_permission_id_rol_usuario_id_unique` (`permission_id`,`rol_usuario_id`),
  ADD KEY `permission_rol_rol_usuario_id_foreign` (`rol_usuario_id`);

--
-- Indices de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_user_permission_id_user_id_unique` (`permission_id`,`user_id`),
  ADD KEY `permission_user_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `preenfriado`
--
ALTER TABLE `preenfriado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `preenfriado_idcamara_foreign` (`idcamara`),
  ADD KEY `preenfriado_idtarima_foreign` (`idtarima`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recepcion`
--
ALTER TABLE `recepcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recepcion_idusuario_foreign` (`idusuario`),
  ADD KEY `recepcion_idcontrato_foreign` (`idcontrato`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rol_permiso_idrol_idpermiso_unique` (`idrol`,`idpermiso`),
  ADD KEY `rol_permiso_idpermiso_foreign` (`idpermiso`);

--
-- Indices de la tabla `rol_usuario`
--
ALTER TABLE `rol_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tarimas`
--
ALTER TABLE `tarimas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tarimas_codigo_unique` (`codigo`);

--
-- Indices de la tabla `tarima_detarec`
--
ALTER TABLE `tarima_detarec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tarima_detarec_iddetalle_foreign` (`iddetalle`),
  ADD KEY `tarima_detarec_idtarima_foreign` (`idtarima`),
  ADD KEY `tarima_detarec_idtipopallet_foreign` (`idtipopallet`);

--
-- Indices de la tabla `tipopallet`
--
ALTER TABLE `tipopallet`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_idrol_foreign` (`idrol`);

--
-- Indices de la tabla `usuario_cooler`
--
ALTER TABLE `usuario_cooler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_cooler_idusuario_idcooler_unique` (`idusuario`,`idcooler`),
  ADD KEY `usuario_cooler_idcooler_foreign` (`idcooler`);

--
-- Indices de la tabla `variedad`
--
ALTER TABLE `variedad`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `camara`
--
ALTER TABLE `camara`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `cobranzas`
--
ALTER TABLE `cobranzas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `comercializadora`
--
ALTER TABLE `comercializadora`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `conservacion`
--
ALTER TABLE `conservacion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `cooler`
--
ALTER TABLE `cooler`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cruce_anden`
--
ALTER TABLE `cruce_anden`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_conservacion`
--
ALTER TABLE `detalle_conservacion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detalle_contrato`
--
ALTER TABLE `detalle_contrato`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `detalle_cruce_anden`
--
ALTER TABLE `detalle_cruce_anden`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_embarcaciones`
--
ALTER TABLE `detalle_embarcaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `detalle_preenfriado`
--
ALTER TABLE `detalle_preenfriado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `detalle_recepcion`
--
ALTER TABLE `detalle_recepcion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `embarcaciones`
--
ALTER TABLE `embarcaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fruta`
--
ALTER TABLE `fruta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `permission_rol`
--
ALTER TABLE `permission_rol`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preenfriado`
--
ALTER TABLE `preenfriado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `recepcion`
--
ALTER TABLE `recepcion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `rol_usuario`
--
ALTER TABLE `rol_usuario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tarimas`
--
ALTER TABLE `tarimas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1075;

--
-- AUTO_INCREMENT de la tabla `tarima_detarec`
--
ALTER TABLE `tarima_detarec`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `tipopallet`
--
ALTER TABLE `tipopallet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuario_cooler`
--
ALTER TABLE `usuario_cooler`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `variedad`
--
ALTER TABLE `variedad`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `camara`
--
ALTER TABLE `camara`
  ADD CONSTRAINT `camara_idcooler_foreign` FOREIGN KEY (`idcooler`) REFERENCES `cooler` (`id`);

--
-- Filtros para la tabla `cobranzas`
--
ALTER TABLE `cobranzas`
  ADD CONSTRAINT `cobranzas_iddetallerecepcion_foreign` FOREIGN KEY (`iddetallerecepcion`) REFERENCES `detalle_recepcion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cobranzas_idrecepcion_foreign` FOREIGN KEY (`idrecepcion`) REFERENCES `recepcion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `conservacion`
--
ALTER TABLE `conservacion`
  ADD CONSTRAINT `conservacion_idcamara_foreign` FOREIGN KEY (`idcamara`) REFERENCES `camara` (`id`),
  ADD CONSTRAINT `conservacion_idtarima_foreign` FOREIGN KEY (`idtarima`) REFERENCES `tarimas` (`id`);

--
-- Filtros para la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD CONSTRAINT `contrato_idcomercializadora_foreign` FOREIGN KEY (`idcomercializadora`) REFERENCES `comercializadora` (`id`),
  ADD CONSTRAINT `contrato_idcooler_foreign` FOREIGN KEY (`idcooler`) REFERENCES `cooler` (`id`),
  ADD CONSTRAINT `contrato_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `cruce_anden`
--
ALTER TABLE `cruce_anden`
  ADD CONSTRAINT `cruce_anden_idcamara_foreign` FOREIGN KEY (`idcamara`) REFERENCES `camara` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cruce_anden_idtarima_foreign` FOREIGN KEY (`idtarima`) REFERENCES `tarimas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_conservacion`
--
ALTER TABLE `detalle_conservacion`
  ADD CONSTRAINT `detalle_conservacion_idconservacion_foreign` FOREIGN KEY (`idconservacion`) REFERENCES `conservacion` (`id`),
  ADD CONSTRAINT `detalle_conservacion_iddetalle_foreign` FOREIGN KEY (`iddetalle`) REFERENCES `detalle_recepcion` (`id`);

--
-- Filtros para la tabla `detalle_contrato`
--
ALTER TABLE `detalle_contrato`
  ADD CONSTRAINT `detalle_contrato_idcontrato_foreign` FOREIGN KEY (`idcontrato`) REFERENCES `contrato` (`id`),
  ADD CONSTRAINT `detalle_contrato_idfruta_foreign` FOREIGN KEY (`idfruta`) REFERENCES `fruta` (`id`),
  ADD CONSTRAINT `detalle_contrato_idpresentacion_foreign` FOREIGN KEY (`idpresentacion`) REFERENCES `presentacion` (`id`),
  ADD CONSTRAINT `detalle_contrato_idvariedad_foreign` FOREIGN KEY (`idvariedad`) REFERENCES `variedad` (`id`);

--
-- Filtros para la tabla `detalle_cruce_anden`
--
ALTER TABLE `detalle_cruce_anden`
  ADD CONSTRAINT `detalle_cruce_anden_idcruce_anden_foreign` FOREIGN KEY (`idcruce_anden`) REFERENCES `cruce_anden` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_cruce_anden_iddetalle_foreign` FOREIGN KEY (`iddetalle`) REFERENCES `detalle_recepcion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_embarcaciones`
--
ALTER TABLE `detalle_embarcaciones`
  ADD CONSTRAINT `detalle_embarcaciones_idconservacion_foreign` FOREIGN KEY (`idconservacion`) REFERENCES `conservacion` (`id`),
  ADD CONSTRAINT `detalle_embarcaciones_idembarcacion_foreign` FOREIGN KEY (`idembarcacion`) REFERENCES `embarcaciones` (`id`);

--
-- Filtros para la tabla `detalle_preenfriado`
--
ALTER TABLE `detalle_preenfriado`
  ADD CONSTRAINT `detalle_preenfriado_iddetalle_foreign` FOREIGN KEY (`iddetalle`) REFERENCES `detalle_recepcion` (`id`),
  ADD CONSTRAINT `detalle_preenfriado_idpreenfrio_foreign` FOREIGN KEY (`idpreenfrio`) REFERENCES `preenfriado` (`id`);

--
-- Filtros para la tabla `detalle_recepcion`
--
ALTER TABLE `detalle_recepcion`
  ADD CONSTRAINT `detalle_recepcion_idfruta_foreign` FOREIGN KEY (`idfruta`) REFERENCES `fruta` (`id`),
  ADD CONSTRAINT `detalle_recepcion_idpresentacion_foreign` FOREIGN KEY (`idpresentacion`) REFERENCES `presentacion` (`id`),
  ADD CONSTRAINT `detalle_recepcion_idrecepcion_foreign` FOREIGN KEY (`idrecepcion`) REFERENCES `recepcion` (`id`),
  ADD CONSTRAINT `detalle_recepcion_idvariedad_foreign` FOREIGN KEY (`idvariedad`) REFERENCES `variedad` (`id`);

--
-- Filtros para la tabla `embarcaciones`
--
ALTER TABLE `embarcaciones`
  ADD CONSTRAINT `embarcaciones_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `permission_rol`
--
ALTER TABLE `permission_rol`
  ADD CONSTRAINT `permission_rol_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_rol_rol_usuario_id_foreign` FOREIGN KEY (`rol_usuario_id`) REFERENCES `rol_usuario` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preenfriado`
--
ALTER TABLE `preenfriado`
  ADD CONSTRAINT `preenfriado_idcamara_foreign` FOREIGN KEY (`idcamara`) REFERENCES `camara` (`id`),
  ADD CONSTRAINT `preenfriado_idtarima_foreign` FOREIGN KEY (`idtarima`) REFERENCES `tarimas` (`id`);

--
-- Filtros para la tabla `recepcion`
--
ALTER TABLE `recepcion`
  ADD CONSTRAINT `recepcion_idcontrato_foreign` FOREIGN KEY (`idcontrato`) REFERENCES `contrato` (`id`),
  ADD CONSTRAINT `recepcion_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_idpermiso_foreign` FOREIGN KEY (`idpermiso`) REFERENCES `permisos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permiso_idrol_foreign` FOREIGN KEY (`idrol`) REFERENCES `rol_usuario` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tarima_detarec`
--
ALTER TABLE `tarima_detarec`
  ADD CONSTRAINT `tarima_detarec_iddetalle_foreign` FOREIGN KEY (`iddetalle`) REFERENCES `detalle_recepcion` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tarima_detarec_idtarima_foreign` FOREIGN KEY (`idtarima`) REFERENCES `tarimas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tarima_detarec_idtipopallet_foreign` FOREIGN KEY (`idtipopallet`) REFERENCES `tipopallet` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_idrol_foreign` FOREIGN KEY (`idrol`) REFERENCES `rol_usuario` (`id`);

--
-- Filtros para la tabla `usuario_cooler`
--
ALTER TABLE `usuario_cooler`
  ADD CONSTRAINT `usuario_cooler_idcooler_foreign` FOREIGN KEY (`idcooler`) REFERENCES `cooler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_cooler_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
