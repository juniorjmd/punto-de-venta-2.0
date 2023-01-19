<?php 




function validaKey($_llaveSession){
    $_NUMERO_RESPUESTA = 200 ;
          if (isset($_llaveSession)){
        
                TRY {
       $conexion =Class_php\DataBase::getInstance(); 
       //($tabla,$where = null)
       $where = "where `key` = '$_llaveSession' ";
               $_result =$conexion->where('vw_session', $where);
      /*$array['datos'] =  $consulta->fetchAll();
         $array['query'] */
      $datos['data']= $_result['datos'];
      $datos['query']= $_result['query'];
            if(sizeof($_result['datos'])>0){
                $_nombreSession = '';
                $_estado = '';
                foreach ($_result['datos'] as $key => $value) {
                    $_nombreSession = $value['nombre'];
                    $_estado =   $value['estado'];;
                    } 
                //session_name($_nombreSession); 
                //session_start();  
                
                if ($_SESSION['access'][$_nombreSession] === false){ 
                     $_NUMERO_RESPUESTA = 502 ;
                      $datos['error']= 'La llave de session pertenece a una session diferente a la actual.';
                }else{
                    if ($_estado == 'A') {
                       $datos['error']= 'llave session correcta, session actualmente activa.';
                    }else{ 
                       $_NUMERO_RESPUESTA = 502 ;
                      $datos['error']= 'La session ha expirado.'; 
                      $_SESSION['access'][$_nombreSession] = false;   
                    }
                }
                    
            }else{ 
                $_NUMERO_RESPUESTA = 501 ;
               $datos['error']= 'La llave de session no es valida';
            }
      
       }
      catch (PDOException $e) {
          $_NUMERO_RESPUESTA = 500 ;
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
          }
          else{
             $_NUMERO_RESPUESTA = 500 ;
              $datos['error']= 'Debe enviar una llave de session';
          }
     $datos['HTTP_COD_RESPONSE']  =    $_NUMERO_RESPUESTA   ;
    return $datos ;      
}
function obtenerPermisosold($id ){ 
    
         
       $conexion =Class_php\DataBase::getInstance(); 
       $link = $conexion->getLink();
       $consulta = $link->prepare("Select * from vw_perfil_recurso where id_perfil = :_id_perfil");
        $consulta->bindParam(':_id_perfil', $id);
        
         if ( $consulta->execute()){
             echo 'ejecuto';
         }else{
             echo 'no ejecuto';}
         $_resultPermisos =  $consulta->fetchAll(); 
      
         print_r($_resultPermisos);
         $_RecursoDetalle = array();
         $_permisos = array();
         if (sizeof($_resultPermisos['datos']) > 0){ 
           //  $where = " where `id` =  '{$_idCaja}' ";
           //idperfil_recurso, id_perfil, idRecurso, nombre_recurso, estado, img, idtipo, tipo, arrayDir  
            foreach ($_resultPermisos['datos'] as $key => $value) {
               $_RecursoDetalle =  array( 
                   "id" => $value['idRecurso'] ,
                   "nombre_recurso" => $value['nombre_recurso'] ,
                   "img" => $value['img'] ,
                   "idtipo" => $value['idtipo'] ,
                   "tipo" => $value['tipo'] ,
                   "estado" => $value['estado'] ,
                   "direccion" => explode (',' , $value['arrayDir']) ) ;
               array_push($_permisos , array(
                   "id" => $value['idperfil_recurso'] ,
                   "id_perfil" => $value['id_perfil'] ,
                   "recurso" => $_RecursoDetalle
               ));
               
            }
            


}
return $_permisos;

            }
function obtenerPermisos($_id){
     TRY {
       $conexion =Class_php\DataBase::getInstance();  
       $where = " where id_perfil = '$_id' ";
       $_result =$conexion->where('vw_perfil_recurso', $where);
      /*$array['datos'] =  $consulta->fetchAll();
         $array['query'] */
      $datos['data']= $_result['datos'];
      $datos['query']= $_result['query'];
      print_r($_result);
        if(sizeof($_result['datos'])>0){}

        }  
      catch (PDOException $e) {
          $_NUMERO_RESPUESTA = 500 ;
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
}
function guardarNuevaPersona(){
	global $conn , $_POST; 
	if (isset($_POST['noExits']) && $_POST['noExits'] == "N"){ 
		 $tabla = $_POST['tipPersonaNueva'];
		 $idCiente = 0;
		 //idCliente, nit, nombre, razonSocial, direccion, telefono, email
		 
		 "select * from ´".$tabla."´";
		 $filas = $result->num_rows;
 if ($filas>0){
while ($row = $result->fetch_row()) {
    $filas=  $row[0];
	   //printf("%s\n",$filas);
    }
 }
		 $idCiente=$filas+1;
		 $query="insert into ".$tabla."(idCliente,nit, nombre, razonSocial, direccion) values('".rellenarTruncar($idCiente,10,'','I',false)."','".$_POST['NewNit']."','".$_POST['NewName']."','".$_POST['NewRSoc']."','".$_POST['NewDir']."')";
		$stmt = $conn->stmt_init();
		//echo $query;
		$stmt->prepare($query);
		if(!$stmt->execute()){
		//Si no se puede insertar mandamos una excepcion
		throw new Exception('No se pudo insertar:' . $stmt->error);
		}else{ }	
	}
}
function guardarNuevaCuenta(){
	global $conn , $_POST; 

	if (isset($_POST['noExitsCnt']) && $_POST['noExitsCnt'] == "N"){ 
           
            $nuevaCuenta = array();
            $nuevaCuenta = $_POST['nuevaCuenta'];
            foreach ($nuevaCuenta as $llave => $valor) {
                 $cod_cuenta = $valor['cuenta'];
                 $nro_cuenta = $valor['subcuenta'];
                 $nombre_cuenta = $valor['nombresub'];
		 "select * from ´cnt_cuentas´ where nro_cuenta = $nro_cuenta ";
		 $filas = $result->num_rows;
            if ($filas== 0){ 
		 $query="insert into cnt_cuentas(cod_cuenta, nro_cuenta, modificar, nombre_cuenta) values "
                         . "('{$cod_cuenta}' ,'{$nro_cuenta}' ,'S' ,'{$nombre_cuenta}'    )";
                $stmt = $conn->stmt_init();
		//echo $query;
		$stmt->prepare($query);
		if(!$stmt->execute()){
		//Si no se puede insertar mandamos una excepcion
		throw new Exception('No se pudo insertar:' . $stmt->error);
		}else{ }
                }
            }
          
		 	
	}
}
function agregarBusquedaPersonas($idRespuesta,$contenedor,array $opciones = null ){
	global $conn, $_POST;
	guardarNuevaPersona();
	$query2="SELECT * FROM proveedores union SELECT * FROM clientes;"; 
	$result = $conn->query($query2);
	$num_rows = 0;
	$num_rows = $conn->affected_rows;
	$divbusqueda = "";
	$clase = "";
        if(!empty($opciones) ){
            $clase = trim($opciones['clase']);

        }
        
	//idCliente, nit, nombre, razonSocial, direccion, telefono, email
	echo '<table><tr><td valign="middle"><input type="hidden" id="existeListado"    value="no" /><input type="hidden" id="noExits" name="noExits"  value="S" /><input type="text" id="nitBusqueda" class = "'.$clase.'" name = "nitBusqueda"   /></td><td><input type="image" id="crearPersonaNueva" src="'.URL_BASE.'jds/imagenes/nuevo.jpg" style="width:35px" value="Crear"/></td><td><input type="image" id="buscarDatos" src="'.URL_BASE.'jds/imagenes/cargarImagen.jpg" style="width:25px" value="Buscar"/></td></tr></table>';
	echo '<input type="hidden" id="HtipPersonaNueva" name="tipPersonaNueva"  /> '.
	' <input type="hidden" id="HNewNit" 	name="NewNit" />'.
	' <input type="hidden" id="HNewName" 	name="NewName" />'.
	' <input type="hidden" id="HNewRSoc" 	name="NewRSoc" />'.
	' <input type="hidden" id="HNewDir" 	name="NewDir" />';
	$divCrearNuevo ='<div id="crearPersonas" style=" display:none;  margin-right: auto;margin-left: auto; top: 50px; width: 60%; height:500px; ">'.
	'<table border="1px" style=" width:100%; border:thin #069; font-family:Consolas, "Andale Mono", "Lucida Console","Lucida Sans Typewriter", Monaco, "Courier New", monospace">'.
	'<tr><td colspan="2" align="center" valign="middle"><h3>CREAR PERSONA.</h3><input type="button" value="cerrar" id="cerrarCrear"/></td></tr>'. 
	'<tr> <td>Tipo persona</td><td><select id="tipPersonaNueva" ><option value="">SELECCIONE UN TIPO</option><option  value="proveedores">PROVEDOR</option><option  value="clientes">CLIENTE</option></select></td> </tr>'.
	'<tr> <td>NIT</td><td><input tipe="text" id="NewNit"   style="width:350px;"/></td> </tr>'.
	'<tr> <td>Nombre</td><td><input tipe="text" id="NewName" style="width:350px;"/></td> </tr>'.
	'<tr> <td>Razon Social</td><td><input tipe="text" id="NewRSoc" style="width:350px;"/></td> </tr>'.
	'<tr> <td>direccion</td><td><input tipe="text" id="NewDir" style="width:350px;"/></td> </tr>'.
	'<tr> <td colspan="2" align="center"><input type="button" value="aceptar" id="aceptarCrear"/></td> </tr></table></div>';
	
	
	$divbusqueda .='<div id="listaPersonas" style=" display:none;  margin-right: auto;margin-left: auto; top: 10px; width: 80%; height:500px;overflow: scroll; ">'.
	'<table border="1px" style=" width:100%; border:thin #069; font-family:Consolas, "Andale Mono", "Lucida Console","Lucida Sans Typewriter", Monaco, "Courier New", monospace">'.
	'<tr><td colspan="4" align="center" valign="middle"><h3>BUSQUEDA DE PERSONA.</h3></td></tr>'.
	'<tr><td colspan="4" align="center"><table><td>Buscar:</td><td><input tipe="text" id="textBusqueda" style="width:350px;"/></td>'.
	'<td align="center"><input type="image" src="'.URL_BASE.'jds/imagenes/limpiar_nuevo.png" value="Clean" style="width:40px" id="cleanBusqueda"/></td><td align="center"><input type="button" value="X" id="cerrarBusqueda"/></td></table></td></tr><tr> <td>NIT</td><td>Nombre</td><td>Razon Social</td>'.
	'<td>direccion</td></tr>';
	$count = 0;
	if($num_rows>0){
	while ($row = $result->fetch_assoc()) {
		$count++;		
		$divbusqueda .='<tr style="cursor:pointer" class="filaBusqueda" id="td_'.$count.'"><td id="nit">'.$row["nit"].'</td><td id="nombre">'.$row["nombre"].'</td><td id="razonSocial">'.$row["razonSocial"].'</td><td id="direccion">'.$row["direccion"].'</td></tr>';
	}
	}
	$divbusqueda .='</table></div>';
	echo'<script>  
	$("#ContBusquedaPersonas").html('."'".$divbusqueda.$divCrearNuevo."'".');
	
	$("#ContBusquedaPersonas").find("input").click(function(event){
    event.preventDefault();
});
//---------------------------------------------
	
	$("#NewName").keyup(function(){
		$("#NewRSoc").val($(this).val() ) 
	})
	$("#cerrarCrear").click(function(){
		$("#noExits").val("S")
		$("#crearPersonas").hide()
		$("#nitBusqueda").val("")
		$("#'.$contenedor.'").fadeIn( "slow", function() {
				// Animation complete
			});	
	});
	$("#NewNit").keyup(function(){
		var resp1 = "";
		var resp2 = ""; 
		var resp3 = "";
		var encontrar = false;
		var nit = $(this).val()
		if (nit != ""){
		$(".filaBusqueda").each(function(){
			//$(this).find("#nombre").html()
			resp3 = $(this).find("#nit").html(); 
			if( (resp3 == nit ) && !encontrar){
				 console.log("entro")
				resp1 = $(this).find("#nombre").html()
				resp2 = $(this).find("#nit").html();
				encontrar = true;				
			}			
		});
		if (encontrar){
			$("#existeListado").val("si")
		}else {$("#existeListado").val("no")}}
	}) 
	$("#aceptarCrear").click(function(){
		if ($("#existeListado").val() == "no"){
			if ($("#NewNit").val() != ""){
				if ($("#NewName").val() != ""){ 
					if ($("#NewRSoc").val() != ""){
							$("#HtipPersonaNueva").val($("#tipPersonaNueva").val() )
							$("#HNewNit").val($("#NewNit").val() ) 
							$("#HNewName").val($("#NewName").val() ) 
							$("#HNewRSoc").val($("#NewRSoc").val() ) 
							$("#HNewDir").val($("#NewDir").val())
							$("#noExits").val("N")
							$("#nitBusqueda").val($("#NewNit").val())
							$("#'.$idRespuesta.'").val($("#NewRSoc").val())
							$("#crearPersonas").hide()
							$("#'.$contenedor.'").fadeIn( "slow", function() {});	
					} else{alert("la razon social no debe estar en blanco");$("#NewRSoc").focus()}
				}else{alert("El nombre no debe estar en blanco");$("#NewName").focus()}
			}else{alert("El NIT no debe estar en blanco");$("#NewNit").focus()}
		}else{alert("El NIT no debe estar en el listado ");$("#NewNit").focus()}
	});
	
//-------------------------------------------------



	$("#buscarDatos").click(function(event){
		event.preventDefault();
		$("#listaPersonas").fadeIn( "slow", function() {
				// Animation complete
			});
		$("#'.$contenedor.'").hide()
	})
	$("#cerrarBusqueda").click(function(){$("#listaPersonas").hide()
	$("#'.$contenedor.'").fadeIn( "slow", function() {
				// Animation complete
			});
	})
	$(document).keydown(function(e){
		var tecla=e.keyCode;
		if(tecla==13){e.preventDefault();
		}
	})
	$("#cleanBusqueda").click(function(event){
		event.preventDefault(); 
		$("#textBusqueda").val("");
		$("#textBusqueda").trigger("keydown")
	});
	$("#textBusqueda").keydown(function(){
		var busqueda = $(this).val();
		var resp2 = ""; 
		var resp3 = ""; 
		if ( busqueda != ""){
		$(".filaBusqueda").each(function(){ 
			$(this).hide()
			resp3 = $(this).find("#nit").html();
			resp2 = $(this).find("#nombre").html() 
			if( (resp3.indexOf(busqueda) != -1  ) || (resp2.indexOf(busqueda) != -1 )  ){
				 console.log("entro")
				$(this).show()
				 				
			}			
		});}else{$(".filaBusqueda").show()}
		 
	});
	$("#nitBusqueda").keydown(function(e){
		
		var tecla=e.keyCode;
		$("#'.$idRespuesta.'").val("")
		if(tecla==13){
			busquedaPersona($("#nitBusqueda").val(),"'.$idRespuesta.'");
			
		}
	});
	$(".filaBusqueda").click(function(){
		//alert("hola papi"+$(this).find("#nombre").html())
		$("#'.$idRespuesta.'").val($(this).find("#nombre").html())
		$("#nitBusqueda").val($(this).find("#nit").html());
		$("#cerrarBusqueda").trigger("click");
		
	})
	 
	$("#crearPersonaNueva").click(function(e){//crearPersonas
	e.preventDefault();
	$("#NewNit").val($("#nitBusqueda").val()).trigger("keyup")
	$("#crearPersonas").fadeIn( "slow", function() {
				// Animation complete
			});
		$("#'.$contenedor.'").hide()	
	})
	function busquedaPersona(nit , id ){
		var resp1 = "";
		var resp2 = ""; 
		var resp3 = "";
		var encontrar = false;
		if (nit != ""){
		$(".filaBusqueda").each(function(){
			//$(this).find("#nombre").html()
			resp3 = $(this).find("#nit").html(); 
			if( (resp3 == nit ) && !encontrar){
				 console.log("entro")
				resp1 = $(this).find("#nombre").html()
				resp2 = $(this).find("#nit").html();
				encontrar = true;				
			}			
		});
		if (encontrar){ $("#"+id).val(resp1)
				$("#noExits").val("S")
		}else{
			alert("el nit que ingreso no se encuentra registrado, para ingresarlo en la lista de proveedores o clientes, presione NEW")
			
			$("#"+id).val("")
		}
		}else{
			$("#"+id).val("")
		}
		
		 
	}
	</script>';
}
        
function agregarBusquedaCuentas($idBucador,$idRespuesta,$contenedor,array $opciones = null ){
	global $conn, $_POST; 
	$query2="SELECT * FROM vw_cnt_scuentas  WHERE nro_scuenta != cod_cuenta;"; 
	$result = $conn->query($query2);
	$num_rows = 0;
	$num_rows = $conn->affected_rows;
	$divbusqueda = "";
        $filasBusquedas = '';
	$clase = "";
        if(!empty($opciones) ){
            $clase = trim($opciones['clase']);

        }
	//idCliente, nit, nombre, razonSocial, direccion, telefono, email
        
            $SELECT_CLASE   = "<SELECT style=' MAX-WIDTH: 200px;' id='clase_sch'>" ;
            $SELECT_GRUPO   = "<SELECT style=' MAX-WIDTH: 200px;' id='grupo_sch'>" ;
            $SELECT_CUENTA  = "<SELECT style=' MAX-WIDTH: 200px;' id='cuenta_sch'>" ;
            
            $SELECT_CLASE_NW   = "<SELECT style=' MAX-WIDTH: 200px;' name='nuevaClase' id='clase_new'>" ;
            $SELECT_GRUPO_NW   = "<SELECT style=' MAX-WIDTH: 200px;' name='nuevaGrupo' id='grupo_new'>" ;
            $SELECT_CUENTA_NW  = "<SELECT style=' MAX-WIDTH: 200px;' name='nuevaCuenta' id='cuenta_new'>" ;
             $SELECT_CLASE_AUX  ='';
            $SELECT_GRUPO_AUX    ='';
            $SELECT_CUENTA_AUX  ='';
            $GRUPO ='';
            $CLASE = '';
            $CUENTA  = '';
            $count = 0;
        if($num_rows>0){
	while ($row = $result->fetch_assoc()) {
		$count++;	
                /*
                nro_scuenta,  nombre_scuenta,
                 cod_clase, nombre_clase, 
                 cod_grupo, nombre_grupo, 
                 cod_cuenta, nombre_cuenta, 
                        "<tr style='cursor:pointer' class='filaBusquedaCNT' "         */
		$filasBusquedas .="<tr style='cursor:pointer' class='filaBusquedaCNT' "  
                        . " data-nro_scuenta='{$row["nro_scuenta"]}'"
                       . " data-nombre_scuenta='{$row["nombre_scuenta"]}'"
                        . " data-cod_clase='{$row["cod_clase"]}'"
                        . " data-nombre_clase='{$row["nombre_clase"]}'"
                        . " data-cod_grupo='{$row["cod_grupo"]}'"
                        . "  data-nombre_grupo='{$row["nombre_grupo"]}'"
                        . " data-cod_cuenta='{$row["cod_cuenta"]}'"
                        . " data-nombre_cuenta='{$row["nombre_cuenta"]}'"
                        . " id='td_CNT_$count' > " 
                        . "<td id='nombre_clase'>".$row['nombre_clase']."</td>"
                        . "<td id='nombre_grupo'>".$row['nombre_grupo']."</td>"
                        . "<td id='nombre_cuenta'>".$row['nombre_cuenta']."</td>"
                        . "<td id='nro_scuenta'>".$row['nro_scuenta']."</td>"
                        . "<td id='nombre_scuenta'>".$row['nombre_scuenta']."</td>"
                        . '</tr>';
                
            
           
            IF ($CLASE != $row["cod_clase"]){
                $CLASE = $row["cod_clase"];
            $SELECT_CLASE_AUX   .= "<option value='{$row["cod_clase"]}'>{$row["nombre_clase"]}</option>" ;
            }
            IF ( $GRUPO !=$row["cod_grupo"]){
                $GRUPO =$row["cod_grupo"];
            $SELECT_GRUPO_AUX   .= "<option value='{$row["cod_grupo"]}' data-clase='{$row["cod_clase"]}'>{$row["nombre_grupo"]}</option>" ;
            }
            IF ( $CUENTA  != $row["cod_cuenta"]){
                 $CUENTA  = $row["cod_cuenta"];
                $SELECT_CUENTA_AUX   .= "<option "
                        . "value='{$row["cod_cuenta"]}' "
                        . "data-clase='{$row["cod_clase"]}' "
                        . "data-grupo='{$row["cod_grupo"]}' >{$row["nombre_cuenta"]}</option>" ;
            }           
                
        }
	}
     //   echo $filasBusquedas;
        //echo $SELECT_CLASE_AUX .$SELECT_GRUPO_AUX .$SELECT_CUENTA_AUX ;
           $SELECT_CLASE   .= "$SELECT_CLASE_AUX</SELECT>" ;
           $SELECT_GRUPO   .= "$SELECT_GRUPO_AUX</SELECT>" ;
           $SELECT_CUENTA  .= "$SELECT_CUENTA_AUX</SELECT>" ;
            
            $SELECT_CLASE_NW   .= "$SELECT_CLASE_AUX</SELECT>" ;
            $SELECT_GRUPO_NW   .= "$SELECT_GRUPO_AUX</SELECT>" ;
            $SELECT_CUENTA_NW  .= "$SELECT_CUENTA_AUX</SELECT>" ; 
        
	echo '<table><tr><td valign="middle"><input type="hidden" id="existeListadoCnt"    value="no" />'
        . '<input type="hidden" id="noExitsCnt" name="noExitsCnt"  value="S" />'
        . '<input type="hidden" id="numNuevosCNT" name="numNuevosCNT"  value="0" />'
        . '<input type="text" id="'.$idBucador.'" class = "'.$clase.'" onKeyPress="return valida_numeros(event)"  name = "'.$idBucador.'"  />' 
                . '<td><input type="image" id="crearCuentaNueva" src="'.URL_BASE.'jds/imagenes/nuevo.jpg" style="width:35px" value="Crear"/></td>'
        . '<td><input type="image" id="buscarDatosCNT" src="'.URL_BASE.'jds/imagenes/cargarImagen.jpg" style="width:25px" value="Buscar"/></td>'
        . '</tr></table>';
        
	$divCrearNuevo =''; 
        echo 
	' <input type="hidden" id="HNew_cod_cuenta" 	name="HNew_cod_cuenta" />'.
	' <input type="hidden" id="HNew_nro_cuenta" 	name="HNew_nro_cuenta" />'. 
	' <input type="hidden" id="HNew_nombre_cuenta" 	name="HNew_nombre_cuenta" />';
        
        
	$divCrearNuevo ="<div id='crearSubCuenta' style=' display:none;  margin-right: auto;margin-left: auto; top: 50px; width: 60%; height:500px; '>".
	"<table border='1px' style=' width:100%; border:thin #069; font-family:Consolas, 'Andale Mono', 'Lucida Console','Lucida Sans Typewriter', Monaco, 'Courier New', monospace'>".
	"<tr><td colspan='2' align='center' valign='middle'><h3>CREAR NUEVA SUBCUENTA.</h3><input type='button' value='cerrar' id='cerrarCrearCNT'/></td></tr>". 
	"<tr> <td>CLASE</td><td>$SELECT_CLASE_NW</td></tr>".
	"<tr> <td>GRUPO</td><td>$SELECT_GRUPO_NW</td> </tr>".
	"<tr> <td>CUENTA</td><td>$SELECT_CUENTA_NW</td> </tr>".
	"<tr> <td>DIGITO SUBCUENTA</td><td><input tipe='text' id='NewDigitoCuenta' onKeyPress='return valida_numeros(event)'   style='width:350px;'/></td> </tr>".
	"<tr> <td>CODIGO SUBCUENTA</td><td><input tipe='text' id='NewCodSubcuenta' name='CodSubcuenta'  readonly style='width:350px;'/> </td> </tr>".
	"<tr> <td>NOMBRE SUBCUENTA</td><td><input tipe='text' id='NewNombreSubcuenta' name='NombreSubcuenta'  style='width:350px;'/> </td> </tr>".
	"<tr> <td colspan='2' align='center'><input type='button' value='aceptar' id='aceptarCrearSCUENTA'/></td> </tr></table></div>";
	
	$divbusqueda= "<div id='listarSubCuenta' style=' display:NONE;  margin-right: auto;margin-left: auto; top: 10px; width: 80%; height:500px;overflow: scroll; '>".
	"<table border='1px' style=' width:100%; border:thin #069; font-family:Consolas, 'Andale Mono', 'Lucida Console','Lucida Sans Typewriter', Monaco, 'Courier New', monospace'>".
	"<tr><td colspan='5' align='center' valign='middle'><h3>BUSQUEDA DE SUBCUENTA.</h3></td></tr>".
	"<tr><td colspan='5' align='center'>"
          . "<table>"
          . "<tr><td COLSPAN='4'>"
                 . "<table>"
          . "<tr><td>Clase</td><td>$SELECT_CLASE</td><td>Grupo</td><td colspan='2'>$SELECT_GRUPO</td><td>Cuenta</td><td>$SELECT_CUENTA</td></tr></TABLE>"
                . "</td></tr>"
          . "<tr><td>Buscar:</td><td ><input tipe='text' id='textBusquedaSCuenta' style='width:350px;'/> "
          . "&nbsp;&nbsp;&nbsp;&nbsp;<input  type='image' src='" .URL_BASE. "jds/imagenes/cargarImagen.jpg' value='Clean' style='width:25px' id='btnBusquedaSCuenta'/></td>"
          . "<td align='center'><input type='image' src='".URL_BASE."jds/imagenes/limpiar_nuevo.png' value='Clean' style='width:40px' id='cleanBusquedaCuenta'/></td>"
          . "<td align='center'><input type='button' value='X' id='cerrarBusqueda_$idBucador'/></td>   </tr>"
          ."</table>"
          . "</td></tr><tr> "
                . "<td>CLASE</td><td>GRUPO</td><td>CUENTA</td><td>NUMERO CUENTA</td><td>SUB-CUENTA</td>"
        . "</tr> $filasBusquedas</table></div>";
        //  ECHO $divbusqueda;
	$count = 0;
        
        
        echo '<script> '
        . "$('#ContBusquedaCuentas').html(\"$divbusqueda$divCrearNuevo\" );"        
        . '</script>';
        
        echo '<script> $("#buscarDatosCNT").click(function(event){
		event.preventDefault();
		$("#listarSubCuenta").fadeIn( "slow", function() {
				// Animation complete
			});
		$("#'.$contenedor.'").hide()
	});';
        echo "$('#cerrarCrearCNT').click(function(){
		$('#noExitsCnt').val('S')
		$('#crearSubCuenta').hide()
		$('#$idBucador').val('')
		$('#$contenedor').fadeIn( 'slow', function() {
				// Animation complete
			});	
	});";
        echo "$('#clase_sch').change(function(){
            if($(this).val() ==''){
            $('.filaBusquedaCNT').show()
            }else{
            
                $('.filaBusquedaCNT').hide()
                $('*[data-cod_clase=\"'+$(this).val()+'\"]').show()
            }});</script>";
          echo "<script>$('#grupo_sch').change(function(){
            if($(this).val() ==''){
            $('.filaBusquedaCNT').show()
            }else{
            
                $('.filaBusquedaCNT').hide()
                $('*[data-cod_grupo=\"'+$(this).val()+'\"]').show()
            }});</script>"  ;
          
           echo "<script>$('#cuenta_sch').change(function(){
            if($(this).val() ==''){
            
            $('.filaBusquedaCNT').show()
            }else{
            
                $('.filaBusquedaCNT').hide()
                
                $('*[data-cod_cuenta=\"'+$(this).val()+'\"]').show()
                var clase = $('#cuenta_sch option:selected').data('clase');
                var grupo = $('#cuenta_sch option:selected').data('grupo');
                //alert(clase +' - '+grupo)
                $('#clase_sch').val(clase)
                $('#grupo_sch').val(grupo)
                
            }}); "  ;
        echo "$('#cerrarBusqueda_$idBucador').click(function(){ $('#listarSubCuenta').hide() 
	$('#$contenedor').fadeIn( 'slow', function() {
				// Animation complete
			});
	});"
        . "$('#cleanBusquedaCuenta').click(function(event){
		event.preventDefault(); 
		$('#textBusquedaSCuenta').val('');
		$('#textBusquedaSCuenta').trigger('keydown')
	}); " 
        ." $('#btnBusquedaSCuenta').click(function(){
		var busqueda = $('#textBusquedaSCuenta').val().toUpperCase();
		var resp2 = ''; 
		var resp3 = ''; 
		if ( busqueda != ''){
		$('.filaBusquedaCNT').each(function(){ 
			$(this).hide()
			resp3 = $(this).find('#nro_scuenta').html();
			resp2 = $(this).find('#nombre_scuenta').html().toUpperCase()
			 console.log(resp3+' -- '+ nit +' -- '+ resp3.indexOf(nit))
			if( (resp3.indexOf(busqueda) != -1  ) || (resp2.indexOf(busqueda) != -1 )  ){
				$(this).show()
				 				
			}			
		});}else{ $('.filaBusquedaCNT').show()}
		 
	});"
        . "$('#$idBucador').keydown(function(e){
		
		var tecla=e.keyCode;
		$('#$idRespuesta').val('')
		if(tecla==13){	busquedaCuentas($('#$idBucador').val(),'$idRespuesta'); }
	  });
	$('.filaBusquedaCNT').click(function(){
		//alert('hola papi'+$(this).find('#nombre_scuenta').html())
		$('#$idRespuesta').val($(this).find('#nombre_scuenta').html())
		$('#$idBucador').val($(this).find('#nro_scuenta').html());
		$('#cerrarBusqueda_codCuenta').trigger('click');
		
	});"
        ."$('#aceptarCrearSCUENTA').click(function(){
            alert( $('[data-nro_scuenta=\"'+$('#NewCodSubcuenta').val()+'\"]').length)
