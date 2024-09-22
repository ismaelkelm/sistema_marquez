-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-09-2024 a las 01:22:54
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
-- Base de datos: `pruebas_marquez2`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertarTecnico` (IN `nombre` VARCHAR(255))   BEGIN
    DECLARE v_id_usuario INT;

    -- Verificar que el usuario tenga el rol 'Técnico'
    SELECT u.id_usuario INTO v_id_usuario
    FROM usuario u
    JOIN roles r ON u.id_roles = r.id_roles
    WHERE r.nombre = 'Tecnico' AND u.nombre = p_nombre_usuario;

    -- Verificar si se encontró el usuario
    IF v_id_usuario IS NOT NULL THEN
        -- Insertar el nuevo técnico en la tabla tecnicos
        INSERT INTO tecnicos (id_usuario)
        VALUES (v_id_usuario);
    ELSE
        -- Manejar el caso en que el usuario no sea un técnico
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario no tiene el rol de Técnico o no existe.';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesorios_y_componentes`
--

CREATE TABLE `accesorios_y_componentes` (
  `id_accesorios_y_componentes` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `stock` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tipo` int(11) NOT NULL,
  `stockmin` int(11) NOT NULL,
  `stockmaximo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `accesorios_y_componentes`
--

INSERT INTO `accesorios_y_componentes` (`id_accesorios_y_componentes`, `nombre`, `descripcion`, `stock`, `precio`, `tipo`, `stockmin`, `stockmaximo`) VALUES
(1, 'Batería de Smartphone', 'Batería recargable para smartphones.', 50, 25.00, 1, 10, 100),
(2, 'Cargador USB-C', 'Cargador rápido USB-C para dispositivos móviles.', 75, 15.00, 2, 20, 150),
(3, 'Protector de Pantalla', 'Protector de pantalla de vidrio templado.', 100, 10.00, 3, 30, 200),
(4, 'Carcasa de Silicona', 'Carcasa protectora de silicona para smartphones.', 60, 12.00, 4, 15, 120),
(5, 'Auriculares Bluetooth', 'Auriculares inalámbricos Bluetooth.', 40, 45.00, 5, 5, 80);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area_tecnico`
--

CREATE TABLE `area_tecnico` (
  `id_area_tecnico` int(11) NOT NULL,
  `descripcion_area` varchar(45) NOT NULL,
  `id_tecnicos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `area_tecnico`
--

INSERT INTO `area_tecnico` (`id_area_tecnico`, `descripcion_area`, `id_tecnicos`) VALUES
(1, 'Reparación de Smartphones', 1),
(2, 'Mantenimiento de Equipos de Cómputo', 2),
(3, 'Reparación de Electrónica de Consumo', 3),
(4, 'Instalación de Redes', 4),
(5, 'Servicio de Atención al Cliente', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabecera_factura`
--

CREATE TABLE `cabecera_factura` (
  `id_cabecera_factura` int(11) NOT NULL,
  `fecha_factura` date NOT NULL,
  `subtotal_factura` decimal(10,2) NOT NULL,
  `impuestos` decimal(10,2) DEFAULT NULL,
  `total_factura` decimal(10,2) DEFAULT NULL,
  `id_clientes` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_operacion` int(11) NOT NULL,
  `id_tipo_comprobante` int(11) NOT NULL,
  `id_tipo_de_pago` int(11) DEFAULT NULL,
  `id_pedido_reparacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cabecera_factura`
--

INSERT INTO `cabecera_factura` (`id_cabecera_factura`, `fecha_factura`, `subtotal_factura`, `impuestos`, `total_factura`, `id_clientes`, `id_usuario`, `id_operacion`, `id_tipo_comprobante`, `id_tipo_de_pago`, `id_pedido_reparacion`) VALUES
(1, '2024-09-18', 100.00, 21.00, 121.00, 1, 2, 3, 1, 2, 1),
(2, '2024-09-19', 200.00, 42.00, 242.00, 2, 3, 4, 2, 1, 2),
(3, '2024-09-20', 150.00, 31.50, 181.50, 3, 2, 5, 1, 2, 3),
(4, '2024-09-21', 300.00, 63.00, 363.00, 4, 1, 3, 3, 1, 4),
(5, '2024-09-22', 250.00, 52.50, 302.50, 5, 4, 2, 2, 2, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_clientes` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `direccion` text NOT NULL,
  `dni` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_clientes`, `nombre`, `apellido`, `telefono`, `correo_electronico`, `direccion`, `dni`) VALUES
(1, 'Juan', 'Pérez', '123456789', 'juan.perez@example.com', 'Calle Falsa 123', '12345'),
(2, 'María', 'Gómez', '987654321', 'maria.gomez@example.com', 'Avenida Siempre Viva 742', '12345'),
(3, 'Carlos', 'Martínez', '555123456', 'carlos.martinez@example.com', 'Calle Luna 456', '12345'),
(4, 'Ana', 'Rodríguez', '444987123', 'ana.rodriguez@example.com', 'Calle Sol 789', '12345'),
(5, 'Luis', 'Fernández', '333678901', 'luis.fernandez@example.com', 'Calle Estrella 101', '12345'),
(6, 'yo', 'el', '1144117752', 'ismael@hotmail.com', 'luis oasterur 2417', '34364892');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_con_usuario`
--

CREATE TABLE `cliente_con_usuario` (
  `id_clientes` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente_con_usuario`
--

INSERT INTO `cliente_con_usuario` (`id_clientes`, `id_usuario`) VALUES
(1, 8),
(2, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante_proveedores`
--

CREATE TABLE `comprobante_proveedores` (
  `id_comprobante_proveedores` int(11) NOT NULL,
  `fecha_de_compra` varchar(45) DEFAULT NULL,
  `cantidad_comprada` varchar(45) DEFAULT NULL,
  `num_de_comprobante` varchar(45) DEFAULT NULL,
  `id_accesorios_y_componentes` int(11) NOT NULL,
  `id_proveedores` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comprobante_proveedores`
--

INSERT INTO `comprobante_proveedores` (`id_comprobante_proveedores`, `fecha_de_compra`, `cantidad_comprada`, `num_de_comprobante`, `id_accesorios_y_componentes`, `id_proveedores`) VALUES
(1, '2024-09-01', '10', 'COMP001', 1, 1),
(2, '2024-09-05', '5', 'COMP002', 2, 1),
(3, '2024-09-10', '8', 'COMP003', 3, 2),
(4, '2024-09-12', '15', 'COMP004', 1, 3),
(5, '2024-09-15', '20', 'COMP005', 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id_detalle_factura` int(11) NOT NULL,
  `cantidad_venta` int(11) NOT NULL,
  `precio_unitario_V` varchar(45) NOT NULL,
  `id_accesorios_y_componentes` int(11) NOT NULL,
  `id_cabecera_factura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`id_detalle_factura`, `cantidad_venta`, `precio_unitario_V`, `id_accesorios_y_componentes`, `id_cabecera_factura`) VALUES
(1, 10, '150.00', 1, 101),
(2, 5, '200.50', 2, 101),
(3, 2, '300.00', 3, 102),
(4, 8, '50.75', 1, 103),
(5, 12, '75.25', 4, 104);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_reparaciones`
--

CREATE TABLE `detalle_reparaciones` (
  `id_detalle_reparaciones` int(11) NOT NULL,
  `fecha_finalizada` datetime NOT NULL,
  `descripcion` varchar(205) NOT NULL,
  `id_pedidos_de_reparacion` int(11) NOT NULL,
  `id_servicios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_reparaciones`
--

INSERT INTO `detalle_reparaciones` (`id_detalle_reparaciones`, `fecha_finalizada`, `descripcion`, `id_pedidos_de_reparacion`, `id_servicios`) VALUES
(1, '2024-09-10 00:00:00', 'Reemplazo de pantalla realizado', 1, 1),
(2, '2024-09-11 00:00:00', 'Cambio de batería completado', 2, 2),
(3, '2024-09-12 00:00:00', 'Actualización de software', 3, 3),
(4, '2024-09-13 00:00:00', 'Reparación de puerto USB', 4, 5),
(5, '2024-09-14 00:00:00', 'Limpieza interna realizada', 5, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_servicios`
--

CREATE TABLE `detalle_servicios` (
  `id_detalle_servicios` int(11) NOT NULL,
  `detalle_servicios` varchar(45) DEFAULT NULL,
  `id_servicios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_servicios`
--

INSERT INTO `detalle_servicios` (`id_detalle_servicios`, `detalle_servicios`, `id_servicios`) VALUES
(1, 'Cambio de pantalla frontal.', 1),
(2, 'Cambio de batería interna.', 2),
(3, 'Actualización de firmware.', 3),
(4, 'Limpieza de componentes internos.', 4),
(5, 'Reparación de cámara trasera.', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

CREATE TABLE `dispositivos` (
  `id_dispositivos` int(11) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `numero_de_serie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dispositivos`
--

INSERT INTO `dispositivos` (`id_dispositivos`, `marca`, `modelo`, `numero_de_serie`) VALUES
(1, 'Samsung', 'Galaxy S21', 'SN1234567890'),
(2, 'Apple', 'iPhone 12', 'SN0987654321'),
(3, 'Sony', 'Xperia 10', 'SN1122334455'),
(4, 'Huawei', 'P40 Pro', 'SN2233445566'),
(5, 'Xiaomi', 'Redmi Note 10', 'SN3344556677'),
(6, 'nokia', '1100', 'w5fefefs');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_cambios_contrasena`
--

CREATE TABLE `historial_cambios_contrasena` (
  `id_cambio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_cambio` date NOT NULL,
  `motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_cambios_contrasena`
--

INSERT INTO `historial_cambios_contrasena` (`id_cambio`, `id_usuario`, `fecha_cambio`, `motivo`) VALUES
(1, 1, '2024-09-01', 'Cambio de contraseña por razones de seguridad'),
(2, 2, '2024-09-02', 'Cambio de contraseña solicitado por el usuario'),
(3, 3, '2024-09-03', 'Cambio de contraseña tras un intento de acceso no autorizado'),
(4, 4, '2024-09-04', 'Cambio de contraseña periódica'),
(5, 5, '2024-09-05', 'Cambio de contraseña para actualización de seguridad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificaciones` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_de_envío` datetime DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `numero_orden` varchar(255) DEFAULT NULL,
  `id_pedidos_de_reparacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id_notificaciones`, `mensaje`, `fecha_de_envío`, `estado`, `numero_orden`, `id_pedidos_de_reparacion`) VALUES
(1, 'El pedido ha sido recibido.', '2024-09-01 09:00:00', 'Enviado', 'ORD001', 1),
(2, 'El pedido está en reparación.', '2024-09-02 10:00:00', 'En proceso', 'ORD002', 2),
(3, 'El pedido ha sido completado.', '2024-09-03 11:00:00', 'Completado', 'ORD003', 3),
(4, 'El pedido ha sido entregado.', '2024-09-04 12:00:00', 'Entregado', 'ORD004', 4),
(5, 'El pedido ha sido cancelado.', '2024-09-05 13:00:00', 'Cancelado', 'ORD005', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operacion`
--

CREATE TABLE `operacion` (
  `id_operacion` int(11) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `operacion`
--

INSERT INTO `operacion` (`id_operacion`, `tipo`) VALUES
(1, 'Venta'),
(2, 'Reparación'),
(3, 'Venta y Reparación'),
(4, 'Compra');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_de_reparacion`
--

CREATE TABLE `pedidos_de_reparacion` (
  `id_pedidos_de_reparacion` int(11) NOT NULL,
  `fecha_de_pedido` date NOT NULL,
  `estado_reparacion` varchar(50) NOT NULL,
  `numero_orden` varchar(7) NOT NULL,
  `observacion` varchar(45) NOT NULL,
  `id_dispositivos` int(11) NOT NULL,
  `id_tecnicos` int(11) NOT NULL,
  `id_clientes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_de_reparacion`
--

INSERT INTO `pedidos_de_reparacion` (`id_pedidos_de_reparacion`, `fecha_de_pedido`, `estado_reparacion`, `numero_orden`, `observacion`, `id_dispositivos`, `id_tecnicos`, `id_clientes`) VALUES
(2, '2024-09-02', 'Completado', 'ORD0002', 'Cambio de batería', 2, 2, 2),
(3, '2024-09-03', 'Pendiente', 'ORD0003', 'Problema con el software', 3, 3, 3),
(4, '2024-09-04', 'En proceso', 'ORD0004', 'Reparación de puerto USB', 4, 4, 4),
(5, '2024-09-05', 'Completado', 'ORD0005', 'Limpieza interna', 5, 5, 5),
(6, '2024-09-20', 'Pendiente', 'ORD0006', 'VIERNESSSSS', 0, 5, 0),
(7, '2024-09-20', 'Pendiente', 'ORD0007', 'VIERNESSSSS', 0, 5, 0),
(8, '2024-09-01', 'En proceso', 'ORD0008', 'Reemplazo de pantalla', 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permisos` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permisos`, `descripcion`) VALUES
(1, 'accesorios_y_componentes'),
(2, 'area_tecnico'),
(3, 'cabecera_factura'),
(4, 'cliente_con_usuario'),
(5, 'clientes'),
(6, 'comprobante_proveedores'),
(7, 'detalle_factura'),
(8, 'detalle_reparaciones'),
(9, 'detalle_servicios'),
(10, 'dispositivos'),
(11, 'historial_cambios_contrasena'),
(12, 'notificaciones'),
(13, 'operacion'),
(14, 'pedidos_de_reparacion'),
(15, 'permisos'),
(16, 'permisos_en_roles'),
(17, 'proveedores'),
(18, 'roles'),
(19, 'servicios'),
(20, 'tecnicos'),
(21, 'tipo_comprobante'),
(22, 'tipo_de_pago'),
(23, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_en_roles`
--

CREATE TABLE `permisos_en_roles` (
  `id_roles` int(11) NOT NULL,
  `id_permisos` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos_en_roles`
--

INSERT INTO `permisos_en_roles` (`id_roles`, `id_permisos`, `estado`) VALUES
(1, 1, 0),
(1, 2, 0),
(1, 3, 0),
(1, 4, 0),
(1, 5, 0),
(1, 6, 0),
(1, 7, 1),
(1, 8, 0),
(1, 9, 0),
(1, 10, 0),
(1, 11, 0),
(1, 12, 0),
(1, 13, 0),
(1, 14, 1),
(1, 15, 0),
(1, 16, 0),
(1, 17, 0),
(1, 18, 0),
(1, 19, 0),
(1, 20, 0),
(1, 21, 0),
(1, 22, 0),
(1, 23, 0),
(2, 1, 0),
(2, 2, 0),
(2, 3, 1),
(2, 4, 0),
(2, 5, 1),
(2, 6, 1),
(2, 7, 1),
(2, 8, 1),
(2, 9, 1),
(2, 10, 1),
(2, 11, 1),
(2, 12, 1),
(2, 13, 1),
(2, 14, 1),
(2, 15, 0),
(2, 16, 0),
(2, 17, 1),
(2, 18, 0),
(2, 19, 0),
(2, 20, 1),
(2, 21, 1),
(2, 22, 1),
(2, 23, 1),
(3, 1, 0),
(3, 2, 1),
(3, 3, 1),
(3, 4, 1),
(3, 5, 1),
(3, 6, 1),
(3, 7, 1),
(3, 8, 1),
(3, 9, 1),
(3, 10, 1),
(3, 11, 1),
(3, 12, 1),
(3, 13, 1),
(3, 14, 1),
(3, 15, 1),
(3, 16, 1),
(3, 17, 1),
(3, 18, 1),
(3, 19, 1),
(3, 20, 1),
(3, 21, 1),
(3, 22, 1),
(3, 23, 1),
(4, 1, 0),
(4, 2, 0),
(4, 3, 0),
(4, 4, 0),
(4, 5, 0),
(4, 6, 0),
(4, 7, 0),
(4, 8, 0),
(4, 9, 0),
(4, 10, 0),
(4, 11, 0),
(4, 12, 0),
(4, 13, 0),
(4, 14, 0),
(4, 15, 0),
(4, 16, 0),
(4, 17, 0),
(4, 18, 0),
(4, 19, 0),
(4, 20, 0),
(4, 21, 0),
(4, 22, 0),
(4, 23, 0),
(5, 1, 1),
(5, 2, 1),
(5, 3, 1),
(5, 4, 1),
(5, 5, 1),
(5, 6, 1),
(5, 7, 1),
(5, 8, 1),
(5, 9, 1),
(5, 10, 1),
(5, 11, 1),
(5, 12, 1),
(5, 13, 1),
(5, 14, 1),
(5, 15, 1),
(5, 16, 1),
(5, 17, 1),
(5, 18, 1),
(5, 19, 1),
(5, 20, 1),
(5, 21, 1),
(5, 22, 1),
(5, 23, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedores` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contacto` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedores`, `nombre`, `contacto`, `telefono`, `correo_electronico`, `direccion`) VALUES
(1, 'Proveedor A', 'Carlos Pérez', '123456789', 'c.perez@proveedora.com', 'Av. Principal 123, Ciudad'),
(2, 'Proveedor B', 'Ana García', '987654321', 'a.garcia@proveedorde.com', 'Calle Secundaria 456, Ciudad'),
(3, 'Proveedor C', 'Luis Martínez', '555555555', 'l.martinez@proveedorc.com', 'Boulevard Norte 789, Ciudad'),
(4, 'Proveedor D', 'Marta López', '444444444', 'm.lopez@proveedord.com', 'Plaza Central 101, Ciudad'),
(5, 'Proveedor E', 'Jorge Rodríguez', '333333333', 'j.rodriguez@proveedore.com', 'Calle de la Industria 202, Ciudad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_roles` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_roles`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Administrativo'),
(3, 'Tecnico'),
(4, 'Cliente'),
(5, 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicios` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `precio_servicio` decimal(10,0) NOT NULL,
  `tipo_servicio` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicios`, `descripcion`, `precio_servicio`, `tipo_servicio`) VALUES
(1, 'Cambio de pantalla', 120, 'Reparación'),
(2, 'Cambio de batería', 80, 'Reparación'),
(3, 'Actualización de software', 50, 'Mantenimiento'),
(4, 'Limpieza interna', 70, 'Mantenimiento'),
(5, 'Reparación de cámara', 100, 'Reparación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id_tecnicos` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id_tecnicos`, `id_usuario`) VALUES
(1, 3),
(2, 4),
(3, 7),
(4, 8),
(5, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_comprobante`
--

CREATE TABLE `tipo_comprobante` (
  `id_tipo_comprobante` int(11) NOT NULL,
  `tipo_comprobante` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_comprobante`
--

INSERT INTO `tipo_comprobante` (`id_tipo_comprobante`, `tipo_comprobante`) VALUES
(1, 'Factura'),
(2, 'Boleta'),
(3, 'Ticket'),
(4, 'Nota de Crédito'),
(5, 'Guía de Remisión');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_de_pago`
--

CREATE TABLE `tipo_de_pago` (
  `id_tipo_de_pago` int(11) NOT NULL,
  `descripcion_de_pago` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_de_pago`
--

INSERT INTO `tipo_de_pago` (`id_tipo_de_pago`, `descripcion_de_pago`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta de Crédito'),
(3, 'Tarjeta de Débito'),
(4, 'Transferencia Bancaria'),
(5, 'Pago Móvil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `correo_electronico` varchar(255) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `id_roles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `contraseña`, `correo_electronico`, `dni`, `id_roles`) VALUES
(1, 'a', '$2y$10$pOlP.sEToQyOPUIl83FrAeLzHlomNRkrsaNIbZcgwMXCMjcDAvJnS', 'a@gmail.com', '11111111', 1),
(2, 'b', '$2y$10$S3vQ036MyL9bbslvR8d7YeaE3pkcp/EuxbKb8RWFOR8tyCXb91T1C', 'b@gmail.com', '22222222', 2),
(3, 'juan', '$2y$10$AIZqYj6SRX7dj3/O.Eo9hOySH2VX0uPgElbQ8LuWLRwGgYV2ASAC.', 'juan@gmail.com', '33333333', 3),
(4, 'pedro', '$2y$10$DPBcmALk2TgqZ3tKS5M5U.zZF8qx4jwU1WHL..Tpuh.zK2/34Qpau', 'pedro@gmail.com', '4444444', 3),
(5, 'maria', '$2y$10$K0Vm8EP75yL8tnZp/bowe.aPCGDE23qZoBBa3UJ5w/cQ6Z7ugSLdO', 'maria@gmail.com', '55555555', 4),
(6, 'rodrigo', '$2y$10$RgbbtoFohktORPDTWL8n5.o3hbma.LjJ3nmgyad5VPN2FVPEj6ob6', 'rodrigo@gmail.com', '6666666', 5),
(7, 'diego', '$2y$10$7HmIzi.lf9UY/B7C8PtqpubwuPHQXMdglX.p3rdXneI6NloyMo4kW', 'diego@gmail.com', '1212321', 3),
(8, 'victor', '$2y$10$/YUCUt5WO/TTU6KqDGi.t.c1lAvMNZjHL4hkVEd03gCGn0ln3L.i.', 'victor@gmail.com', '45623789', 3),
(9, 'carla', '$2y$10$U3fE1s3E8g3/MbQAoe8UV.tCAMCK.ij5P0eJMn8LPMHrCOdtVwIN2', 'carla@gmail.com', '63123587', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesorios_y_componentes`
--
ALTER TABLE `accesorios_y_componentes`
  ADD PRIMARY KEY (`id_accesorios_y_componentes`);

--
-- Indices de la tabla `area_tecnico`
--
ALTER TABLE `area_tecnico`
  ADD PRIMARY KEY (`id_area_tecnico`);

--
-- Indices de la tabla `cabecera_factura`
--
ALTER TABLE `cabecera_factura`
  ADD PRIMARY KEY (`id_cabecera_factura`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_clientes`);

--
-- Indices de la tabla `cliente_con_usuario`
--
ALTER TABLE `cliente_con_usuario`
  ADD PRIMARY KEY (`id_clientes`,`id_usuario`);

--
-- Indices de la tabla `comprobante_proveedores`
--
ALTER TABLE `comprobante_proveedores`
  ADD PRIMARY KEY (`id_comprobante_proveedores`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id_detalle_factura`);

--
-- Indices de la tabla `detalle_reparaciones`
--
ALTER TABLE `detalle_reparaciones`
  ADD PRIMARY KEY (`id_detalle_reparaciones`);

--
-- Indices de la tabla `detalle_servicios`
--
ALTER TABLE `detalle_servicios`
  ADD PRIMARY KEY (`id_detalle_servicios`);

--
-- Indices de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`id_dispositivos`);

--
-- Indices de la tabla `historial_cambios_contrasena`
--
ALTER TABLE `historial_cambios_contrasena`
  ADD PRIMARY KEY (`id_cambio`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificaciones`);

--
-- Indices de la tabla `operacion`
--
ALTER TABLE `operacion`
  ADD PRIMARY KEY (`id_operacion`);

--
-- Indices de la tabla `pedidos_de_reparacion`
--
ALTER TABLE `pedidos_de_reparacion`
  ADD PRIMARY KEY (`id_pedidos_de_reparacion`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permisos`);

--
-- Indices de la tabla `permisos_en_roles`
--
ALTER TABLE `permisos_en_roles`
  ADD PRIMARY KEY (`id_roles`,`id_permisos`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedores`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_roles`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicios`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id_tecnicos`);

--
-- Indices de la tabla `tipo_comprobante`
--
ALTER TABLE `tipo_comprobante`
  ADD PRIMARY KEY (`id_tipo_comprobante`);

--
-- Indices de la tabla `tipo_de_pago`
--
ALTER TABLE `tipo_de_pago`
  ADD PRIMARY KEY (`id_tipo_de_pago`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesorios_y_componentes`
--
ALTER TABLE `accesorios_y_componentes`
  MODIFY `id_accesorios_y_componentes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cabecera_factura`
--
ALTER TABLE `cabecera_factura`
  MODIFY `id_cabecera_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_clientes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id_detalle_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_reparaciones`
--
ALTER TABLE `detalle_reparaciones`
  MODIFY `id_detalle_reparaciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `id_dispositivos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `historial_cambios_contrasena`
--
ALTER TABLE `historial_cambios_contrasena`
  MODIFY `id_cambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificaciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos_de_reparacion`
--
ALTER TABLE `pedidos_de_reparacion`
  MODIFY `id_pedidos_de_reparacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_roles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id_tecnicos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
