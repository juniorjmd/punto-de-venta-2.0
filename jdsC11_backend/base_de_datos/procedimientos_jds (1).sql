-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: jds_mc_20180708
-- ------------------------------------------------------
-- Server version	5.7.17-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping routines for database 'jds_mc_20180708'
--
/*!50003 DROP FUNCTION IF EXISTS `idTipoRecurso` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE FUNCTION `idTipoRecurso`(_name_recurso varchar(150)) RETURNS int(11)
BEGIN
if _name_recurso <> '' then
set @idtipoRecurso = '';
SELECT idtipoRecurso into @idtipoRecurso FROM tipoRecurso where nombre_recurso = trim(_name_recurso);
else 
	set @idtipoRecurso = '-1';
end if;
RETURN @idtipoRecurso;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `id_cuenta_contable` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE FUNCTION `id_cuenta_contable`(  cuenta int(11)) RETURNS int(11)
BEGIN
RETURN (select ifnull(id_cuenta, -1 ) from cnt_cuentas where nro_cuenta = cuenta LIMIT 1);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `ID_TIPO_RELACION` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE FUNCTION `ID_TIPO_RELACION`(_name_tipo_relacion varchar(150)) RETURNS int(11)
BEGIN
if _name_tipo_relacion <> '' then
set @id_tipo = '';
SELECT id_relacion  into @id_tipo FROM tipo_relacion_mail_cliente where descripcion = trim(_name_tipo_relacion);
else 
	set @id_tipo = '-1';
end if;
RETURN @id_tipo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `ID_TIP_NOTIFICACION` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE FUNCTION `ID_TIP_NOTIFICACION`(_name_tip_notificacion varchar(150)) RETURNS int(11)
BEGIN
if _name_tip_notificacion <> '' then
set @id_tipo = '';
SELECT id_tipo  into @id_tipo FROM tipo_notificacion where nom_tipo_notificacion = trim(_name_tip_notificacion);
else 
	set @id_tipo = '-1';
end if;
RETURN @id_tipo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `buscaProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `buscaProducto`(in id_producto varchar(100))
BEGIN
declare total int;
select count(*) from producto where idProducto = id_producto into total;
if total = 0 then 
	select * from producto where barcode = id_producto ;
else 
	select * from producto where idProducto = id_producto ;
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `busqueda_allproductplustotalsales` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `busqueda_allproductplustotalsales`(
in _dato_busqueda text 

)
BEGIN
set @w = '';
set @id_sh = '';
set @like_sh = '';
set @cantidad = 0;
set @cantBarcode = 0;
set @sql_exe='select * from allproductplustotalsales';
if _dato_busqueda != '' then

	select COUNT(*) from allproductplustotalsales WHERE idProducto = _dato_busqueda  INTO @cantidad;
   if @cantidad = 0 then
    select COUNT(*) from allproductplustotalsales  WHERE barcode = _dato_busqueda INTO @cantBarcode;
		if @cantBarcode = 0 then
			set @w = ' where ';
            set @like_sh = concat('`nombre` LIKE ''%',_dato_busqueda,'%''  OR `Grupo`  LIKE  ''%',_dato_busqueda,'%''  OR `descripcion`  LIKE  ''%',_dato_busqueda,'%''  OR `nom_subgrupo_1`  LIKE  ''%',_dato_busqueda,'%''  OR `nom_subgrupo_2`  LIKE  ''%',_dato_busqueda,'%''  OR  `laboratorio` LIKE ''%',_dato_busqueda,'%''');
		else
			set @w = ' where ';
			set @id_sh = concat(' barcode = ''',_dato_busqueda ,'''');
		end if;
	else
		set @w = ' where ';
		set @id_sh = concat(' idProducto = ''',_dato_busqueda ,'''');
    end if;
    
    
    set @sql_exe =  concat(@sql_exe,@w,@id_sh,@like_sh,'  order by IDLINEA');
    
end if;  
PREPARE insertar FROM @sql_exe;
      EXECUTE insertar;
 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `cambia_productos_compra` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `cambia_productos_compra`(
IN `_Id_compra` int
)
BEGIN
DECLARE finished INTEGER DEFAULT 0;
DECLARE _cantidad varchar(10)  ;
DECLARE _idProducto varchar(10)  ;

 DECLARE recorrer_compras CURSOR FOR 
 select 
 cantidad ,idProducto 
 from temp_compras_actualiza;
 
 DECLARE CONTINUE HANDLER 
FOR NOT FOUND SET finished = 1;
 
 -- actulizo la tabla de las compras a actualizar
SET SQL_SAFE_UPDATES = 0;
 delete from temp_compras_actualiza;
 insert into temp_compras_actualiza 
select * from listacompra where idCompra = _Id_compra  ;

OPEN recorrer_compras;






get_email: LOOP
FETCH recorrer_compras INTO _cantidad ,_idProducto ;
 IF v_finished = 1 THEN 
 LEAVE get_email;
 END IF;
 -- build email list

update producto set compras = compras + _cantidad , 
cantActual = cantActual + _cantidad
 where  idProducto = _idProducto ;
 
 
END LOOP get_email;


CLOSE recorrer_compras;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `devolucionTotal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `devolucionTotal`(IN `Id_Venta` VARCHAR(12), IN `codusuario` INT, OUT `estatus` VARCHAR(10), OUT `totalDevuelto` DOUBLE, OUT `ivaDevuelto` DOUBLE, OUT `totalProductos` INT, OUT `totalPresioVent` DOUBLE)
BEGIN
		declare count int ; 
        declare id_cuenta_cartera varchar(100);
		declare contProductos int;
		declare  iva_devuelto,  precio_venta ,total_devuelto , cntDevuelta, cntDevuelta_real float ;
		declare vidProducto varchar(12) ;		
		DECLARE recorrerVentas CURSOR FOR SELECT  `idProducto` , `cantidadVendida` , cant_real_descontada from ventastemp where `idVenta` =  Id_Venta;
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET @hecho = TRUE; 
		set count = 0 ;
		set contProductos = 0 ;
		OPEN recorrerVentas;
		SET autocommit=0;
		set @flagInicio =0;
		START TRANSACTION;
		select count(*) from ventastemp where  `idVenta` =  Id_Venta and `estado_venta` = 'C' into @flagInicio ;
		
		if (@flagInicio = 0) then

		loop1: LOOP
		FETCH recorrerVentas INTO  vidProducto, cntDevuelta, cntDevuelta_real;
		
        IF @hecho THEN
				LEAVE loop1;
		END IF;
        
		set contProductos = contProductos + cntDevuelta;
		update producto set  `cantActual` =  `cantActual` + cntDevuelta_real ,
						`ventas` = `ventas` - cntDevuelta_real , `devoluciones` = `devoluciones` + cntDevuelta_real
		where `idProducto` = vidProducto;
		if ( ROW_COUNT() <= 0) THEN
					set count = 1 ;
		end if ;
 		
		END LOOP loop1;
		update ventastemp set `estado_venta` = 'C'  where `idVenta` =  Id_Venta;
		 select ROW_COUNT() into @flag ;
		if ( @flag <= 0) THEN
					select 'entro';
					set count = 1 ;
		end if ;

		update ventas  set estadoFactura = 'C' where `idVenta` =  Id_Venta ; 

		if ( ROW_COUNT() <= 0) THEN
					set count = 1 ;
		end if ; 
select   `valorIVA` ,    `valorParcial` , `valorTotal` from  
ventas  where `idVenta` =  Id_Venta into iva_devuelto , precio_venta , total_devuelto;

INSERT INTO `devoluciones`
( `id_factura`,
`fec_devolucion`,
`cnt_prd_devueltos`,
`iva_devuelto`,
`precio_venta`,
`total_devuelto`,
`estado_cierre_caja`,
`estado_cierre1`,
`cod_usuario_generador`)
VALUES
(Id_Venta,CURDATE(),
contProductos,
iva_devuelto,
 precio_venta ,total_devuelto ,'ACTIVO','ACTIVO',codusuario
);
	if ( ROW_COUNT() <= 0) THEN
					set count = 1 ;
		end if ;
set totalDevuelto    =   total_devuelto;
set ivaDevuelto      = 	 iva_devuelto;
set totalProductos   =   contProductos;
set totalPresioVent  =   precio_venta ;

SET SQL_SAFE_UPDATES = 0;

select idCuenta from  cartera where refFact = Id_Venta into id_cuenta_cartera;

update cartera set TotalActual = 0 , origen = 'devolucion' where refFact = Id_Venta ;

insert into abonoscartera (  idCliente, idFactura, valorAbono, fecha )
SELECT  idCliente, idFactura, (-1 * valorAbono), curdate() 
from abonoscartera where idFactura = id_cuenta_cartera;

		if ( count = 0 ) THEN
					COMMIT;
					set estatus = 'ok';
		else 
					ROLLBACK;
					set estatus = 'error';
					
		end if ;	
else
set estatus = 'error2';
end if ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `editarListaCompra` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `editarListaCompra`( in id_compra int)
BEGIN 
declare _idLinea int; 
declare _idCompra VARCHAR(10);  
declare _idProducto VARCHAR(10);  
declare _nombreProducto VARCHAR(100);  
declare _presioCompra VARCHAR(10);   
declare _cantidad VARCHAR(10);   
declare _valorTotal VARCHAR(10);  
declare _usuario VARCHAR(10);  
declare _iva double;  
declare _porcent_iva float;  
declare _valorsiva double;  
declare _estado VARCHAR(1);  
declare _id_linea_editar INT;  
declare _cantidad_edicion FLOAT;

DECLARE recorrerCompraEditada CURSOR FOR 		
SELECT * FROM `listacompraedicion`
where  `idCompra` = id_compra ;



DECLARE CONTINUE HANDLER FOR NOT FOUND SET @hecho = TRUE; 
 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN 
ROLLBACK; 
SELECT '-2'as _error; 
END; 

SET autocommit = 0;
START TRANSACTION;
    OPEN recorrerCompraEditada;
    
    loop1: LOOP 
	FETCH recorrerCompraEditada into _idLinea, _idCompra,  _idProducto,  _nombreProducto,  _presioCompra,  
    _cantidad,  _valorTotal,  _usuario,  _iva,  _porcent_iva,  _valorsiva,  _estado,  _id_linea_editar,  
    _cantidad_edicion ;
    
     IF @hecho THEN
		LEAVE loop1;
	END IF;

	 IF _estado = 'E'  THEN 
        
        IF _cantidad_edicion <>  _cantidad THEN
         update `producto` set `producto`.`cantActual` = (`producto`.`cantActual` - _cantidad ) ,
		`producto`.`compras` = (`producto`.`compras` - _cantidad) where `idProducto` = _idProducto ;
        
        
         update `producto` set `producto`.`cantActual` = (`producto`.`cantActual` + _cantidad_edicion ),
		`producto`.`compras` = ( `producto`.`compras` + _cantidad_edicion ) where `idProducto` = _idProducto ;
        
        UPDATE listacompra SET cantidad = _cantidad_edicion ,    
        valorTotal =  ( _presioCompra * _cantidad_edicion  ) , 
        valorsiva = (( _presioCompra * _cantidad_edicion  ) -(( _presioCompra * _cantidad_edicion  ) * (_porcent_iva / 100))) 
         WHERE idLinea = _id_linea_editar;
        
        
       END IF;
       
		ELSEIF _estado = 'D' THEN 
			update `producto` set `producto`.`cantActual` = `producto`.`cantActual` - _cantidad ,
		     `producto`.`compras` = `producto`.`compras` - _cantidad where `idProducto` = _idProducto ;
             
            DELETE FROM listacompra WHERE idLinea = _id_linea_editar;
      
			ELSEIF _estado = 'N'  THEN   
            
              INSERT INTO `listacompra`  ( `idCompra`,`idProducto`,`nombreProducto`,`presioCompra`,
				`cantidad`,`valorTotal`,`usuario`,`iva`,`porcent_iva`,`valorsiva`) VALUES
				(_idCompra,_idProducto,_nombreProducto,_presioCompra,_cantidad,
				_valorTotal,_usuario,_iva,_porcent_iva,_valorsiva);

            
              update `producto` set `producto`.`cantActual` = `producto`.`cantActual` + _cantidad ,
		`producto`.`compras` = `producto`.`compras` + _cantidad where `idProducto` = _idProducto ;

            
    END IF;

   
    
    
   
	END LOOP loop1;
                
	COMMIT;
    CLOSE recorrerCompraEditada;
    SET autocommit = 1;
        select '100' _error ;
 
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `eliminarCompras` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `eliminarCompras`( in id_compra int)
BEGIN
declare _idProducto , _cantidad float ; 
DECLARE recorrerCompra CURSOR FOR 		
SELECT `listacompra`.`idProducto`, `listacompra`.`cantidad`
FROM `listacompra`
where `listacompra`.`idCompra` = id_compra ;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET @hecho = TRUE; 
 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN 
SELECT '-2'as _error; 
ROLLBACK; 
END; 

 
SELECT  
    `compras`.`estado`,
    `compras`.`referencia`
FROM `compras` where `compras`.`idCompra` = id_compra into @estado,@referencia;

SET autocommit = 0;
START TRANSACTION;
if @estado = 'activo' then 	if @referencia <> 'ninguno' then 		SELECT count(*) FROM  abonoscredito  where 
        abonoscredito.idFactura = @referencia into @countAbonos;
        if @countAbonos = 0 then 
			delete from credito where idCuenta = @referencia;
            set @continuar = 'ok';
		else
			select '-33' _error ;             set @continuar = 'nok';
        end if;
     else
     set @continuar = 'ok';
	END if ;
    OPEN recorrerCompra;
    loop1: LOOP 
	FETCH recorrerCompra into _idProducto , _cantidad;
    IF @hecho THEN
		LEAVE loop1;
	END IF;
    
    update `producto` set `producto`.`cantActual` = `producto`.`cantActual` - _cantidad ,
    `producto`.`compras` = `producto`.`compras` - _cantidad where `idProducto` = _idProducto ;
    
	END LOOP loop1;
    DELETE FROM `listacompra` WHERE `listacompra`.`idCompra` = id_compra;
	DELETE FROM `compras` WHERE `compras`.`idCompra` = id_compra;
    COMMIT;
    CLOSE recorrerCompra;
    SET autocommit = 1;
        select '100' _error ;
else
	select '-44' _error ; end if;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ingresar_saldo_cuenta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `ingresar_saldo_cuenta`(
in id_subcuenta int,
in _fecha date,
in _debito decimal(16,2),
in _credito decimal(16,2),
in origen varchar(10))
BEGIN
declare _dia ,_mes,_anio , _count int;
declare  _clase ,_grupo , _cuenta int;
select YEAR(_fecha)into _anio;  #Selecciona el año
select MONTH (_fecha) into _mes;  #Selecciona el mes
select DAY(_fecha) into _dia; #Selecciona el día  

select cod_clase ,cod_grupo ,cod_cuenta from vw_cnt_scuentas where id_scuenta = id_subcuenta 
into _clase ,_grupo , _cuenta;
set @_tabla = '' ;
set @columna = '' ;
 
if _debito = 0 then
	set @num_debito = 0;
	set @num_credito = 1;
end if;
if _credito = 0 then
	set @num_debito = 1;
	set @num_credito = 0;
end if;
 
-- when 'cuenta' then
select count(*) from cnt_saldo_cuenta where cod_cuenta = _cuenta
and anio = _anio into _count;
if _count = 0 then  
insert into cnt_saldo_cuenta (cod_cuenta,anio,grupo,clase) values(_cuenta ,_anio , _grupo  , _clase );
END IF;


-- when 'grupo' then
select count(*) from cnt_saldo_grupo where cod_grupo = _grupo
and anio = _anio into _count;
-- _clase ,_grupo , _cuenta
if _count = 0 then  
insert into cnt_saldo_grupo (cod_grupo,anio , clase) values(_grupo ,_anio , _clase);
END IF;

-- when 'clase' then
select count(*) from cnt_saldo_clases where cod_clase = _clase
and anio = _anio into _count;
if _count = 0 then  
  insert into cnt_saldo_clases (cod_clase,anio) values(_clase ,_anio );
END IF;

case _mes
when 1 then
update cnt_saldo_cuenta
set debito_1 = debito_1 + _debito ,
credito_1 = credito_1 + _credito,
num_trn_c_1 = num_trn_c_1 + @num_credito ,
num_trn_d_1 = num_trn_d_1 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_1 = saldo_1 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_1 = debito_1 + _debito ,
credito_1 = credito_1 + _credito,
num_trn_c_1 = num_trn_c_1 + @num_credito ,
num_trn_d_1 = num_trn_d_1 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1 ,
saldo_1 = saldo_1 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_1 = debito_1 + _debito ,
credito_1 = credito_1 + _credito,
num_trn_c_1 = num_trn_c_1 + @num_credito ,
num_trn_d_1 = num_trn_d_1 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1 ,
saldo_1 = saldo_1 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;
 
when 2 then
update cnt_saldo_cuenta
set debito_2 = debito_2 + _debito ,
credito_2 = credito_2 + _credito,
num_trn_c_2 = num_trn_c_2 + @num_credito ,
num_trn_d_2 = num_trn_d_2 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_2 = saldo_2 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_2 = debito_2 + _debito ,
credito_2 = credito_2 + _credito,
num_trn_c_2 = num_trn_c_2 + @num_credito ,
num_trn_d_2 = num_trn_d_2 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_2 = saldo_2 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_2 = debito_2 + _debito ,
credito_2 = credito_2 + _credito,
num_trn_c_2 = num_trn_c_2 + @num_credito ,
num_trn_d_2 = num_trn_d_2 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_2 = saldo_2 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 3 then
update cnt_saldo_cuenta
set debito_3 = debito_3 + _debito ,
credito_3 = credito_3 + _credito,
num_trn_c_3 = num_trn_c_3 + @num_credito ,
num_trn_d_3 = num_trn_d_3 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_3 = saldo_3 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_3 = debito_3 + _debito ,
credito_3 = credito_3 + _credito,
num_trn_c_3 = num_trn_c_3 + @num_credito ,
num_trn_d_3 = num_trn_d_3 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_3 = saldo_3 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_3 = debito_3 + _debito ,
credito_3 = credito_3 + _credito,
num_trn_c_3 = num_trn_c_3 + @num_credito ,
num_trn_d_3 = num_trn_d_3 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_3 = saldo_3 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 4 then
update cnt_saldo_cuenta
set debito_4 = debito_4 + _debito ,
credito_4 = credito_4 + _credito,
num_trn_c_4 = num_trn_c_4 + @num_credito ,
num_trn_d_4 = num_trn_d_4 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_4 = saldo_4 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_4 = debito_4 + _debito ,
credito_4 = credito_4 + _credito,
num_trn_c_4 = num_trn_c_4 + @num_credito ,
num_trn_d_4 = num_trn_d_4 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_4 = saldo_4 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_4 = debito_4 + _debito ,
credito_4 = credito_4 + _credito,
num_trn_c_4 = num_trn_c_4 + @num_credito ,
num_trn_d_4 = num_trn_d_4 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_4 = saldo_4 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 5 then
update cnt_saldo_cuenta
set debito_5 = debito_5 + _debito ,
credito_5 = credito_5 + _credito,
num_trn_c_5 = num_trn_c_5 + @num_credito ,
num_trn_d_5 = num_trn_d_5 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_5 = saldo_5 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_5 = debito_5 + _debito ,
credito_5 = credito_5 + _credito,
num_trn_c_5 = num_trn_c_5 + @num_credito ,
num_trn_d_5 = num_trn_d_5 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_5 = saldo_5 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_5 = debito_5 + _debito ,
credito_5 = credito_5 + _credito,
num_trn_c_5 = num_trn_c_5 + @num_credito ,
num_trn_d_5 = num_trn_d_5 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_5 = saldo_5 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 6 then
update cnt_saldo_cuenta
set debito_6 = debito_6 + _debito ,
credito_6 = credito_6 + _credito,
num_trn_c_6 = num_trn_c_6 + @num_credito ,
num_trn_d_6 = num_trn_d_6 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_6 = saldo_6 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_6 = debito_6 + _debito ,
credito_6 = credito_6 + _credito,
num_trn_c_6 = num_trn_c_6 + @num_credito ,
num_trn_d_6 = num_trn_d_6 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_6 = saldo_6 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_6 = debito_6 + _debito ,
credito_6 = credito_6 + _credito,
num_trn_c_6 = num_trn_c_6 + @num_credito ,
num_trn_d_6 = num_trn_d_6 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_6 = saldo_6 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 7 then
update cnt_saldo_cuenta
set debito_7 = debito_7 + _debito ,
credito_7 = credito_7 + _credito,
num_trn_c_7 = num_trn_c_7 + @num_credito ,
num_trn_d_7 = num_trn_d_7 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_7 = saldo_7 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_7 = debito_7 + _debito ,
credito_7 = credito_7 + _credito,
num_trn_c_7 = num_trn_c_7 + @num_credito ,
num_trn_d_7 = num_trn_d_7 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_7 = saldo_7 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_7 = debito_7 + _debito ,
credito_7 = credito_7 + _credito,
num_trn_c_7 = num_trn_c_7 + @num_credito ,
num_trn_d_7 = num_trn_d_7 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_7 = saldo_7 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 8 then
update cnt_saldo_cuenta
set debito_8 = debito_8 + _debito ,
credito_8 = credito_8 + _credito,
num_trn_c_8 = num_trn_c_8 + @num_credito ,
num_trn_d_8 = num_trn_d_8 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_8 = saldo_8 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_8 = debito_8 + _debito ,
credito_8 = credito_8 + _credito,
num_trn_c_8 = num_trn_c_8 + @num_credito ,
num_trn_d_8 = num_trn_d_8 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_8 = saldo_8 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_8 = debito_8 + _debito ,
credito_8 = credito_8 + _credito,
num_trn_c_8 = num_trn_c_8 + @num_credito ,
num_trn_d_8 = num_trn_d_8 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_8 = saldo_8 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;
 
when 9 then
update cnt_saldo_cuenta
set debito_9 = debito_9 + _debito ,
credito_9 = credito_9 + _credito,
num_trn_c_9 = num_trn_c_9 + @num_credito ,
num_trn_d_9 = num_trn_d_9 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_9 = saldo_9 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_9 = debito_9 + _debito ,
credito_9 = credito_9 + _credito,
num_trn_c_9 = num_trn_c_9 + @num_credito ,
num_trn_d_9 = num_trn_d_9 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_9 = saldo_9 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_9 = debito_9 + _debito ,
credito_9 = credito_9 + _credito,
num_trn_c_9 = num_trn_c_9 + @num_credito ,
num_trn_d_9 = num_trn_d_9 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_9 = saldo_9 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 10 then
update cnt_saldo_cuenta
set debito_10 = debito_10 + _debito ,
credito_10 = credito_10 + _credito,
num_trn_c_10 = num_trn_c_10 + @num_credito ,
num_trn_d_10 = num_trn_d_10 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_10 = saldo_10 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_10 = debito_10 + _debito ,
credito_10 = credito_10 + _credito,
num_trn_c_10 = num_trn_c_10 + @num_credito ,
num_trn_d_10 = num_trn_d_10 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_10 = saldo_10 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_10 = debito_10 + _debito ,
credito_10 = credito_10 + _credito,
num_trn_c_10 = num_trn_c_10 + @num_credito ,
num_trn_d_10 = num_trn_d_10 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_10 = saldo_10 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;
 
when 11 then
update cnt_saldo_cuenta
set debito_11 = debito_11 + _debito ,
credito_11 = credito_11 + _credito,
num_trn_c_11 = num_trn_c_11 + @num_credito ,
num_trn_d_11 = num_trn_d_11 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1  ,
saldo_11 = saldo_11 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_11 = debito_11 + _debito ,
credito_11 = credito_11 + _credito,
num_trn_c_11 = num_trn_c_11 + @num_credito ,
num_trn_d_11 = num_trn_d_11 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1 ,
saldo_11 = saldo_11 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_11 = debito_11 + _debito ,
credito_11 = credito_11 + _credito,
num_trn_c_11 = num_trn_c_11 + @num_credito ,
num_trn_d_11 = num_trn_d_11 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1 ,
saldo_11 = saldo_11 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

when 12 then

update cnt_saldo_cuenta
set debito_12 = debito_12 + _debito ,
credito_12 = credito_12 + _credito,
num_trn_c_12 = num_trn_c_12 + @num_credito ,
num_trn_d_12 = num_trn_d_12 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1   ,
saldo_12 = saldo_12 +( _debito - _credito) 
where cod_cuenta = _cuenta and  anio =  _anio;

update cnt_saldo_grupo
set debito_12 = debito_12 + _debito ,
credito_12 = credito_12 + _credito,
num_trn_c_12 = num_trn_c_12 + @num_credito ,
num_trn_d_12 = num_trn_d_12 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1 ,
saldo_12 = saldo_12 +( _debito - _credito) 
where cod_grupo = _grupo and  anio =  _anio;

update cnt_saldo_clases 
set debito_12 = debito_12 + _debito ,
credito_12 = credito_12 + _credito,
num_trn_c_12 = num_trn_c_12 + @num_credito ,
num_trn_d_12 = num_trn_d_12 + @num_debito,
saldo_credito_t = saldo_credito_t + _credito,
saldo_debito_t = saldo_debito_t + _debito,
saldo_total = saldo_total +( _debito - _credito)  , 
num_trn_credito = num_trn_credito + @num_credito ,
num_trn_debito = num_trn_debito  + @num_debito ,
num_trn_total = num_trn_total + 1 ,
saldo_12 = saldo_12 +( _debito - _credito) 
where cod_clase = _clase and  anio =  _anio;

end case;
 
 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertaRetefuente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `insertaRetefuente`(
 in _id_usuario varchar(50),
 in _id_venta  varchar(50),
in  _IVA double ,
in _total_venta_bruta double ,
in  _total_venta_neta double ,
in _porcentaje_retefuente double )
BEGIN
set @tRF = 0;

set @tRF = (_total_venta_neta * _porcentaje_retefuente)/100;

insert into retefuente ( id_usuario, IVA, FECHA, total_venta_bruta, total_venta_neta, id_cierre_de_caja,id_venta,porcentaje_retefuente,totalRf)
values (_id_usuario, _IVA, CURDATE(), _total_venta_bruta, _total_venta_neta, '', _id_venta,_porcentaje_retefuente, @tRF
) ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `rectificaCantidades` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `rectificaCantidades`()
BEGIN
declare vidProducto varchar(12) ;
declare cant int ;
DECLARE recorrerVentas CURSOR FOR SELECT    idProducto , ifnull(sum(cantidad),0)  
  FROM listacompra group by  idProducto ;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET @hecho = TRUE; 
 OPEN recorrerVentas;
loop1: LOOP

FETCH recorrerVentas INTO  vidProducto, cant;
IF @hecho THEN
	LEAVE loop1;
END IF;
select vidProducto ,cant;


END LOOP loop1;
close recorrerVentas;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_agregar_recurso_menu_hijo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_agregar_recurso_menu_hijo`(
	in _idmenus  varchar(45) ,
    in _Nombre varchar(45) ,
    in _PadreId  varchar(45)
)
BEGIN
	declare _numRecursos int;    
    declare _idrecurso int ;
    select count(*) into _numRecursos from recurso where id_recurso_sistema = _idmenus and tipo_recurso =   idTipoRecurso ('menu_hijo');
   -- select _numRecursos;
   if _numRecursos = 0 then
		insert into recurso ( id_menu, tipo_recurso, nombre_recurso,id_recurso_sistema  ) values (
			_PadreId,
			idTipoRecurso ('menu_hijo'),
            _Nombre,
            _idmenus         );
    else
    -- idrecurso, id_menu, tipo_recurso, nombre_recurso, estado
		select idrecurso  into _idrecurso from recurso 
        where id_recurso_sistema = _idmenus and tipo_recurso =   idTipoRecurso ('menu_hijo');
        update recurso 
        set id_menu = _PadreId 
        where idrecurso = _idrecurso;
    
    end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_asignar_consecutivo_factura` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_asignar_consecutivo_factura`(in _usuario int , in _modulo varchar(5),out s_id_venta varchar(100), out _id_venta varchar(100))
BEGIN
	declare _cod_generado int;
    declare _cont_codigo int;
    select count(*) from consecutivos_factura where estado = 'ACTIVO'
    AND modulo_factura = _modulo AND cod_usuario = _usuario  INTO  _cont_codigo ;
    
    
    IF _cont_codigo <= 0 THEN	     
		select count(*) from consecutivos_factura where estado = 'noAsignado'  INTO  _cont_codigo ;
        IF _cont_codigo > 0 THEN
			UPDATE consecutivos_factura 
			SET	modulo_factura = _modulo , cod_usuario = _usuario , estado = 'ACTIVO'
            where estado = 'noAsignado'   ;
        ELSE
			SELECT IFNULL(MAX(cod_factura),0) FROM consecutivos_factura INTO _cod_generado;
            
			INSERT INTO consecutivos_factura
            (  cod_factura, modulo_factura, cod_usuario, estado) 
            VALUES(_cod_generado + 1 , _modulo , _usuario, 'ACTIVO') ,
            (_cod_generado + 2 , 'NN' , 0 , 'noAsignado') ;
            
            
            
        END IF;
        
        
        
        
    END IF;
    
    
    select cod_factura , CONCAT(_modulo,'_',cod_factura) AS id_venta_generado from consecutivos_factura where estado = 'ACTIVO'
		AND modulo_factura = _modulo AND cod_usuario = _usuario into _id_venta , s_id_venta    ;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_cerrar_remision` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_cerrar_remision`( 
in _id_remision int ,
in _id_orden_compra int ,
IN _usuario VARCHAR(100) )
BEGIN
declare _cantidadVendida decimal(12,2);
declare _presioSinIVa decimal(12,2);
declare _valorIVA decimal(12,2) ;
declare _presioVenta decimal(12,2);
declare _valorTotal decimal(12,2);
declare _total_valores int;

select count(*)  FROM remision_detalle 
where id_remision = _id_remision and id_orden_compra = _id_orden_compra into _total_valores ;

if (_total_valores > 0 )THEN 
 
 select 
sum(cantidadVendida) as cantidadVendida ,
SUM(CAST( ( presioSinIVa * cantidadVendida ) AS DECIMAL(16,2) ) ) as presioSinIVa,
sum(CAST( ( IVA * cantidadVendida ) AS DECIMAL(16,2) ) ) as IVA,
sum(presioVenta) as presioVenta,
sum(valorTotal) as valorTotal 
 FROM remision_detalle 
where id_remision = _id_remision and id_orden_compra = _id_orden_compra
group by 
id_orden_compra, id_remision into _cantidadVendida , _presioSinIVa ,_valorIVA ,_presioVenta , _valorTotal
 ;

INSERT INTO `remision_cabeza`
(`num_remision`,
`orden_de_compra`,
`cantidadVendida`,
`valorParcial`,
`descuento`,
`valorIVA`,
`valorTotal`,
`fecha`,
`hora`,
`usuario`,  
`fecha_entrega`)
VALUES
(_id_remision,
_id_orden_compra ,
_cantidadVendida ,
_presioSinIVa,
	0,
_valorIVA,
_valorTotal,
curdate(),
CURTIME() ,
_usuario  ,
curdate());


	SELECT '100' as result; 
 
else  
	SELECT 'No existen datos para ingresar en el cierre.' as result; 
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_change_pass` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_change_pass`(
in _ID VARCHAR(20) , 
in _usr_registro  varchar(150) , 
in _pass  blob )
BEGIN 
set @usuarioIngresado = _usr_registro;
IF _ID is null or _ID = ''  THEN
 
     	SELECT '-1' as result;
	 
else
	    
				UPDATE `usuarios`
					SET					 
					`pass` = _pass ,
                    `change` = '1'  
					WHERE `ID` =  CAST(_ID AS UNSIGNED) ;       
        
        SELECT '101' as result;
END if;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_AreasDeControl` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_AreasDeControl`(
in _codAreasControl  varchar(45),
    in _nomAreasControl  varchar(45),
    in usuarioIngresado varchar(150) )
BEGIN
declare crear varchar(10);
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error al ingresar a la base de datos'  AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;
set crear = null;
select count(*) into crear from areas_de_control where ACC = _codAreasControl;
 
if crear = 0 then
	insert into areas_de_control ( ACC, DENOMINACION) values (_codAreasControl ,  _nomAreasControl) ;
    select '100' as result;
else
    update areas_de_control  set   DENOMINACION = _nomAreasControl where ACC = _codAreasControl ; 
    select '100' as result;
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_AreasDeControl_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_AreasDeControl_usuario`(
	in _id_relacion  varchar(45),
    in _id_usuario  varchar(200),    
    in _codAreasControl  varchar(45),
    in _nomAreasControl  varchar(45),
    in usuarioIngresado varchar(150))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error al ingresar a la base de datos'  AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;
if _id_relacion = '' then
INSERT INTO `relacion_usuario_areas_de_control`
(  codusuario, codAreasControl, nomAreasControl )
VALUES
( _id_usuario,_codAreasControl, _nomAreasControl );
 select '100' as result;
else
UPDATE `relacion_usuario_areas_de_control`
SET 
`codusuario` = _id_usuario,
`codAreasControl` = _codAreasControl ,
`nomAreasControl` = _nomAreasControl  
WHERE `id` = _id_relacion;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_areaVenta_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_areaVenta_usuario`(in _id_relacion  varchar(45),
    in _id_usuario  varchar(200),     
    in _VKORG  varchar(45),
    in _VTWEG  varchar(45),
    in _SPART  varchar(45), 
    in usuarioIngresado varchar(150))
BEGIN
declare existe int;
declare _nom_usuario varchar(150);
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error en el proceso de insertar en la base de datos relacion_usuario_area_venta' AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;

set @usuarioIngresado = usuarioIngresado;
if _id_relacion = '' then
	select count(*) into existe from  `relacion_usuario_area_venta` where  
     VKORG = _VKORG and VTWEG = _VTWEG and SPART = _SPART and cod_usuario = _id_usuario;
	if existe = 0 then
	INSERT INTO `relacion_usuario_area_venta`
	(   cod_usuario, VKORG, VTWEG, SPART )
	VALUES
	( _id_usuario,_VKORG, _VTWEG, _SPART);
	select '100' as result;
    else
		SELECT  nombreCompleto into _nom_usuario FROM usuarios where ID = _id_usuario;
		select concat('la relacion con ',_VKORG,'-', _VTWEG,'-', _SPART,' ya existe para el usuario ',_nom_usuario) as result;
	 end if;
     
else 
UPDATE `relacion_usuario_area_venta`
SET    VKORG = _VKORG, VTWEG= _VTWEG, SPART = _SPART
 
WHERE `id_relacion` = _id_relacion;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_cliente_mail_notificacion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_cliente_mail_notificacion`(in _id_relacion  varchar(45), 
in _id_cliente_SAP varchar(15), 
in _tipo_relacion varchar(10), 
in  _tipo_notificacion varchar(10), 
in  _id_correo_notificacion varchar(10),
in usuarioIngresado varchar(150) , 
in _nom_cliente_SAP varchar(250))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error en la base de datos' AS result LIMIT 1; 
	END;  
DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado; 
if _id_relacion = '' then

  	INSERT INTO `relacion_mail_cliente`
  	( id_cliente_SAP, tipo_relacion, tipo_notificacion, id_correo_notificacion , nom_cliente_SAP)
 	VALUES
  	(_id_cliente_SAP, _tipo_relacion, _tipo_notificacion, _id_correo_notificacion , _nom_cliente_SAP );
	select '100' as result;
else
  UPDATE `relacion_mail_cliente`
  SET 
  id_cliente_SAP = _id_cliente_SAP, tipo_relacion = _tipo_relacion, 
  tipo_notificacion = _tipo_notificacion, id_correo_notificacion = _id_correo_notificacion
  WHERE `id_relacion` = _id_relacion;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_cliente_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_cliente_usuario`(in _id_relacion  varchar(45),
    in _id_usuario  varchar(200),
    in _id_cliente_SAP  varchar(45),
    in _nombre_cliente_SAP  varchar(45),
    in _estado  varchar(45), 
    in usuarioIngresado varchar(150))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select ERRORS AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;
if _id_relacion = '' then
INSERT INTO `relacion_usuario_clientes`
( 
`id_usuario`,
`id_cliente_SAP`,
`nombre_cliente_SAP`,
`estado`)
VALUES
( _id_usuario,_id_cliente_SAP, _nombre_cliente_SAP,_estado);
 select '100' as result;
else
UPDATE `relacion_usuario_clientes`
SET 
`id_usuario` = _id_usuario,
`id_cliente_SAP` = _id_cliente_SAP ,
`nombre_cliente_SAP` = _nombre_cliente_SAP ,
`estado` = _estado 
WHERE `id_relacion` = _id_relacion;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_mail_notificacion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_mail_notificacion`(
in _id_correo  varchar(45), 
in _nombre_usuario varchar(150), 
in _mail varchar(250), 
in  _mail_reemplazo varchar(250),  
in usuarioIngresado varchar(150)  )
BEGIN 

declare _lastIsertId int;
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error en la base de datos' AS result LIMIT 1; 
	END;   
DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;

 -- select _id_cliente_SAP, _tipo_relacion, _tipo_notificacion, _id_correo_notificacion , _nom_cliente_SAP;
if _id_correo = '' then

  	INSERT INTO `correos_notificacion`
  	(   nombre_usuario, mail, mail_reemplazo
)
 	VALUES
  	(  _nombre_usuario, _mail, _mail_reemplazo  );
    set _lastIsertId = LAST_INSERT_ID(); 
	INSERT INTO `relacion_mail_cliente`
	( `id_cliente_SAP`,`tipo_relacion`,`tipo_notificacion`,`id_correo_notificacion`,`nom_cliente_SAP`)
	select distinct  `id_cliente_SAP`,`tipo_relacion`, `tipo_notificacion`, _lastIsertId, `nom_cliente_SAP` 
	FROM `aux_relacion_mail_cliente`;
	set sql_safe_updates = 0 ;
	delete from aux_relacion_mail_cliente;
	select '100' as result;
else
  UPDATE `correos_notificacion`
  SET   nombre_usuario = _nombre_usuario , mail= _mail, mail_reemplazo = _mail_reemplazo
  WHERE  id_correo = _id_correo;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_editar_menu` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_crear_editar_menu`(in _idmenus  varchar(45),
    in _Descripcion  varchar(200),
    in _PadreId  varchar(45),
    in _Icono  varchar(45),
    in _Url  varchar(45), 
    in _Nombre varchar(45),
    in usuarioIngresado varchar(150)
    )
BEGIN
declare _cont_menu int;
declare _Orden int;
declare _continue int;
set _continue = 1;
set @usuarioIngresado = usuarioIngresado;
 
set _Orden = IFNULL( (select Orden from  `menus`  where idmenus = _PadreId  ),0) + 1;
 
if _idmenus = '' then
	SELECT count(*) into _cont_menu
	FROM `menus` where`menus`.`idmenus` = _idmenus ;
else
	set _cont_menu = 0;
end if;
if _cont_menu = 0 and _idmenus = '' then
	INSERT INTO `menus`
	( 
    `Nombre`,
	`Descripcion`,
	`PadreId`,
	`Icono`,
	`Url`,
	`Orden`)
	VALUES
	( _Nombre, _Descripcion ,	_PadreId ,	_Icono ,	_Url ,	_Orden);
    set _idmenus = LAST_INSERT_ID();
     select '100' as result;
else
	if _idmenus = '' then
		select '-1' as result;
        set _continue = 0;
		
    else
		update `menus` set
			`Descripcion` =  _Descripcion ,
			`PadreId` = _PadreId ,
			`Icono` = _Icono,
			`Url` = _Url,
			`Orden` = _Orden ,
            `Nombre` = _Nombre
            where`menus`.`idmenus` = _idmenus ;
            
            select '100' as result;
    end if;
end if;
if  _continue = 1 then
	call sp_agregar_recurso_menu_hijo( _idmenus,_Nombre , _PadreId );
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_editar_pedidos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_crear_editar_pedidos`(
_id_pedido_SAP varchar(20), _cod_generado_sistema varchar(20), _estado varchar(2) , _usuario varchar(150)
)
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error al ingresar los valores a la base de datos'  AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
    
set @usuarioIngresado = _usuario;

set @cont_datos = 0;
SELECT COUNT(*) INTO @cont_datos FROM pedidos_proceso WHERE id_pedido_SAP = _id_pedido_SAP;
 
if @cont_datos = 0 then

	insert into  pedidos_proceso (id_pedido_SAP, cod_generado_sistema, estado, usuario) 
	value (_id_pedido_SAP, _cod_generado_sistema, _estado, _usuario);
    

	SELECT '100' as result;
else
 SET SQL_SAFE_UPDATES = 0;
	UPDATE pedidos_proceso SET estado = _estado;  

	SELECT '101' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_editar_perfil` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_crear_editar_perfil`( 
in _Perf_ID VARCHAR(12) ,
in _Perf_Nombre varchar(150),
in _estado char(1),
IN _usuario VARCHAR(100) )
BEGIN
set @usuarioIngresado = _usuario;   

IF _Perf_ID is null or _Perf_ID = ''  THEN

INSERT INTO `Perfiles`
	(`Perf_ID`,
	`Perf_Nombre`,
	`estado`)
	VALUES
	(_Perf_ID,
	_Perf_Nombre,
	_estado );

	SELECT '100' as result;
else     
	UPDATE `Perfiles`
	SET
	`Perf_ID` = _Perf_ID,
	`Perf_Nombre` =_Perf_Nombre,
	`estado` = _estado
	WHERE `Perf_ID` = CAST(_Perf_ID AS UNSIGNED) ;    
        
        SELECT '101' as result;
	   
	
END if;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_editar_recurso` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_crear_editar_recurso`(
in _idrecurso varchar(5), in _id_menu varchar(5), in  _tipo_recurso varchar(5), 
in  _nombre_recurso varchar(200), in  _id_recurso_sistema varchar(100), 
in  _estado  varchar(1), in _usuario  varchar(150) 
)
BEGIN


declare _cont_menu int; 
set @usuarioIngresado = _usuario;
  
 

-- idrecurso, id_menu, tipo_recurso, nombre_recurso, id_recurso_sistema, estado
 
if _idrecurso = '' then
	INSERT INTO `recurso`
( 
`id_menu`,
`tipo_recurso`,
`nombre_recurso`,
`id_recurso_sistema`,
`estado`)
VALUES
(
_id_menu ,
_tipo_recurso ,
_nombre_recurso ,
_id_recurso_sistema ,
_estado );

    set _idrecurso = LAST_INSERT_ID();
     select '100' as result;
else
	if _idrecurso = '' then
		select '-1' as result; 		
    else
		UPDATE `recurso`
SET
`idrecurso` =_idrecurso,
`id_menu` =_id_menu,
`tipo_recurso` = _tipo_recurso,
`nombre_recurso` =_nombre_recurso,
`id_recurso_sistema` = _id_recurso_sistema,
`estado` = _estado
WHERE `idrecurso` = _idrecurso;            
            select '101' as result;
    end if;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_tmp_AreasDeControl_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_tmp_AreasDeControl_usuario`(
	in _id_relacion  varchar(45),
    in _id_usuario  int ,    
    in _codAreasControl  varchar(45),
    in _nomAreasControl  varchar(45),
    in usuarioIngresado varchar(150))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error al ingresar a la base de datos' AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;
if _id_relacion = '' then
INSERT INTO `aux_relacion_usuario_areas_de_control`
(  codusuario, codAreasControl, nomAreasControl )
VALUES
( _id_usuario,_codAreasControl, _nomAreasControl );
 select '100' as result;
else
UPDATE `aux_relacion_usuario_areas_de_control`
SET 
`codusuario` = _id_usuario,
`codAreasControl` = _codAreasControl ,
`nomAreasControl` = _nomAreasControl  
WHERE `id` = _id_relacion;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_tmp_areaVenta_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_tmp_areaVenta_usuario`(in _id_relacion  varchar(45),
    in _id_usuario  varchar(200),     
    in _VKORG  varchar(45),
    in _VTWEG  varchar(45),
    in _SPART  varchar(45), 
    in usuarioIngresado varchar(150))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error al ingresar los datos a la tabla' AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;
if _id_relacion = '' then
INSERT INTO `aux_relacion_usuario_area_venta`
(   cod_usuario, VKORG, VTWEG, SPART )
VALUES
( _id_usuario,_VKORG, _VTWEG, _SPART);
 select '100' as result;
else
UPDATE `Aux_relacion_usuario_area_venta`
SET    VKORG = _VKORG, VTWEG= _VTWEG, SPART = _SPART
 
WHERE `id_relacion` = _id_relacion;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_tmp_cliente_mail_notificacion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_tmp_cliente_mail_notificacion`(
in _id_relacion  varchar(45), 
in _id_cliente_SAP varchar(15), 
in _tipo_relacion varchar(10), 
in  _tipo_notificacion varchar(10), 
in  _id_correo_notificacion varchar(10),
in usuarioIngresado varchar(150) , 
in _nom_cliente_SAP varchar(250))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error en la base de datos' AS result LIMIT 1; 
	END;  
DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;

 -- select _id_cliente_SAP, _tipo_relacion, _tipo_notificacion, _id_correo_notificacion , _nom_cliente_SAP;
if _id_relacion = '' then

  	INSERT INTO `aux_relacion_mail_cliente`
  	( id_cliente_SAP, tipo_relacion, tipo_notificacion, id_correo_notificacion , nom_cliente_SAP)
 	VALUES
  	(_id_cliente_SAP, _tipo_relacion, _tipo_notificacion, _id_correo_notificacion , _nom_cliente_SAP );
	select '100' as result;
else
	set sql_safe_updates = 0;
  UPDATE `aux_relacion_mail_cliente`
  SET  tipo_relacion = _tipo_relacion, 
  tipo_notificacion = _tipo_notificacion, 
  id_correo_notificacion = _id_correo_notificacion
  WHERE `id_relacion` = _id_relacion and  
  id_cliente_SAP = _id_cliente_SAP
  
  ;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_tmp_cliente_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_tmp_cliente_usuario`(in _id_relacion  varchar(45),
        in _id_cliente_SAP  varchar(45),
    in _nombre_cliente_SAP  varchar(45),
    in _estado  varchar(45), 
    in usuarioIngresado varchar(150))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error en la base de datos' AS result LIMIT 1; 
	END; 
DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;
if _id_relacion = '' then
	INSERT INTO `Aux_relacion_usuario_clientes`
	( 
	
	`id_cliente_SAP`,
	`nombre_cliente_SAP`,
	`estado`)
	VALUES
	(_id_cliente_SAP, _nombre_cliente_SAP,_estado);
	select '100' as result;
else

UPDATE `Aux_relacion_usuario_clientes`
SET 
`id_usuario` = _id_usuario,
`id_cliente_SAP` = _id_cliente_SAP ,
`nombre_cliente_SAP` = _nombre_cliente_SAP ,
`estado` = _estado 
WHERE `id_relacion` = _id_relacion;
 select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Crear_editar_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_Crear_editar_usuario`(
in _ID VARCHAR(20) ,
in _Login varchar(150) ,
in _Nombre1 varchar(150) ,
in _Nombre2 varchar(150) ,
in _Apellido1 varchar(150) ,
in _Apellido2 varchar(150) ,
in _estado varchar(150) ,  
in _usr_registro  varchar(150) , 
in _pass  blob ,
in _change CHAR(1) ,
in _mail varchar(200))
BEGIN
declare _numusuario int;
declare _lastIsertId int;

declaRE _nombreCompleto  varchar(150) ;
declare _cod_remision varchar(3);
declare _cod_1 int;
declare _cod_2 int;
declare _cod_3 int;
DECLARE LT1 CHAR(1);
DECLARE LT2 CHAR(1);
DECLARE LT3 CHAR(1);

DECLARE CLAVE_OK CHAR(1);

SET CLAVE_OK = 1;

select FLOOR(RAND(now())*20) into  _cod_1;
select FLOOR(RAND( _cod_1 )*20) into _cod_2;
select FLOOR(RAND(_cod_2)*20) into _cod_3;




set _nombreCompleto = '';
if _Nombre1 != '' then
	set _nombreCompleto =  _Nombre1 ;
end if;

if _Nombre2 != '' then
set _nombreCompleto = CONCAT_WS(" ", trim(_nombreCompleto) ,trim(_Nombre2) );
end if;

if _Apellido1 != '' then
set _nombreCompleto = CONCAT_WS(" ", trim(_nombreCompleto) , trim(_Apellido1) );
end if;

if _Apellido2 != '' then
set _nombreCompleto = CONCAT_WS(" ", trim(_nombreCompleto) ,  trim(_Apellido2));
end if;
 


IF _ID is null or _ID = ''  THEN
  
	SELECT count(*) into _numusuario FROM  `usuarios` where Login = _Login;
 	IF _numusuario = 0 THEN   
    
     while CLAVE_OK > 0 do     
     
		  while _cod_3 > 0 and LT1 is  null do      
				SELECT letras INTO LT1 FROM letras ORDER BY rand() LIMIT 1 ;
				SET _cod_3 =  _cod_3 - 1;
		  end while;
		
		 while _cod_1 > 0 and LT2 is null  do      
				SELECT letras INTO LT2 FROM letras ORDER BY rand() LIMIT 1 ;
				SET _cod_1 =  _cod_1 - 1;
		  end while;
		  
		 while _cod_2 > 0 and LT3 is  null do      
				SELECT letras INTO LT3 FROM letras ORDER BY rand() LIMIT 1 ;
				SET _cod_2 =  _cod_2 - 1;
		  end while;
		-- select LT1,LT2,LT3;
		SET _cod_remision = CONCAT(LT1,LT2,LT3); 
       -- select _cod_remision;
        SELECT COUNT(*) INTO CLAVE_OK FROM usuarios WHERE convert(cod_remision using utf8 ) = convert(_cod_remision using utf8);  
      
    end while;
    -- select _cod_remision;
		INSERT INTO `usuarios`
		(`Login`,`Nombre1`,`Nombre2`,`Apellido1`,`Apellido2`,`nombreCompleto`,`estado`,`usr_registro`,`Fecha_Registro`,`pass`,cod_remision,mail)
		VALUES
		( _Login,_Nombre1,_Nombre2,_Apellido1,_Apellido2,_nombreCompleto,_estado,_usr_registro,now(),_pass,_cod_remision , _mail);
        
        set _lastIsertId = LAST_INSERT_ID();
        
       
        
        SELECT '100' as result;
	  
    ELSE
     	SELECT '-1' as result;
	END if;  
else

	SELECT count(*) into _numusuario FROM  `usuarios` where ID = _ID;
	IF _numusuario  >= 1 THEN 
    
		IF _change =  'P' THEN
           
		   UPDATE `usuarios`
					SET
					`Login` = _Login,
					`Nombre1` = _Nombre1,
					`Nombre2` = _Nombre2,
					`Apellido1` = _Apellido1,
					`Apellido2` = _Apellido2,
					`nombreCompleto` = _nombreCompleto,
					`estado` = _estado, 
					`Usr_Modif` = _usr_registro,
					`Fecha_Modif` = now(),
					`pass` = _pass ,
                    `change` = '0' ,
                    mail = _mail
					WHERE `ID` =  CAST(_ID AS UNSIGNED) ;  
			
            
		ELSE
				UPDATE `usuarios`
					SET
					`Login` = _Login,
					`Nombre1` = _Nombre1,
					`Nombre2` = _Nombre2,
					`Apellido1` = _Apellido1,
					`Apellido2` = _Apellido2,
					`nombreCompleto` = _nombreCompleto,
					`estado` = _estado, 
					`Usr_Modif` = _usr_registro,
					`Fecha_Modif` = now(), 
                    mail = _mail
					WHERE `ID` =  CAST(_ID AS UNSIGNED) ;    	 
        END IF;
				
        
        SELECT '101' as result;
	  
    ELSE
     	SELECT '-2' as result;
	END if;  
	
END if;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_relacion_centro_log_area_venta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_crear_relacion_centro_log_area_venta`(
_id_relacion varchar(15),
_VKORG varchar(4),
 _VTEXTVKORG varchar(45), 
 _VTWEG varchar(2), 
 _VTEXTVTWEG varchar(45), 
 _SPART varchar(2), 
 _VTEXTSPART varchar(45),
 _WERKS varchar(4),
 _NAME1 varchar(45),
 usuarioIngresado varchar(255)
 
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error en la base de datos' AS result LIMIT 1; 
	END;  
DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = usuarioIngresado;

if _id_relacion = '' then
	INSERT INTO `SAP_rfc_Areasdeventa_centros_log`
	(`VKORG`,`VTEXTVKORG`,`VTWEG`,`VTEXTVTWEG`,`SPART`,`VTEXTSPART`,`WERKS`,`NAME1`)
		VALUES(  _VKORG ,_VTEXTVKORG ,_VTWEG ,_VTEXTVTWEG ,_SPART ,_VTEXTSPART ,_WERKS ,_NAME1 ) ;
        	select '100' as result;
else 
	update `SAP_rfc_Areasdeventa_centros_log`
    set  `VKORG`= _VKORG,`VTEXTVKORG`= _VTEXTVKORG,`VTWEG`= _VTWEG,
    `VTEXTVTWEG`= _VTEXTVTWEG,`SPART`= _SPART,`VTEXTSPART`= _VTEXTSPART,
    `WERKS`= _WERKS,`NAME1`= _NAME1 ;
    	select '100' as result;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_relacion_user_perfil` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_crear_relacion_user_perfil`( 
in _idRelacion VARCHAR(12) ,
in _user_id VARCHAR(12) ,
in _perfil_id varchar(12),
IN _usuario VARCHAR(100) )
BEGIN
 set @usuarioIngresado = _usuario;   

IF _idRelacion is null or _idRelacion = ''  THEN

INSERT INTO  `relacion_user_perfiles`
( 
`user_id`,
`perfil_id`)
VALUES
( _user_id ,
_perfil_id);


	SELECT '100' as result;
else     
	UPDATE  `relacion_user_perfiles`
	SET
	`perfil_id` = _perfil_id 
	WHERE `idRelacion` = CAST(_idRelacion AS UNSIGNED) ;    
        
        SELECT '101' as result;
	   
	
END if;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_devolucion_prd_remision` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_devolucion_prd_remision`(IN _usuario VARCHAR(100))
BEGIN

DECLARE _ID_REG_REMISION INT;
DECLARE _CANT_INGRESA DECIMAL(16,2);

declare _id_remision int;
declare _id_orden_compra int;
declare  _cantidadVendida ,
 _presioSinIVa,
 _valorIVA,
 _presioVenta,
 _valorTotal decimal(16,2) ;

DECLARE recorrerParametrosEntrada CURSOR FOR 		
SELECT id_registro_int , id_registro_dec FROM `aux_tabla_parametros`
where  `cod_duenio_registros` = 'REMISION' ;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET @hecho = TRUE; 
 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN 
ROLLBACK; 
SELECT '-2'as _error;  
END; 


SET autocommit = 0;
START TRANSACTION;
    OPEN recorrerParametrosEntrada;
    
    loop1: LOOP 
    	FETCH recorrerParametrosEntrada into _ID_REG_REMISION , _CANT_INGRESA;
    
     IF @hecho THEN
		LEAVE loop1;
	END IF;
     
    
    SELECT 
    id_remision, 
    id_orden_compra into _id_remision , _id_orden_compra from remision_detalle
    WHERE  id_remision_detalle =  _ID_REG_REMISION;
 
	INSERT INTO remision_detalle (
     `id_remision`,
`id_orden_compra`,
`id_producto`,
`nombreProducto`,
`presioVenta`,
`porcent_iva`,
`presioSinIVa`,
`IVA`,
`cantidadVendida`,
`descuento`,
`valorTotal`,
`usuario`,
`fecha`,
`time`,
`maq_activa`,
`estado_remision`,
`cant_real_descontada`,
id_referencia
    
    
    ) SELECT 
    id_remision, 
    id_orden_compra, 
    id_producto, 
    nombreProducto, 
    presioVenta, 
    porcent_iva, 
    presioSinIVa, 
    IVA, 
    (_CANT_INGRESA * -1), 
    descuento,     
   ( _CANT_INGRESA * presioVenta  * -1 )  
    , 
    usuario, CURDATE(), CURTIME(), -- cantidadVendida
    maq_activa, 
    estado_remision, (
    (cant_real_descontada / cantidadVendida ) * (_CANT_INGRESA * -1)) , _ID_REG_REMISION from remision_detalle
    WHERE  id_remision_detalle =  _ID_REG_REMISION;

 
	END LOOP loop1;
                
	COMMIT;
    CLOSE recorrerParametrosEntrada;
    
    select 
sum(cantidadVendida) as cantidadVendida ,
SUM(CAST( ( presioSinIVa * cantidadVendida ) AS DECIMAL(16,2) ) ) as presioSinIVa,
sum(CAST( ( IVA * cantidadVendida ) AS DECIMAL(16,2) ) ) as IVA,
sum(presioVenta) as presioVenta,
sum(valorTotal) as valorTotal 
 FROM remision_detalle 
where id_remision = _id_remision and id_orden_compra = _id_orden_compra
group by 
id_orden_compra, id_remision into _cantidadVendida , _presioSinIVa ,_valorIVA ,_presioVenta , _valorTotal
 ;
 
 
update `remision_cabeza`
 SET
`cantidadVendida` = _cantidadVendida,
`valorParcial` = _presioSinIVa,
`valorIVA` = _valorIVA,
`valorTotal` = _valorTotal,
`fecha` = curdate(),
`hora` = CURTIME() ,
`usuario`  = _usuario     
where num_remision = _id_remision and orden_de_compra = _id_orden_compra;
    
    SET autocommit = 1;
        select '100' _error ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_eliminar_elemento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_eliminar_elemento`(in _user VARCHAR(150),in _TABLA VARCHAR(150),IN _DATO VARCHAR(150),IN _COLUMNA VARCHAR(150))
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error al intentar eliminar' AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END; 
	IF _TABLA = '' OR _TABLA IS NULL  then
		SELECT '-1' AS RESULT;
    ELSE
		IF _DATO = '' OR _DATO IS NULL  then
			SELECT '-2' AS RESULT;
		ELSE
			IF _COLUMNA = '' OR _COLUMNA IS NULL  then
				SELECT '-3' AS RESULT;
			ELSE
				set @usuarioIngresado = _user; 
				set @sqlExe = concat_ws(' ','delete from ',_TABLA,'WHERE',_COLUMNA,'=',concat('''',_DATO,''''));
				-- select @sqlExe ;
                PREPARE stmt1 FROM @sqlExe; 
                -- select @sqlExe;
				SET SQL_SAFE_UPDATES = 0;
                EXECUTE stmt1;                 
                select '100' as result;
			END IF; 
        END IF; 
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_facturar_orden_compra` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE  PROCEDURE `sp_facturar_orden_compra`( 
in _id_orden_compra int ,
IN _usuario VARCHAR(100) ,
in _pago_retefuente char,
in _porc_retefuente decimal(12,2),
in _tipo_venta varchar(100),
in _abonoInicial decimal(12,2),
in _numCuotas decimal(12,2),
in _intervalo decimal(12,2),
in _num_vauche varchar(100)
)
BEGIN
declare _retefuente decimal(12,2);
declare _grupo_remisiones  varchar(100) ;
declare _cod_orden_compra_externa varchar(100);
declare _valorTotal decimal(12,2);
declare _total_valores int;
declare _id_venta varchar(100); 
declare _cantidadVendida decimal(12,2);
declare _valorParcial decimal(12,2);
declare _descuento decimal(12,2);
declare _valorIVA decimal(12,2); 
declare _id_usuario_creador int;
declare _id_cliente_orden_compra varchar(100);
DECLARE _MODULO VARCHAR(3);
declare _idCartera varchar(10);

DECLARE _valorInicial decimal(12,2);
DECLARE _TotalInicial decimal(12,2);
DECLARE _valorCuota decimal(12,2);
DECLARE _TotalActual decimal(12,2);
 
 
START TRANSACTION;
SET autocommit=0;

SELECT cod_modulos FROM  mst_modulos 
where nom_modulo ='REMISIONES'
group by 1 INTO _MODULO;


call sp_asignar_consecutivo_factura(_usuario , _MODULO , @s_id_venta , @_id_venta);

 
 

select count(*)  FROM remision_detalle 
where  id_orden_compra = _id_orden_compra into _total_valores ;

if (_total_valores > 0 )THEN 

INSERT INTO  `ventastemp`
( 
`codMesa`,
`idVenta`,
`idProducto`,
`nombreProducto`,
`presioVenta`,
`porcent_iva`,
`presioSinIVa`,
`IVA`,
`cantidadVendida`,
`descuento`,
`valorTotal`,
`usuario`,
`fecha`,
`hora`,
`maq_activa`, 
`cant_real_descontada`)
select _MODULO,
@s_id_venta,
id_producto,
nombreProducto,
presioVenta, 
porcent_iva, 
presioSinIVa, 
IVA, 
cantidadVendida, 
descuento, 
valorTotal,  
_usuario, 
curdate(),
CURTIME(),
maq_activa, 
cant_real_descontada 
from remision_detalle 
where  id_orden_compra = _id_orden_compra  ;
 
SELECT 
group_concat(id_remision separator ' - ') remisiones ,oc.cod_orden_externa ,
oc.usuario as usuario_creador , oc.id_cliente 
 FROM remision_cabeza rc , remision_orden_de_compra oc where oc.id_orde = rc.orden_de_compra and orden_de_compra = _id_orden_compra 
 into _grupo_remisiones , _cod_orden_compra_externa,
 _id_usuario_creador, _id_cliente_orden_compra;

insert into ventacliente(
 idCliente, idVenta)values(_id_cliente_orden_compra ,  @s_id_venta  );


select 
sum(cantidadVendida),
sum(valorParcial),
sum(descuento),
sum(valorIVA),
sum(valorTotal) from remision_cabeza 
where orden_de_compra = _id_orden_compra  group by orden_de_compra
into _cantidadVendida ,
_valorParcial ,
_descuento  ,
_valorIVA ,
_valorTotal  ;

if _pago_retefuente = 'S' then
set _retefuente = _valorParcial * _porc_retefuente / 100;
else
set _retefuente = 0;
end if;

set _valorTotal = _valorTotal - _retefuente;

ALTER TABLE `ventas` DISABLE KEYS;
INSERT INTO  `ventas`
(`orden`,
`idVenta`,
`codMesa`,
`cantidadVendida`,
`valorParcial`,
`descuento`,
`valorIVA`,
`valorTotal`,
`fecha`,
`hora`,
`usuario`,  
`fecha_entrega`,
`porc_retefuente`,
`retefuente`,
`remisiones`,
`cod_orden_compra`,tipoDeVenta,idCierre)
values (@_id_venta ,@s_id_venta,_MODULO ,_cantidadVendida,_valorParcial,_descuento,_valorIVA,
_valorTotal,curdate(),curtime(),_usuario,  curdate(),_porc_retefuente,_retefuente,_grupo_remisiones ,
_id_orden_compra,_tipo_venta, '');

ALTER TABLE `ventas` ENABLE KEYS;

if _tipo_venta = 'CREDITO' then

 SELECT    ifnull(max(idCartera),0)  +1 as newId
FROM `cartera` WHERE `idCliente` = _id_cliente_orden_compra into _idCartera ;
 
set _valorInicial 	= _valorTotal - _abonoInicial ;
set _TotalInicial 	= _valorTotal ;
set _TotalActual 	= _valorInicial;
set _valorCuota 	=  _valorInicial / _numCuotas;
 

INSERT INTO `cartera` (  `idCuenta`, `descripcion`, `idCliente`, `nombre`, `fechaIngreso`,
 `valorInicial`, `abonoInicial`, `TotalInicial`, `numCuotas`, `intervalo`, `valorCuota`, 
 `TotalActual`,`refFact`,usuario_creador)values(
 concat(_id_cliente_orden_compra,'_',trim(_idCartera)) ,
 concat('CUENTA CREADA A PARTIR DE LA FACTURA # ',@s_id_venta,' de la orden de compra ',_id_orden_compra,' GENERADA EL DIA ',curdate()) ,
 _id_cliente_orden_compra ,
 (SELECT razonSocial FROM clientes where nit = _id_cliente_orden_compra limit 1 ),
 curdate(), 
 _valorInicial,_abonoInicial,_TotalInicial,_numCuotas,_intervalo,_valorCuota,_TotalActual,
 @s_id_venta,_usuario
 
 )
 
 ;

elseif _tipo_venta = 'ELECTRONICA' then
 
 
 INSERT INTO `bancos` (`id_deposito`,
			`provieneDe` ,
			`VALOR` ,
			`VAUCHE` ,
			`FECHA` ,
			`HORA` ,
			`DESCRIPCION` ,
			`IMANGEN`
			)
			VALUES (NULL ,  concat('VENTA # ',@s_id_venta),  _valorTotal,  _num_vauche,  CURDATE(),  CURTIME(),  concat('VENTA PAGADA POR DATAFONO  GENERADA EL DIA ',curdate()),  'imagenes/Sin_imangen.png');
 
 
 
 
 end if;

update remision_orden_de_compra set estado_orden = 4 ,
cod_factura = @s_id_venta
where  id_orde = _id_orden_compra
;
UPDATE consecutivos_factura SET ESTADO = 'INACTIVA' WHERE cod_factura = @_id_venta;

COMMIT;
SELECT '100' as result ,  @_id_venta as id_int_venta , @s_id_venta AS id_venta ; 
 
else  
	SELECT 'No existen datos para ingresar en la facturacion .' as result; 
end if;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_genera_fechas_pagos_compromisos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_genera_fechas_pagos_compromisos`(_cuotas int,_intervalo_pagos int,_fecha_obligacion date, _usuario varchar(100),_compromiso int , _tipo_compromiso char(3))
BEGIN
declare _contador int;
declare _fecha_pago date;
set _contador = 0;
set _fecha_pago = _fecha_obligacion;
while  _cuotas > _contador do 

set _contador = _contador +1;
set _fecha_pago = DATE_ADD( CAST(_fecha_pago AS char), INTERVAL  _intervalo_pagos DAY);
 
INSERT INTO  `fecha_cuotas_creditos`
( 
`usuario`,
`id_compromiso`,
`tipo_compromiso`,
`fecha_max_pago`,
`numero_cuota`)
VALUES
( 
_usuario,
_compromiso,
_tipo_compromiso,
_fecha_pago,
_contador);
end while;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_inserta_tabla_auditoria` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_inserta_tabla_auditoria`(
	IN `_usuario` VARCHAR(255) CHARSET utf8, 
    IN `_tabla` VARCHAR(255) CHARSET utf8, 
    IN `_campo` VARCHAR(255) CHARSET utf8, 
    IN `_valor_anterior` BLOB,
    IN `_valor_nuevo` BLOB,
    IN `_accion` VARCHAR(255) CHARSET utf8)
    NO SQL
    SQL SECURITY INVOKER
    COMMENT 'Inserta en sat_auditoria array IN'
BEGIN    
SET lc_time_names = 'es_MX';

 

INSERT INTO `auditoria`(`usuario`, `fecha_crea`, `tabla`, `campo`, `valor_anterior`, `valor_nuevo`, `accion`) VALUES (
   _usuario,
    NOW(),
    _tabla,
    _campo,
    _valor_anterior,
    _valor_nuevo,
    _accion);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_insert_medida_grupo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_insert_medida_grupo`( in _id_tipo_medida int ,
	in _id_grupo int, 
    in usuarioIngresado varchar(150))
BEGIN	
 declare exit handler for 1452
    begin
        select '-1' as result;
    end;

set @usuarioIngresado = usuarioIngresado;
delete from tipo_medida_grupo where id_tipo_medida= _id_tipo_medida and id_grupo = _id_grupo;
insert into tipo_medida_grupo(  id_tipo_medida, id_grupo ) values(_id_tipo_medida,_id_grupo);
select '100' as result;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_insert_recurso_perfil` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_insert_recurso_perfil`( in _id_perfil varchar(50),
	in _id_recurso varchar(50), 
    in usuarioIngresado varchar(150))
BEGIN	
 declare exit handler for 1452
    begin
        select '-1' as result;
    end;

set @usuarioIngresado = usuarioIngresado;
delete from perfil_recurso where id_perfil= _id_perfil and id_recurso = _id_recurso;
insert into perfil_recurso(  id_perfil, id_recurso ) values(_id_perfil,_id_recurso);
select '100' as result;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_procesar_detalle_ordenes_carga` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_procesar_detalle_ordenes_carga`(
  _id_cabecera int(11) ,
  _pedido int(11) ,
  _id_centro varchar(10)  ,
  _centro varchar(50)  ,
  _id_puesto_exp varchar(15),
  _puesto_exp varchar(50),
  _posicion int(11) ,
  _id_material int(11) ,
  _material varchar(50),
  _cantidad float,
  _cod_usuario_registro  int(11)  
)
BEGIN
DECLARE _RETORNO INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		delete from clientes_cabeza_remision where cod_remitente_conse = _id_cabecera;
        delete from clientes_detalle_remision where id_cabecera = _id_cabecera;
		select 'error al ingresar valores a la base de datos clientes_detalle_remision'  AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		delete from clientes_cabeza_remision where cod_remitente_conse = _id_cabecera;
        delete from clientes_detalle_remision where id_cabecera = _id_cabecera;

		select  WARNINGS  AS result LIMIT 1; 
	END;
set @usuarioIngresado = _cod_usuario_registro;
set _RETORNO = -1 ;
	INSERT INTO `clientes_detalle_remision` (`id_cabecera`,`pedido`,`id_centro`,`centro`,`id_puesto_exp`,`puesto_exp`,`posicion`,`id_material`,`material`,`cantidad`)
		VALUES( _id_cabecera ,_pedido ,_id_centro ,_centro ,_id_puesto_exp ,_puesto_exp ,_posicion ,_id_material ,_material ,_cantidad );
	set _RETORNO = 100 ;
    
	SELECT _RETORNO as result;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_procesar_ordenes_de_carga` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_procesar_ordenes_de_carga`( 
 _cod_cliente  varchar(10)   ,
  _cod_remitente_conse  int(10)  ,
  _ref_externa  varchar(50)   ,
  _cod_ciudad  varchar(45)   ,
  _ciudad_destino  varchar(45) ,
  _cod_destinataio  varchar(20)  ,
  _destinatario  varchar(45) ,
  _placa  varchar(6)  ,
  _cod_cedula  varchar(45)  ,
  _cedula  varchar(45)   ,
  _cod_transportador  varchar(45) ,
  _transportador  varchar(150)  ,
  _fecha_estimada  varchar(10), 
  _cod_usuario_registro  int(11)  ,
  _estado  varchar(2)  ,
  _cod_remision_sap  varchar(45)  ,
  _fecha_remisioin_sap  varchar(10),
  _proceso varchar(10)
  
)
BEGIN
-- COLLATE utf8_unicode_ci 
DECLARE _RETORNO INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error  al ingresar las cabecera de ordenes de carga a la base de datos -clientes_cabeza_remision :'  AS result LIMIT 1; 
	END; 
	DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END; 
set @usuarioIngresado = _cod_usuario_registro;
-- select _cod_cliente ,_ref_externa , _cod_ciudad ,_ciudad_destino ,_cod_destinataio ,_destinatario ,_placa ,_cod_cedula ,_cedula ,_cod_transportador ,_transportador ,_fecha_estimada ,NOW() ,_cod_usuario_registro , 'ER'  ;
 
case _proceso 
 when 'INICIO'
 THEN
 set _RETORNO = 0;
 -- select _cod_cliente ,_ref_externa , _cod_ciudad ,_ciudad_destino ,_cod_destinataio ,_destinatario ,_placa ,_cod_cedula ,_cedula ,_cod_transportador ,_transportador ,_fecha_estimada ,NOW() ,_cod_usuario_registro , 'ER'  ;

 INSERT INTO  `clientes_cabeza_remision`
(`cod_cliente`, 
`ref_externa`, 
`cod_ciudad`,
`ciudad_destino`,
`cod_destinataio`,
`destinatario`,
`placa`,
`cod_cedula`,
`cedula`,
`cod_transportador`,
`transportador`,
`fecha_estimada`,
`fecha_registro`,
`cod_usuario_registro`,
`estado` )
VALUES
(_cod_cliente ,_ref_externa , _cod_ciudad ,_ciudad_destino ,_cod_destinataio ,_destinatario ,_placa ,_cod_cedula ,_cedula ,_cod_transportador ,_transportador ,_fecha_estimada ,NOW() ,_cod_usuario_registro , 'PR'  );
 
-- select _cod_destinataio ,_destinatario ,_placa ,_cod_cedula ,_cedula ,_cod_transportador ,_transportador ,_fecha_estimada ,NOW() ,_cod_usuario_registro ;

SET _RETORNO = last_insert_id();

WHEN 'RECHAZAR'
THEN 
 set _RETORNO = 0;
	UPDATE clientes_cabeza_remision SET estado = 'RE' WHERE cod_remitente_conse = _cod_remitente_conse;
  set _RETORNO = 1;   
    
WHEN 'APROBAR'
THEN
set _RETORNO = 0;
 -- select 'llego',_cod_remitente_conse as '_cod_remitente_conse',_cod_remision_sap as '_cod_remision_sap';
 
	UPDATE clientes_cabeza_remision SET 
    estado = 'AR', 
    cod_remision_sap = _cod_remision_sap , 
    fecha_remisioin_sap = NOW() WHERE cod_remitente_conse = _cod_remitente_conse;
set _RETORNO = 1;   
end CASE;
 
SELECT _RETORNO as result;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_registro_ultimo_ingreso` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_registro_ultimo_ingreso`(in _id_usuario varchar(10))
BEGIN
	update usuarios set ultimo_ingreso = now() where ID = _id_usuario;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_verificar_usuarios_permisos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_verificar_usuarios_permisos`( in _nickname varchar(150), in _pass varchar(150))
BEGIN
	select * from usuarios where Login = _nickname  and pass = _pass ; 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `truncate_table` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `truncate_table`( in nomTable varchar(150))
BEGIN
	
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		select 'error en la base de datos' AS result LIMIT 1; 
	END; 
DECLARE EXIT HANDLER FOR SQLWARNING
	BEGIN
		select  WARNINGS  AS result LIMIT 1; 
	END;
	if nomTable = '' then
		select '-1' as result;
    else 
        set @sqlExe = concat_ws(' ','DELETE FROM ',nomTable);
				PREPARE stmt1 FROM @sqlExe; 
                -- select @sqlExe;
				SET SQL_SAFE_UPDATES = 0;
                EXECUTE stmt1;              
        
		select '100' as result;
	end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `updateFechaVentas` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE PROCEDURE `updateFechaVentas`(in _id_venta text ,in _fecha_venta date)
BEGIN
	UPDATE `ventastemp` SET  `fecha` =  _fecha_venta WHERE `idVenta` = _id_venta;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-01 16:15:06
