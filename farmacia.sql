-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2025 a las 21:46:20
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
-- Base de datos: `farmacia`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarCategoria` (IN `id` INT, IN `Nom` VARCHAR(100), IN `Des` TEXT)   BEGIN
    update categoria set Nombre = Nom, Descripcion = Des where idCategoria = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarDetalleVenta` (IN `id` INT, IN `Fec` TIMESTAMP, IN `Des` DECIMAL(10,2), IN `TiPago` VARCHAR(30), IN `Tot` DECIMAL(10,2), IN `fkEmp` INT)   BEGIN
	update detalleventa set Fecha = Fec, Descuentos = Des, TipoPago = TiPago, Total = Tot, fkEmpleado = fkEmp where idDetalle = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarEmpleado` (IN `id` INT, IN `Nom` VARCHAR(100), IN `Usu` TEXT, IN `Contra` TEXT, IN `Fot` MEDIUMBLOB, IN `Est` VARCHAR(30))   BEGIN
	update empleado set Nombre = Nom, usuario = Usu, Contrasenia = Contra, Foto = Fot, Estado = Est where idEmpleado = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarImporte` (IN `id` INT, IN `Ven` DATE, IN `fecCompra` DATE, IN `codProducto` INT, IN `Est` VARCHAR(30), IN `fkPro` INT, IN `fkProv` INT)   BEGIN
	update importe set Vencimiento = Ven, FechaCompra = fecCompra, CodigoProducto = codProducto, Estado = Est, fkProducto = fkPro, fkProveedor = fkProv where idImporte = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarProducto` (IN `id` INT, IN `Nom` VARCHAR(100), IN `Pre` DECIMAL(10,2), IN `Des` TEXT, IN `Fot` MEDIUMBLOB, IN `fkCat` INT)   BEGIN
    update producto set Nombre = Nom, Precio = Pre, Descripcion = Des, Foto = Fot, fkCategoria = fkCat where idProducto = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarProveedor` (IN `id` INT, IN `Nom` VARCHAR(100), IN `Tel` VARCHAR(12), IN `Dir` TEXT)   BEGIN
	update proveedor set Nombre = Nom, Telefono = Tel, Direccion = Dir where idProveedor = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarRelacionVentaProducto` (IN `id` INT, IN `kfDetalle` INT, IN `fkPro` INT)   BEGIN
	update producto_muestra_detalleventa set fkDetalleVenta = kfDetalle, fkProducto = fkPro where idDetalleASProducto = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarCategoria` (IN `palabra` TEXT, IN `desde` INT, IN `hasta` INT)   BEGIN
	if desde < 0 and hasta < 0 then
    	set desde = (select min(idCategoria) from categoria);
        set hasta = (select max(idCategoria) from categoria);
    elseif desde < 0 then
    	set desde = (select min(idCategoria) from categoria);
    elseif hasta < 0 then
    	set hasta = (select max(idCategoria) from categoria);
    end if;
    select * from categoria where (upper(Nombre) like concat('%',upper(palabra),'%') or upper(Descripcion) like concat('%',upper(palabra),'%')) and idCategoria > (desde -1) limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarCategoriaPorId` (IN `id` INT)   BEGIN
	select * from categoria where idCategoria = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarEmpleadoPorId` (IN `id` INT)   BEGIN
	select * from empleado where idEmpleado = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarEmpleadoPorUsuario` (IN `us` TEXT)   BEGIN
	select * from empleado where Usuario = us;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarProducto` (IN `palabra` TEXT, IN `desde` INT, IN `hasta` INT)   BEGIN
    if desde < 0 and hasta < 0 then
        set desde = (select min(idProducto) from producto);
        set hasta = (select max(idProducto) from producto);
    elseif desde < 0 then
        set desde = (select min(idProducto) from producto);
    elseif hasta < 0 then
        set hasta = (select max(idProducto) from producto);
    end if;
    
    select * from producto where (upper(Nombre) like concat('%',upper(palabra),'%') or upper(Descripcion) like concat('%', upper(palabra),'%')) and idProducto > (desde -1) limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarProductoPorId` (IN `id` INT)   BEGIN
	select * from producto where idProducto = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarProveedor` (IN `palabra` TEXT, IN `desde` INT, IN `hasta` INT)   BEGIN
	if desde < 0 and hasta < 0 then
    	set desde = (select min(idProveedor) from proveedor);
        set hasta = (select max(idProveedor) from proveedor);
    elseif desde < 0 then
    	set desde = (select min(idProveedor) from proveedor);
    elseif hasta < 0 then
    	set hasta = (select max(idProveedor) from proveedor);
    end if;
    
    select * from proveedor where (upper(Nombre) like concat('%', upper(palabra),'%') or upper(Telefono) like concat('%', upper(palabra),'%') or upper(Direccion) like concat('%', upper(palabra),'%')) and idProveedor >= desde limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `detalleVentaPorEmpleado` (IN `id` INT, IN `fechaInicio` DATE, IN `fechaFin` DATE, IN `desde` INT, IN `hasta` INT)   BEGIN
	if fechaInicio is null and fechaFin is null then
    	set fechaInicio = (select min(Fecha) from detalleventa);
        set fechaFin = (select max(Fecha) from detalleventa);
    elseif fechaInicio is null then
    	set fechaInicio = (select min(Fecha) from detalleventa);
    elseif fechaFin is null then
    	set fechaFin = (select max(Fecha) from detalleventa);
    end if;
    
    if desde < 0 and hasta < 0 then
    	set desde = (select min(idDetalle) from detalleventa);
        set hasta = (select max(idDetalle) from detalleventa);
    elseif desde < 0 then
    	set desde = (select min(idDetalle) from detalleventa);
    elseif hasta < 0 then
    	set hasta = (select max(idDetalle) from detalleventa);
    end if;
    
    select dv.* from detalleventa dv join empleado e on e.idEmpleado = dv.fkEmpleado where e.idEmpleado = id and dv.idDetalle >= desde limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `detalleVentaPorProducto` (IN `id` INT, IN `fechaInicio` DATE, `fechaFin` DATE, IN `desde` INT, IN `hasta` INT)   BEGIN
    if fechaInicio is null and fechaFin is null then
        set fechaInicio = (select min(Fecha) from detalleventa);
        set fechaFin = (select max(Fecha) from detalleventa);
    elseif fechaInicio is null then
        set fechaInicio = (select min(Fecha) from detalleventa);
    elseif fechaFin is null then
        set fechaFin = (select max(Fecha) from detalleventa);
    end if;
    
    if desde < 0 and hasta < 0 then
        set desde = (select min(idDetalle) from detalleventa);
        set hasta = (select max(idDetalle) from detalleventa);
    elseif desde < 0 then
        set desde = (select min(idDetalle) from detalleventa);
    elseif hasta < 0 then
        set hasta = (select max(idDetalle) from detalleventa);
    end if;
    
    select dv.* from detalleventa dv join producto_muestra_detalleventa rvp on rvp.fkDetalleVenta = dv.idDetalle join producto p on p.idProducto = rvp.fkProducto where p.idProducto = id and p.idProducto >= desde limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarCategoria` (IN `id` INT)   BEGIN
	delete from categoria where idCategoria = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarDetalleVenta` (IN `id` INT)   BEGIN
	delete from detalleventa where idDetalle = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarEmpleado` (IN `id` INT)   BEGIN
	delete from empleado where idEmpleado = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarImporte` (IN `id` INT)   BEGIN
	delete from importe where idImporte = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarProducto` (IN `id` INT)   BEGIN
	delete from producto where idProducto = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarProveedor` (IN `id` INT)   BEGIN
	delete from proveedor where idProveedor = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarRelacionVentaProducto` (IN `id` INT)   BEGIN
	delete from producto_muestra_detalleventa where idDetalleASProducto = id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `importePorProducto` (IN `id` INT, IN `fechaInicio` DATE, IN `fechaFin` DATE, IN `Est` VARCHAR(30), IN `desde` INT, IN `hasta` INT)   BEGIN
    if desde < 0 and hasta < 0 then
        set desde = (select min(idImporte) from importe);
        set hasta = (select max(idImporte) from importe);
    elseif desde < 0 then
        set desde = (select min(idImporte) from importe);
    elseif hasta < 0 then
        set hasta = (select max(idImporte) from importe);
    end if;
    
    if (fechaInicio is null) and (fechaFin is null) then
        set fechaInicio = (select min(FechaCompra) from importe);
        set fechaFin = (select max(FechaCompra) from importe);
    elseif fechaInicio is null then
        set fechaInicio = (select min(FechaCompra) from importe);
    elseif fechaFin is null then
        set fechaFin = (select max(FechaCompra) from importe);
    end if;
    
    select * from importe where Estado = Est and idImporte > (desde -1) and fkProducto = id and FechaCompra >= fechaInicio and FechaCompra <= fechaFin limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `importePorProveedor` (IN `id` INT, IN `fechaInicio` DATE, IN `fechaFin` DATE, IN `desde` INT, IN `hasta` INT)   BEGIN
	if desde < 0 and hasta < 0 then
    	set desde = (select min(idImporte) from importe);
        set hasta = (select max(idImporte) from importe);
    elseif desde < 0 then
    	set desde = (select min(idImporte) from importe);
    elseif hasta < 0 then
    	set hasta = (select max(idImporte) from importe);
    end if;
    
    if fechaInicio is null and fechaFin is null then
    	set fechaInicio = (select min(FechaCompra) from importe);
        set fechaFin = (select max(FechaCompra) from importe);
    elseif fechaInicio is null then
    	set fechaInicio = (select min(FechaCompra) from importe);
    elseif fechaFin is null then
    	set fechaFin = (select max(FechaCompra) from importe);
    end if;
    
    select * from importe where fkProveedor = id and idImporte >= desde and FechaCompra >= fechaInicio and FechaCompra <= fechaFin limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarCategoria` (IN `Nombre` VARCHAR(100), IN `Descripcion` TEXT)   BEGIN
	insert into categoria(Nombre, Descripcion) value (Nombre, Descripcion);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarDetalleVenta` (IN `Fec` TIMESTAMP, IN `Des` DECIMAL(10,2), IN `tiPago` VARCHAR(30), IN `tot` DECIMAL(10,2), IN `fkEm` INT)   BEGIN
	insert detalleventa(Fecha, Descuentos, TipoPago, Total, fkEmpleado) VALUES (Fec, Des, tiPago, tot, fkEm);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarEmpleado` (IN `Nom` VARCHAR(100), IN `User` TEXT, IN `Contra` TEXT, IN `Fot` MEDIUMBLOB, IN `Est` VARCHAR(10))   BEGIN
	insert into empleado(Nombre, Usuario, contrasenia, Foto, Estado) value (Nom, User, Contra, Fot, Est);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarImporte` (IN `Venci` DATE, IN `FechCompra` DATE, IN `codProducto` INT, IN `est` VARCHAR(30), IN `fkPro` INT, IN `fkProv` INT)   BEGIN
insert into importe(vencimiento, FechaCompra, CodigoProducto, Estado, fkProducto, fkProveedor) value (Venci, FechCompra, codProducto, est, fkPro, fkProv);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarProducto` (IN `Nom` VARCHAR(100), IN `Pre` DECIMAL(10,2), IN `Des` TEXT, IN `Fot` MEDIUMBLOB, IN `fkCat` INT)   BEGIN
	insert into producto(Nombre, Precio, Descripcion, Foto, fkCategoria) value (Nom, Pre, Des, Fot, fkCat);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarProveedor` (IN `Nom` VARCHAR(100), IN `Tel` VARCHAR(12), IN `Dir` TEXT)   BEGIN
	insert into proveedor(Nombre, Telefono, Direccion) value (Nom, Tel, Dir);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarRelacionVentaProducto` (IN `fkDet` INT, IN `fkPro` INT)   BEGIN
	insert into producto_muestra_detalleventa(fkDetalleVenta, fkProducto) VALUES (fkDet, fkPro);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerCategorias` (IN `desde` INT, IN `hasta` INT)   BEGIN
    declare inicio int;
    declare fin int;
    set inicio = desde;
    set fin = hasta;
    
    if desde < 0 and hasta < 0 then
        set inicio = (select min(idCategoria) from categoria);
        set fin = (select max(idCategoria) from categoria);
    elseif desde < 0 then
        set inicio = (select min(idCategoria) from categoria);
    elseif hasta < 0 then
        set fin = (select max(idCategoria) from categoria);
    end if;
    
    select * from categoria where idCategoria > (inicio-1) limit fin;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerEmpleados` (IN `Est` VARCHAR(30), IN `desde` INT, IN `hasta` INT)   BEGIN
	if desde < 0 and hasta < 0 then
    	set desde = (select min(idEmpleado) from empleado);
        set hasta = (select max(idEmpleado) from empleado);
    elseif desde < 0 then
    	set desde = (select min(idEmpleado) from empleado);
    elseif hasta < 0 then
    	set hasta = (select max(idEmpleado) from empleado);
    end if;
    
    select * from empleado where idEmpleado >= desde and Estado = Est limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerProductos` (IN `desde` INT, IN `hasta` INT)   BEGIN
    if desde < 0 and hasta < 0 then
    	set desde =(select min(idProducto) from producto);
        set hasta = (select max(idProducto) from producto);
    elseif desde < 0 then
    	set desde =(select min(idProducto) from producto);
    elseif hasta < 0 then
    	set hasta = (select max(idProducto) from producto);
    end if;
    
    select * from producto where idProducto > (desde -1) limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerProveedores` (IN `desde` INT, IN `hasta` INT)   BEGIN
	if desde < 0 and hasta < 0 then
    	set desde = (select min(idProveedor) from proveedor);
        set hasta = (select max(idProveedor) from proveedor);
    elseif desde < 0 then
    	set desde = (select min(idProveedor) from proveedor);
    elseif hasta < 0 then
    	set hasta = (select max(idProveedor) from proveedor);
    end if;
    select * from proveedor where idProveedor >= desde limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `productoAndImportePorProveedor` (IN `idProd` INT, IN `idProv` INT, IN `fechaInicio` DATE, IN `fechaFin` DATE, IN `desde` INT, IN `hasta` INT)   BEGIN
	if desde < 0 and hasta < 0 then
    	set desde = (select min(idImporte) from importe);
        set hasta = (select max(idImporte) from importe);
    elseif desde < 0 then
    	set desde = (select min(idImporte) from importe);
    elseif hasta < 0 then
    	set hasta = (select max(idImporte) from importe);
    end if;
    
    if fechaInicio is null and fechaFin is null then
    	set fechaInicio = (select min(FechaCompra) from importe);
        set fechaFin = (select max(FechaCompra) from importe);
    elseif fechaInicio is null then
    	set fechaInicio = (select min(FechaCompra) from importe);
    elseif fechaFin is null then
    	set fechaFin = (select max(FechaCompra) from importe);
    end if;
    
    select inp.* from importe inp join producto p on p.idProducto = inp.fkProducto join proveedor por on por.idProveedor = inp.fkProveedor where p.idProducto = idProd and por.idProveedor = idProv and inp.FechaCompra >= fechaInicio and inp.FechaCompra <= fechaFin and inp.idImporte >= desde limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `productoPorDetalleVenta` (IN `id` INT, IN `desde` INT, IN `hasta` INT)   BEGIN
	if desde < 0 and hasta < 0 then
    	set desde = (select min(idProducto) from producto);
        set hasta = (select max(idProducto) from producto);
    elseif desde < 0 then
    	set desde = (select min(idProducto) from producto);
    elseif hasta < 0 then
    	set hasta = (select max(idProducto) from producto);
    end if;
    
    select p.* from producto p join producto_muestra_detalleventa rdv on rdv.fkProducto = p.idProducto join detalleventa dv on dv.idDetalle = rdv.fkDetalleVenta where dv.idDetalle = id and p.idProducto >= desde limit hasta;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `productosPorCategoria` (IN `id` INT, IN `desde` INT, IN `hasta` INT)   BEGIN
    if desde < 0 and hasta < 0 then
        set desde = (select min(idProducto) from producto);
        set hasta = (select max(idProducto) from producto);
    elseif desde < 0 then
        set desde = (select min(idProducto) from producto);
    elseif hasta < 0 then
        set hasta = (select max(idProducto) from producto);
    end if;
    
    select p.* from producto p join categoria c on c.idCategoria = p.fkCategoria where p.idProducto >=desde and c.idCategoria = id limit hasta; 
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proveedorPorId` (IN `id` INT)   BEGIN
	select * from proveedor where idProveedor = id;
end$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fin` () RETURNS INT(11)  BEGIN
	return -2;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `inicio` () RETURNS INT(11)  BEGIN
	return -1;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `Nombre`, `Descripcion`) VALUES