if ( $('[data-nro_scuenta=\"'+$('#NewCodSubcuenta').val()+'\"]').length <= 0 ){
			if ($('#NewCodSubcuenta').val() != ''){
				if ($('#NewNombreSubcuenta').val() != ''){ 
					if ($('#cuenta_new').val() != ''){
                                        var numNuevosCNT = $('#numNuevosCNT').val()
                                        numNuevosCNT++;
                                        $('#HNew_cod_cuenta').val($('#cuenta_new').val() )
                                        $('#HNew_nro_cuenta').val($('#NewCodSubcuenta').val() ) 
                                        $('#HNew_nombre_cuenta').val($('#NewNombreSubcuenta').val() )  
                                        $('#$contenedor').append('<input type=\"hidden\" name=\"nuevaCuenta['+numNuevosCNT+'][cuenta]\" value=\"'+$('#cuenta_new').val()+'\"/>')
                                        $('#$contenedor').append('<input type=\"hidden\" name=\"nuevaCuenta['+numNuevosCNT+'][subcuenta]\" value=\"'+$('#NewCodSubcuenta').val()+'\"/>')
                                        $('#$contenedor').append('<input type=\"hidden\" name=\"nuevaCuenta['+numNuevosCNT+'][nombresub]\" value=\"'+$('#NewNombreSubcuenta').val()+'\"/>')
                                        $('#noExitsCnt').val('N')
                                        $('#$idBucador').val($('#HNew_nro_cuenta').val())
                                        $('#$idRespuesta').val($('#HNew_nombre_cuenta').val())
                                        $('#crearSubCuenta').hide()
                                        $('#$contenedor').fadeIn( 'slow', function() {});	
							
							
					} else{alert('la cuenta no debe estar en blanco');$('#cuenta_new').focus()}
				}else{alert('El nombre de la subcuenta no debe estar en blanco');$('#NewNombreSubcuenta').focus()}
			}else{alert('El codigo de subcuenta no debe estar en blanco');$('#NewCodSubcuenta').focus()}
		}else{alert('La subcuenta no debe estar en el listado ');$('#NewCodSubcuenta').focus()}
	});
	 "
        . "$('#crearCuentaNueva').click(function(e){ 
	e.preventDefault(); 
	$('#crearSubCuenta').fadeIn( 'slow', function() {
				// Animation complete
			});
		$('#$contenedor').hide()	
	});"
       . "$('#clase_new').change(function(e){ 
	e.preventDefault(); 
	 var dato = $(this).val()
         $('#grupo_new').val('')
	  $('#grupo_new option').hide();
	 $('#grupo_new option').each(function(){
		if($(this).data('clase') == dato ) {
			$(this).show()
		}
	 }) 
        $('#cuenta_new').val('')
	  $('#cuenta_new option').hide();
	 $('#cuenta_new option').each(function(){
		if($(this).data('clase') == dato ) {
			$(this).show()
		}
	 }) 
	});"
        ."$('#grupo_new').change(function(e){ 
	e.preventDefault(); 
	 var dato = $(this).val()
         if (dato!=''){
         $('#clase_new').val($('#grupo_new option:selected').data('clase'))
	
        $('#cuenta_new').val('')
	  $('#cuenta_new option').hide();
	 $('#cuenta_new option').each(function(){
		if($(this).data('grupo') == dato ) {
			$(this).show()
		}
	 })} 
	});" 
        ."$('#cuenta_new').change(function(e){ 
	e.preventDefault(); 
	 var dato = $(this).val()
         if (dato!=''){
         $('#clase_new').val($('#cuenta_new option:selected').data('clase'))
         $('#grupo_new').val($('#cuenta_new option:selected').data('grupo'))
            var digito = $('#NewDigitoCuenta').val();            
            var subcuenta = dato + digito
            $('#NewCodSubcuenta').val(digito)
	 } 
         
	});"  
        . "$('#NewDigitoCuenta').keyup(function(e){ 
	e.preventDefault(); 
	 var dato = $(this).val()
         if (dato!=''){ 
            var digito = $(this).val();
            var Cuenta = $('#cuenta_new').val();
            var subcuenta = Cuenta + digito
            $('#NewCodSubcuenta').val(subcuenta)
            $('#noExitsCnt').val('N')
	 } 
         
	});" 
        . "function busquedaCuentas(nit , id ){
		var resp1 = '';
		var resp2 = ''; 
		var resp3 = ''; 
		if (nit != ''){
		
		 
		if ( $('[data-nro_scuenta=\"'+nit+'\"]').length ) {
		       $('#'+id).val($('[data-nro_scuenta=\"'+nit+'\"]').data('nombre_scuenta'))
				
				$('#noExitsCnt').val('S')
                                $('#debito').focus()
				
		}else{
			alert('la cuenta que ingreso no se encuentra registrado, para ingresarlo en la lista de subCuentas presione NEW')
			
			$('#'+id).val('')
		}
		}else{
			$('#'+id).val('')
		}
		
		 
	}"
        . "</script> ";
        
}
function utf8_string_array_encode(&$array){
    $func = function(&$value,&$key){
        if(is_string($value)){
            $value = utf8_encode($value);
        } 
        if(is_string($key)){
            $key = utf8_encode($key);
        }
        if(is_array($value)){
            utf8_string_array_encode($value);
        }
    };
    array_walk($array,$func);
    return $array;
}
function utf8_string_array_dencode(&$array){
    $func = function(&$value,&$key){
        if(is_string($value)){
            $value = utf8_decode($value);
        } 
        if(is_string($key)){
            $key = utf8_decode($key);
        }
        if(is_array($value)){
            utf8_string_array_encode($value);
        }
    };
    array_walk($array,$func);
    return $array;
}
function enviaCorreo($destinatario, $asunto, $mensaje, $headers) {
    try {
        ob_start();
        $message = ob_get_clean();
         if (!@mail($destinatario, $asunto, $mensaje, $headers)){ 
           return '<p>en el momento no es posible enviar correo de notificacion</p>';
      }
      else { return '_ok';}
      
       } catch (Exception $ex ) {
        return '<p>Se genero un problema al enviar correo</p>';
    }
} 
function normalize_date_rfc($date,$separa){
 
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			//echo $var['0'];
			  return "$var[2]".$separa."$var[0]".$separa."$var[1]";}
        
 
	}
