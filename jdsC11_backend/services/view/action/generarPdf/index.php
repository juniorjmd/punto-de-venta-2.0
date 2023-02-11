<?php
header("Access-Control-Allow-Origin: * ");
header("Content-type:application/json; charset=utf-8");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding,Authorization,Autorizacion");


require_once '../../../../php/helpers.php';  
require_once'../../../../php/fpdf181/fpdf.php';
//echo print_r(FILTRAR_DATOS_RFC_EASYSALE_CLIENTES());
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
  //  ECHO $nomClass.'<BR>';
    require_once $nomClass;
 });
new Core\Config();  

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {    
  header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, autorizacion");
 header("HTTP/1.1 200 OK");
die();
}     

$headers = getallheaders();
foreach ($headers as $header => $value) { 
   
     //echo "<$header>";
   if ( strtoupper(trim($header))==='AUTORIZACION'){ 
       define('LLAVE_SESSION', TRIM($value));
     //  echo 'la definio '. LLAVE_SESSION;
   }
   
}  
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    http_response_code(200);
    
   $json = file_get_contents('php://input');
   $_POST = json_decode($json, true); 
//print_r($_POST);  
foreach ($_POST as $clave => $valor){
    $$clave = $valor;
}  
//echo "DESC_SUC_PRINCIPAL : ".DESC_SUC_PRINCIPAL;
switch ($action){ 
   case '23929870008e23007350be74a708ab3a806dce13':
      // generar_pdf_simulacros : 23929870008e23007350be74a708ab3a806dce13
          TRY {
       $conexion =Class_php\DataBase::getInstance();  
     $where = '';
      if (isset($_r1548juy) and is_numeric($_r1548juy)  and $_r1548juy > 0 )
      {
          $where = " cod_simulacro = $_r1548juy ";
      }
          if (isset($_85247efg) and is_numeric($_85247efg)  and $_85247efg > 0 )
      {
          if (trim($where) != ''){
              $where .= ' and '; 
          }
          $where .= " cod_estudiante = $_85247efg "; 
      } 
      if(trim($where) != ''){
        $where = 'where '. trim($where) ;
      }  
   
      $_result =$conexion->where('vw_simulacro_estudiante', $where);
      
      
      
      $_result2 =$conexion->where('vw_calificacion_por_materia', $where);
         $datos_clientes = $_result['datos'];
      $datos_materias = $_result2['datos'];
      $contador = 0;
      $arrayMaterias = array();
      foreach ($datos_materias as $value2) {
          $arrayMaterias[$value2['cod_estudiante']] = array();
      }
       foreach ($datos_materias as $value2) {
           $arrayMateria['materia'] = $value2['nombre_materia'];
           $arrayMateria['puntaje'] = $value2['total_materia'];
           $arrayMateria['porcentaje'] = 10 ;
           array_push( $arrayMaterias[$value2['cod_estudiante']], $arrayMateria ) ;
          // $arrayMaterias[$value2['cod_estudiante']]
       } 
      // print_r($datos_clientes);
      foreach ($datos_clientes as $value) {
         $estudiante['PUNTAJE_GLOBAL'] = $value['valor_total_simulacro'];  
         $estudiante['SIMULACRO'] = $value['cod_simulacro'];   
         $estudiante['CURSO'] = $value['descripcion'];  
         $estudiante['COLEGIO'] = $value['razon_social'];  
         $estudiante['NOMBRE_APELLIDO'] = $value['nombre'];  
         $estudiante['FECHA_APLICACION'] = $value['fecha_simulacro']; 
         $estudiante['FECHA_RESULTADOS'] = $value['fecha_cierre'];  
         $estudiante['NUMERO_DE_REGISTOS'] = $value['cod_colegio'].$value['cod_curso'].$value['cod_estudiante'];
         $estudiante['DATOS_POR_MATERIA'] =   $arrayMaterias[$value['cod_estudiante']] ;
         $estudiante['ruta_resultado'] = generaPdfEstudiantes($estudiante);
         $simulacros[$contador] = $estudiante;
         $contador++;
      }
      $datos['resultados_simulacros'] = $simulacros;
      $datos['query']= $_result['query'];
      
      $datos['numdata']= sizeof($_result['datos']);
      $datos['error'] ='ok';  
            
        
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
     break;
    
    case 'e06c06e7e4ef58bdb0cf1858541b3017fdd35473'://select .
        /*
     
    action = e06c06e7e4ef58bdb0cf1858541b3017fdd35473
    _tabla =  nombre de la tabla 
    _where array({columna : nombrecolumna , tipocomp : tipocomp , dato : dato}) 
         * 
         * 
    tipocomp ahi colocaremos que tipo de comparacion hacemos igual (=) diferente (<>) menor que (<) like, en todas menos en like se coloca el signo 
    
    _where es un array asociativo con todos los que vamos a buscar.
    
    en javascript seria algo como 
    _where = [{columna : nombrecolumna , tipocomp : tipocomp , dato : dato} , {columna : nombrecolumna , tipocomp : tipocomp , dato : dato},
        {columna : nombrecolumna , tipocomp : tipocomp , dato : dato}
       ]
         * 
     _tablau =  nombre de la tabla para la union de la consulta
    _whereu array({columna : nombrecolumna , tipocomp : tipocomp , dato : dato}) igual que el where de la tabla principal pero para la tabla de union
    */
         TRY {
       $conexion =Class_php\DataBase::getInstance(); 
       //($tabla,$where = null)
       $where = '';
       if (isset($_where) and sizeof($_where)> 0 and is_array($_where)){
         $where = ' WHERE '; 
         $and = ''; 
         foreach ($_where as $clave => $valor){
             $valor['dato'] = trim($valor['dato']);
             $_dato = "'{$valor['dato']}'";
             if (trim($valor['tipocomp']) == 'like'){
                 $_dato = "'%{$valor['dato']}%'";
             }
             if (trim($valor['tipocomp']) == 'in'){
                 $_dato = "({$valor['dato']})";
             }
            $where.= " $and {$valor['columna']} {$valor['tipocomp']} $_dato "; 
            $and = ' and ';
         }
       } 
       
          $whereu = '';
       if (isset($_whereu) and sizeof($_whereu)> 0 and is_array($_whereu)){
         $whereu = ' WHERE '; 
         $and = ''; 
         foreach ($_whereu as $clave => $valor){
             $valor['dato'] = trim($valor['dato']);
             $_dato = "'{$valor['dato']}'";
             if (trim($valor['tipocomp']) == 'like'){
                 $_dato = "'%{$valor['dato']}%'";
             }
             if (trim($valor['tipocomp']) == 'in'){
                 $_dato = "({$valor['dato']})";
             }
            $whereu.= " $and {$valor['columna']} {$valor['tipocomp']} $_dato "; 
            $and = ' and ';
         }
       }
       
      if  (isset($_tablau) && trim($_tablau) != ''){
          $_result =$conexion->where_union($_tabla, $where,$_tablau, $whereu);
      }else{
      $_result =$conexion->where($_tabla, $where);}
      /*$array['datos'] =  $consulta->fetchAll();
         $array['query'] */
      $datos['data']= $_result['datos'];
      $datos['query']= $_result['query'];
      
      $datos['numdata']= sizeof($_result['datos']);
      $datos['error'] ='ok';  
            
        
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    
       
       
    break;

case 'b0ecfb6a24fa75f0108286c898f3dea3158655d2'://delete .
        /*
      delete_estudiante_simulacro : b0ecfb6a24fa75f0108286c898f3dea3158655d2 
     
    action = b0ecfb6a24fa75f0108286c898f3dea3158655d2
    _id_simulacro =  nombre de la tabla  
	_lista_estudiantes = array estudiantes
    */
         TRY {
		  $conexion =\Class_php\DataBase::getInstance();
          $link = $conexion->getLink();   
           $_values = $_coma ='';
           foreach ($_lista_estudiantes as $clave => $valor){
             $valor= trim($valor);
             $valor = "'$valor'";
             $_values .= " $_coma  $valor " ;             
            $_coma = ',';
        }     
     $queryFinal = "delete from `simulacros_estudiantes` where  cod_simulacro = '$_id_simulacro' and  cod_estudiante in ( $_values ); ";
	$consulta = $link->prepare($queryFinal);
                    $datos['queryFinal'] = $queryFinal;
                    if ($consulta->execute()){
                      $datos['error']='ok';  

                    }else{
					$datos['error']="Error al tratar de ingresar los valores a la base de datos TABLA ($_tabla)"; }
      
        
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexi��n: ' . $e->getMessage();
           
     }
    
       
       
    break;    
    
 case 'e251ba9c423f96654579e892fd50d50a38337616'://insert estudiantes .
        /*
		insert_estudiante_simulacro : e251ba9c423f96654579e892fd50d50a38337616
     
    action = e251ba9c423f96654579e892fd50d50a38337616
    _id_simulacro =  nombre de la tabla  
	_lista_estudiantes = array estudiantes
    */
         TRY {
		  $conexion =\Class_php\DataBase::getInstance();
          $link = $conexion->getLink();   
           $_values = $_coma ='';
           foreach ($_lista_estudiantes as $clave => $valor){
             $valor= trim($valor);
             $valor = "'$valor'";
             $_values .= "$_coma ('$_id_simulacro',$valor)" ;             
            $_coma = ',';
        }     
     $queryFinal = "INSERT INTO  `simulacros_estudiantes` ( cod_simulacro , cod_estudiante) VALUES $_values  ; ";
	 
						
	 
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryFinal'] = $queryFinal;
                    if ($consulta->execute()){
                      $datos['error']='ok';  

                    }else{
					$datos['error']="Error al tratar de ingresar los valores a la base de datos TABLA ($_tabla)"; }
      
        
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexi��n: ' . $e->getMessage();
           
     }
    
       
       
    break;
    
    
    case 'da5cbea2f73b029d0ce3a1dc2a05a46e7f0461c4'://insert
        $_columnas = '';
        $_valores = '';
        $_coma = '';
          
 TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
        /*
        _arraydatos = {col1 : dato1 , col2 : dato2 }
        _tabla = nombre de la tabla
        */
         $_parent_ini = '(';
        foreach ($_arraydatos as $clave => $valor){
            
             $valor= trim($valor);
             $valor = "'$valor'";
             
             $_columnas .= "  $_coma $clave ";
             $_valores  .= "  $_coma $valor";
             $_parent_ini = '';
            $_coma = ',';
        }
         
$queryFinal = "INSERT INTO  `$_tabla` ( $_columnas ) VALUES( $_valores); ";
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryFinal'] = $queryFinal;
                    if ($consulta->execute()){
                      $datos['error']='ok';  

                    }else{ $datos['error']="Error al tratar de ingresar los valores a la base de datos TABLA ($_tabla)"; }
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    
    break;
    
    
    
    
    case '781e41a4c6237dbaecab19579643041de310c041'://insert usuario nuevo
        $_columnas = '';
        $_valores = '';
        $_coma = '';
        //$_passGenerado = generaPass();
        $_sha_pass = sha1($pass);
        $_mail_usuario = '' ;   
   
 TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
        /*
        _arraydatos = {col1 : dato1 , col2 : dato2 }
        _tabla = nombre de la tabla
        */
         $_parent_ini = '(';
         $bandera = false;
        foreach ($_arraydatos as $clave => $valor){
             $valor= trim($valor);
             if(trim($clave) == 'cod_persona'){
                 $query_mail = 'select email , nombre from persona where cod_persona = ? ';
                 $_mail_usuario = '' ; 
                 $consulta = $link->prepare($query_mail);
                 //$datos['queryFinal'] = $query_mail;
                 $consulta->execute([$valor]); 
                 $array_datos =  $consulta->fetchAll();
                 $_mail_usuario = $array_datos['email'];
                 $_nombre_persona = $array_datos['nombre'];
             } 
             if(trim($clave) == 'usuario'){
                 $_usuario = $clave;
             }
             
             //$valor = "'$valor'";
             
             $_columnas .= "  $_coma $clave ";
             if(trim($clave) == 'pass'){
                 $_sha_pass = sha1($valor);
                 $datos['pass'] = $valor;
                  $_valores  .= "  $_coma '$_sha_pass' ";
                  $bandera =  true;
             }else{ $_valores  .= "  $_coma '$valor'";}
             
            
              
             
             $_parent_ini = '';
            $_coma = ',';
        }
        
        if (!$bandera){
            $_valores  .= "  $_coma '$_sha_pass' ";
            $_columnas .= "  $_coma pass ";
        }
         
$queryFinal = "INSERT INTO  `$_tabla` ( $_columnas ) VALUES( $_valores); ";
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryFinal'] = $queryFinal;
                    if ($consulta->execute()){
                      $datos['error']='ok';  
                      
$mail_from = 'administrador@sicae.jdpsoluciones.com'   ;                   
$headers = "From: " . strip_tags($mail_from) . "\r\n";
$headers .= "Reply-To: ". strip_tags($mail_from) . "\r\n";
$headers .= "CC: juniorjmd@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
			//$message .= '<img src="http://css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" />';
			$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
			$message .= "<tr style='background: #eee;'><td><strong>Nombre :</strong> </td><td>" . strip_tags($_nombre_persona) . "</td></tr>";
			$message .= "<tr><td><strong>Usuario:</strong> </td><td>" . strip_tags($_usuario) . "</td></tr>";
			$message .= "<tr><td><strong>Contraseña:</strong> </td><td>" . strip_tags($_passGenerado) . "</td></tr>";
   
			$message .= "</table>";
			$message .= "</body></html>";
                      
                      mail($_mail_usuario, 'SICAE - PASS INICIAL', $message, $headers);  
                    }else{ $datos['error']="Error al tratar de ingresar los valores a la base de datos TABLA ($_tabla)"; }
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    
    break;
    case '3f1b76f2d7c28054c92ab1d00ef626b45ab80a8a'://delete
    /*
    delete 
    action = 3f1b76f2d7c28054c92ab1d00ef626b45ab80a8a
    _tabla 
    _dato
    _columna 
    */
         TRY {
      $conexion =Class_php\DataBase::getInstance(); 
        $where = '';
       if (isset($_where) and sizeof($_where)> 0 and is_array($_where)){
         $where = ' WHERE '; 
         $and = ''; 
         foreach ($_where as $clave => $valor){
             $valor['dato'] = trim($valor['dato']);
             $_dato = "'{$valor['dato']}'";
             if (trim($valor['tipocomp']) == 'like'){
                 $_dato = "'%{$valor['dato']}%'";
             }
             if (trim($valor['tipocomp']) == 'in'){
                 $_dato = "({$valor['dato']})";
             }
            $where.= " $and {$valor['columna']} {$valor['tipocomp']} $_dato "; 
            $and = ' and ';
         }
      $queryFinal = "delete from $_tabla $where";
      $link = $conexion->getLink();  
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryFinal'] = $queryFinal;
                    if ($consulta->execute()){
                      $datos['error']='ok';  

                    }else{http_response_code(500);
                        $datos['error']="Error al tratar de actualizar los valores a la base de datos TABLA ($_tabla)"; 
                    }
     
           }else{
               $datos['error']="Error no se reconoce la condicion necesario para eliminar los datos";
                http_response_code(500);
           } 
      
     /* $_result =$conexion->eliminarDato($_tabla,  $_dato ,$_columna);
      switch ($_result[0]['result'])
      {
           case '100':
                $datos['error'] ='ok';  
           break;
           case '-1':
                http_response_code(501);
                $datos['error'] ='_tabla';  
           break;   
           case '-2':
                http_response_code(502);
                $datos['error'] ='_dato';  
           break;   
           case '-3':
                http_response_code(503);
                $datos['error'] ='_COLUMNA';  
           break;   
           default :
                http_response_code(504);
                $datos['error'] = $_result[0]['result'];
           break;
       }*/
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexion: ' . $e->getMessage();
           
     }
    
    break;





    case '9d9fa03fe878f82f47b0befd5421049b989eb5d2':
            /*
     
    action = 9d9fa03fe878f82f47b0befd5421049b989eb5d2
     _arraydatos = {col1 : dato1 , col2 : dato2 }
    _tabla =  nombre de la tabla 
    _where array({columna : nombrecolumna , tipocomp : tipocomp , dato : dato}) 
    tipocomp ahi colocaremos que tipo de comparacion hacemos igual (=) diferente (<>) menor que (<) like, en todas menos en like se coloca el signo 
    
    _where es un array asociativo con todos los que vamos a buscar.
    
    en javascript seria algo como 
    _where = [{columna : nombrecolumna , tipocomp : tipocomp , dato : dato} , {columna : nombrecolumna , tipocomp : tipocomp , dato : dato},
        {columna : nombrecolumna , tipocomp : tipocomp , dato : dato}
       ]
    */
         TRY { 
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
       //($tabla,$where = null)
       $where = '';
       if (isset($_where) and sizeof($_where)> 0){
         $where = ' WHERE '; 
         $and = '';
         foreach ($_where as $clave => $valor){
             $valor['dato'] = trim($valor['dato']);
             $_dato = "'{$valor['dato']}'";
             if (trim($valor['tipocomp']) == 'like'){
                 $_dato = "'%{$valor['dato']}%'";
             }
              if (trim($valor['tipocomp']) == 'in'){
                 $_dato = "({$valor['dato']})";
             }
            $where.= " $and {$valor['columna']} {$valor['tipocomp']} $_dato "; 
            $and = ' and ';
         }
       } 
       
       
       
        $_columnas = '';
        $_valores = '';
        $_coma = ''; 
         
        foreach ($_arraydatos as $clave => $valor){
              
              $valor  = trim($valor);
             $valor = "'$valor'";
             
             $_valores .= "  $_coma $clave =  $valor";
            $_coma = ',';
        }
         
        $queryFinal = "update `$_tabla` set "
                   ."   $_valores  $where ; ";
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryFinal'] = $queryFinal;
                    if ($consulta->execute()){
                      $datos['error']='ok';  

                    }else{ $datos['error']="Error al tratar de actualizar los valores a la base de datos TABLA ($_tabla)"; }
    
            
        
      }
      catch (PDOException $e) {
          http_response_code(500);
        $datos['error']= 'Error de conexion: ' . $e->getMessage();
           
     }
    break;
default :
 http_response_code(520);
        $datos['error']= 'Accion no valida';
  break; 
}


 
}ELSE{
     http_response_code(510);
        $datos['error']= 'Metodo no valido';
}
echo json_encode($datos);