(1, 'Respiratorio', 'Medicamento que alluda a tratar problemas de respiracion.');

--
-- Disparadores `categoria`
--
DELIMITER $$
CREATE TRIGGER `categoriaInsert` BEFORE INSERT ON `categoria` FOR EACH ROW BEGIN
    if (select count(idCategoria) as coincidencias from categoria where upper(Nombre) like upper(new.Nombre)) > 0 then
        SIGNAL SQLSTATE '23000'
        set MESSAGE_TEXT = 'La categoria en realidad existe.';
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categoriaUpdate` BEFORE UPDATE ON `categoria` FOR EACH ROW BEGIN
	if new.Descripcion is null THEN
    	set new.Descripcion = old.Descripcion;
    end if;
    if new.Nombre is null THEN
    	set new.Nombre = old.Nombre;
    ELSE
    	if (select count(idCategoria) as coincidencias from categoria where upper(Nombre) like upper(new.Nombre)) > 0 then
        	SIGNAL SQLSTATE '23000'
        	set MESSAGE_TEXT = 'La categoria en realidad existe.';
    	end if;
    end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `idDetalle` int(11) NOT NULL,
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Descuentos` decimal(10,2) DEFAULT NULL,
  `TipoPago` varchar(30) NOT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `fkEmpleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalleventa`