function normalize_date_db_rfc($date,$separa){
 
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			//echo $var['0'];
			  return "$var[0]".$separa."$var[2]".$separa."$var[1]";}
        
 
	}        
function normalize_date($date,$separa){
 
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			//echo $var['0'];
			  return "$var[2]".$separa."$var[0]".$separa."$var[1]";}
        
 
	}
function is_session_started(){
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}
function cargarVariablesSessionUsuario(array $ArrayUsuario ){
    $_SESSION['access'] = true; 
    if (!isset($ArrayUsuario["posicion"]))$ArrayUsuario["posicion"] = '';
    if (!isset($ArrayUsuario["galeria"]))$ArrayUsuario["galeria"] = '';
    $galeria = $ArrayUsuario["galeria"];
    $_SESSION["ID"] = $ArrayUsuario['ID'];
    $_SESSION["Login"] = $ArrayUsuario['Login'] ;
    $_SESSION["usuario_logeado"] = $ArrayUsuario['Login'] ;
    $_SESSION["Nombre1"] = $ArrayUsuario['Nombre1'] ;
    $_SESSION["Nombre2"] = $ArrayUsuario['Nombre2'] ;
    $_SESSION["Apellido1"] = $ArrayUsuario['Apellido1'];
    $_SESSION["Apellido2"] = $ArrayUsuario['Apellido2'] ;
    $_SESSION["nombreCompleto"] = $ArrayUsuario['nombreCompleto'] ;
    $_SESSION["estado"] = $ArrayUsuario['estado'] ;
    $_SESSION["pass"] = $ArrayUsuario['pass'] ;
    $_SESSION["change"] = $ArrayUsuario['change'] ;
    $_SESSION["mail"] = $ArrayUsuario['mail'] ;    
    $_SESSION["cod_remision"] = $ArrayUsuario["codRemision"]; 
    
		$_SESSION["usuario"]		=	$ArrayUsuario['nombreCompleto'] ;
		$_SESSION["nombreUsuario"]	=	$ArrayUsuario['nombreCompleto'] ;
		$_SESSION["usuarioid"]		=	$ArrayUsuario['ID'];
		$_SESSION["IJKLEM1934589"]	=	$ArrayUsuario['pass'];
		$_SESSION["posicion"]		=	$ArrayUsuario["posicion"];
		$_SESSION["database"]		=	DB_NAME_INICIO;
		$_SESSION["galeria"]		=	$galeria;
		$_SESSION["host"] 		=	DB_HOST;
		$_SESSION["clavedb"]		=	DB_PASS ;
		$_SESSION["usuariodb"]		=	DB_USER ;
		$_SESSION["usertype"]		=	'';
       
                
             
    
    
    
 } 
