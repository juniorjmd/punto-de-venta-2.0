DELIMITER $$
CREATE   PROCEDURE `sp_facturar_orden_compra`( 
in _id_orden_compra int ,
IN _USUARIO VARCHAR(100) ,
in _pago_retefuente char,
in _porc_retefuente decimal(12,2),
in _tipo_venta varchar(100),
in _abonoInicial decimal(12,2),
in _numCuotas decimal(12,2),
in _intervalo decimal(12,2),
in _num_vauche varchar(100),
in _nit_sucursal VARCHAR(45) 
)
BEGIN
declare _retefuente decimal(12,2);
declare _idCompraGenerada decimal(12,2);
declare _idCreditoGenerado decimal(12,2);
declare _grupo_remisiones  varchar(100) ;
declare _cod_orden_compra_externa char;
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
declare _idCartera varchar(3);

DECLARE _valorInicial decimal(12,2);
DECLARE _TotalInicial decimal(12,2);
DECLARE _valorCuota decimal(12,2);
DECLARE _TotalActual decimal(12,2);
declare _es_sucursal int; 
declare _nom_database varchar(45);
 
START TRANSACTION;
SET autocommit=0;
SET SQL_SAFE_UPDATES = 0;
SELECT cod_modulos FROM mst_modulos 
where nom_modulo ='REMISIONES'
group by 1 INTO _MODULO;


call sp_asignar_consecutivo_factura(_USUARIO , _MODULO , @s_id_venta , @_id_venta);

 
 

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
_USUARIO, 
curdate(),
CURTIME(),
maq_activa, 
cant_real_descontada 
from remision_detalle 
where  id_orden_compra = _id_orden_compra  ;
 
SELECT 
group_concat(id_remision separator ' - ') remisiones ,oc.cod_orden_externa ,
oc.usuario as usuario_creador , oc.id_cliente 
 FROM remision_cabeza rc , remision_orden_de_compra oc 
 where oc.id_orde = rc.orden_de_compra and orden_de_compra = _id_orden_compra 
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
set _retefuente = _valorTotal * _porc_retefuente / 100;
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
`cod_orden_compra`,tipoDeVenta)
values (@_id_venta ,@s_id_venta,_MODULO ,_cantidadVendida,_valorParcial,_descuento,_valorIVA,
_valorTotal,curdate(),curtime(),_USUARIO,  curdate(),_porc_retefuente,_retefuente,_grupo_remisiones ,
_id_orden_compra,_tipo_venta);

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
 concat(_id_cliente_orden_compra,'_',_idCartera) ,
 concat('CUENTA CREADA A PARTIR DE LA FACTURA # ',@s_id_venta,' de la orden de compra ',_id_orden_compra,' GENERADA EL DIA ',curdate()) ,
 _id_cliente_orden_compra ,
 (SELECT razonSocial FROM vw_clientes where nit = _id_cliente_orden_compra limit 1 ),
 curdate(), 
 _valorInicial,_abonoInicial,_TotalInicial,_numCuotas,_intervalo,_valorCuota,_TotalActual,
 @s_id_venta,_USUARIO
 
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

 
select count(0) into _es_sucursal from sucursales where  CONCAT(`sucursales`.`nit_sucursal`,
                '_s',TRIM(`sucursales`.`id_suc`)) = _id_cliente_orden_compra ; 

if _es_sucursal > 0 then
  select 'es una sucursal'; 
  
  select nom_database into _nom_database from sucursales 
  where  CONCAT(`sucursales`.`nit_sucursal`,
                '_s',TRIM(`sucursales`.`id_suc`)) = _id_cliente_orden_compra ; 
  