--

INSERT INTO `detalleventa` (`idDetalle`, `Fecha`, `Descuentos`, `TipoPago`, `Total`, `fkEmpleado`) VALUES
(1, '2025-11-20 06:32:36', 1.25, 'Efectivo', 3.00, 3),
(3, '2025-11-20 02:14:13', 0.00, 'Efectivo', 2.75, 1),
(5, '2025-11-20 04:50:15', 0.00, 'Tarjeta', 6.36, 3);

--
-- Disparadores `detalleventa`
--
DELIMITER $$
CREATE TRIGGER `detalleVentaUpdate` BEFORE UPDATE ON `detalleventa` FOR EACH ROW BEGIN
	if new.Fecha is null then
    	set new.Fecha = old.Fecha;
    end if;
    if new.Descuentos is null then
    	set new.Descuentos = old.Descuentos;
    end if;
    if new.TipoPago is null then
    	set new.TipoPago = old.TipoPago;
    end if;
    if new.Total is null then
    	set new.Total = old.Total;
    end if;
    if new.fkEmpleado is null then
    	set new.fkEmpleado = old.fkEmpleado;
    end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `idEmpleado` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Usuario` text NOT NULL,
  `contrasenia` text NOT NULL,
  `Foto` mediumblob DEFAULT NULL,
  `Estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`idEmpleado`, `Nombre`, `Usuario`, `contrasenia`, `Foto`, `Estado`) VALUES