function cargarTablaLIstar($idTabla , $busqueda = false , $option = false ){
    $cabeceras = obtenerCabecera($idTabla);
   $countTabla = 0;   $cabeceraHtml = '';
  
   $NumCabezas = sizeof ($cabeceras);
   
   $espacio = 100;
   if ($option){ 
         $cabeceraHtml  = '<td width="6%" style="background-color:white;"><img style="width:100%; max-width:50px" src="../images/s_fulltext.JPG"/></td>' ;
         $espacio = 94;
    }
    $widCab = round ($espacio /$NumCabezas);
    $selectTipoBusqueda = '<SELECT class="form-control" id="col_busqueda_dinamica_'.$idTabla.
            '_20170510"><OPTION value="*">TODOS</OPTION>';
    foreach ($cabeceras as $key => $value) { 
        //print_r($value); 
         if($value[1]==""){$auxWid=':w:';}else{
              $auxWid=$value[1];  
              $espacio = $espacio-$auxWid;
              $NumCabezas--;
              if ($NumCabezas>0){
                $widCab =round (($espacio-$value[1] )  /$NumCabezas);
          }
          }
          $display ='';
          if (!$value[3]){$display = 'style="display:none;"';
            $NumCabezas--;
            if ($NumCabezas>0){
                  $widCab =round (($espacio)  /$NumCabezas);
            }
          }
          $NOWRAP = '';
          if($value[2]){$NOWRAP = 'NOWRAP';}
        
          $cabeceraHtml .= '<td width ="'.$auxWid.'%" '.$NOWRAP.' '.$display.' >'.$value[0].'</td>';
          if (!isset($value[5]) || $value[5] === false) {
            $selectTipoBusqueda .= "<OPTION value='$key'> $value[0] </OPTION>";
        }
    }
     $selectTipoBusqueda .='</SELECT>';
    $cabeceraHtml = str_replace( ':w:', $widCab,$cabeceraHtml);
    $varBusqueda = '';
    if ($busqueda){
        $varBusqueda = "<div class='row' style='width:100%'><div  class='col-md-1 col-sm-12'></div>"
                . "<div  class='col-md-3 col-sm-12'><label for='columna'>Columna :</label>$selectTipoBusqueda</div>" 
                . "<div  class='col-md-3 col-sm-12'><label for='concordancia'>Concordancia :</label><select class='form-control' id='tip_concordancia_20170510'>"
                . "<option value ='1'>Que contenga</option>"
                . "<option value ='0'>Igual</option>"
                . "<option value ='2'>Que inicie</option>"
                . "<option value ='3'>Que finalice</option>" 
                . "</select></div>"
                . "<div  class='col-md-4 col-sm-12'>"
                . "<label for='item'>Item de busqueda :</label>"
                . "<div class='input-group'><input class='form-control' id='busqueda_dinamica_{$idTabla}_20170510' type=text>"
                . "<span class='input-group-btn'>"
                . "<button  type='button' title='buscar item' class='btn_enviar_busqueda_dinamica_{$idTabla}_20170510 btn btn-secondary'>"
                . "</span><i class= 'fa fa-search'  aria-hidden='true'></i></button></span></div>"
                . "</div>"
                . "</div>"; 
    }   
    
    
  return ' <br><div id="busqueda'.$idTabla.'" class="tablas-generada"  style="width:100%"> '
          .'<div class="row" style="width:100%">'
          .'<div class="col-md-3 col-sm-12"></div>'
          .'<div class="col-md-2 col-sm-12">'          
          . '<div id="'.$idTabla.'_cont_Load_gif" style="padding-left:20%;padding-right: 20%; " >'
          . '<span style="float: left; color: gray;font-size: 13px "  >Cargando&nbsp;</span>  '
          . '<div class="loadingDiv" id="esperaMarcas" style="height:20px; width:100px;  " >'
          . '<div id="blockG_1" class="facebook_blockG"></div>'
          . '<div id="blockG_2" class="facebook_blockG"></div>'
          . '<div id="blockG_3" class="facebook_blockG"></div></div></div></div></div>'
          . $varBusqueda
          . '<input type="hidden" id="respuesta'.$idTabla.'"><input type="hidden" id="gridID">'
          . '<table border="0" id="Tablacolor" width="100%"  ><tbody><tr><td><br>'
          . '<table border="0" id="listarTabla'.$idTabla.'"   width="100%">'
          . '<tbody><tr id="cabPac" align="center"  >'.$cabeceraHtml
          . '</tr><tr><td colspan="'.$countTabla.'"><span id=""></span></td></tr></tbody></table>'
          . '</td></tr><tr><td><div id="tablasLista'.$idTabla.'"></div>'
          . '<div id="indiceLista'.$idTabla.'" align="center"></div>'
          . '</td></tr></tbody></table></div>';
}
function obtenerNomCabecera($idTabla){
     $cabeceraLLamado = array();
     $cabeceras = obtenerCabecera($idTabla);
     $i=0;
     foreach ($cabeceras as $key => $value) { 
         $cabeceraLLamado[$i] = $key;
         $i++;
     }
     return $cabeceraLLamado;
}
function obtenerNomCabeceraBusqueda($idTabla){
     $cabeceraLLamado = array();
     $cabeceras = obtenerCabecera($idTabla);
     $i=0;
     foreach ($cabeceras as $key => $value) { 
         if ($value[4]){
         $cabeceraLLamado[$i] = $key;
         $i++;
     }}
     return $cabeceraLLamado;
}
function obtenerCabecera($idTabla){
    $cab = array();
    //array que contendra el nombre de la columna en la base de datos con el header y el tamaño de la columna en la tabla
    //"idmenus"=>array("cod_menu","10",false,true),
    //fied en la tabla, 
    //cabecera dela columna en la página, 
    //tamaño -si se envia vacio le asigna un % - , 
    //warp -si permitirá en la cabecera de la columa partir el texto-
    //visible (true,false)
    //incluido en la busqueda (true,false)
    //protectec
    switch ($idTabla){
        case 'areasDeControl':
             $cab=array(//ACC, DENOMINACION
                "ACC"=>array("cod_area","10",false,true,true),
                "DENOMINACION"=>array("Denominacion","15",false,true,true)
                
                );
            break;
        
        case 'menuSistema':
            
            $cab=array(
                "idmenus"=>array("cod_menu","10",false,true,true),
                "Nombre"=>array("nombre","15",false,true,true),
                "Descripcion"=>array("descripcion","",true,true,true),
                "PadreId"=>array("cod_padre","10",false,false,true),
                "padre"=>array("nom. padre","15",false,true,true),
                "Url"=>array("url","10",false,true,true) 
                
                );
        break;//id_correo, nombre_usuario, mail, mail_reemplazo
        case 'SAP_clientes_con_mail';
         $cab=array(
                "id_cliente_SAP"=>array("codigo cliente",false,false,true,true),
                "nom_cliente_SAP"=>array("nombre del cliente",false,false,true,true),
        
                
                );
        break; 
        case 'usuarios':
            
            $cab=array(
                "ID" =>array("cod. usu.","6",false,true,true), 
                "Login"=>array("nick","12",false,true,true), 
                "Nombre1"=>array("P. nombre","",false,false,true), 
                "Nombre2"=>array("S. nombre","",false,false,true),
                "Apellido1"=>array("P. apellido","",false,false,true), 
                "Apellido2"=>array("S. apellido","",false,false,true), 
                "nombreCompleto"=>array("Nombre","",false,true,true),
                "estado"=>array("Estado","10",false,true, false), 
                "usr_registro"=>array("user reg.","",false,false,false,true ),
                "Fecha_Registro"=>array("fec. reg.","",false,false,false,true), 
                "Usr_Modif"=>array("user mod.","",false,false,false,true), 
                "Fecha_Modif"=>array("fec. mod.","",false,false,false,true), 
                "pass"=>array("Contraseña","",false,false,false,true),
                "ultimo_ingreso"=>array("Ult. ingreso","",false,true,false),
                "cod_remision"=>array("cod. Rem","8",false,true,false),
                "mail"=>array("correo electronico","",false,true,false),
                "ultimo_ingreso"=>array("Ult. ingreso","",false,true,false,)
                );
        break;
        case 'perfiles':            
        $cab=array(
            "Perf_ID"=>array("cod. perfil","",false,true,true), 
            "Perf_Nombre"=>array("nombre","",false,true,true), 
            "estado"=>array("estado","",false,true,true)  
               );    
         break; 
        
        case 'perfiles_usuarios':            
        $cab=array(//idRelacion, user_id, perfil_id, Perf_Nombre, nombreCompleto
            "idRelacion"=>array("cod. asignación","",false,true,true), 
            "user_id"=>array("cod. usuario","",false,true,true), 
            "nombreCompleto"=>array("nombre usuario","",false,true,true), 
            "perfil_id"=>array("cod. perfil","",false,true,true), 
            "Perf_Nombre"=>array("nombre perfil","",false,true,true)  
               );    
         break; 
        case 'recursos':            
        $cab=array(// 
            "idrecurso"=>array("cod. recurso","",false,false,true), 
            "id_menu"=>array("id menu","",false,false,true), 
            "nombreMenu"=>array("Menú","",false,true,true) ,
            "nombre_tipo_recurso"=>array("tipo de recurso","",false,true,true), 
            "tipo_recurso"=>array("id tipo de recurso","",false,FALSE,true), 
            "nombre_recurso"=>array("Recurso","",false,true,true), 
            "id_recurso_sistema"=>array("ID Rec. en sistema","",false,true,true)  ,            
            "estado"=>array("estado","",false,true,true) 
               );    
         break;
     //id_relacion, id_usuario, id_cliente_SAP, nombre_cliente_SAP, estado  relacion_areasDeControl_usuarios
        case 'relacion_cliente_usuarios':            
        $cab=array(// 
            "id_relacion"=>array("cod. relacion","",false,false,true), 
            "id_usuario"=>array("cod. usuario","",false,false,true), 
            "id_cliente_SAP"=>array("cod. del cliente","",false,true,true) ,
            "nombre_cliente_SAP"=>array("nombre del cliente","",false,true,true), 
            "estado"=>array("id estado","",false,FALSE,true) , 
            "nameEstado"=>array("estado","",false,true,true) ,
            "num_mail_principal"=>array("num_mail_principal","",false,false,true)
               );    
         break; 
     //id_cuenta, cod_grupo, cod_cuenta, nombre_cuenta, id_grupo, cod_clase, nombre_grupo, id_clase, nombre_clase
     case 'vw_cnt_cuenta': 
         $cab=array(// 
            "id_cuenta"=>array("id cuenta","",false,true,true), 
            "cod_clase"=>array("cod. clase","",false,true,true), 
            "cod_grupo"=>array("cod. grupo","",false,true,true) ,           
            "digito"=>array("digito","",false,true,true) ,           
            "cod_cuenta"=>array("cod. cuenta","",false,true,true) , 
            "nombre_clase"=>array("clase","",false,true,true), 
            "nombre_grupo"=>array("grupo","",false,true,true), 
            "nombre_cuenta"=>array("cuenta","",false,true,true)  
               );  
     break; 
  //id_scuenta, nro_scuenta, modificar, nombre_scuenta, cod_clase, nombre_clase, cod_grupo, nombre_grupo, cod_cuenta, nombre_cuenta
     case 'vw_cnt_scuentas': 
         $cab=array(// 
            "modificar"=>array("modificar","",false,false,true), 
            "id_scuenta"=>array("id subcuenta","",false,true,true),              
            "cod_clase"=>array("cod. clase","",false,true,true), 
            "cod_grupo"=>array("cod. grupo","",false,true,true) ,              
            "cod_cuenta"=>array("cod. cuenta","",false,true,true) ,         
            "nro_scuenta"=>array("cod. subcuenta","",false,true,true) ,             
            "digito"=>array("digito","",false,true,true) ,          
            "nombre_clase"=>array("clase","",false,true,true), 
            "nombre_grupo"=>array("grupo","",false,true,true), 
            "nombre_cuenta"=>array("cuenta","",false,true,true), 
            "nombre_scuenta"=>array("subcuenta","",false,true,true)  
               );  
     break; 
     case 'cnt_clase': 
         //array que contendra el nombre de la columna en la base de datos con el header y el tamaño de la columna en la tabla
    //"idmenus"=>array("cod_menu","10",false,true),
    //fied en la tabla, 
    //cabecera dela columna en la página, 
    //tamaño -si se envia vacio le asigna un % - , 
    //warp -si permitirá en la cabecera de la columa partir el texto-
    //visible (true,false)
    //incluido en la busqueda (true,false)
    //protectec
        $cab=array(// id_clase, cod_clase, nombre_clase
            "id_clase"=>array("ID","",false,false,false), 
            "cod_clase"=>array("CODIGO","",false,true,true), 
            "nombre_clase"=>array("DENOMINACION","",false,true,true), 
        
               );    
         break;
     
      case 'vw_cnt_listar_operaciones':   
          //cod_operacion, usuario, nombre, fecha, totalD, idAux, totalC
        $cab=array( 
            "cod_operacion"=>array("Codigo","",false,false,false), 
            "usuario"=>array("Usuario","",false,false,false), 
            "nombre"=>array("Descripción","",false,true,true), 
            "fecha"=>array("Fecha Operacion","",false,false,true), 
            "totalD"=>array("Debito","",false,true,false),             
            "totalC"=>array("Crédito","",false,true,false),              
            "idAux"=>array("No. Comprobante","",false,true,false) ,              
            "nombreCompleto"=>array("nombreCompleto","",false,false,false)       
               );    
         break;
      case 'vw_cnt_grupos':   
        $cab=array( 
            "id_grupo"=>array("Id Grupo","",false,false,false), 
            "cod_clase"=>array("Cod. Clase","",false,false,true), 
            "cod_grupo"=>array("Cod. Grupo","",false,true,true), 
            "digito"=>array("digito","",false,false,true), 
            "nombre_clase"=>array("DENOMINACION","",false,true,true),             
            "nombre_grupo"=>array("Nombre","",false,true,true),  
        
               );    
         break;
    }
    return $cab;
}
function obtenerArrayHijos(){
    foreach ($arrayMenus as $key => $value) { 
            $menuGenerado .= '<tr><td class="moduloTitle_principal">'.$value["Nombre"].'</td></tr>';	
            } 

    }
