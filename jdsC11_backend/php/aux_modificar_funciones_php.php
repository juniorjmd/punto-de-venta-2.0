<?php
function agregarBusquedaCuentas($idBucador,$idRespuesta,$contenedor,array $opciones = null ){
	global $conn, $_POST; 
	$query2="SELECT * FROM vw_cnt_scuentas  WHERE nro_scuenta != cod_cuenta;;"; 
	$result = $conn->query($query2);
	$num_rows = 0;
	$num_rows = $conn->affected_rows;
	$divbusqueda = "";
	$clase = "";
        if(!empty($opciones) ){
            $clase = trim($opciones['clase']);

        }
	//idCliente, nit, nombre, razonSocial, direccion, telefono, email
            $SELECT_CLASE   = "<SELECT>" ;
            $SELECT_GRUPO   = "<SELECT>" ;
            $SELECT_CUENTA  = "<SELECT>" ;
            
            $SELECT_CLASE_NW   = "<SELECT>" ;
            $SELECT_GRUPO_NW   = "<SELECT>" ;
            $SELECT_CUENTA_NW  = "<SELECT>" ;
        if($num_rows>0){
	while ($row = $result->fetch_assoc()) {
		$count++;	
                /*
                nro_scuenta,  nombre_scuenta,
                 cod_clase, nombre_clase, 
                 cod_grupo, nombre_grupo, 
                 cod_cuenta, nombre_cuenta, 
                                  */
		$divbusqueda .='<tr style="cursor:pointer" class="filaBusquedaCNT"'
                        . "data-nro_scuenta='{$row["nro_scuenta"]}'"
                       . "data-nombre_scuenta='{$row["nombre_scuenta"]}'"
                        . "data-cod_clase='{$row["cod_clase"]}'"
                        . "data-nombre_clase='{$row["nombre_clase"]}'"
                        . "data-cod_grupo='{$row["cod_grupo"]}'"
                        . "data-nombre_grupo='{$row["nombre_grupo"]}'"
                        . "data-cod_cuenta='{$row["cod_cuenta"]}'"
                        . "data-nombre_cuenta='{$row["nombre_cuenta"]}'"
                        . ' id="td_CNT_'.$count.'">'
                        . '<td id="nombre_clase">'.$row["nombre_clase"].'</td>'
                        . '<td id="nombre_grupo">'.$row["nombre_grupo"].'</td>'
                        . '<td id="nombre_cuenta">'.$row["nombre_cuenta"].'</td>'
                        . '<td id="nro_scuenta">'.$row["nro_scuenta"].'</td>'
                        . '<td id="nombre_scuenta">'.$row["nombre_scuenta"].'</td>'
                        . '</tr>';
                
            $SELECT_CLASE_AUX   = "<option value='{$row["cod_clase"]}'>{$row["nombre_clase"]}<option>" ;
            $SELECT_GRUPO_AUX    = "<option value='{$row["cod_grupo"]}' data-clase='{$row["cod_clase"]}'>{$row["nombre_grupo"]}<option>" ;
            $SELECT_CUENTA_AUX   = "<option value='{$row["cod_cuenta"]}' data-clase='{$row["cod_clase"]}' data-grupo='{$row["cod_grupo"]}' >{$row["nombre_cuenta"]}<option>" ;
                        
                
        }
	}
           $SELECT_CLASE   .= "$SELECT_CLASE_AUX</SELECT>" ;
           $SELECT_GRUPO   .= "$SELECT_GRUPO_AUX</SELECT>" ;
           $SELECT_CUENTA  .= "</SELECT>" ;
            
            $SELECT_CLASE_NW   = "$SELECT_CLASE_AUX</SELECT>" ;
            $SELECT_GRUPO_NW   = "$SELECT_GRUPO_AUX</SELECT>" ;
            $SELECT_CUENTA_NW  = "$SELECT_CUENTA_AUX</SELECT>" ;
        
	echo '<table><tr><td valign="middle"><input type="hidden" id="existeListadoCnt"    value="no" />'
        . '<input type="hidden" id="noExitsCnt" name="noExitsCnt"  value="S" />'
        . '<input type="text" id="'.$idBucador.'" class = "'.$clase.'" name = "'.$idBucador.'"  />' 
                . '<td><input type="image" id="crearCuentaNueva" src="../../jds/imagenes/nuevo.jpg" style="width:35px" value="Crear"/></td>'
        . '<td><input type="image" id="buscarDatosCNT" src="../../jds/imagenes/cargarImagen.jpg" style="width:25px" value="Buscar"/></td>'
        . '</tr></table>';
        
	$divCrearNuevo ='';
	//cod_cuenta, nro_cuenta, modificar, nombre_cuenta
        /*echo '<input type="hidden" id="HtipPersonaNueva" name="tipPersonaNueva"  /> '.
	' <input type="hidden" id="HNewNit" 	name="NewNit" />'.
	' <input type="hidden" id="HNewName" 	name="NewName" />'.
	' <input type="hidden" id="HNewRSoc" 	name="NewRSoc" />'.
	' <input type="hidden" id="HNewDir" 	name="NewDir" />';*/
        echo 
	' <input type="hidden" id="HNew_cod_cuenta" 	name="HNew_cod_cuenta" />'.
	' <input type="hidden" id="HNew_nro_cuenta" 	name="HNew_nro_cuenta" />'. 
	' <input type="hidden" id="HNew_nombre_cuenta" 	name="HNew_nombre_cuenta" />';
        
        
	$divCrearNuevo ='<div id="crearSubCuenta" style=" display:none;  margin-right: auto;margin-left: auto; top: 50px; width: 60%; height:500px; ">'.
	'<table border="1px" style=" width:100%; border:thin #069; font-family:Consolas, "Andale Mono", "Lucida Console","Lucida Sans Typewriter", Monaco, "Courier New", monospace">'.
	'<tr><td colspan="2" align="center" valign="middle"><h3>CREAR NUEVA SUBCUENTA.</h3><input type="button" value="cerrar" id="cerrarCrearCNT"/></td></tr>'. 
	"<tr> <td>CLASE</td><td>$SELECT_CLASE_NW</td></tr>".
	"<tr> <td>GRUPO</td><td>$SELECT_GRUPO_NW</td> </tr>".
	"<tr> <td>CUENTA</td><td>$SELECT_CUENTA_NW</td> </tr>".
	'<tr> <td>DIGITO SUBCUENTA</td><td><input tipe="text" id="NewRSoc" style="width:350px;"/></td> </tr>'.
	'<tr> <td>CODIGO SUBCUENTA/td><td><input tipe="text" id="NewDir"  style="width:350px;"/></td> </tr>'.
	'<tr> <td colspan="2" align="center"><input type="button" value="aceptar" id="aceptarCrearSCUENTA"/></td> </tr></table></div>';
 
	
	$divbusqueda= "<div id='listarSubCuenta' style=' display:none;  margin-right: auto;margin-left: auto; top: 10px; width: 80%; height:500px;overflow: scroll; '>".
	"<table border='1px' style=' width:100%; border:thin #069; font-family:Consolas, 'Andale Mono', 'Lucida Console','Lucida Sans Typewriter', Monaco, 'Courier New', monospace'>".
	"<tr><td colspan='4' align='center' valign='middle'><h3>BUSQUEDA DE SUBCUENTA.</h3></td></tr>".
	"<tr><td colspan='4' align='center'>"
          . "<table><tr><td>Clase</td><td>$SELECT_CLASE</td><td>Grupo</td><td>$SELECT_GRUPO</td><td>Cuenta</td><td>$SELECT_CUENTA</td></tr>"
          . "<tr><td>Buscar:</td><td><input tipe='text' id='textBusquedaSCuenta' style='width:350px;'/></td>"
          . "<td align='center'><input type='image' src='".URL_BASE."jds/imagenes/limpiar_nuevo.png' value='Clean' style='width:40px' id='cleanBusqueda'/></td><td align='center'><input type='button' value='X' id='cerrarBusqueda'/></td></tr>"
          ."</table>"
          . "</td></tr><tr> <td>NIT</td><td>Nombre</td><td>Razon Social</td>".
	"<td>direccion</td></tr>".$divbusqueda;
	$count = 0;
	
	$divbusqueda .='</table></div>';
        
        echo '<script>'
        . '$("#ContBusquedaPersonas").html('."'".$divbusqueda.$divCrearNuevo."'".');'
        . '$("#buscarDatosCNT").click(function(event){
		event.preventDefault();
		$("#listarSubCuenta").fadeIn( "slow", function() {
				// Animation complete
			});
		$("#'.$contenedor.'").hide()
	})'
        .''
        . '</script>';

}