(1, 'Jonathan', 'Jonathan@gmail.com', 'cola loca', NULL, 'Activo'),
(3, 'Migue', 'mig@lemus.com', 'calio', NULL, 'Activo');

--
-- Disparadores `empleado`
--
DELIMITER $$
CREATE TRIGGER `empleadoInsert` BEFORE INSERT ON `empleado` FOR EACH ROW BEGIN
    declare user int;
    set user =  (select count(idEmpleado) as coincidencias from empleado where upper(Usuario) like upper(new.Usuario));
    if user > 0 then
        SIGNAL SQLSTATE '23000'
        set MESSAGE_TEXT = 'El usuario ya en realidad ya existe.';
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `empleadoUpdate` BEFORE UPDATE ON `empleado` FOR EACH ROW BEGIN
	declare user int;
    if new.Nombre is null THEN
    	set new.Nombre = old.Nombre;
    end if;
    if new.Contrasenia is null THEN
    	set new.Contrasenia = old.Contrasenia;
    end if;
    if new.Foto is null THEN
    	set new.Foto = old.Foto;
    end if;
    if new.Estado is null THEN
    	set new.Estado = old.Estado;
    end if;
    if new.Usuario is null THEN
    	set new.Usuario = old.Usuario;
    else
    	set user = (select count(idEmpleado) as coincidencias from empleado where upper(Usuario) like upper(new.Usuario));
    	if user > 0 then
    		SIGNAL SQLSTATE '23000'
        	SET MESSAGE_TEXT = 'El usuario en realidad ya existe.';
    	end if;
    end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `importe`