function eliminarSession( ){     
    unset($_SESSION["ID"]); 
    unset($_SESSION["Login"]); 
    unset($_SESSION["usuario_logeado"]); 
    unset($_SESSION["Nombre1"]); 
    unset($_SESSION["Nombre2"]); 
    unset($_SESSION["Apellido1"]); 
    unset($_SESSION["Apellido2"]); 
    unset($_SESSION["nombreCompleto"]); 
    unset($_SESSION["estado"]); 
    unset($_SESSION["pass"]); 
    unset($_SESSION["change"]);   
    $_SESSION['access'] = false;
    $_SESSION = array();
    session_destroy();
    
       
}
// public function __construct($ID = null,$Login,$Nombre1,$Nombre2 = '',$Apellido1,$Apellido2 = '',$nombreCompleto = null,$estado = 'A',$pass = '' )
function cargaDatosUsuarioActual(){
        
    $usuario = new Class_php\Usuarios($_SESSION["ID"],$_SESSION["Login"],$_SESSION["Nombre1"],$_SESSION["Nombre2"],$_SESSION["Apellido1"],$_SESSION["Apellido2"],$_SESSION["nombreCompleto"] ,  $_SESSION["estado"],$_SESSION["pass"],$_SESSION["mail"], 0,$_SESSION["cod_remision"]);
        
    return $usuario;
}
function mostrarMenu($idMenu){
$datos = Class_php\MenuSistema::get_recursos_by_menu($idMenu);
print_r($datos); 
if (sizeof($datos['datos']) == 0){return '';}else{
  $listaHtml ='<ul>';
foreach ($datos['datos'] as $key => $value) {
    $auxHijosHtml ='';
    $clase ='';
    $listaHtml = str_replace(':clase:','nodoAbierto', $listaHtml );
    $listaHtml = str_replace(':clasehijo:','nodoHijo', $listaHtml );
     $idMenu_aux = $value['id_recurso_sistema'];
     echo '<br>'.$idMenu_aux;
     //$auxHijosHtml = mostrarMenu($idMenu_aux);
    if ($auxHijosHtml != ''){ $auxHijosHtml = '<ul>'.str_replace(':clasehijo:','nodoHijoFin', $auxHijosHtml ).'</ul>';  $clase =':clase:'; }else {   $clase =':clasehijo:';}
    
    
    
    $listaHtml .="<li class='$clase' id='menu_{$value['id_recurso_sistema']}'>"
    . "<input type='checkbox' data-tipo='recurso' data-recurso_id='{$value['idrecurso']}' class='menu_{$value['id_recurso_sistema']}' />"
    . "<span>{$value['nombre_recurso']}</span>$auxHijosHtml</li>";  
}


$listaHtml = str_replace(':clase:','nodoAbiertoFin', $listaHtml );                                     
$listaHtml = str_replace(':clasehijo:','nodoHijoFin', $listaHtml );
$listaHtml .='<ul>';  
     return $listaHtml;
 }                    
 }
