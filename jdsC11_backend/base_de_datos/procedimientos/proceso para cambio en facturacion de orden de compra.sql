use jdpsoluc_jds_dm_4 ;
select * from ventas where orden > 365;
delete from ventacliente where idVenta = 'M4_366';
DELETE FROM `jdpsoluc_jds_dm_4`.`ventas` WHERE `orden`>='366';

call sp_facturar_orden_compra(1, '2' , 'N', 0 ,'CREDITO','0','12','30','','88155896-8');

select 
(select count(0) from sucursales where  CONCAT(`sucursales`.`nit_sucursal`,
                '_s',TRIM(`sucursales`.`id_suc`)) = nit) , vw_clientes.* 
from vw_clientes;

select *, concat(id_suc,'_',nit_sucursal) from sucursales;
 
call sp_asignar_consecutivo_factura(  '2' , (SELECT cod_modulos FROM mst_modulos 
where nom_modulo ='REMISIONES' group by 1), @s_id_venta , @_id_venta);

select  @s_id_venta , @_id_venta;
/*

CREATE TABLE `ingresos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codPov` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cantidadIngresada` decimal(16,2) NOT NULL,
  `valorParcial` decimal(16,2) NOT NULL,
  `descuento` decimal(16,2) NOT NULL,
  `t_iva` decimal(16,2) NOT NULL DEFAULT 0.00,
  `valorTotal` decimal(16,2) NOT NULL,
  `fecha` date NOT NULL,
  `usuario` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'activo',
  `idOrdenCompra` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ninguno',
  `nombre_sucursal_proviene` varchar(150) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `ingresos_lista_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ingreso` int(11) NOT NULL,
  `idProducto` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreProducto` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `presioCompra` decimal(16,2) NOT NULL,
  `cantidad` decimal(16,2) NOT NULL,
  `valorTotal` decimal(16,2) NOT NULL,
  `usuario` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `iva` decimal(16,2) DEFAULT 0.00,
  `porcent_iva` decimal(16,2) DEFAULT 0.00,
  `valorsiva` decimal(16,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `id_ingreso_idx` (`id_ingreso`),
  CONSTRAINT `id_ingreso-ingresos` FOREIGN KEY (`id_ingreso`) REFERENCES `ingresos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
*/