--

CREATE TABLE `importe` (
  `idImporte` int(11) NOT NULL,
  `Vencimiento` date NOT NULL,
  `FechaCompra` date NOT NULL,
  `CodigoProducto` int(11) NOT NULL,
  `Estado` varchar(30) NOT NULL,
  `fkProducto` int(11) NOT NULL,
  `fkProveedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `importe`
--

INSERT INTO `importe` (`idImporte`, `Vencimiento`, `FechaCompra`, `CodigoProducto`, `Estado`, `fkProducto`, `fkProveedor`) VALUES
(3, '2025-11-20', '2025-11-20', 4528796, 'Disponible', 2, 2);

--
-- Disparadores `importe`
--
DELIMITER $$
CREATE TRIGGER `importeInsert` BEFORE INSERT ON `importe` FOR EACH ROW BEGIN
    if new.Vencimiento < curdate() then
    	SIGNAL SQLSTATE '23000'
        set MESSAGE_TEXT = 'Un producto no puede estar previamente vencido.';
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `importeUpdate` BEFORE UPDATE ON `importe` FOR EACH ROW BEGIN
	if new.FechaCompra is null THEN
    	set new.FechaCompra = old.FechaCompra;
    end if;
    if new.CodigoProducto is null THEN
    	set new.CodigoProducto = old.CodigoProducto;
    end if;
    if new.Estado is null THEN
    	set new.Estado = old.Estado;
    end if;
    if new.fkProducto is null THEN
    	set new.fkProducto = old.fkProducto;
    end if;
    if new.fkProveedor is null THEN
    	set new.fkProveedor = old.fkProveedor;
    end if;
    
    if new.Vencimiento is null THEN
    	set new.Vencimiento = old.Vencimiento;
    ELSE
    	if new.Vencimiento < curdate() then
        	SIGNAL SQLSTATE '23000'
        	SET MESSAGE_TEXT = 'La fecha de vencimiento no puede ser inferior a la fecha actual.';
    	end if;
    end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idProducto` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Foto` mediumblob DEFAULT NULL,
  `fkCategoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `Nombre`, `Precio`, `Descripcion`, `Foto`, `fkCategoria`) VALUES
