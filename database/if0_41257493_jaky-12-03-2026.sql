-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db:3306
-- Tiempo de generación: 12-03-2026 a las 23:18:00
-- Versión del servidor: 8.0.45
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_41257493_jaky`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `accion` varchar(50) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `modulo`, `descripcion`, `ip`, `fecha`) VALUES
(1, 1, 'productos', 'crear', 'Alta de producto: Letras doradas grandes (Código: let222)', '::1', '2026-02-21 18:47:30'),
(2, 1, 'eliminar', 'productos', 'Eliminó producto ID: 7', '::1', '2026-02-21 18:51:36'),
(3, 1, 'movimiento', 'movimientos', 'Movimiento: entrada - Producto ID 5 - Cantidad 25', '::1', '2026-02-21 18:57:21'),
(4, 1, 'crear', 'usuarios', 'Creó usuario: dario', '::1', '2026-02-21 19:01:48'),
(5, 1, 'modificar', 'permisos', 'Modificó permisos del usuario ID 11', '::1', '2026-02-21 19:06:45'),
(6, 1, 'eliminar', 'usuarios', 'Eliminó usuario ID: ', '::1', '2026-02-21 19:12:44'),
(7, 1, 'crear', 'usuarios', 'Creó usuario: prueba', '::1', '2026-02-21 19:16:47'),
(8, 1, 'eliminar', 'usuarios', 'Eliminó usuario ID: 12', '::1', '2026-02-21 19:17:00'),
(15, 1, 'productos', 'crear', 'Alta de producto: Pulsera dorada n33 (Código: pul666)', '::1', '2026-02-23 14:43:54'),
(16, 1, 'Cambio depósito', 'productos', 'Producto ID 19: Depósito Central → Depósito Secundario', '::1', '2026-02-23 15:07:44'),
(17, 1, 'Cambio categoría', 'productos', 'Producto ID 19: Sin categoría → Pulseras', '::1', '2026-02-23 15:07:44'),
(18, 1, 'Edición producto', 'productos', 'Editó producto ID 19', '::1', '2026-02-23 15:07:44'),
(19, 1, 'movimiento', 'movimientos', 'Movimiento: entrada - Producto ID 19 - Cantidad 150', '::1', '2026-02-23 15:09:01'),
(20, 1, 'eliminar', 'productos', 'Eliminó producto ID: 15', '::1', '2026-02-23 15:28:38'),
(21, 1, 'Cambio categoría', 'productos', 'Producto ID 5: Sin categoría → Anillos', '::1', '2026-02-24 10:05:20'),
(22, 1, 'Edición producto', 'productos', 'Editó producto ID 5', '::1', '2026-02-24 10:05:20'),
(23, 1, 'Cambio depósito', 'productos', 'Producto ID 5: Depósito Central → Depósito Secundario', '::1', '2026-02-24 10:05:31'),
(24, 1, 'Edición producto', 'productos', 'Editó producto ID 5', '::1', '2026-02-24 10:05:31'),
(25, 1, 'Cambio categoría', 'productos', 'Producto ID 10: Sin categoría → Anillos', '::1', '2026-02-24 11:53:52'),
(26, 1, 'Edición producto', 'productos', 'Editó producto ID 10', '::1', '2026-02-24 11:53:52'),
(27, 1, 'productos', 'crear', 'Alta de producto: imagen uno (Código: img1)', '::1', '2026-02-24 12:58:27'),
(28, 1, 'Edición producto', 'productos', 'Editó producto ID 21', '::1', '2026-02-24 12:59:16'),
(29, 1, 'eliminar', 'productos', 'Eliminó producto ID: 21', '::1', '2026-02-24 12:59:30'),
(30, 1, 'eliminar', 'productos', 'Eliminó producto ID: 21', '::1', '2026-02-24 12:59:44'),
(31, 1, 'eliminar', 'productos', 'Eliminó producto ID: 21', '::1', '2026-02-24 13:01:25'),
(32, 1, 'eliminar', 'productos', 'Eliminó producto ID: 21', '::1', '2026-02-24 13:01:36'),
(33, 1, 'eliminar', 'productos', 'Eliminó producto ID: 21', '::1', '2026-02-24 13:48:32'),
(34, 1, 'eliminar', 'productos', 'Eliminó producto ID: 16', '::1', '2026-02-24 13:51:21'),
(35, 1, 'Cambio categoría', 'productos', 'Producto ID 9: Sin categoría → Anillos', '::1', '2026-02-24 13:55:14'),
(36, 1, 'Edición producto', 'productos', 'Editó producto ID 9', '::1', '2026-02-24 13:55:14'),
(37, 1, 'productos', 'crear', 'Alta de producto: Anillo plata (Código: imgan0124)', '::1', '2026-02-24 13:58:07'),
(38, 1, 'productos', 'crear', 'Alta de producto: Pulsera dorada n32 (Código: pul001)', '::1', '2026-02-24 14:11:38'),
(39, 1, 'productos', 'crear', 'Alta de producto: Cadena barbada fina (Código: cad002)', '::1', '2026-02-24 14:19:59'),
(40, 1, 'movimiento', 'movimientos', 'Movimiento: entrada - Producto ID 24 - Cantidad 6', '::1', '2026-02-24 14:21:08'),
(41, 1, 'movimiento', 'movimientos', 'Movimiento: entrada - Producto ID 22 - Cantidad 2', '190.104.235.247', '2026-02-25 08:47:27'),
(42, 1, 'productos', 'crear', 'Alta de producto: Anillo inoxidable nue n23 (Código: nue555)', '172.20.0.1', '2026-03-10 19:52:29'),
(43, 1, 'productos', 'crear', 'Alta de producto: sin imagen (Código: borrar)', '172.20.0.1', '2026-03-11 16:14:56'),
(44, 1, 'stock', 'editar', 'Actualización de stock del producto ID 9', '172.20.0.1', '2026-03-11 18:09:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Anillos'),
(2, 'Pulseras'),
(3, 'Cadenas'),
(4, 'Dijes'),
(5, 'Aros'),
(6, 'Otros'),
(7, 'piercing');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`, `email`) VALUES
(1, 'José Perez', '113036666', 'dssssss@hotmail.com'),
(4, 'Gerardo Montiel', '3884848', 'kdfgdgf@kdkdk.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `depositos`
--

CREATE TABLE `depositos` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `depositos`
--

INSERT INTO `depositos` (`id`, `nombre`, `descripcion`, `activo`, `created_at`) VALUES
(1, 'Depósito Central', 'Principal', 1, '2026-02-23 16:16:00'),
(2, 'Depósito Secundario', 'Respaldo', 1, '2026-02-23 16:16:00'),
(3, 'Depósito Mostrador', 'Venta directa', 1, '2026-02-23 16:16:00'),
(8, 'Reservas pedidos', 'Stock reservado automáticamente por pedidos web', 1, '2026-03-09 14:00:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int NOT NULL,
  `producto_id` int NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `lote` varchar(100) DEFAULT NULL,
  `cliente_id` int DEFAULT NULL,
  `proveedor_id` int DEFAULT NULL,
  `usuario_id` int NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `producto_id`, `tipo`, `cantidad`, `lote`, `cliente_id`, `proveedor_id`, `usuario_id`, `fecha`) VALUES
(1, 3, 'entrada', 5.00, NULL, NULL, 1, 1, '2026-02-18 17:00:06'),
(2, 5, 'entrada', 12.00, NULL, NULL, 1, 1, '2026-02-19 15:35:59'),
(3, 7, 'entrada', 15.00, NULL, NULL, 3, 1, '2026-02-19 15:46:18'),
(4, 7, 'salida', 1.00, NULL, 1, NULL, 1, '2026-02-19 15:56:14'),
(5, 9, 'entrada', 5.00, NULL, NULL, 3, 1, '2026-02-19 16:44:46'),
(6, 10, 'entrada', 100.00, 'Lote A', NULL, 3, 1, '2026-02-19 16:58:34'),
(7, 10, 'entrada', 200.00, 'Lote B', NULL, 3, 1, '2026-02-19 16:59:27'),
(8, 10, 'salida', 120.00, NULL, 1, NULL, 1, '2026-02-19 17:00:15'),
(9, 6, 'entrada', 22.00, 'Lote A', NULL, 4, 4, '2026-02-19 18:01:42'),
(10, 12, 'entrada', 55.00, 'Lote A', NULL, 4, 1, '2026-02-20 08:51:31'),
(11, 13, 'entrada', 300.00, 'Lote 20-02-2026', NULL, 3, 1, '2026-02-20 08:53:28'),
(12, 13, 'salida', 22.00, 'Lote 20-02-2026', 1, NULL, 1, '2026-02-20 08:54:13'),
(13, 8, 'entrada', 25.00, 'Lote A', NULL, 3, 1, '2026-02-20 09:46:12'),
(14, 14, 'entrada', 55.00, 'Lote 21-02-2026', NULL, 3, 1, '2026-02-21 18:30:24'),
(15, 14, 'entrada', 22.00, 'Lote 21-02-2026-b', NULL, 3, 9, '2026-02-21 18:34:22'),
(16, 5, 'entrada', 25.00, 'Lote 21-02-2026', NULL, 1, 1, '2026-02-21 18:57:21'),
(17, 19, 'entrada', 150.00, 'Lote 23-02-2026', NULL, 1, 1, '2026-02-23 15:09:01'),
(18, 24, 'entrada', 6.00, 'Lote 23-02-2026-b', NULL, 3, 1, '2026-02-24 14:21:08'),
(19, 22, 'entrada', 2.00, 'Lote 25-02-2026', NULL, 4, 1, '2026-02-25 08:47:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(50) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `fecha`, `nombre`, `telefono`, `email`, `direccion`, `total`, `estado`) VALUES
(3, '2026-02-27 17:04:49', 'para eliminar', '445315315', 'y5wywy@hotmail.com', 'dfg 646576', 2700.00, 'enviado'),
(4, '2026-02-27 17:06:40', 'para eliminar', '5555', 'dsfafa@hotmail.com', 'dfaef 555', 1600.00, 'pagado'),
(12, '2026-03-09 18:26:08', 'Andrea Romero', '384874', 'dodfoodf@hotmail.com', 'dkfjaljf 485', 1500.00, 'cancelado'),
(24, '2026-03-10 15:18:46', 'Dario Sergio Moreda', '939393', 'dariosergio@hotmail.com', 'Aranguren 1399', 1000.00, 'entregado'),
(25, '2026-03-10 15:57:20', 'Ped Varios', '73737', 'variospedidos@hotmail.com', 'Aksksk 99', 2200.00, 'entregado'),
(26, '2026-03-10 16:21:16', 'Flor Estado', '7457', 'dkdfgdkjf@hotmail.com', 'Ajdjdjd 848', 600.00, 'enviado'),
(27, '2026-03-10 18:24:12', 'Gerardo Montiel', '546546', 'ggjj@hotmail.com', 'Afgfgfg 988', 1000.00, 'entregado'),
(28, '2026-03-12 14:07:57', 'Prueba depositos', '474747', 'dep@hotmail.com', 'Nada 77', 1000.00, 'pendiente'),
(31, '2026-03-12 15:14:11', 'Prueba dep2', '3468347', 'dep2@hotmail.com', 'Nada2 383', 4000.00, 'pendiente'),
(32, '2026-03-12 16:30:54', 'pru dep 3', '4858845', 'dep3@hotmail.com', 'Nada3 484', 2400.00, 'cancelado'),
(34, '2026-03-12 18:26:13', 'Pru dep 4', '666', 'dep4@hotmail.com', 'Dep4 444', 7500.00, 'cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `id` int NOT NULL,
  `pedido_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `deposito_origen` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`id`, `pedido_id`, `producto_id`, `descripcion`, `precio`, `cantidad`, `subtotal`, `deposito_origen`) VALUES
(5, 3, 22, 'Anillo plata n18', 1200.00, 1.00, 1200.00, 1),
(6, 3, 24, 'Cadena barbada fina', 1500.00, 1.00, 1500.00, 1),
(7, 4, 9, 'Anillo de inoxidable liso n23', 1000.00, 1.00, 1000.00, 1),
(8, 4, 13, 'Flor azul', 600.00, 1.00, 600.00, 1),
(16, 12, 14, 'Pulsera premium n35', 1500.00, 1.00, 1500.00, 1),
(28, 24, 9, 'Anillo de inoxidable liso n23', 1000.00, 1.00, 1000.00, 1),
(29, 25, 13, 'Flor azul', 600.00, 2.00, 1200.00, 1),
(30, 25, 9, 'Anillo de inoxidable liso n23', 1000.00, 1.00, 1000.00, 1),
(31, 26, 13, 'Flor azul', 600.00, 1.00, 600.00, 1),
(32, 27, 10, 'Anillo dorado n20', 1000.00, 1.00, 1000.00, 1),
(33, 28, 9, 'Anillo de inoxidable liso n23', 1000.00, 1.00, 1000.00, 1),
(36, 31, 9, 'Anillo de inoxidable liso n23', 1000.00, 4.00, 4000.00, 1),
(37, 32, 22, 'Anillo plata n18', 1200.00, 2.00, 2400.00, 1),
(39, 34, 24, 'Cadena barbada fina', 1500.00, 5.00, 7500.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_usuario`
--

CREATE TABLE `permisos_usuario` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `modulo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `permisos_usuario`
--

INSERT INTO `permisos_usuario` (`id`, `usuario_id`, `modulo`) VALUES
(5, 9, 'categorias'),
(6, 10, 'productos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `deposito_id` int NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int DEFAULT NULL,
  `stock` int DEFAULT '0',
  `stock_minimo` int DEFAULT '0',
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `deposito_id`, `codigo`, `descripcion`, `imagen`, `categoria_id`, `stock`, `stock_minimo`, `precio_compra`, `precio_venta`, `activo`) VALUES
(3, 1, 'aro00259', 'Aros Dragón', '699f42ff05118.jpg', 5, 5, 3, 55.00, 75.00, 1),
(5, 2, 'cinco555', 'cinco', NULL, 1, 37, 3, 1.00, 2.00, 0),
(6, 1, 'aro0025', 'Aro común dorado', '699f426962705.jpg', 5, 22, 15, 220.00, 290.00, 1),
(7, 1, '777', 'Siete', NULL, 3, 14, 7, 5.00, 7.00, 0),
(8, 1, 'aro0066', 'Aro premium dorado', '699f434dbae22.jpg', 5, 25, 3, 1200.00, 2200.00, 1),
(9, 1, 'an0124', 'Anillo de inoxidable liso n23', '699f3f9f59c63.jpg', 1, 5, 1, 800.00, 1000.00, 1),
(10, 1, 'an2841', 'Anillo dorado n20', '699f3fb39866c.jpg', 1, 180, 5, 700.00, 1000.00, 1),
(11, 1, 'pul0024', 'Pulsera piedras n35', '699f447fb01e1.jpg', 2, 0, 50, 1200.00, 1800.00, 1),
(12, 1, 'cad345', 'Cadena inox n30', '699f44aba43a2.png', 3, 55, 20, 600.00, 800.00, 1),
(13, 1, 'dij004', 'Flor azul', '699f45377a23f.jpg', 4, 278, 50, 300.00, 600.00, 1),
(14, 1, 'pul0165', 'Pulsera premium n35', '699f445ddf47a.jpg', 2, 77, 5, 1100.00, 1500.00, 1),
(15, 1, 'let125', 'Letras doradas medianas', NULL, 4, 0, 100, 500.00, 1200.00, 0),
(16, 1, 'let222', 'Letras doradas grandes', NULL, 4, 0, 50, 800.00, 1800.00, 0),
(19, 2, 'pul666', 'Pulsera dorada n33', '699f443777eff.jpg', 2, 150, 18, 800.00, 1800.00, 1),
(22, 1, 'imgan0124', 'Anillo plata n18', '699ddad1c9a10.jpg', 1, 2, 5, 600.00, 1200.00, 1),
(23, 1, 'pul001', 'Pulsera dorada n32', '699ddbca7a5fa.png', 2, 0, 5, 700.00, 1400.00, 1),
(24, 1, 'cad002', 'Cadena barbada fina', '699de182d1090.png', 3, 6, 3, 800.00, 1500.00, 1),
(25, 2, 'nue555', 'Anillo inoxidable nue n23', '69b0767d67ac1.jpg', 1, 0, 5, 500.00, 1000.00, 1),
(26, 1, 'borrar', 'sin imagen', NULL, 1, 0, 5, 300.00, 600.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `email`) VALUES
(1, 'Gerardo Montiel', '3884848', 'ger@kdkdk.com'),
(3, 'Luciana Kesner', '884585959', 'kdkdkdk@hotmail.com'),
(4, 'Alberto Molina', '858585858', 'alb@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_deposito`
--

CREATE TABLE `stock_deposito` (
  `id` int NOT NULL,
  `producto_id` int NOT NULL,
  `deposito_id` int NOT NULL,
  `lote` varchar(50) NOT NULL DEFAULT '',
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vencimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `stock_deposito`
--

INSERT INTO `stock_deposito` (`id`, `producto_id`, `deposito_id`, `lote`, `cantidad`, `vencimiento`) VALUES
(4, 3, 1, '', 5.00, NULL),
(5, 5, 2, '', 37.00, NULL),
(6, 6, 1, '', 22.00, NULL),
(7, 7, 1, '', 14.00, NULL),
(8, 8, 1, '', 24.00, NULL),
(9, 9, 1, '', 2.00, NULL),
(10, 10, 1, '', 179.00, NULL),
(11, 12, 1, '', 55.00, NULL),
(12, 13, 1, '', 274.00, NULL),
(13, 14, 1, '', 75.00, NULL),
(14, 19, 2, '', 150.00, NULL),
(16, 24, 1, '', 6.00, NULL),
(44, 25, 2, '', 10.00, NULL),
(45, 26, 1, '', 10.00, NULL),
(46, 9, 3, '', 0.00, NULL),
(47, 9, 2, '', 2.00, NULL),
(49, 9, 8, '', 5.00, NULL),
(51, 22, 8, '', 2.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_lotes`
--

CREATE TABLE `stock_lotes` (
  `id` int NOT NULL,
  `producto_id` int NOT NULL,
  `lote` varchar(100) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `fecha_ingreso` datetime DEFAULT CURRENT_TIMESTAMP,
  `vencimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `stock_lotes`
--

INSERT INTO `stock_lotes` (`id`, `producto_id`, `lote`, `cantidad`, `fecha_ingreso`, `vencimiento`) VALUES
(1, 10, 'Lote A', 0.00, '2026-02-19 16:58:34', NULL),
(2, 10, 'Lote B', 180.00, '2026-02-19 16:59:27', NULL),
(3, 6, 'Lote A', 22.00, '2026-02-19 18:01:42', NULL),
(4, 12, 'Lote A', 55.00, '2026-02-20 08:51:31', NULL),
(5, 13, 'Lote 20-02-2026', 278.00, '2026-02-20 08:53:28', NULL),
(6, 8, 'Lote A', 25.00, '2026-02-20 09:46:12', NULL),
(7, 14, 'Lote 21-02-2026', 55.00, '2026-02-21 18:30:24', NULL),
(8, 14, 'Lote 21-02-2026-b', 22.00, '2026-02-21 18:34:22', NULL),
(9, 5, 'Lote 21-02-2026', 25.00, '2026-02-21 18:57:21', NULL),
(10, 19, 'Lote 23-02-2026', 150.00, '2026-02-23 15:09:01', NULL),
(11, 24, 'Lote 23-02-2026-b', 6.00, '2026-02-24 14:21:08', NULL),
(12, 22, 'Lote 25-02-2026', 2.00, '2026-02-25 08:47:27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencias_stock`
--

CREATE TABLE `transferencias_stock` (
  `id` int NOT NULL,
  `producto_id` int NOT NULL,
  `deposito_origen_id` int NOT NULL,
  `deposito_destino_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','empleado','operador','consulta') NOT NULL,
  `activo` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`, `activo`) VALUES
(1, 'Administrador', 'admin', '$2y$10$kWw9ADE1y6Hf6w9blKQ6muN536SKDmNmQ.6WWiJ7.wA0eoAWZkNXm', 'admin', 1),
(4, 'Pedro Perez', 'pedro', '$2y$10$RrMwZ0ESF9tHZ35mU3ehOOULHmWFUHhdh30nxaWt0Dtn67NfV5.J6', 'empleado', 1),
(9, 'Gerardo Montiel', 'operador1', '$2y$10$hz6/Crimlf0FiZmpB.XXIO3LcDb6F7OotDofEjRKJbpZR1wnX7wTK', 'operador', 1),
(10, 'Ramón Hernadez', 'consulta1', '$2y$10$pRx/gXDo2d40MmqsJhI.Du0b9fGl1iOe8whWo5Ue5p6dWxQFM6JEO', 'consulta', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `depositos`
--
ALTER TABLE `depositos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `permisos_usuario`
--
ALTER TABLE `permisos_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`modulo`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_producto_deposito` (`deposito_id`),
  ADD KEY `idx_prod_categoria` (`categoria_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock_deposito`
--
ALTER TABLE `stock_deposito`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `producto_id` (`producto_id`,`deposito_id`,`lote`),
  ADD UNIQUE KEY `uniq_producto_deposito_lote` (`producto_id`,`deposito_id`,`lote`),
  ADD KEY `deposito_id` (`deposito_id`);

--
-- Indices de la tabla `stock_lotes`
--
ALTER TABLE `stock_lotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `producto_id` (`producto_id`,`lote`);

--
-- Indices de la tabla `transferencias_stock`
--
ALTER TABLE `transferencias_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `deposito_origen_id` (`deposito_origen_id`),
  ADD KEY `deposito_destino_id` (`deposito_destino_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `depositos`
--
ALTER TABLE `depositos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `permisos_usuario`
--
ALTER TABLE `permisos_usuario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `stock_deposito`
--
ALTER TABLE `stock_deposito`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `stock_lotes`
--
ALTER TABLE `stock_lotes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `transferencias_stock`
--
ALTER TABLE `transferencias_stock`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `movimientos_ibfk_3` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `movimientos_ibfk_4` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD CONSTRAINT `pedido_detalle_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `permisos_usuario`
--
ALTER TABLE `permisos_usuario`
  ADD CONSTRAINT `permisos_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_prod_cat` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prod_categoria_01` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_producto_deposito` FOREIGN KEY (`deposito_id`) REFERENCES `depositos` (`id`),
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `stock_deposito`
--
ALTER TABLE `stock_deposito`
  ADD CONSTRAINT `stock_deposito_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `stock_deposito_ibfk_2` FOREIGN KEY (`deposito_id`) REFERENCES `depositos` (`id`);

--
-- Filtros para la tabla `stock_lotes`
--
ALTER TABLE `stock_lotes`
  ADD CONSTRAINT `stock_lotes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `transferencias_stock`
--
ALTER TABLE `transferencias_stock`
  ADD CONSTRAINT `transferencias_stock_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `transferencias_stock_ibfk_2` FOREIGN KEY (`deposito_origen_id`) REFERENCES `depositos` (`id`),
  ADD CONSTRAINT `transferencias_stock_ibfk_3` FOREIGN KEY (`deposito_destino_id`) REFERENCES `depositos` (`id`),
  ADD CONSTRAINT `transferencias_stock_ibfk_4` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