set @sql_aux = concat('select count(*) into @existeProveedor from ',trim(_nom_database),'.proveedores where nit = ''' , _nit_sucursal,''' ')  ;

set @existeProveedor = 123454;
-- select @existeProveedor;
SET @SQL := @sql_aux;
        PREPARE stmt FROM @SQL;
        execute stmt;
        DEALLOCATE PREPARE stmt; 
   if  @existeProveedor = 0 then
	set @sql_aux = concat(  'insert into ',trim(_nom_database),'.proveedores ( nit, nombre, razonSocial, direccion, telefono, email ) 
select nit_sucursal ,   concat (trim(nombre_suc) , ''-'', trim(nombre_sucursal_sec )),
concat (trim(nombre_suc) , ''-'', trim(nombre_sucursal_sec )),concat (trim( dir ) , ''-'',trim(ciudad )),
 tel1,  mail from sucursales where descripcion = ''PRINCIPAL'' ') ;
     
	SET @SQL := @sql_aux;
    PREPARE stmt FROM @SQL;
    execute stmt;
    DEALLOCATE PREPARE stmt; 
  end if;        
  
  -- insertamos los productos que no existan
 set @sql_aux = concat( 'insert IGNORE into  ',trim(_nom_database),'.producto (IDLINEA, idProducto, idGrupo, Grupo, idLab, laboratorio, nombre, precioVenta, PsIVA, IVA, porcent_iva, precioCompra, cantInicial, cantActual, compras, ventas, devoluciones, stock, imagen, barcode, descripcion, subgrupo_1, nom_subgrupo_1, subgrupo_2, nom_subgrupo_2, tipo_producto, remisionada, fecha_creacion)
select b.IDLINEA, b.idProducto, b.idGrupo, b.Grupo, b.idLab, b.laboratorio, b.nombre, ( b.precioVenta + (b.precioVenta * 0.1) )
 , ( b.precioVenta + (b.precioVenta * 0.1) ) / concat("1.",LPAD(b.porcent_iva,2,"0")),
 ( b.precioVenta + (b.precioVenta * 0.1) ) - ( ( b.precioVenta + (b.precioVenta * 0.1) ) / concat("1.",LPAD(b.porcent_iva,2,"0")))
  , b.porcent_iva, b.precioVenta,0, 0, 0,0,0, b.stock, b.imagen, b.barcode, b.descripcion, b.subgrupo_1, b.nom_subgrupo_1, b.subgrupo_2, b.nom_subgrupo_2, b.tipo_producto, 0 , now()
from producto b where b.idProducto in (
  select id_producto from remision_detalle 
where  id_orden_compra = ',_id_orden_compra ,')') ;
   select @sql_aux;  
	SET @SQL := @sql_aux;
    PREPARE stmt FROM @SQL;
    execute stmt;
    DEALLOCATE PREPARE stmt; 
   
-- insertamos la compra y obtenemos el id de la compra
IF EXISTS (select Table_Name from information_schema.TABLES where Table_Name='ingresos' and TABLE_SCHEMA=_nom_database) then
 select 'existe la tabla';
else 
 select 'no existe la tabla';
end if;

set @sql_aux = concat( 'insert into ',trim(_nom_database),'.compras (codPov, cantidadVendida, valorParcial, descuento, t_iva, valorTotal, fecha, usuario ,idCierre) 
values( ''',_nit_sucursal,''', 1,1, 0, 0, 1, curdate(), "',_USUARIO,'" , '''');') ;
   select @sql_aux;  
	SET @SQL := @sql_aux;
    PREPARE stmt FROM @SQL;
    execute stmt;
    DEALLOCATE PREPARE stmt; 
select LAST_INSERT_ID() into _idCompraGenerada ;  
 select _idCompraGenerada ;
/*
declare _idCompraGenerada decimal(12,2);
declare _idCreditoGenerado decimal(12,2);*/   

 /*
set _valorInicial 	= _valorTotal - _abonoInicial ;
set _TotalInicial 	= _valorTotal ;
set _TotalActual 	= _valorInicial;
set _valorCuota 	=  _valorInicial / _numCuotas;
 

INSERT INTO `cartera` (  `idCuenta`, `descripcion`, `idCliente`, `nombre`, `fechaIngreso`,
 `valorInicial`, `abonoInicial`, `TotalInicial`, `numCuotas`, `intervalo`, `valorCuota`, 
 `TotalActual`,`refFact`,usuario_creador)values(
 concat(_id_cliente_orden_compra,'_',_idCartera) ,
 concat('CUENTA CREADA A PARTIR DE LA FACTURA # ',@s_id_venta,' de la orden de compra ',_id_orden_compra,' GENERADA EL DIA ',curdate()) ,
 _id_cliente_orden_compra ,
 (SELECT razonSocial FROM vw_clientes where nit = _id_cliente_orden_compra limit 1 ),
 curdate(), 
 _valorInicial,_abonoInicial,_TotalInicial,_numCuotas,_intervalo,_valorCuota,_TotalActual,
 @s_id_venta,_USUARIO );*/
  
end if; 
 

COMMIT;
SELECT '100' as result ,  @_id_venta as id_int_venta , @s_id_venta AS id_venta , _id_cliente_orden_compra ; 

 
 
else  
	SELECT 'No existen datos para ingresar en la facturacion .' as result; 
end if;



END$$
DELIMITER ;