(1, 'MisoPrepileno', 4.25, NULL, NULL, 1),
(2, 'MisoSepileno', 2.25, NULL, NULL, 1);

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `productInsert` BEFORE INSERT ON `producto` FOR EACH ROW BEGIN
    declare nom int;
    set nom = (select count(idProducto) as coincidencias from producto where upper(Nombre) like upper(new.Nombre));
    IF new.Precio < 0 THEN
    SIGNAL SQLSTATE '23000'
    set MESSAGE_TEXT = 'Un producto no puede tener un precio menor a cero.';
    ELSEIF nom > 0 THEN
    SIGNAL SQLSTATE '23000'
    set MESSAGE_TEXT = 'El producto en realidad ya existe.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `productUpdate` BEFORE UPDATE ON `producto` FOR EACH ROW BEGIN
    declare nomE int;
    if new.Precio is null THEN
    	set new.Precio = old.Precio;
    end if;
    if new.Descripcion is null THEN
    	set new.Descripcion = old.Descripcion;
    end if;
    if new.Foto is null THEN
    	set new.Foto = old.Foto;
    end if;
    if new.fkCategoria is null THEN
    	set new.fkCategoria = old.fkCategoria;
    end if;
    
    if new.Nombre is null THEN
    	set new.Nombre = old.Nombre;
    ELSE
    	set nomE = (select count(idProducto) as coincidencias from producto where upper(Nombre) like upper(new.Nombre));
    	if nomE > 0 then
    		SIGNAL SQLSTATE '23000'
        	SET MESSAGE_TEXT = 'En realidad ya existe el nombre.';
    	elseif new.Precio < 0 then
    		SIGNAL SQLSTATE '23000'
        	set MESSAGE_TEXT = 'El preio no puede ser inferior a cero.';
    	end if;
    end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_muestra_detalleventa`
--

CREATE TABLE `producto_muestra_detalleventa` (
  `idDetalleASProducto` int(11) NOT NULL,
  `fkDetalleVenta` int(11) NOT NULL,
  `fkProducto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_muestra_detalleventa`
--

INSERT INTO `producto_muestra_detalleventa` (`idDetalleASProducto`, `fkDetalleVenta`, `fkProducto`) VALUES
(2, 3, 1);

--
-- Disparadores `producto_muestra_detalleventa`
--
DELIMITER $$
CREATE TRIGGER `relacionVentaProductoUpdate` BEFORE UPDATE ON `producto_muestra_detalleventa` FOR EACH ROW BEGIN
    if new.fkDetalleVenta is null then
        set new.fkDetalleVenta = old.fkDetalleVenta;
    end if;
    if new.fkProducto is null then
        set new.fkProducto = old.fkProducto;
    end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `idProveedor` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Telefono` varchar(12) NOT NULL,
  `Direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`idProveedor`, `Nombre`, `Telefono`, `Direccion`) VALUES
(2, 'salamandra', '45279365', NULL);

--
-- Disparadores `proveedor`
--
DELIMITER $$
CREATE TRIGGER `proveedorInsert` BEFORE INSERT ON `proveedor` FOR EACH ROW BEGIN
    declare telE int;
    set telE = (select count(idProveedor) as coincidencias from proveedor where upper(Telefono) like upper(new.Telefono));
    if telE > 0 then
        SIGNAL SQLSTATE '23000'
        set MESSAGE_TEXT = 'No pueden haber dos proveedores con el mismo telefono.';
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `proveedorUpdate` BEFORE UPDATE ON `proveedor` FOR EACH ROW BEGIN
    declare telE int;
    if new.Nombre is null THEN
    	set new.Nombre = old.Nombre;
    end if;
    if new.Direccion is null THEN
    	set new.Direccion = old.Direccion;
    end if;
    
    if new.Telefono is null THEN
    	set new.Telefono = old.Telefono;
    ELSE
    	set telE = (select count(idProveedor) as coincidencias from proveedor where upper(Telefono) like upper(new.Telefono));
    	if telE > 0 then
    		SIGNAL SQLSTATE '23000'
        	SET MESSAGE_TEXT = 'El telefono en realidad ya existe.';
    	end if;
    end if;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`idDetalle`),
  ADD KEY `empleado_genera_detalleventa` (`fkEmpleado`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`idEmpleado`);

--
-- Indices de la tabla `importe`
--
ALTER TABLE `importe`
  ADD PRIMARY KEY (`idImporte`),
  ADD KEY `producto_obtiene_importe` (`fkProducto`),
  ADD KEY `proveedor_ofrece_importe` (`fkProveedor`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idProducto`),
  ADD KEY `categoria_clasifica_producto` (`fkCategoria`);

