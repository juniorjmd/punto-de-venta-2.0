<?php
require_once '../../../../../php/helpers.php';  
require_once'../../../../../php/fpdf181/fpdf.php'; 
/*
index de insercion y actualizacion en la base de datos propia del 
sistema, no agregar actions que no sean solo de insercion o actualizacion 
en base de datos de sistema, para bases de datos externas crear otro archivo exclusivo
*/
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../../../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
   if ( file_exists($nomClass ) ) {  require_once $nomClass;}
 });
new Core\Config();  
header("Content-type:application/json; charset=utf-8");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding,Authorization,Autorizacion");
header("Access-Control-Allow-Origin: * ");
ini_set('memory_limit', '-1');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {    
  header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, autorizacion");
 header("HTTP/1.1 200 OK");
die();
}     
$headers = getallheaders(); 
foreach ($headers as $header => $value) {  
   if ( strtoupper(trim($header))==='AUTORIZACION'){ 
       define('LLAVE_SESSION', TRIM($value)); 
   }
}  
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    http_response_code(200);
    if (defined('LLAVE_SESSION')) {
$_validacion = validaKey(LLAVE_SESSION);
IF ($_validacion['HTTP_COD_RESPONSE'] > 200){
    http_response_code($_validacion['HTTP_COD_RESPONSE']);
   $datos['error']= $_validacion['error']; 
   echo json_encode($datos);
    die();
}}
   $json = file_get_contents('php://input');
   $_POST = json_decode($json, true); 
foreach ($_POST as $clave => $valor){
    $$clave = $valor;
}  

