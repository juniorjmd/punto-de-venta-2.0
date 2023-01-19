<?php
include 'db_conection.php';
include 'inicioFunction.php';

echo 'no esta recibiendo '.$_POST[""];

if( isset($_POST["dataBase"])){
$mysqli = cargarBD(trim($_POST["dataBase"]),trim($_POST["host"]),trim($_POST["c14562jk"]),trim($_POST["er2345"]));}else{$mysqli = cargarBD();}


if( isset($_POST["tabla"])){
$tabla=$_POST["tabla"];}
$limit="";
$inicial=$_POST['inicio'];
$columnasRequeridas='';

$auxDR=$_POST["datosRequeridos"];
if (!isset($_POST["datosRequeridos"]))
{
	$auxDR = array(
		1 => 'concat',
		2 => 'nombre',
		3 => 'apellido',
		4 => 'fconcat',
		5 => 'id' 		
	);
}
if(is_null($auxDR)){
$columnasRequeridas='*';}else{
	$cout=0;
	$swcont = 0;
	//CONCAT_WS(" ",u.nombre,u.apellido) as nombre 
	foreach ($auxDR as $value) {
		$cout++;
		if($cout>1){$coma=" , ";}else{$coma=" ";}
		if ($value == 'concat'){ 
		$columnasRequeridas=$columnasRequeridas.'CONCAT_WS(" "';
		}
		else{
			if($value == 'fconcat')
		{$swcont++;
			$columnasRequeridas=$columnasRequeridas.') as concat'.$swcont;}
			else{$columnasRequeridas=$columnasRequeridas.$coma.$value;}}
	
	}
	}
//echo $columnasRequeridas;
$datos["where"]=$_POST['where'];
if(isset($_POST['where'])){
		if(isset($_POST["igual"])){
			$asignar="=";
			$porcent="";
			}else{$asignar="LIKE ";
			$porcent="%";}
		$primeBusqueda ="WHERE ".$_POST["columna1"].$asignar."'".$porcent.$_POST["dato"].$porcent."'";
		if(isset($_POST["tabla2"])){$segundaBusqueda=$_POST['conector'].' '.$_POST["tabla2"].$asignar."'".$porcent.$_POST["dato"].$porcent."'";}
		if(isset($_POST["tabla3"])){$terceraBusqueda=$_POST['conector'].' '.$_POST["tabla3"].$asignar."'".$porcent.$_POST["dato"].$porcent."'";}
}

if($inicial!="")
{	if($_POST['tamanioBloque']){$bloquePmetro=$_POST['tamanioBloque'];}
							else{$bloquePmetro='30';}
	$limit='LIMIT '.$inicial.' ,'.$bloquePmetro;
	$queryaux="select * from `".$tabla."`".$primeBusqueda.$segundaBusqueda.$terceraBusqueda.' ;';
//echo $query;
$result2 = $mysqli->query($queryaux);
$totalRegistrosDB=$mysqli->affected_rows;
$siguiente=$inicial+$bloquePmetro;
$anterior=$inicial-$bloquePmetro;
$ultimo=(verificaNumeroDeIntervalos($totalRegistrosDB,$bloquePmetro,NULL)-1)*$bloquePmetro;
if($anterior<0){$anterior=$ultimo;}
if($siguiente>$totalRegistrosDB){$siguiente=0;}
		$datos['anterior']=$anterior;
		$datos['siguiente']=$siguiente;
		$datos['ultimo']=$ultimo;
}


$datos["filasTotales"]=$totalRegistrosDB;


$query="select ".$columnasRequeridas." from `".$tabla."`".$primeBusqueda.$segundaBusqueda.$terceraBusqueda.$limit.' ;';
//echo $query;
$result = $mysqli->query($query);
$datos["filas"]=$mysqli->affected_rows;

$i=0;
while ($row = $result->fetch_assoc()) {
$data[$i] =$row;
$i++;
}

$datos["query"]=$query;
  $datos["datos"]=$data;
$result->free();
$mysqli->close();
echo json_encode($datos);
?>