--
-- Indices de la tabla `producto_muestra_detalleventa`
--
ALTER TABLE `producto_muestra_detalleventa`
  ADD PRIMARY KEY (`idDetalleASProducto`),
  ADD KEY `detalleventa_muestra_producto` (`fkDetalleVenta`),
  ADD KEY `producto_muestra_detalleventa` (`fkProducto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`idProveedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `idEmpleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `importe`
--
ALTER TABLE `importe`
  MODIFY `idImporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto_muestra_detalleventa`
--
ALTER TABLE `producto_muestra_detalleventa`
  MODIFY `idDetalleASProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `empleado_genera_detalleventa` FOREIGN KEY (`fkEmpleado`) REFERENCES `empleado` (`idEmpleado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `importe`
--
ALTER TABLE `importe`
  ADD CONSTRAINT `producto_obtiene_importe` FOREIGN KEY (`fkProducto`) REFERENCES `producto` (`idProducto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proveedor_ofrece_importe` FOREIGN KEY (`fkProveedor`) REFERENCES `proveedor` (`idProveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `categoria_clasifica_producto` FOREIGN KEY (`fkCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_muestra_detalleventa`
--
ALTER TABLE `producto_muestra_detalleventa`
  ADD CONSTRAINT `detalleventa_muestra_producto` FOREIGN KEY (`fkDetalleVenta`) REFERENCES `detalleventa` (`idDetalle`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_muestra_detalleventa` FOREIGN KEY (`fkProducto`) REFERENCES `producto` (`idProducto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
