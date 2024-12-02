-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2024 a las 07:52:27
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
-- Base de datos: `tienda-pi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_compras`
--

CREATE TABLE `carrito_compras` (
  `id_producto_en_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_compras`
--

INSERT INTO `carrito_compras` (`id_producto_en_carrito`, `id_usuario`, `id_producto`, `cantidad`) VALUES
(27, 6, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id_direccion` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `direccion` text NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id_direccion`, `usuario_id`, `direccion`, `ciudad`, `codigo_postal`, `pais`) VALUES
(1, 7, 'dr fernando uriarte 1939', 'culiacan', '80058', 'México'),
(3, 7, 'fuente de monasterio 4', 'Huixquilucan', '52788', 'México'),
(4, 1, 'dr fernando uriarte 1939', 'culiacan', '80058', 'México');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_compras`
--

CREATE TABLE `historial_compras` (
  `id_compra` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_compras`
--

INSERT INTO `historial_compras` (`id_compra`, `id_usuario`, `id_producto`, `fecha_compra`, `cantidad`) VALUES
(1, 1, 1, '2024-11-24 21:33:48', 2),
(2, 1, 1, '2024-11-26 04:56:07', 2),
(3, 1, 2, '2024-11-26 04:56:07', 2),
(4, 1, 1, '2024-11-26 04:56:47', 1),
(5, 1, 2, '2024-11-26 04:56:47', 1),
(6, 3, 1, '2024-11-26 05:21:35', 1),
(7, 3, 1, '2024-11-26 05:23:02', 1),
(8, 1, 8, '2024-11-29 22:50:00', 2),
(9, 1, 3, '2024-12-02 05:31:02', 2),
(10, 1, 1, '2024-12-02 05:31:02', 1),
(11, 1, 4, '2024-12-02 05:41:45', 1),
(12, 7, 7, '2024-12-02 05:43:51', 1),
(13, 7, 3, '2024-12-02 05:43:51', 1),
(14, 7, 10, '2024-12-02 05:43:51', 1),
(15, 7, 1, '2024-12-02 05:55:12', 1),
(16, 7, 4, '2024-12-02 05:58:12', 1),
(17, 7, 3, '2024-12-02 06:00:08', 1),
(18, 7, 1, '2024-12-02 06:02:59', 1),
(19, 7, 1, '2024-12-02 06:09:56', 1),
(20, 7, 2, '2024-12-02 06:13:19', 1),
(21, 1, 9, '2024-12-02 06:39:36', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `foto`, `precio`, `cantidad`) VALUES
(1, 'Adidas Predator Edge', 'Diseñado para precisión y potencia en el campo.', 'images/product_1.png', 50.00, 6),
(2, 'Nike Mercurial Superfly', 'Ligero con enfoque en velocidad y agilidad.', 'images/product_2.png', 51.50, 7),
(3, 'Puma Future Z', 'Ajuste flexible para control y comodidad total.', 'images/product_3.png', 53.00, 8),
(4, 'Adidas Copa Sense', 'Diseño clásico con tecnología moderna para comodidad.', 'images/product_4.png', 54.50, 11),
(5, 'Nike Phantom GT2', 'Ingeniería avanzada para mayor agarre y precisión.', 'images/product_5.png', 56.00, 14),
(6, 'Puma Ultra', 'Diseño ultraligero para máxima velocidad.', 'images/product_6.png', 57.50, 10),
(7, 'Adidas X Speedflow', 'Proporciona retorno de energía y aceleración óptimos.', 'images/product_7.png', 59.00, 10),
(8, 'Nike Tiempo Legend', 'Fabricado para durabilidad y control superior del balón.', 'images/product_8.png', 60.50, 10),
(9, 'Mizuno Morelia Neo', 'Cuero premium para una sensación y ajuste tradicional.', 'images/product_9.png', 62.00, 12),
(10, 'New Balance Furon', 'Combina velocidad y comodidad con un diseño elegante.', 'images/product_10.png', 63.50, 13),
(11, 'Adidas Nemeziz', 'Diseño dinámico para agilidad y movimientos rápidos.', 'images/product_11.png', 65.00, 10),
(12, 'Nike Hypervenom Phantom', 'Ofrece una mezcla perfecta de potencia y control.', 'images/product_12.png', 66.50, 11),
(13, 'Umbro Medusae', 'Aspecto tradicional con características de alto rendimiento.', 'images/product_13.png', 68.00, 12),
(14, 'Diadora Brasil', 'Diseño icónico con artesanía italiana.', 'images/product_14.png', 69.50, 13),
(15, 'Puma King Platinum', 'Bota de herencia con mejoras modernas en rendimiento.', 'images/product_15.png', 71.00, 14),
(16, 'Under Armour Magnetico', 'Ajuste innovador para comodidad y soporte todo el día.', 'images/product_16.png', 72.50, 10),
(17, 'Adidas Predator Freak', 'Diseñado para amplificar el efecto y giro del balón.', 'images/product_17.png', 74.00, 11),
(18, 'Nike Mercurial Vapor', 'Diseño aerodinámico para velocidad explosiva.', 'images/product_18.png', 75.50, 12),
(19, 'Adidas Copa Mundial', 'Diseño atemporal para jugadores de todos los niveles.', 'images/product_19.png', 78.50, 14),
(20, 'Nike Phantom Vision', 'Toque mejorado y precisión con el balón.', 'images/product_20.png', 80.00, 10),
(21, 'Adidas Predator Mutator', 'Suela innovadora para mejor tracción y control.', 'images/product_21.png', 81.50, 11),
(22, 'Nike Tiempo Premier', 'Diseño clásico con durabilidad superior.', 'images/product_22.png', 83.00, 12),
(23, 'Mizuno Rebula', 'Optimizado para precisión y tiros potentes.', 'images/product_23.png', 84.50, 13),
(24, 'Adidas X Ghosted', 'Diseñado para velocidad y agilidad en terrenos firmes.', 'images/product_24.png', 86.00, 14),
(25, 'Nike CTR360 Maestri', 'Bota enfocada en el control para creadores de juego.', 'images/product_25.png', 87.50, 10),
(26, 'Puma evoPOWER', 'Optimizado para precisión y disparos potentes.', 'images/product_26.png', 89.00, 11),
(27, 'Under Armour Blur', 'Construido para velocidad y cambios de dirección rápidos.', 'images/product_27.png', 90.50, 12),
(28, 'Joma Top Flex', 'Perfecto para juegos interiores y exteriores.', 'images/product_28.png', 92.00, 13),
(29, 'Hummel Old School Star', 'Diseño retro con mejoras modernas.', 'images/product_29.png', 93.50, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `numero_tarjeta` varchar(20) DEFAULT NULL,
  `es_administrador` tinyint(1) DEFAULT 0,
  `direccion_postal` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo_electronico`, `contrasena`, `fecha_nacimiento`, `numero_tarjeta`, `es_administrador`, `direccion_postal`) VALUES
(1, '', 'ontiverosc.jorge@gmail.com', '$2y$10$E7jVMs5okMJppH6QZ7bLTeF2Wv.pNLPdwYJtVx8/JkO/jZVQ7m/7u', NULL, NULL, 1, NULL),
(3, 'Jorge Eduardo Ontiveros Cota', 'ontiverosc_jorge@hotmail.com', '$2y$10$T88/8rYTbMEH00K1RjBP9uDsNQYAz45Dtw7QrQLA7Pjw/1bKo9v2W', NULL, NULL, 0, NULL),
(4, 'Bruno', 'ravelo.bruno@gmail.com', '$2y$10$qLPdmQSeU3EcGNS5X8O8H.k3KBmWObBiirzUEko5jB0PGsBQ7b0xi', NULL, NULL, 0, NULL),
(5, 'Manuela Cota Cárdenas', 'manuela@gmail.com', '$2y$10$wxPiQRf7VnlSGcz.ep6vSua/c1WoVt1wd9EAT6a4xhuRYiWsPxGhu', NULL, NULL, 0, NULL),
(6, 'iker ontiveros', 'iker@gmail.com', '$2y$10$8bibcwQNZmdQZ.nDHRgLOOJGwhD920nAjdzy1D.bdDgosSv07RP/W', NULL, NULL, 0, NULL),
(7, 'karla', 'ontiverosc.karla@gmail.com', '$2y$10$EO0IScjq3JF123X6y8oBruvatXTUqR1AE24l/2fNNW/A3DmGQ8RMa', NULL, NULL, 0, NULL),
(8, 'guille', 'ontiverosc.guille@gmail.com', '$2y$10$HB4Tl0twhN6.HiFKO/l7nup568P6Zz9Vg03pPPh5rtiUrju5mue1e', NULL, NULL, 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD PRIMARY KEY (`id_producto_en_carrito`),
  ADD KEY `usuario_id` (`id_usuario`),
  ADD KEY `producto_id` (`id_producto`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id_direccion`);

--
-- Indices de la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `usuario_id` (`id_usuario`),
  ADD KEY `producto_id` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `id_producto_en_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD CONSTRAINT `carrito_compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `carrito_compras_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  ADD CONSTRAINT `historial_compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `historial_compras_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
