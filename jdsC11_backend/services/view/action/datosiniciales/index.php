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
   case 'GET_SUCURSAL_PRINCIPAL_DATA':
         TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
        /*
         * _qazxswe = codigo registro
         _poikmjuy = clave actual
         _wsxedc = clave nueva
         _kjhgtyuhybv  clave nueva confirmacion
        */
        $_key = DESC_SUC_PRINCIPAL;
         $where = " where `descripcion` =  '{$_key}' ";
         $_result =$conexion->where('vw_sucursales', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){
             $datos = $_result['datos'] ;
         }else{
            $datos['error']= 'Error de datos, No existen valores iniciales para consultar ' ;
           echo json_encode($datos);
             http_response_code(500);
           die();  
         } 
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
       break;
   
   case '52444d9072f7ec12a26cb2879ebb4ab0bf5aa553' :
       TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
        /*
         * _qazxswe = codigo registro
         _poikmjuy = clave actual
         _wsxedc = clave nueva
         _kjhgtyuhybv  clave nueva confirmacion
        */
        $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){
             $_qazxswe = $_result['datos'][0]['usuario'];
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        // $datos['$_qazxswe'] = $_qazxswe;

         if ( trim($_qazxswe) === '' or !isset($_qazxswe) or 
         trim($_poikmjuy) === '' or !isset ($_poikmjuy) or
         trim($_wsxedc) === '' or !isset ($_wsxedc) or       
         trim($_kjhgtyuhybv) === '' or !isset($_kjhgtyuhybv) ) 
         {             
           http_response_code(200);
           $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();
         } 
         
         $_poikmjuy =  sha1($_poikmjuy); 
         $_wsxedc =  sha1($_wsxedc); 
         $_kjhgtyuhybv =  sha1($_kjhgtyuhybv); 
         $where = " where cod_registro = {$_qazxswe} and pass = '{$_poikmjuy}' ";
         $_result =$conexion->where('usuario', $where);
         if (sizeof($_result['datos']) <= 0){
             http_response_code(200);
           $datos['error']= 'Error de datos' ;
           echo json_encode($datos);
           die();
         }
         if ($_wsxedc <> $_kjhgtyuhybv){
               http_response_code(200);
           $datos['error']= 'Error de datos - Las contraseñas ingresadas no coinciden' ;
           echo json_encode($datos);
           die();
         }
         //`cod_registro`, `cod_persona`, `descripcion`, `perfil`, `usuario`, `pass`, `estado`SELECT * FROM `usuario` WHERE 1         
         $queryFinal = "update  usuario set  pass = '{$_wsxedc}' ".
                       " where cod_registro = {$_qazxswe} and pass = '{$_poikmjuy}' ";;
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
   case '52444d9072f7ec12aJEE8FFJJKVNASDHQWFLKA' :
       TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
        /*
         * _qazxswe = codigo registro 
         _wsxedc = clave nueva
         _kjhgtyuhybv  clave nueva confirmacion
        */ 
         $where = " where `cod_registro` =  '{$_qazxswe}' ";
         $_result =$conexion->where('vw_usuario', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){
             $_qazxswe = $_result['datos'][0]['usuario'];
         }else{
            $datos['error']= 'Error de datos, el codigo de usuario incorrecto ' ;
           echo json_encode($datos);
           die();  
         }
        // $datos['$_qazxswe'] = $_qazxswe;

         if ( trim($_qazxswe) === '' or !isset($_qazxswe) or  
         trim($_wsxedc) === '' or !isset ($_wsxedc) or       
         trim($_kjhgtyuhybv) === '' or !isset($_kjhgtyuhybv) ) 
         {             
           http_response_code(200);
           $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();
         } 
   
         $_wsxedc =  sha1($_wsxedc); 
         $_kjhgtyuhybv =  sha1($_kjhgtyuhybv);  
         if ($_wsxedc <> $_kjhgtyuhybv){
               http_response_code(200);
           $datos['error']= 'Error de datos - Las contraseñas ingresadas no coinciden' ;
           echo json_encode($datos);
           die();
         }
         //`cod_registro`, `cod_persona`, `descripcion`, `perfil`, `usuario`, `pass`, `estado`SELECT * FROM `usuario` WHERE 1         
         $queryFinal = "update  usuario set  pass = '{$_wsxedc}' ".
                       " where cod_registro = {$_qazxswe} ";
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
   case '8e9ae038c37d3b59fc1eed456c77aefb5eadffea' :
    //   obtener_resultados_simulacros : 8e9ae038c37d3b59fc1eed456c77aefb5eadffea
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
      //SELECT * FROM jdpsoluc_sicae_snbx.vw_calificacion_por_materia;
      $_result =$conexion->where('vw_simulacro_estudiante', $where);
      
      $_result2 =$conexion->where('vw_calificacion_por_materia', $where);
      /*$array['datos'] =  $consulta->fetchAll();
         $array['query']   
       * codigo_sistema, cod_curso, cod_colegio, descripcion, nombre_curso, anio, razon_social, colegio_direccion, colegio_email, cod_representante, nit, tip_identificacion, cod_identificacion, nombre, direccion, email, tel1, tel2, f_nacimiento, lugar_nacimiento, cod_persona, cod_simulacro, fecha_simulacro, fecha_cierre, num_estudiantes, numero_preguntas, numero_buenas, numero_malas, total_suma_materias, valor_total_simulacro       */ 
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
      foreach ($datos_clientes as $value) {
         $estudiante['PUNTAJE_GLOBAL'] = $value['valor_total_simulacro'];  
         $estudiante['CURSO'] = $value['descripcion'];  
         $estudiante['COLEGIO'] = $value['razon_social'];  
         $estudiante['NOMBRE_APELLIDO'] = $value['nombre'];  
         $estudiante['FECHA_APLICACION'] = $value['fecha_simulacro']; 
         $estudiante['FECHA_RESULTADOS'] = $value['fecha_cierre'];  
         $estudiante['NUMERO_DE_REGISTOS'] = $value['cod_colegio'].$value['cod_curso'].$value['cod_estudiante'];
         $estudiante['DATOS_POR_MATERIA'] =   $arrayMaterias[$value['cod_estudiante']] ;
         $simulacros[$contador] = $estudiante;
          $contador++;
         // $nombre_generado = generaPdfEstudiantes($estudiante);
   
      } 
   
      $datos['resultados_simulacros'] = $simulacros;
      $datos['numdata']= sizeof($_result['datos']);
      $datos['error'] ='ok';  
            
        
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    
       
   
     break;  
   case '99c505a66a9d8a984059baf1b99bb9e6456ae4bb': //insert preguntaspreguntas formularo 
             /*
		{"action":"99c505a66a9d8a984059baf1b99bb9e6456ae4bb","componente":"1","formulario":"12","preguntas":["42","45","46"]}
    */
       
         TRY {
       $error_en_algo = 0;
		  $conexion =\Class_php\DataBase::getInstance();
          $link = $conexion->getLink();   
           $_values = $_coma ='';
           $count=0;
           $numero_en_formulario = 10;
           $coma = '';
           //$componente , $formulario ;
          $query="select orden_general_formulario ($formulario , $componente) as orden_general ;";
       $datos['orden_general_formulario']  = $query;
       $consulta = $link->prepare($query);
         $consulta->execute(); 
         $datos_orden =  $consulta->fetchAll(); 
          $nEnFormularioArr  = $datos_orden[0]; 
          $numero_en_formulario = $nEnFormularioArr['orden_general'];
   
           $datos['numero_en_formulario']  = $numero_en_formulario;
           
           
           $query = "select orden_componente_en_formulario ($formulario , $componente) as orden_componente_en_formulario";
           $datos['orden_general_formulario']  = $query;
       $consulta = $link->prepare($query);
         $consulta->execute(); 
         $datos_orden =  $consulta->fetchAll(); 
          $nEnFormularioArr  = $datos_orden[0]; 
          $numero_componente_en_formulario = $nEnFormularioArr['orden_componente_en_formulario'];
          $datos['numero_componente_en_formulario']  = $numero_componente_en_formulario;
   
           foreach ($preguntas as $clave => $valor){
               $datos_delete .= "$coma $valor ";
               $coma = ',';
            }  
            //   $queryFinal =  "delete from preguntas_formulario where cod_pregunta not in ($datos_delete) and cod_formulario = $formulario ";
             $queryFinal = "delete from preguntas_formulario where cod_reg in " .
                "(select cod_reg from  ( select cod_reg from  preguntas_formulario pf " . 
                "inner join preguntas p on pf.cod_pregunta = p.cod_pregunta " . 
                "inner join formularios f on  f.cod_formulario = pf.cod_formulario  " . 
                "inner join relacion_grupo_formularios_componentes r on cod_grupo_formulario = grupo_formulario and r.cod_componente = p.cod_componente " .
                "where pf.cod_formulario =  $formulario   " .
                "and r.cod_componente = $componente";
              if (trim($datos_delete) <> '' )   
                $queryFinal .= " and pf.cod_pregunta  not in ($datos_delete)" ;
             
              $queryFinal .= " )  t)";
             
             
            $consulta = $link->prepare($queryFinal);
             $datos['query_delete'] = $queryFinal;
             if ($consulta->execute()){
                 $datos['error_delete'] ='ok';  
             }else{$datos['error_delete'] ="Error al tratar de ingresar los valores a la base de datos "; $error_en_algo = 1 ;}
   
   
           foreach ($preguntas as $clave => $valor){
            $count++;
            $numero_en_formulario++;   
            $queryFinal = "INSERT INTO  `preguntas_formulario` (`cod_formulario`, `cod_pregunta`, `estado`, `orden`, `numero_en_formulario`) VALUES ($formulario, $valor, '1', $count, $numero_en_formulario) " 
             . "ON DUPLICATE KEY UPDATE "
             . "`orden` = $count, `numero_en_formulario` = $numero_en_formulario" ;
             $consulta = $link->prepare($queryFinal);
             $datos['queryFinal'][$clave] = $queryFinal;
             if ($consulta->execute()){
                 $datos['error_insert'][$clave] ='ok';  
             }else{$datos['error_insert'][$clave] ="Error al tratar de ingresar los valores a la base de datos "; $error_en_algo = 1 ;}
        }
        
        $queryActualizarNumForm = "select pf.cod_reg , pf.cod_pregunta  from preguntas_formulario pf ".
                                  "inner join preguntas p on pf.cod_pregunta = p.cod_pregunta ".
                                  "inner join formularios f on  f.cod_formulario = pf.cod_formulario ".
                                  "inner join relacion_grupo_formularios_componentes r on r.cod_grupo_formulario = grupo_formulario ".
                                  "and r.cod_componente = p.cod_componente ". 
                                  "where f.cod_formulario= $formulario ".
                                  "and r.orden_formulario > $numero_componente_en_formulario order by pf.numero_en_formulario;";
         $datos['queryActualizarNumForm'] = $queryActualizarNumForm;
         $consulta = $link->prepare($queryActualizarNumForm);
         $consulta->execute(); 
         $datos_orden =  $consulta->fetchAll(); 
         //echo'----';
         //print_r($datos_orden);
         
         foreach ($datos_orden as $llave => $array_orden) {
             $numero_en_formulario++; 
             $qryUpdOrden = "update preguntas_formulario set numero_en_formulario = {$numero_en_formulario}"
                          . " where cod_reg = {$array_orden['cod_reg']}";
             $datos['qryUpdOrden'][$llave] = $qryUpdOrden;
             $consulta = $link->prepare($qryUpdOrden);
             $consulta->execute(); 
         } 
          
          
          
        $datos['error'] ='ok';  
         if ($error_en_algo == 1){
             $datos['error'] ='Error en el proceso de insercion de una o mas preguntas'; 
         }
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexion: ' . $e->getMessage();
           
     }
    
       
       
    break;
    
   case '54cf0ad78873b07d7756976e37b6ed1e659a573f'://insert respuestas
           TRY {
       
       //llave_registro ;
       $conexion =Class_php\DataBase::getInstance(); 
       //($tabla,$where = null)
       $link = $conexion->getLink();   
       $queryFinal = "insert into simulacros_resultados (llave_registro, cod_simulacro, cod_estudiante, fecha, usuario, cod_formulario,"
               . " anw_1, anw_2, anw_3, anw_4, anw_5, anw_6, anw_7, anw_8, anw_9, anw_10, anw_11, anw_12, anw_13, anw_14,"
               . " anw_15, anw_16, anw_17, anw_18, anw_19, anw_20, anw_21, anw_22, anw_23, anw_24, anw_25, anw_26, anw_27, "
               . "anw_28, anw_29, anw_30, anw_31, anw_32, anw_33, anw_34, anw_35, anw_36, anw_37, anw_38, anw_39, anw_40, "
               . "anw_41, anw_42, anw_43, anw_44, anw_45, anw_46, anw_47, anw_48, anw_49, anw_50, anw_51, anw_52, anw_53, "
               . "anw_54, anw_55, anw_56, anw_57, anw_58, anw_59, anw_60, anw_61, anw_62, anw_63, anw_64, anw_65, anw_66, "
               . "anw_67, anw_68, anw_69, anw_70, anw_71, anw_72, anw_73, anw_74, anw_75, anw_76, anw_77, anw_78, anw_79,"
               . " anw_80, anw_81, anw_82, anw_83, anw_84, anw_85, anw_86, anw_87, anw_88, anw_89, anw_90, anw_91, anw_92,"
               . " anw_93, anw_94, anw_95, anw_96, anw_97, anw_98, anw_99, anw_100, anw_101, anw_102, anw_103, anw_104, "
               . "anw_105, anw_106, anw_107, anw_108, anw_109, anw_110, anw_111, anw_112, anw_113, anw_114, anw_115, "
               . "anw_116, anw_117, anw_118, anw_119, anw_120, anw_121, anw_122, anw_123, anw_124, anw_125, anw_126, "
               . "anw_127, anw_128, anw_129, anw_130, anw_131, anw_132, anw_133, anw_134 ) "
               . "select '$_llave_registro', cod_simulacro, cod_estudiante,fecha, usuario, id_formulario,pregunta010, pregunta011, "
               . "pregunta012, pregunta013, pregunta014, pregunta015, pregunta016, pregunta017, pregunta018,"
               . " pregunta019, pregunta020, pregunta021, pregunta022, pregunta023, pregunta024, pregunta025, "
               . "pregunta026, pregunta027, pregunta028, pregunta029, pregunta030, pregunta031, pregunta032, "
               . "pregunta033, pregunta034, pregunta035, pregunta036, pregunta037, pregunta038, pregunta039,"
               . "pregunta040, pregunta041, pregunta042, pregunta043, pregunta044, pregunta045, pregunta046, "
               . "pregunta047, pregunta048, pregunta049, pregunta050, pregunta051, pregunta052, pregunta053, "
               . "pregunta054, pregunta055, pregunta056, pregunta057, pregunta058, pregunta059, pregunta060, "
               . "pregunta061, pregunta062, pregunta063, pregunta064, pregunta065, pregunta066, pregunta067,"
               . " pregunta068, pregunta069, pregunta070, pregunta071, pregunta072, pregunta073, pregunta074, "
               . "pregunta075, pregunta076, pregunta077, pregunta078, pregunta079, pregunta080, pregunta081, "
               . "pregunta082, pregunta083, pregunta084, pregunta085, pregunta086, pregunta087, pregunta088,"
               . " pregunta089, pregunta090, pregunta091, pregunta092, pregunta093, pregunta094, pregunta095, "
               . "pregunta096, pregunta097, pregunta098, pregunta099, pregunta100, pregunta101, pregunta102, "
               . "pregunta103, pregunta104, pregunta105, pregunta106, pregunta107, pregunta108, pregunta109, "
               . "pregunta110, pregunta111, pregunta112, pregunta113, pregunta114, pregunta115, pregunta116, "
               . "pregunta117, pregunta118, pregunta119, pregunta120, pregunta121, pregunta122, pregunta123, "
               . "pregunta124, pregunta125, pregunta126, pregunta127, pregunta128, pregunta129, pregunta130, "
               . "pregunta131, pregunta132, pregunta133, pregunta134, pregunta135, pregunta136, pregunta137, "
               . "pregunta138, pregunta139, pregunta140, pregunta141, pregunta142, pregunta143 "
               . "from vw_respuestas_ingresadas_validadas where   correcto <> 'N' "
               . "and llave_registro = '$_llave_registro'  order by cod_estudiante ; ";
	$consulta = $link->prepare($queryFinal);
        $datos['queryFinal'] = $queryFinal;
        if ($consulta->execute()){
          /*$where=" where  $columna_id  in ( $id_insert ) ";}
		   $_result =$mbd->where($vista, $where );
                   $respuesta['idinsert'] = $id_insert;
           
      $respuesta['datos']= $_result['datos'];
      $respuesta['query']= $_result['query'];
      $respuesta['numdata']= sizeof($_result['datos']);*/  
          $where=" where llave_registro = '$_llave_registro'  ";
	$_result =$conexion->where('simulacros_resultados', $where );  
        $_result_datos = $_result['datos'];
        $count =0;
        foreach ($_result_datos as $key => $value) {
          $query =  "call ingresa_evaluacion_estudiante ("
                  . " '{$value['cod_simulacro']}' , '{$value['cod_formulario']}' , '{$value['cod_estudiante']}'   , '{$value['usuario']}' , "
                  . "'{$value['anw_1']}' , '{$value['anw_2']}' , '{$value['anw_3']}' , '{$value['anw_4']}' , '{$value['anw_5']}' , '{$value['anw_6']}' ,"
                  . "'{$value['anw_7']}' , '{$value['anw_8']}' , '{$value['anw_9']}' , '{$value['anw_10']}' , '{$value['anw_11']}' , '{$value['anw_12']}' ,"
                  . "'{$value['anw_13']}' , '{$value['anw_14']}' , '{$value['anw_15']}' , '{$value['anw_16']}' , '{$value['anw_17']}' , '{$value['anw_18']}'"
                  . ", '{$value['anw_19']}' , '{$value['anw_20']}' , '{$value['anw_21']}' , '{$value['anw_22']}' , '{$value['anw_23']}' , '{$value['anw_24']}'"
                  . ", '{$value['anw_25']}' , '{$value['anw_26']}' , '{$value['anw_27']}' , '{$value['anw_28']}' , '{$value['anw_29']}' , '{$value['anw_30']}'"
                  . ", '{$value['anw_31']}' , '{$value['anw_32']}' , '{$value['anw_33']}' , '{$value['anw_34']}' , '{$value['anw_35']}' , '{$value['anw_36']}'"
                  . ", '{$value['anw_37']}' , '{$value['anw_38']}' , '{$value['anw_39']}' , '{$value['anw_40']}' , '{$value['anw_41']}' , '{$value['anw_42']}' "
                  . ", '{$value['anw_43']}' , '{$value['anw_44']}' , '{$value['anw_45']}' , '{$value['anw_46']}' , '{$value['anw_47']}' , '{$value['anw_48']}' "
                  . ", '{$value['anw_49']}' , '{$value['anw_50']}' , '{$value['anw_51']}' , '{$value['anw_52']}' , '{$value['anw_53']}' , '{$value['anw_54']}' "
                  . ", '{$value['anw_55']}' , '{$value['anw_56']}' , '{$value['anw_57']}' , '{$value['anw_58']}' , '{$value['anw_59']}' , '{$value['anw_60']}' "
                  . ", '{$value['anw_61']}' , '{$value['anw_62']}' , '{$value['anw_63']}' , '{$value['anw_64']}' , '{$value['anw_65']}' , '{$value['anw_66']}' "
                  . ", '{$value['anw_67']}' , '{$value['anw_68']}' , '{$value['anw_69']}' , '{$value['anw_70']}' , '{$value['anw_71']}' , '{$value['anw_72']}' "
                  . ", '{$value['anw_73']}' , '{$value['anw_74']}' , '{$value['anw_75']}' , '{$value['anw_76']}' , '{$value['anw_77']}' , '{$value['anw_78']}' "
                  . ", '{$value['anw_79']}' , '{$value['anw_80']}' , '{$value['anw_81']}' , '{$value['anw_82']}' , '{$value['anw_83']}' , '{$value['anw_84']}' "
                  . ", '{$value['anw_85']}' , '{$value['anw_86']}' , '{$value['anw_87']}' , '{$value['anw_88']}' , '{$value['anw_89']}' , '{$value['anw_90']}' "
                  . ", '{$value['anw_91']}' , '{$value['anw_92']}' , '{$value['anw_93']}' , '{$value['anw_94']}' , '{$value['anw_95']}' , '{$value['anw_96']}' "
                  . ", '{$value['anw_97']}' , '{$value['anw_98']}' , '{$value['anw_99']}' , '{$value['anw_100']}' , '{$value['anw_101']}' , '{$value['anw_102']}' "
                  . ", '{$value['anw_103']}' , '{$value['anw_104']}' , '{$value['anw_105']}' , '{$value['anw_106']}' , '{$value['anw_107']}' , '{$value['anw_108']}' "
                  . ", '{$value['anw_109']}' , '{$value['anw_110']}' , '{$value['anw_111']}' , '{$value['anw_112']}' , '{$value['anw_113']}' , '{$value['anw_114']}' "
                  . ", '{$value['anw_115']}' , '{$value['anw_116']}' , '{$value['anw_117']}' , '{$value['anw_118']}' , '{$value['anw_119']}' , '{$value['anw_120']}' "
                  . ", '{$value['anw_121']}' , '{$value['anw_122']}' , '{$value['anw_123']}' , '{$value['anw_124']}' , '{$value['anw_125']}' , '{$value['anw_126']}' "
                  . ", '{$value['anw_127']}' , '{$value['anw_128']}' , '{$value['anw_129']}' , '{$value['anw_130']}' , '{$value['anw_131']}' , '{$value['anw_132']}' "
                  . ", '{$value['anw_133']}' , '{$value['anw_134']}' , '{$value['anw_135']}' , '{$value['anw_136']}' , '{$value['anw_137']}' , '{$value['anw_138']}' "
                  . ", '{$value['anw_139']}' , '{$value['anw_140']}' , '{$value['anw_141']}' , '{$value['anw_142']}' , '{$value['anw_143']}'"
                  . ") ; ";
                  $consulta = $link->prepare($query);
                  $consulta->execute() ;
                  $count++;
                    $datos['queryCalificacion'][$count] = $query;
                     
        }
          $datos['error']='ok';  

        }else{$datos['error']="Error al tratar de ingresar los valores a la base de datos TABLA (simulacros_resultados)"; }

        
        
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