$datos['post']= $_POST;
$OdooTbl = new Class_php\OdooDB();
function revisa(&$x1 = 0 , &$x2 = 1 ){
    $result =  $x1 + $x2;
    $x1 = $x2;
    $x2 =  $result;
    return $result;
}
switch ($action){ 
    
    case 'CAMBIAR_DOCUMENTO_POR_CAJA': 
        
      
          TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
        $query = "update documentos set ".
            " caja = {$datos['id']} , ".
            "usuario = {$datos['usuarioEstadoCaja']} ".
            "where orden = {$datos['documentoActivoCaja']}";
         $datos['query']= $query; 
            
       
          $consulta = $link->prepare($query);
         if ($consulta->execute()){
              $datos['error']='ok';
         }else{  
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           http_response_code(500);
           echo json_encode($datos);
           die();  
         }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    case 'CAMBIAR_DOCUMENTO_A_ENVIO':
         TRY { 
         if(isset($_documento) && is_numeric($_documento) && $_documento > 0){
             $conexion =\Class_php\DataBase::getInstance();
            $link = $conexion->getLink();
             $datos['error']='ok'; 
                $queryFinal = "update documentos "
                         . "set tipoDocumentoFinal = getIdTipoDocumentoPorNombre('domicilio') where "
                        . "orden = {$_documento} ; ";
                 $consulta = $link->prepare($queryFinal);
                 $datos['queryDocumento']  = $queryFinal;
                 if (!$consulta->execute()){ $datos['error']="Error al tratar de actualizar los valores a "
                . "la base de datos TABLA (documentos)";  
                echo json_encode($datos);
                 die();
             } else{
                // generarDomicilio
                $_result =$conexion->procedimiento('generarDomicilio', array("documento" => $_documento));
                if ($_result['_result'] !== 'ok'){
                     $datos['error']="Error al generar el Domicilio  en la tabla documentos_domicilio";  
                     echo json_encode($datos);
                     die();
                }
             }
             
             
             
                 }else{
                 $datos['error']="Es necesario el numero del documento a modificar";  
                echo json_encode($datos);
                 die();
             }
         }
         catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
         break;
    case 'CANCELAR_DOCUMENTO_POR_USUARIO':
    TRY {
          $varOdoo = new Class_php\Odoo(null, null, null,null);
      IF ($varOdoo->checkAccess() === false ){
        $datos['error']= 'Error de coneccion a ODOO !!!'; 
        echo json_encode($datos);
        die();  
        }
         $conexion =\Class_php\DataBase::getInstance();
            $link = $conexion->getLink();  
            $_llave_usuario = LLAVE_SESSION;
                 $where = " where orden = {$_documento} ";
               $_rst_documento  =$conexion->where('vw_obj_documentos', $where  );   
			   
                if ( (sizeof($_rst_documento['datos']) > 0)){ 
                    $_doc_obj = json_decode( $_rst_documento['datos'][0]['objeto']) ;
                    $datos['obj']  = $_doc_obj     ;  
                   $_loc_wh = $_doc_obj->idStockOdooPOS  ;  
                   $_loc_virt = $_doc_obj->idStockOdooVtl  ; 
                    $datos['$_loc_wh']=  $_loc_wh  ;
                    $datos['$_loc_virt']=  $_loc_virt ; 
                   if (sizeof($_doc_obj->listado ) >0 ){
                    $OdooTbl->clearParam();
                    $OdooTbl->setLimit(1);
                    $OdooTbl->setNewParam("id", "=" , $_loc_wh );  
                    $loc_wh = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
                    $loc_wh_r =  array();
                    $loc_wh_r = $varOdoo->DataRead($OdooTbl->bodegas, $loc_wh );
                    $OdooTbl->clearParam();
                    $OdooTbl->setLimit(1);
                    $OdooTbl->setNewParam("id", "=" , $_loc_virt ); 
                    $loc_virt =  $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit()); 
                    $loc_virt_r = array();
                    $loc_virt_r =  $varOdoo->DataRead($OdooTbl->bodegas,$loc_virt); 
                    
                    
                    
                    
                     $cntMov = 0;
                     foreach ($_doc_obj->listado as  $value) {
                         $_move_ids[$cntMov] = $value->id_stock_move_odoo ;
                         $cntMov++;
                         if ($value->estado_linea_venta == 'A'){
                             $OdooTbl->clearParam();
                            $OdooTbl->setLimit(1);
                            $linea = $value->id;
                            $OdooTbl->setNewParam("id","=", $value->idProducto );
                            $dataPrd = $varOdoo->DataSet($OdooTbl->productos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
                            $fields = [ "id","price","price_extra","lst_price","default_code","code","partner_ref","active","barcode", 
                            "display_name", "name",  "categ_id"  ];
                            $dataRPrd = array();
                            $dataRPrd = $varOdoo->DataRead($OdooTbl->productos, $dataPrd , $fields);  
                            $_move_id_1 = $varOdoo->devolucionStockMoves( $dataRPrd[0]['id'] , $value->cant_real_descontada , $loc_wh_r[0]['id'] , $loc_virt_r[0]['id'] );  
                          //  print_r( $_move_id_1 );
                             $queryFinal = "insert into documentos_listado_productos "
                                     . "SELECT null , orden, tipoDocumento, idDocumento, idProducto, "
                                     . "nombreProducto, presioVenta, porcent_iva, presioSinIVa, IVA,  "
                                     . " (-1 * cantidadVendida), descuento,  (-1 * valorTotal), "
                                     . "usuario, now(), now() , maq_activa, estado_venta,  "
                                     . "(-1 * cant_real_descontada), idDocumentoFinal, "
                                     . "{$_move_id_1},  (-1 * total_IVA),  (-1 * total_presioSinIVa), 'E' "
                                     . "FROM documentos_listado_productos where id = {$value->id};";
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryDevolucionLinea'][$cntMov] = $queryFinal;
                    if ($consulta->execute()){
                        $_move_ids[$cntMov] = $_move_id_1;
                         $cntMov++;
                      $datos['error']='ok';  

                    }else{ $datos['error']="Error eliminar valores en el stock move de devolucion en odoo";
                     echo json_encode($datos);
                  die(); 
                    }
                            
                            
                         }
                     }
                     $datos['$_move_ids']= $_move_ids ;
                     //  
                    // se debe adquirir de la base de datos con el cod del documento que se esta cerrando 
                    // de la tabla documentos_listado_productos 
                     $_cod_stock_p = $_doc_obj->cod_stock_p ;
                   } 
                  
            } else{
                   http_response_code(500);
                  $datos['error']= 'Error al obtener los datos del documento enviado !!! ';
                  echo json_encode($datos);
                  die(); 
            }
            
            if (sizeof($_doc_obj->listado ) > 0){
             $OdooTbl->clearParam();
            $OdooTbl->setLimit(1); 
              $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", $_cod_stock_p); 
             $OdooTbl->setNewParam("sequence_code","=", $_cod_stock_p);  
           $_op_type_id = $varOdoo->DataSet($OdooTbl->tipo_s_p,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());  
		   
            
           
            $idMovimiento = $varOdoo->setSP($_op_type_id[0], $loc_wh[0], $loc_virt[0], $_move_ids , "Devol-{$_doc_obj->orden}") ; 
            $datos['cod_stock_picking'] = $idMovimiento;
    }else{$idMovimiento = 0 ;}
                     
             if ( is_array($idMovimiento) ){
             //   print_r($result);
                $datos['error']= 'Error al crear el stock picking ODOO !!!' .
                        'statusCode:'.$idMovimiento[0].'-'.$idMovimiento[1]; 
                   echo json_encode($datos);
                   die();     
               }else{
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){ 
             $_qazxswe = $_result['datos'][0]['usuario'];   
              $_result =$conexion->procedimiento('cancelarDocumento', array("usuario" => $_qazxswe,"documento" => $_documento));
    
         if ($_result['_result'] === 'ok'){
             
             $datos['datos']= $_result['datos'];
             $datos['query']= $_result['query'];
             $datos['parm_query']= $_result['parm_query'];
             $datos['error']='ok';
             //print_r($datos['datos'][0]);
             if(intval($datos['datos'][0]['_result']) !== 100 ){
                 $datos['error']=$datos['datos'][0]['msg'];
                 
             }
         }
         
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        
               }  
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    case 'da5195132f73b029d0ce3a1dc2a05a46e7f0461c4':
           TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0 && isset($_procedure) && trim($_procedure) != ''){ 
             $_qazxswe = $_result['datos'][0]['usuario'];    
             $parametros = array();
             if (isset($_arraydatos) and is_array($_arraydatos) ){
                 
             foreach($_arraydatos as $key => $value ) 
             {  
                 if (trim($key) == 'USUARIO_LOGUEADO'){
    
                     $parametros[$key] = $_qazxswe ;
                 }else{
                 $parametros[$key] = $value ; }
                 
             }
             
             }
    
              $_result =$conexion->procedimiento($_procedure, $parametros   ); 
         if ($_result['_result'] === 'ok'){
             $datos['datos']= $_result['datos'];
             $datos['query']= $_result['query'];
             $datos['parm_query']= $_result['parm_query'];
             $datos['error']='ok';
             //print_r($datos['datos'][0]);
             if(intval($datos['datos'][0]['_result']) !== 100 ){
                 $datos['error']=$datos['datos'][0]['msg'];
                 
             }
         }else{
            $datos['error']= 'Error al insertar en la base de datos ' ;
           echo json_encode($datos);
           die();  
         }
         
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    case 'CREAR_DOCUMENTO_POR_USUARIO':
           TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){ 
             $_qazxswe = $_result['datos'][0]['usuario'];   
              $_result =$conexion->procedimiento('crearNuevoDocumento', array("usuario" => $_qazxswe));
    
         if ($_result['_result'] === 'ok'){
             
             $datos['datos']= $_result['datos'];
             $datos['query']= $_result['query'];
             $datos['parm_query']= $_result['parm_query'];
             $datos['error']='ok';
             //print_r($datos['datos'][0]);
             if(intval($datos['datos'][0]['_result']) !== 100 ){
                 $datos['error']=$datos['datos'][0]['msg'];
                 
             }
         }else{
              $datos['auxError'] = $_result['query'];
             $datos['error']= 'Error de datos, Procedimiento : crearNuevoDocumento '. $_result['_result'] ;
           echo json_encode($datos);
           die();  
         }
         
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    case 'CAMBIAR_DOCUMENTO_ACTIVO_POR_USUARIO':
           TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0 && isset($_docActual) && trim($_docActual)!= '' && is_numeric($_docActual) ){ 
             $_qazxswe = $_result['datos'][0]['usuario'];   
              $_result =$conexion->procedimiento('cambiarDocumentoActual', array("usuario" => $_qazxswe,"_documento" => $_docActual));
    
         if ($_result['_result'] === 'ok'){
             
             $datos['datos']= $_result['datos'];
             $datos['query']= $_result['query'];
             $datos['parm_query']= $_result['parm_query'];
             $datos['error']='ok';
             //print_r($datos['datos'][0]);
             if(intval($datos['datos'][0]['_result']) !== 100 ){
                 $datos['error']=$datos['datos'][0]['msg'];
                 
             }
         }
         
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    case 'ABRIR_CAJA_ACTIVA':
             TRY {
        
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){
             $_qazxswe = $_result['datos'][0]['usuario'];  
                     $_parametro['usuario'] = $_qazxswe; 
                     $_parametro['_valor_inicial'] = $_valorIngresar;
              $_result =$conexion->procedimiento('abrir_caja', $_parametro);
         if ($_result['_result'] === 'ok'){
             $datos['datos']= $_result['datos'];
             $datos['query']= $_result['query'];
             $datos['parm_query']= $_result['parm_query'];
             $datos['error']='ok';
             //print_r($datos['datos'][0]);
             if(intval($datos['datos'][0]['_result']) !== 100 ){
                 $datos['error']=$datos['datos'][0]['msg'];
                 
             }
         }
         
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
        
        
        
    case 'OBTENER_RESUMEN_CAJA':
              TRY {
        
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
       $_result =$conexion->procedimiento('resumenCaja', $_parametro);
       if ($_result['_result'] === 'ok'){
           $datos['data']= array();
           foreach ($_result['datos'] as $key => $value) {
               array_push( $datos['data'] ,   json_decode($value['OBJ'], true)); 
               
           } 
           $datos['numdata'] = sizeof( $datos['datos'] );
           $datos['query']= $_result['query'];
           $datos['parm_query']= $_result['parm_query'];
           $datos['error']='ok'; 
       }else{$datos['error']= 'Error el ejecutar la consulta!!' ;
            echo json_encode($datos);
            die();  }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;   
    
    case 'INSERT_PERFIL_USUARIO':
              TRY {
        
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
       $_result =$conexion->procedimiento('setPerfilAUsuario', $_parametro);
       if ($_result['_result'] === 'ok'){
           $datos['error']='ok'; 
         //  $datos['query']=$_result['query'];
           $datos['datos'] = $_result['datos'];
           if(intval($datos['datos'][0]['_result'])!== 100){
                    $datos['error']=$datos['datos'][0]['msg'];
                } 
       }else{$datos['error']= 'Error el ejecutar la consulta!!' ;
            echo json_encode($datos);
            die();  }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;  
    case 'CERRAR_CAJA_ACTIVA':
              TRY {
        
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where); 
         if (sizeof($_result['datos']) > 0){
             $_qazxswe = $_result['datos'][0]['usuario']; 
             $_parametro2 = $_parametro ;
             $_parametro['usuario'] = $_qazxswe; 
             $_result =$conexion->procedimiento('cerrarCaja', $_parametro);
            if ($_result['_result'] === 'ok'){
                $datos['datos']= $_result['datos'];
                $datos['query']= $_result['query'];
                $datos['parm_query']= $_result['parm_query'];
                $datos['error']='ok';
                if(intval($datos['datos'][0]['_result'])!== 100){
                    $datos['error']=$datos['datos'][0]['msg'];
                }else{
                     $_result =$conexion->procedimiento('resumenCaja', $_parametro2);
                    if ($_result['_result'] === 'ok'){
                        $datos['data']= array();
                        foreach ($_result['datos'] as $key => $value) {
                            array_push( $datos['data'] ,   json_decode($value['OBJ'], true)); 

                        } 
                        $datos['numdata'] = sizeof( $datos['datos'] );
                        $datos['query2']= $_result['query'];
                        $datos['parm_query2']= $_result['parm_query'];
                        $datos['error']='ok'; 
                    }else{$datos['error']= 'Error al optener el resumen de la caja!!' ;
                         echo json_encode($datos);
                         die(); 
                         }
                }
                
            }
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
        
    case 'CERRAR_CAJA_PARCIAL':
              TRY {
        
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink(); 
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where); 
         if (sizeof($_result['datos']) > 0){
             $_qazxswe = $_result['datos'][0]['usuario']; 
             $_parametro2 = $_parametro ;
             $_parametro['usuario'] = $_qazxswe; 
             $_result =$conexion->procedimiento('sp_cerrarCajaParcial', $_parametro);
            if ($_result['_result'] === 'ok'){
                $datos['datos']= $_result['datos'];
                $datos['query']= $_result['query'];
                $datos['parm_query']= $_result['parm_query'];
                $datos['error']='ok';
                if(intval($datos['datos'][0]['_result'])!== 100){
                    $datos['error']=$datos['datos'][0]['msg'];
                }else{
                     $_result =$conexion->procedimiento('resumenCajaParcial', $_parametro2);
                    if ($_result['_result'] === 'ok'){
                        $datos['data']= array();
                        foreach ($_result['datos'] as $key => $value) {
                            array_push( $datos['data'] ,   json_decode($value['OBJ'], true)); 

                        } 
                        $datos['numdata'] = sizeof( $datos['datos'] );
                        $datos['query2']= $_result['query'];
                        $datos['parm_query2']= $_result['parm_query'];
                        $datos['error']='ok'; 
                    }else{$datos['error']= 'Error al optener el resumen de la caja!!' ;
                         echo json_encode($datos);
                         die(); 
                         }
                }
                
            }
         }else{
            $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
           echo json_encode($datos);
           die();  
         }
        
         
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    case 'mnbvcxzxcxcxasdfewq15616':
           TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
               $where = "";
         $_result =$conexion->where('cajas', $where);
         if (sizeof($_result['datos']) > 0){ 
           //  $where = " where `id` =  '{$_idCaja}' ";
             
             foreach ($_result['datos'] as $key => $value) {
                 $_result['datos'][$key]['asignada'] = false ;  
                   $where = " where idCaja = {$value['id']} and idUsuario = {$_usuario}";
                   $_result2 =$conexion->where('rel_usuario_cajas', $where);
                   if (sizeof($_result2['datos'])> 0 ) {
                       $_result['datos'][$key]['asignada'] = true ; 
                   }
             }
              $datos['data']= $_result['datos'];
             $datos['query']= $_result['query'];
      
                $datos['numdata']= sizeof($_result['datos']);
                $datos['error'] ='ok';  
         }else{
            $datos['error']= 'No existen Cajas para consultar ' ;
           echo json_encode($datos);
           die();  
         }
         
   
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
     break;
     
     
    case 'qwer12356yhn7ujm8ik':
           TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
           
         $queryFinal = "delete from rel_usuario_cajas ".
                       " where idUsuario = {$_idUsuario} ";
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryDelete'] = $queryFinal;
                    if ($consulta->execute()){ 
                        foreach ($_cajas as $key => $value) {
                           $queryFinal =  "insert into rel_usuario_cajas (idUsuario , idCaja )values"
                            . " ({$_idUsuario} , {$value})"; 
                            $consulta = $link->prepare($queryFinal);
                            $datos['queryCaja'][$key] = $queryFinal;
                            if ($consulta->execute()){
                                $datos['error']='ok';  
                                }else{ $datos['error']="Error al tratar de ingresar los valores a "
                                . "la base de datos TABLA (rel_usuario_cajas)";  echo json_encode($datos);
                                 die();}
                                
                        }
                    

                    }else{ $datos['error']="Error al tratar de ingresar los valores a la base de datos TABLA (rel_usuario_cajas)"; }
 }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
     
   
    break;
     
     
     
    case 'ASIGNAR_PAGOS_DOCUMENTOS':
           TRY {
       $conexion =\Class_php\DataBase::getInstance();
       $link = $conexion->getLink();  
       /*id, idDocumento, idMedioDePago, valorPagado, valorTotalAPagar, valorRecibido, vueltos, referencia*/    
         $queryFinal = "delete from documentos_pagos ".
                       " where idDocumento = {$_ordenDocumento} ";
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryDelete'] = $queryFinal;
                    if ($consulta->execute()){ 
                        foreach ($_pagos as $key => $value) {
                            
                           foreach ($value as $clave => $valor){
                                $$clave = $valor;
                            }  
                            if ($valorPagado> 0){
                           $queryFinal =  "insert into documentos_pagos "
                                   . "( idDocumento, idMedioDePago, valorPagado, valorTotalAPagar, valorRecibido, vueltos, referencia) "
                                   . " values ( {$_ordenDocumento}, {$idMedioDePago}, {$valorPagado}, "
                                   . "{$valorTotalAPagar}, {$valorRecibido}, {$vueltos}, '{$referencia}')"; 
                            
                            //idMedioDePago: '1', nombreMedio: 'Efectivo', valorPagado: 
                            $consulta = $link->prepare($queryFinal);
                            $datos['queryPagos'][$key] = $queryFinal;
                            if ($consulta->execute()){
                                $datos['error']='ok';  
                                }else{ $datos['error']="Error al tratar de ingresar los valores a "
                                . "la base de datos TABLA (rel_usuario_cajas)";  echo json_encode($datos);
                                 die();}
                                
                        }}
                    

                    }else{ $datos['error']="Error al tratar de ingresar los valores a la base de datos TABLA (rel_usuario_cajas)"; }
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
       $columnas =null;
       if(isset($_columnas) && sizeof($_columnas) > 0 ){
           foreach ($_columnas as $key => $value) {
              if( $columnas === null){
                  $columnas = '';
              } else{
                  $columnas .= ',';
              }
              $columnas .= "`$value`";
               
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
      $_result =$conexion->where($_tabla, $where,$columnas);}
      /*$array['datos'] =  $consulta->fetchAll();
         $array['query'] */
       if (isset($_obj) && sizeof($_obj) > 0  ){
          $datos['$_obj'] = $_obj;
          foreach ($_result['datos'] as $key => $value) { 
              foreach ($value as $_k => $_val){
               $indice = false  ;
               //$indice = array_search($_k,$_obj,true);
               foreach ($_obj as  $value_aux) {
                   if ($value_aux === $_k)
                       $indice = true ;
               }
                     
                 
                if ($indice ){
                   // echo'entro';
                  $value[$_k] =  json_decode($_val, true) ;    
                } 
              }
              $_result['datos'][ $key ] =  $value ;
              
          }  
    
      }
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

    case 'e06c06e7e4ef58bdbdd35473wdc741qaz'://select por usuario logueado .
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
        
        if(isset($_columnaUsuario) && trim($_columnaUsuario)!= '' ){
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
       $columnas =null;
       if(isset($_columnas) && sizeof($_columnas) > 0 ){
           foreach ($_columnas as $key => $value) {
              if( $columnas === null){
                  $columnas = '';
              } else{
                  $columnas .= ',';
              }
              $columnas .= "`$value`";
               
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
      if (trim($where)== ''){
         $where = ' where ' ;
      }
      else{
          $where .= ' and ' ;
      }
      $where .= "`{$_columnaUsuario}` = (SELECT usuario FROM  session WHERE `key` = '".LLAVE_SESSION."')";
      
      if (isset($_datoUsuario) ){
            foreach ($_datoUsuario as $key => $value) {
              $key = "_usr_$key"; 
             $$key = $value ;
          }
          
        $_usr_datoWereFinal = "";   
        $and = 'where ';   
        foreach ($_usr_datoWere as $clave => $valor){
             $valor['dato'] = trim($valor['dato']);
             if ( $valor['dato'] ==='USUARIO_ACTIVO'){
               $_dato =  " (SELECT usuario FROM  session WHERE `key` = '".LLAVE_SESSION."')";
             }else{
             $_dato = "'{$valor['dato']}'";}
             if (trim($valor['tipocomp']) == 'like'){
                 $_dato = "'%{$valor['dato']}%'";
             }
             if (trim($valor['tipocomp']) == 'in'){
                 $_dato = "({$valor['dato']})";
             }
            $_usr_datoWereFinal.= " $and {$valor['columna']} {$valor['tipocomp']} $_dato "; 
            $and = ' and ';
         
        }
        
      if (trim($where)== ''){
         $where = ' where ' ;
      }
      else{
          $where .= ' and ' ;
      } 
      $where .= "$_usr_columna = ( select  $_usr_nomColDato  from $_usr_tabla   $_usr_datoWereFinal ) " ; 
      
      } 
      if (trim($whereu)== ''){
         $whereu = ' where ' ;
      }
      else{
          $whereu .= ' and ' ;
      }
      $whereu .= "`{$_columnaUsuario}` = (SELECT usuario FROM  session WHERE `key` = '".LLAVE_SESSION."')";
      if  (isset($_tablau) && trim($_tablau) != ''){
          $_result =$conexion->where_union($_tabla, $where,$_tablau, $whereu);
      }else{
      $_result =$conexion->where($_tabla, $where, $columnas);}
      /*$array['datos'] =  $consulta->fetchAll();
         $array['query'] */
      if (isset($_obj) && sizeof($_obj) > 0  ){
          
          foreach ($_result['datos'] as $key => $value) {
              foreach ($value as $_k => $_val){
               $indice = array_search($_k,$_obj,true);
                if ($indice >= 0 ){
                  $value[$_k] =  json_decode($_val, true) ;    
                }
              }
              $_result['datos'][ $key ] =  $value ;
              
          }  
    
      }
      $datos['data']= $_result['datos'];
        
      $datos['query']= $_result['query'];
      
      $datos['numdata']= sizeof($_result['datos']);
      $datos['error'] ='ok';  
            
        
      }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
        }
        
      }
        else{
              http_response_code(500);
        $datos['error']= 'Es necesario el nombre de la columna del usuario!';
            
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
       $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){
             $_qazxswe = $_result['datos'][0]['usuario']; 
             
         }
         
         $_parent_ini = '(';
        foreach ($_arraydatos as $clave => $valor){
            
             $valor= trim($valor);
             if ($valor === 'USUARIO_LOGUEADO'){
               $valor = $_qazxswe ;
             }
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
         $_key = LLAVE_SESSION;
         $where = " where `key` =  '{$_key}' ";
         $_result =$conexion->where('session', $where);
        // $datos['11'] =  $_result['datos'][0]['usuario'];;
         if (sizeof($_result['datos']) > 0){
             $_qazxswe = $_result['datos'][0]['usuario']; 
             
         }
    
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
         $datos['$_arraydatos'] = $_arraydatos;
        foreach ($_arraydatos as $clave => $valor){
              
              $valor= trim($valor);
             if ($valor === 'USUARIO_LOGUEADO'){
               $valor = $_qazxswe ;
             }
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