function getMenuHijosHtml ($idMenu){
    global $htmlHijos , $menus ;
    $datos = $menus::get_recursos_by_menu($idMenu);
    print_r($datos);
    foreach ($datos['datos'] as $key => $value) {
       $htmlHijos ="<li class='$clase' id='menu_{$value['id_recurso_sistema']}'>"
    . "<input type='checkbox' data-tipo='recurso' data-recurso_id='{$value['idrecurso']}' class='menu_{$value['id_recurso_sistema']}' />"
    . "<span>{$value['nombre_recurso']}</span>$auxHijosHtml</li>";
    getMenuHijosHtml($value['id_recurso_sistema']);
    }
}  
function mostrarMenu_($idMenu){
     
     $menuHtml .=''; 
       $datos = Class_php\MenuSistema::get_recursos_by_menu($idMenu);
  $listaHtml ='<ul>';
foreach ($datos['datos'] as $key => $value) {
    $auxHijosHtml ='';
    $clase ='';
    $listaHtml = str_replace(':clase:','nodoAbierto', $listaHtml );
    $listaHtml = str_replace(':clasehijo:','nodoHijo', $listaHtml );
     $idMenu = $value['id_recurso_sistema'];
     $datosAux = array();
    $datosAux = Class_php\MenuSistema::get_recursos_by_menu($idMenu ) ;
    //print_r($datosAux);
    foreach ($datosAux['datos'] as $auxkey => $Hijosvalue) {
        $auxHijosHtml = str_replace(':clasehijo:','nodoHijo', $auxHijosHtml );
        $auxHijosHtml .="<li class=':clasehijo:' id='menu_{$Hijosvalue['id_recurso_sistema']}'>"
        . "<input type='checkbox' data-tipo='recurso' data-recurso_id='{$Hijosvalue['idrecurso']}' class='menu_{$Hijosvalue['id_recurso_sistema']}'  />"
        . "<span>{$Hijosvalue['nombre_recurso']}</span> </li>"; 
    } 
    if ($auxHijosHtml != ''){ $auxHijosHtml = '<ul>'.str_replace(':clasehijo:','nodoHijoFin', $auxHijosHtml ).'</ul>';  $clase =':clase:'; }else {   $clase =':clasehijo:';}
    $listaHtml .="<li class='$clase' id='menu_{$value['id_recurso_sistema']}'>"
    . "<input type='checkbox' data-tipo='recurso' data-recurso_id='{$value['idrecurso']}' class='menu_{$value['id_recurso_sistema']}' />"
    . "<span>{$value['nombre_recurso']}</span>$auxHijosHtml</li>";  
}
$listaHtml = str_replace(':clase:','nodoAbiertoFin', $listaHtml );                                     
$listaHtml = str_replace(':clasehijo:','nodoHijoFin', $listaHtml );
$listaHtml .='<ul>';  
     return $menuHtml;
 } 
 ///////////////////////////////////////////////////
function verificaContenidoArray(array $arreglo , $columna ,$dato , $concordancia ){
     $retorno = array();
     $i = 0;
     foreach ($arreglo as $key => $value) {
       $registra = FALSE;
       if ($columna == '*'){
           foreach ($value as $value_aux) { 
                switch ($concordancia){
                case '0':
                   if (strtoupper($value_aux) == strtoupper($dato)){
                        $registra = true;
                        break 2;
                    }else
                    break;
                case '1'://donde sea
                   $poss = substr_count(strtoupper($value_aux),strtoupper($dato));
                    if ($poss >0){  
                        $registra = true;
                        break 2;
                    }else
                    break;
                case '3'://final
                     $poss = strripos($value_aux,$dato);
                     if ($poss !== false && $poss ==(strlen($value_aux)-strlen($dato))  ){
                        $registra = true;
                        break 1;
                    }else
                    break;
                case '2'://inicio
                   $poss = stripos($value_aux,$dato);
                   if ($poss !== false && $poss ==0){
                        $registra = true;
                        break 1;
                    }else
                    break;
            }
               
           } 
       }else{
               switch ($concordancia){
                case '0':
                   if (strtoupper($value[$columna]) == strtoupper($dato)){
                        $registra = true;
                
                    }
                    break;
                case '1'://donde sea
                    $poss = substr_count(strtoupper($value[$columna]),strtoupper($dato));
                    //echo "<br>cantidad de veces que aparece : $dato en {$value[$columna]}". $poss;
                    //substr_count($text, 'is')
                     if ($poss >0){
                        $registra = true;                
                    }
                    break;
                case '3'://final
                     $poss = strripos($value[$columna],$dato);
                     if ($poss !== false && $poss ==(strlen($value[$columna])-strlen($dato))  ){
                        $registra = true;                
                    }
                    break;
                case '2'://inicio
                   $poss = stripos($value[$columna],$dato);
                   if ($poss !== false && $poss ==0){
                        $registra = true;
                    }
                    break;
            }
               
       }
       
       if($registra){
        $retorno[$i] = $value ;
        $i++;
       }
     }
     return $retorno;
 }
////////////////////////////////////////////////////////////////////
function funciones_javaSc(){
        echo  'function irUrl(url){'.
        'if (url){window.location.href=url;}}'; 
    }    
function reemplazaAcentos($cadena){
  $cadena =   str_replace('á','&aacute;',$cadena) ;
  $cadena =    str_replace('é','&eacute;',$cadena) ;
  $cadena =    str_replace('í','&iacute;',$cadena) ;
  $cadena =    str_replace('ó','&oacute;',$cadena) ;
  $cadena =    str_replace('ú','&uacute;',$cadena) ;
  $cadena =    str_replace('Á','&Aacute;',$cadena) ;
  $cadena =    str_replace('É','&Eacute;',$cadena) ;
  $cadena =    str_replace('Í','&Iacute;',$cadena) ;
  $cadena =    str_replace('Ó','&Oacute;',$cadena) ;
  $cadena =    str_replace('Ú','&Uacute;',$cadena) ;
  $cadena =    str_replace('ñ','&ntilde;',$cadena) ;
  $cadena =    str_replace('Ñ','&Ntilde;',$cadena) ;
    return $cadena;
}
function getCentrosDelSistema($WERKS = null){
    global $datos;
    if(is_null($WERKS)){
       $query= " SELECT * FROM clientes.vw_listar_centros_del_sistema;";
    }else{
        $query= " SELECT * FROM clientes.vw_listar_centros_del_sistema where  WERKS = '$WERKS';";
    }
   $arrayVKORG = array();
   try {                   
            $conexion = Class_php\DataBase::getInstance();
            $link = $conexion->getLink();//vw_SAP_rfc_Areasdeventa
            $consulta = $link->prepare($query); 
            $consulta->execute();
            $registros = $consulta->fetchAll();
//           
//            $datos['datos'] = $registros;
//            $datos['filas'] = sizeof( $registros) ; 
            $COUNT = 0;
            if(sizeof( $registros) > 0){ 
                foreach ($registros as $key => $value) {
                    $arrayVKORG[$COUNT] = $value;
                    $COUNT++;
                }}
    
        } catch (PDOException $e) {
               $datos['error']=  'Error de conexión: ' . $e->getMessage();            
               exit();
        } 
        return $arrayVKORG;
}
function getPuntoDeExpedicion($VSTEL){
    $arreglo_ZMCV_RFC_PD_PSEXPED = array();
    header('Content-Type: text/html; charset=ISO-8859-1');  
    $arreglo_ZMCV_RFC_PD_PSEXPED = ZMCV_RFC_PD_PSEXPED($VSTEL);
    foreach ( $arreglo_ZMCV_RFC_PD_PSEXPED as $key => $value) {  
          $value['VTEXT']=reemplazaAcentos(utf8_encode( $value['VTEXT']));
        $arreglo=$value;                  
    }
    $arreglo_ZMCV_RFC_PD_PSEXPED = $arreglo; 
    RETURN $arreglo_ZMCV_RFC_PD_PSEXPED ; 
}
function getNombreAmacen($almacen = null){
      header('Content-Type: text/html; charset=ISO-8859-1'); 
         if (!isset($_SESSION['DATOS_RFC_ALMACENES_02']) || $_SESSION['DATOS_RFC_ALMACENES_02'] == '' || sizeof($_SESSION['DATOS_RFC_ALMACENES_02'])== 0){
            $arreglo_RFC_ALMACENES = ZMCV_RFC_ALMACENES_02();
            foreach ( $arreglo_RFC_ALMACENES as $key => $value) { 
                $value['NAME1']=reemplazaAcentos(utf8_encode( $value['NAME1'])); 
                  $value['LGOBE']=reemplazaAcentos(utf8_encode( $value['LGOBE']));
                  $value['VTEXT']=reemplazaAcentos(utf8_encode( $value['VTEXT']));
                $arreglo[$key]=$value;
                  
            }
            $_SESSION['DATOS_RFC_ALMACENES_02'] = $arreglo;
         }
         $arrayRegreso = array();
        $arreglo_RFC_ALMACENES =  $_SESSION['DATOS_RFC_ALMACENES_02'] ;  
        print_r($arreglo_RFC_ALMACENES);
        if (is_null($almacen)){}else{
        foreach ($arreglo_RFC_ALMACENES as $key => $value) {
            if ($almacen == $value[''] ) {
            $arrayRegreso = $value;}
        }
        }
}
// END FUNCTION
function amoneda($numero, $moneda = null)
{
 $moneda = is_null($moneda)? "pesos" : $moneda;
$longitud = strlen($numero);
$punto = substr($numero, -1,1);
$punto2 = substr($numero, 0,1);
$separador = ".";
if($punto == "."){
$numero = substr($numero, 0,$longitud-1);
$longitud = strlen($numero);
}
if($punto2 == "."){
$numero = "0".$numero;
$longitud = strlen($numero);
}
$num_entero = 0;
$num_entero = strpos ($numero, $separador);
$centavos = substr ($numero, ($num_entero));
                
$l_cent = strlen($centavos);
if($l_cent == 2){$centavos = $centavos."0";}
elseif($l_cent == 3){$centavos = $centavos;}
elseif($l_cent > 3){$centavos = substr($centavos, 0,3);}
$entero = substr($numero, -$longitud,$longitud-$l_cent);
if(!$num_entero){
	$num_entero = $longitud;
	$centavos = ".00";
	$entero = substr($numero, -$longitud,$longitud);
} 
$start = floor($num_entero/3);
$res = $num_entero-($start*3);
$final='';
if($res == 0){$coma = $start-1; $init = 0;}else{$coma = $start; $init = 3-$res;}

$d= $init; $i = 0; $c = $coma; 
$d=0;
	
         IF ($num_entero > 1){  
             $final = '';
             FOR ($i= $num_entero ;$i>=1;$i--){
                 $coma = '';
                 if ($d == 3){
                     $coma = ',';
                     $d = 0;
                 }
                 $d++; 
                 $aux_dato =  $entero[$i-1]; 
                 $final =  $aux_dato.$coma.$final;  
                 
             } 
                
         } else {
           $final =  $entero ;
        }
                
        /////////////////////
	if($moneda == "pesos")  {$moneda = "$";
	return $moneda." ".$final.$centavos;
	}
	elseif($moneda == "dolares"){$moneda = "USD";
	return $moneda." ".$final.$centavos;
	}
	elseif($moneda == "euros")  {$moneda = "EUR";
	return $final.$centavos." ".$moneda;
	}
}

Function OrdenPortal(){
        $sllave  = "";
       $letras=array("A", "B", "C", "D", "F", "G", "H", "I", "J", "K", "L", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "Y", "Z");
        $numeros=array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $aleatorio = 0 ;
        for($i=0;$i<=3;$i++)
        { 
          $aleatorio =  rand ( 0 ,(sizeof($numeros)-1) ) ;
          $sllave = $sllave . $numeros[$aleatorio];
        }
        for($i=0;$i<=3;$i++)
        {
         $aleatorio = rand ( 0 ,(sizeof($letras)-1) ) ; 
          $sllave = $sllave . $letras[$aleatorio];
        }
        

        Return $sllave;
}
function CREAR_SELECT($ID,$LABEL,$TABLA,$OPTION,$TEXT, array $WHERE = NULL ,  $groupby = false ,  array $arr_data = null , array $array_text = null ){ ?>
 <select  name="agente"  id="<?=$ID;?>" class=" form-control">
  <option value='' >Selecciona <?=$LABEL;?></option>
<?php 
try { 
    $conexion =\Class_php\DataBase::getInstance();
    $link = $conexion->getLink();    
}
catch (PDOException $e) {
   echo 'Error de conexión: ' . $e->getMessage();
}
if (!is_null($array_text) && sizeof($array_text) > 0){
    $WHERE_FINAL = ' WHERE ';
    $AND = '';
    foreach ($WHERE as $keyW => $valueW) {
        $WHERE_FINAL.= $AND ."{$value[$value3]['COL']} = {{$value[$value3]['VALOR']}}";
        $AND = ' AND ';
    }
}else{
    $WHERE_FINAL  = '';
}
    $datos_sel = '*';
    $GROUP_BY = '';
    if (!is_null($arr_data) && sizeof($arr_data) > 0){
        $coma ='';
        $datos_sel = '';
            foreach ($arr_data as $key2 => $value2) {
                $datos_sel .= $coma.$value2;
               $coma = ',';
            }
        if($groupby){
             $GROUP_BY = 'GROUP BY '.$datos_sel;
        }
    }
    $consulta = $link->prepare("SELECT  $datos_sel FROM  {$TABLA} {$WHERE_FINAL} {$GROUP_BY}  order by {$TEXT} desc");
//$datos['query'] = 'SELECT id_tipo  , nombre  FROM dbo.tipo_padre_elemento ';
    $consulta->execute();
    $registros = $consulta->fetchAll();
//  print_r($registros);
foreach ($registros as $key => $value) {
    $the_data = '';
    if (!is_null($arr_data) && sizeof($arr_data) > 0){
        foreach ($arr_data as $key2 => $value2) {
            $the_data.=" data-{$value2} = '{$value[$value2]}' ";
        }
    }
     $txt_texto = '';
    if (!is_null($array_text) && sizeof($array_text) > 0){
         $signo = '';
        foreach ($array_text as $key3 => $value3) {
             $txt_texto.="$signo{$value[$value3]}";
             $signo = '-';
        }
    }else{
        $txt_texto  = $value[$TEXT];
    } 
       ?>
        <option value='<?php echo $value[$OPTION] ;?>' <?php echo $the_data;?> ><?php echo $txt_texto ;?> </option> 
       <?php 
}
?>                                                 
</select> 
<?php                                                              
} 
function arrayReemplazaAcentos_utf8_encode(&$array){
    $func = function(&$value,&$key){
        if(is_string($value)){
            $value = reemplazaAcentos(utf8_encode($value));
        } 
        if(is_string($key)){
            $key = utf8_encode($key);
        }
        if(is_array($value)){
            arrayReemplazaAcentos_utf8_encode($value);
        }
    };
    array_walk($array,$func);
    return $array;
}
//reemplazaAcentos($cadena)
function arrayReemplazaAcentos_utf8_decode(&$array){
    $func = function(&$value,&$key){
        if(is_string($value)){
            $value = reemplazaAcentos(utf8_decode($value));
        } 
        if(is_string($key)){
            $key = utf8_decode($key);
        }
        if(is_array($value)){
            arrayReemplazaAcentos_utf8_decode($value);
        }
    };
    array_walk($array,$func);
    return $array;
}
function utf8_string_array_decode(&$array){
    $func = function(&$value,&$key){
        if(is_string($value)){
            $value = utf8_decode($value);
        } 
        if(is_string($key)){
            $key = utf8_decode($key);
        }
        if(is_array($value)){
            utf8_string_array_decode($value);
        }
    };
    array_walk($array,$func);
    return $array;
}

