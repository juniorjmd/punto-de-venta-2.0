<?php
require_once '../../../../../php/helpers.php';  
require_once'../../../../../php/fpdf181/fpdf.php';  
/*
index de consulta en odoo no agregar actions que no sean solo de consulta
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
switch ($action){ 
 
    case 'GET_VENTAS_ODOO':
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }    
    
           $OdooTbl->clearParam();
           $dataSet = $varOdoo->DataSet($OdooTbl->ventas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit()); 
       $dataRead = array();
               $dataRead = $varOdoo->DataRead($OdooTbl->ventas, $dataSet );
    $datos['datos'] = $dataRead ;
         }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break; 
    case 'GET_VENTAS_LISTADO_ODOO':
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }    
    
           $OdooTbl->clearParam();
           $dataSet = $varOdoo->DataSet($OdooTbl->ventas_linea,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit()); 
       $dataRead = array();
               $dataRead = $varOdoo->DataRead($OdooTbl->ventas_linea, $dataSet );
    $datos['datos'] = $dataRead ;
         }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;  
    case 'GET_TIPOS_DOCUMENTO_ODOO':
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }    
    
           $OdooTbl->clearParam();
            /*$OdooTbl->setLimit(1); 
              $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("sequence_code","=", "POS_JDS_MOVE");  */
           $dataSet = $varOdoo->DataSet($OdooTbl->tipDocumentos,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit()); 
       $dataRead = array();
               $dataRead = $varOdoo->DataRead($OdooTbl->tipDocumentos, $dataSet );
    $datos['datos'] = $dataRead ;
         }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    case 'BUSCAR_ODOO_TIPO_ID':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
              /*$OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("sequence_code","=", "POS_JDS_MOVE");  */
           $_op_type_id = $varOdoo->DataSet($OdooTbl->tipDocumentos,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $dataread = array();
           $dataread = $varOdoo->DataRead($OdooTbl->tipDocumentos, $_op_type_id , ['id' , 'display_name']);
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_ODOO_PAIS':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
              /*$OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("sequence_code","=", "POS_JDS_MOVE");  */
           $_op_type_id = $varOdoo->DataSet($OdooTbl->country,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $dataread = array();
           $dataread = $varOdoo->DataRead($OdooTbl->country, $_op_type_id , ['id' , 'display_name']);
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_ODOO_DEP':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
              /*$OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("sequence_code","=", "POS_JDS_MOVE");  */
        if ((isset($_id_pais) || trim($_id_pais) !== "") ){
            $OdooTbl->setNewParam("country_id","=", $_id_pais); 
        }
           $_op_type_id = $varOdoo->DataSet($OdooTbl->State,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $dataread = array();
           $dataread = $varOdoo->DataRead($OdooTbl->State, $_op_type_id , ['id' , 'display_name']);
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_ODOO_CIUDAD':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
              /*$OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("sequence_code","=", "POS_JDS_MOVE");  */
        if ((isset($_state_id) || trim($_state_id) !== "") ){
            $OdooTbl->setNewParam("state_id","=", $_state_id); 
        }
           $_op_type_id = $varOdoo->DataSet($OdooTbl->city,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $dataread = array();
        $dataread = $varOdoo->DataRead($OdooTbl->city, $_op_type_id , ['id' , 'city_code', 'display_name']);
          //    $dataread = $varOdoo->DataRead($OdooTbl->city, $_op_type_id );
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_ODOO_TITULO_PERSONA':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
              /*$OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("sequence_code","=", "POS_JDS_MOVE");  */
           $_op_type_id = $varOdoo->DataSet($OdooTbl->titulo,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $dataread = array();
           $dataread = $varOdoo->DataRead($OdooTbl->titulo, $_op_type_id , ['id' , 'display_name']);
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_ODOO_CATEGORIAS':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
            /*  $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("company_type","=", "company"); */
             $OdooTbl->setNewParam("parent_id","!=", "false");   
             
           $_op_type_id = $varOdoo->DataSet($OdooTbl->categorias,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $FELDS =['id' , 'parent_id','display_name','company_type', 'is_company' , 'email' , 'mobile' ,  'phone' , 'type' , 'vat' , 'lang' ,'l10n_latam_identification_type_id' ];
           $FELDS =[];
           $dataread = array();
           $dataread = $varOdoo->DataRead($OdooTbl->categorias, $_op_type_id   , $FELDS);
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_ODOO_CATEGORIAS_PRD':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
            /*  $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("company_type","=", "company"); */
              $OdooTbl->setNewParam("parent_id","!=", "false");   
             
           $_op_type_id = $varOdoo->DataSet($OdooTbl->categorias_prd,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $FELDS =['id' , 'parent_id','display_name','company_type', 'is_company' , 'email' , 'mobile' ,  'phone' , 'type' , 'vat' , 'lang' ,'l10n_latam_identification_type_id' ];
           $FELDS =[];
           $dataread = array();
           $dataread = $varOdoo->DataRead($OdooTbl->categorias_prd, $_op_type_id   , $FELDS);
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'GET_EMPLEADOS_ODOO':
           $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
                 
            $conexion =\Class_php\DataBase::getInstance();
            $link = $conexion->getLink();  
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
            /*  $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("company_type","=", "company"); */
                /* $_tipo_busqueda = 'nombre';
                 $_dato = 'j';*/
             if(isset($_tipo_busqueda) && trim($_tipo_busqueda) != '' && isset($_dato) && trim($_dato) != '' )
             {
                 switch ($_tipo_busqueda){
                     case'id' : 
                          $OdooTbl->setNewParam("vat","=", $_dato);  
                         break; 
                     case'nombre' :  
                          if(strlen($_dato) == 1){
                          $OdooTbl->setRelacionOR();
                          $OdooTbl->setNewParam("display_name","like","{$_dato}%"); 
                          $_dato2 = ucwords($_dato);
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}%");  
                            $OdooTbl->setRelacionOR();
                           $_dato2 = strtoupper($_dato);
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}%");  }
                          else{
                               $OdooTbl->setRelacionOR();
                          $OdooTbl->setNewParam("display_name","like","{$_dato}"); 
                          $_dato2 = ucwords($_dato);
                            $OdooTbl->setRelacionOR();
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}");  
                            $OdooTbl->setRelacionOR();
                           $_dato2 = strtoupper($_dato);
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}"); 
                          }
                         break;
                 }
             }
              // print_r($OdooTbl->getArrayParam());
             $datos['tabla'] = $OdooTbl->empleados;  
           $_op_type_id = $varOdoo->DataSet($OdooTbl->empleados,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $FELDS =["id", 'identification_id',"name" , 'mobile_phone' , 'category_ids' , 'job_title' , 'work_phone'];
           $dataread = array();
           $dataread = $varOdoo->DataRead($OdooTbl->empleados, $_op_type_id   , $FELDS);
           
            $where = "";
               $datos_internos =$conexion->where('datos_auxiliares_empleados', $where  );  
            $db_clientes_permisos = array(); 
            if ( (sizeof($datos_internos['datos']) > 0)){  
                foreach ($datos_internos['datos'] as $key => $value) {
                    $db_clientes_permisos[$value['cod_odoo']] = $value;
                } 
            } 
           
           foreach ($dataread as $key => $value) {
               $value['permisos_internos'] = $db_clientes_permisos[$value['id']];
               $dataread[$key] = $value;
           }
           
        $datos['data'] = $dataread;
        $datos['numdata'] = sizeof($datos['data'] );
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break; 
    case 'BUSCAR_ODOO_CLIENTES':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
                 if(isset($_limit) && is_numeric($_limit) && $_limit > 0){
                     $OdooTbl->clearArrayLimit();
                     $OdooTbl->setLimit($_limit);
                 }
            /*  $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("company_type","=", "company"); */
                /* $_tipo_busqueda = 'nombre';
                 $_dato = 'j';*/
             if(isset($_tipo_busqueda) && trim($_tipo_busqueda) != '' && isset($_dato) && trim($_dato) != '' )
             {
                 switch ($_tipo_busqueda){
                     case'id' : 
                          $OdooTbl->setNewParam("vat","=", $_dato);  
                         break; 
                     case'nombre' :  
                          if(strlen($_dato) == 1){
                          $OdooTbl->setRelacionOR();
                          $OdooTbl->setNewParam("display_name","like","{$_dato}%"); 
                          $_dato2 = ucwords($_dato);
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}%");  
                            $OdooTbl->setRelacionOR();
                           $_dato2 = strtoupper($_dato);
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}%");  }
                          else{
                               $OdooTbl->setRelacionOR();
                          $OdooTbl->setNewParam("display_name","like","{$_dato}"); 
                          $_dato2 = ucwords($_dato);
                            $OdooTbl->setRelacionOR();
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}");  
                            $OdooTbl->setRelacionOR();
                           $_dato2 = strtoupper($_dato);
                          $OdooTbl->setNewParam("display_name","like","{$_dato2}"); 
                          }
                         break;
                 }
             }
              //$OdooTbl->setRelacionAND();
              $OdooTbl->setNewParam("parent_id","=", false ); 
              $OdooTbl->setNewParam("is_company","=", false ); 
                     
               $OdooTbl->setNewParam("company_type","=", 'person'); 
              // print_r($OdooTbl->getArrayParam());
           $_op_type_id = $varOdoo->DataSet($OdooTbl->cliente,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $FELDS =["id","name","parent_id","display_name","company_type","is_company" ,"email" ,"mobile" ,"phone" ,"type" ,"vat" ,"lang" ,"street" ,"city" ,
               "street2" ,"state_id","zip" ,"country_id","function" ,"category_id" ,"title" ,"l10n_latam_identification_type_id" ];
           $dataread = array();
           if(!isset($_todo) || $_todo = 'NO' ){
           $dataread = $varOdoo->DataRead($OdooTbl->cliente, $_op_type_id   , $FELDS);}ELSE{
                $dataread = $varOdoo->DataRead($OdooTbl->cliente, $_op_type_id   );
           }
        $datos['data'] = $dataread;
        $datos['numdata'] = sizeof($datos['data'] );
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_ODOO_TIPO_COMPANIA':
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                 $OdooTbl->clearParam(); 
              /*$OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE");  */
             $OdooTbl->setNewParam("company_type","=", "company"); 
             $OdooTbl->setNewParam("display_name","!=", ""); 
             
           $_op_type_id = $varOdoo->DataSet($OdooTbl->cliente,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
          $dataread = array(); 
           $dataread = $varOdoo->DataRead($OdooTbl->cliente, $_op_type_id , ['id' , 'display_name','company_type']);
        $datos['data'] = $dataread;
        $datos['error'] = 'ok' ;
                  }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_STOCK_LOCATION':
       $varOdoo = new Class_php\Odoo(null, null, null,null); 
         TRY{ 
         $datos['error']='ok';
        IF ($varOdoo->checkAccess() === false ){
          $datos['error']= 'Error de coneccion a ODOO !!!'; 
            echo json_encode($datos);
            die();  
        }    
    
      $OdooTbl->clearParam();
        if (isset($_principal) && $_principal === true){
            $OdooTbl->setNewParam("location_id","=", false); 
        }
        
         if (isset($_fisicas) && $_fisicas === true){
            $OdooTbl->setNewParam("location_id","=", "Physical Locations"); 
        }
         if ((!isset($_principal) || $_principal === false) && (isset($_id_principal))){
            $OdooTbl->setNewParam("location_id","=", $_id_principal); 
        }
        
        
         if ((isset($_virtual) || $_virtual === true) ){
            $OdooTbl->setNewParam("location_id","=", "Virtual Locations"); 
        }
        
        
        $OdooTbl->setNewParam("active","=", true);   
        $dataSet = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit()); 
       $dataRead = $varOdoo->DataRead($OdooTbl->bodegas, $dataSet );
        $conexion =\Class_php\DataBase::getInstance();
         $link = $conexion->getLink(); 
          $where = "";
        if (isset($_principal) && $_principal === true){
         
          $_rst_asig =$conexion->where('establecimiento', $where  );  
         if (!(sizeof($_rst_asig['datos']) > 0)){
            $_rst_asig['datos']= array();}  
            $id_array = array_column( $_rst_asig['datos'], 'idAuxiliar');
            $keyArray = array_keys( $_rst_asig['datos']);
          //  print_r($id_array);
          //  print_r($keyArray);
         foreach ($dataRead as $key => $value) { 
             $clave = array_search($value['id'], $id_array);
             $asignado = 'N0';
             $idEstablecimiento = 0;
             $nombreEstablecimiento = '';
             if ($clave ){
                
                $idEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['id'];
                $nombreEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['nombre'];
                $asignado = 'SI';
                 
             }
             $dataRead[$key]['asignado'] = $asignado ;
             $dataRead[$key]['nombreEsta'] = $nombreEstablecimiento ;
             $dataRead[$key]['idEsta'] = $idEstablecimiento ;
             $dataRead[$key]['tipLocacion'] = 'PRINCIPAL' ;
            }
    
         
        }elseif ((!isset($_principal) || $_principal === false) && (isset($_id_principal))){
            
         $where = " where idAuxiliar =  $_id_principal ";
          $_rst_asig =$conexion->where('establecimiento', $where  );  
         if (!(sizeof($_rst_asig['datos']) > 0)){
            $_rst_asig['datos']= array();
            
         }  
            $id_BS_array = array_column( $_rst_asig['datos'], 'idBodegaStock');
            $id_BV_array = array_column( $_rst_asig['datos'], 'idBodegaVitual');
            $keyArray = array_keys( $_rst_asig['datos']);
          //  print_r($id_array);
          //  print_r($keyArray);
         foreach ($dataRead as $key => $value) { 
             $clave = array_search($value['id'], $id_BS_array);
             $asignado = 'N0';
             $idEstablecimiento = 0;
             $nombreEstablecimiento = '';
             $tipLocacion = '';
             if ($clave ){
                
                $idEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['id'];
                $nombreEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['nombre'];
                $asignado = 'SI';
                $tipLocacion = 'STOCK';
                 
             }else{
               $clave = array_search($value['id'], $id_BV_array); 
               if ($clave ){
                 $idEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['id'];
                 $nombreEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['nombre'];
                 $asignado = 'SI';
                 $tipLocacion = 'VIRTUAL';
                 }
             }
             $dataRead[$key]['asignado'] = $asignado ;
             $dataRead[$key]['nombreEsta'] = $nombreEstablecimiento ;
             $dataRead[$key]['idEsta'] = $idEstablecimiento ;
             $dataRead[$key]['tipLocacion'] = $tipLocacion;

            }
    
         
        }else{
             $where = " where idAuxiliar =  $_id_principal ";
          $_rst_asig =$conexion->where('establecimiento', $where  );  
         if (!(sizeof($_rst_asig['datos']) > 0)){
            $_rst_asig['datos']= array();} 
            
            $id_array = array_column( $_rst_asig['datos'], 'idAuxiliar');
            $id_BS_array = array_column( $_rst_asig['datos'], 'idBodegaStock');
            $id_BV_array = array_column( $_rst_asig['datos'], 'idBodegaVitual');
            $keyArray = array_keys( $_rst_asig['datos']); 
         foreach ($dataRead as $key => $value) { 
             $clave = array_search($value['id'], $id_BS_array);
             $asignado = 'N0';
             $idEstablecimiento = 0;
             $nombreEstablecimiento = '';
             $tipLocacion = '';
             if ($clave ){
                
                $idEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['id'];
                $nombreEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['nombre'];
                $asignado = 'SI';
                $tipLocacion = 'STOCK';
                 
             }else{
               $clave = array_search($value['id'], $id_BV_array); 
               if ($clave ){
                 $idEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['id'];
                 $nombreEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['nombre'];
                 $asignado = 'SI';
                 $tipLocacion = 'VIRTUAL';
                 }else {
                     $clave = array_search($value['id'], $id_array); //principal
                    if ($clave ){
                      $idEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['id'];
                      $nombreEstablecimiento = $_rst_asig['datos'][$keyArray[$clave]]['nombre'];
                      $asignado = 'SI';
                        $tipLocacion = 'PRINCIPAL';
                      }
                 }
             }
             $dataRead[$key]['asignado'] = $asignado ;
             $dataRead[$key]['nombreEsta'] = $nombreEstablecimiento ;
             $dataRead[$key]['idEsta'] = $idEstablecimiento ;
             $dataRead[$key]['tipLocacion'] = $tipLocacion;

            }
        } 
       
        $datos['data'] = $dataRead; 
         $datos['numdata'] = sizeof($dataRead);
       }
        catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'BUSCAR_PRODUCTO':
       $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
            
         $datos['error']='ok';
        IF ($varOdoo->checkAccess() === false ){
            http_response_code(500);
          $datos['error']= 'Error de coneccion a ODOO !!!'; 
            echo json_encode($datos);
            die();  
        }    
        
         if (isset($_validar_existencia) && $_validar_existencia === true ) {
            $conexion =\Class_php\DataBase::getInstance();
            $link = $conexion->getLink();  
            $_llave_usuario = LLAVE_SESSION;
            $where = "  where  estadoCaja = '1'   and  usuarioEstadoCaja ="
                   . " (SELECT usuario FROM  session WHERE `key` = '{$_llave_usuario}' ) ";
               $_rst_asig =$conexion->where('vw_cajas', $where  );  
             $datos['sel_rst_asig'] =   $_rst_asig;
            if ( (sizeof($_rst_asig['datos']) > 0)){ 
                $id_bodega_stock_asignada = $_rst_asig['datos'][0]['idBodegaStock'];
                //$id_bodega_stock_asignada = 19;
                $datos['$id_bodega_stock_asignada'] = $id_bodega_stock_asignada ;
            } else{
                   http_response_code(500);
                  $datos['error']= 'Error al Validar la existencia ';
                  echo json_encode($datos);
                  die(); 
            }
        }    
    $_flag_compa = false ;
      $OdooTbl->clearParam();
      /*  if (isset($_principal) && $_principal === true){
            $OdooTbl->setNewParam("location_id","=", false); 
        }
        */
      if (  (isset($_codBarra) && $_codBarra === true)  || 
            (isset($_marca) && $_marca === true)        ||
            (isset($_categoria) && $_categoria === true) 
             ){ 
              $OdooTbl->setRelacionAND();}
        $OdooTbl->setNewParam("active","=", true);  
        
         if (  (isset($_codBarra) && $_codBarra === true)){
            $OdooTbl->setNewParam("barcode","=", $_data); 
            $_flag_compa = true ;
            $_limit =1;
        }
         if (  (isset($_marca) && $_marca === true)){
            $OdooTbl->setNewParam("x_studio_marca","=", $_data); 
            $_flag_compa = false ; 
        }
          if (  (isset($_categoria) && $_categoria === true)){
            $OdooTbl->setNewParam("categ_id","=", $_data); 
            $_flag_compa = false ; 
        }
         if (isset($_descripcion) && $_descripcion === true && trim($_data) != '') { 
            $OdooTbl->setRelacionOR(); 
            $_dato = $_data ; 
             $OdooTbl->setNewParam("code","like", "{$_dato}%");  
            $OdooTbl->setNewParam("display_name","like", "{$_dato}%");  
            $OdooTbl->setNewParam("name","like","{$_dato}%"); 
            
            $OdooTbl->setRelacionOR(); 
             $_dato = ucwords($_data);
             $OdooTbl->setNewParam("code","like", "{$_dato}%");  
             $OdooTbl->setNewParam("display_name","like", "{$_dato}%");  
             $OdooTbl->setNewParam("name","like","{$_dato}%"); 
             
             $OdooTbl->setRelacionOR();
             $_dato = strtoupper($_data);
             $OdooTbl->setNewParam("code","like", "{$_dato}%");  
             $OdooTbl->setNewParam("display_name","like", "{$_dato}%");  
             $OdooTbl->setNewParam("name","like","{$_dato}%"); 
             //************************************************
              $OdooTbl->setRelacionOR(); 
            $_dato = $_data ; 
             $OdooTbl->setNewParam("code","like", "{$_dato}");  
            $OdooTbl->setNewParam("display_name","like", "{$_dato}");  
            $OdooTbl->setNewParam("name","like","{$_dato}"); 
            
            $OdooTbl->setRelacionOR(); 
             $_dato = ucwords($_data);
             $OdooTbl->setNewParam("code","like", "{$_dato}");  
             $OdooTbl->setNewParam("display_name","like", "{$_dato}");  
             $OdooTbl->setNewParam("name","like","{$_dato}"); 
             
             $OdooTbl->setRelacionOR();
             $_dato = strtoupper($_data);
             $OdooTbl->setNewParam("code","like", "{$_dato}");  
             $OdooTbl->setNewParam("display_name","like", "{$_dato}");  
             $OdooTbl->setNewParam("name","like","{$_dato}"); 
             
        }
        if (isset($_all) && $_all === true ) { 
            $OdooTbl->setRelacionOR(); 
            $OdooTbl->setNewParam("code","=", $_data);  
            $OdooTbl->setNewParam("display_name","=", $_data);  
            $OdooTbl->setNewParam("name","=", $_data);  
            $OdooTbl->setNewParam("categ_id","=", $_data);  
             
        }
        if (isset($_limit) && is_numeric($_limit)){
               $OdooTbl->setLimit($_limit);
               if($_limit == 1){
                   $_flag_compa = true ;
               }
         }
         if (isset($_offset) && is_numeric($_offset)){
               $OdooTbl->setOffset($_offset);
         }
        $data['parametros'] = $OdooTbl->getArrayParam();
        $dataSet = $varOdoo->DataSet($OdooTbl->productos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
        //$datos['dataSet'] = $dataSet; 
        $fields = [ "id","price","price_extra","lst_price","default_code","code","partner_ref","active","barcode",
            "image_1920" ,"image_1024" ,"image_512" ,"image_256" ,"image_128" ,
            "display_name", "name",  "categ_id" , "taxes_id" , "x_studio_marca" ,"sales_count" ];
        if(isset($_todo) && $_todo === true){
            $fields = [];
        }
        
       $dataRead = $varOdoo->DataRead($OdooTbl->productos, $dataSet , $fields);
       $datos['numdata'] = sizeof($dataRead);
        $datos['data'] = $dataRead; 
     $datos['parametros'] = $OdooTbl->getArrayParam();
        
         $datos['numdataInicial'] = sizeof($dataRead);
       if ($_flag_compa && $datos['numdata'] == 0 &&  is_numeric($_data)){ 
           $_flag_compa = false;
            $OdooTbl->clearParam();  
            $OdooTbl->setRelacionAND();
            $OdooTbl->setNewParam("active","=", true); 
            IF (is_numeric($_data))
            $OdooTbl->setNewParam("id","=", $_data); 
            
            
            $datos['limitNum'] = $_limit;
            if (isset($_limit ) && is_numeric($_limit)){
                    $OdooTbl->setLimit($_limit);
                   if($_limit == 1){
                       $_flag_compa = true ;
                   }
             }
             if (isset($_offset) && is_numeric($_offset)){
               $OdooTbl->setLimit($_offset);
             } 
        $dataSet = $varOdoo->DataSet($OdooTbl->productos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
    
       $dataRead = $varOdoo->DataRead($OdooTbl->productos, $dataSet , $fields);
       $datos['numdata'] = sizeof($dataRead);
        $datos['data'] = $dataRead; 
    
       }
    
       $datos['limitArr'] = $OdooTbl->getArrayLimit(); 
        if ($_flag_compa && $datos['numdata'] == 0 ){ 
           $_flag_compa = false;
           $OdooTbl->clearParam();  
             $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("active","=", true);  
             $OdooTbl->setNewParam("default_code","=", $_data); 
            if (isset($_limit) && is_numeric($_limit)){
                  $OdooTbl->setLimit($_limit);
                   if($_limit == 1){
                       $_flag_compa = true ;
                   }
             }
             if (isset($_offset) && is_numeric($_offset)){
               $OdooTbl->setLimit($_offset);
             } 
        $dataSet = $varOdoo->DataSet($OdooTbl->productos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
         $dataRead = $varOdoo->DataRead($OdooTbl->productos, $dataSet , $fields);
         
       
       $datos['numdata'] = sizeof($dataRead);
        $datos['data'] = $dataRead;  
        }
        
       
                     
      if( $datos['numdata'] > 0 && !isset($datos['data']['faultCode'])){
          foreach ($datos['data'] as $key => $value) {
              //print_r($value);
              
              $value['impuestos']['cnt'] = 1 ;
            
              $value['impuestos']['datos'] = 
                      array(
                          "id" => 0,"name" => "IVA",
                          "amount"  => 0, 
                          "amount_type" => "percent" ) ;
               $OdooTbl->clearParam();   
          $fieldsTX = [ "id","name","amount" , "amount_type"  ]; 
           $OdooTbl->setNewParam("id","=",  $datos['data'][0]['taxes_id']);
            $OdooTbl->setNewParam("name","like",  '%IVA%'); 
          $dataSetTax = $varOdoo->DataSet($OdooTbl->impuestos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit()); 
          $dataReadTax = $varOdoo->DataRead($OdooTbl->impuestos, $dataSetTax , $fieldsTX ); 
          $datos[ '$dataReadTax'][ $key ] = $dataReadTax ;
          $datos[ '$dataSetTax'][ $key ] = $dataSetTax ;
       if(sizeof($dataReadTax)> 0){
         $value['impuestos']['datos'] = $dataReadTax; 
         $value['impuestos']['cnt'] = sizeof($dataReadTax);}
         $datos['data'][ $key ]=$value;
          } 
      } 
        
         if (isset($_validar_existencia) && $_validar_existencia === true && $datos['numdata'] > 0) { 
    
                 $OdooTbl->clearParam();    
                $OdooTbl->setLimit(1);
                $cnt = array();
                  
         //*************************
           $OdooTbl->clearParam();    
        $OdooTbl->setNewParam("id","=", $id_bodega_stock_asignada);  
        $OdooTbl->setLimit(1);
          $dataSetLocation = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
         // $datos['dataLocation'] =   $varOdoo->DataRead($OdooTbl->bodegas, $dataSetLocation );
          
         //**************** 
           $OdooTbl->clearParam(); 
                 $OdooTbl->setNewParam("product_id","=", $dataSet); 
                 
                  $OdooTbl->setNewParam("location_id","=", $dataSetLocation);   
                $cnt =  $varOdoo->DataSet($OdooTbl->existencias,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit()); 
                $dataReadCnt = $varOdoo->DataRead($OdooTbl->existencias, $cnt  );
                if (sizeof($cnt) > 0 ){
                     $datos['data'][0]['cantidad'] =$dataReadCnt[0][ "quantity"]; 
                }else{
                     $datos['data'][0]['cantidad'] = 0 ; 
                }
              
                
           } 
       if(isset($_sin_parceros) && $_sin_parceros == true){$_flag_compa = false;}
        
        if ($_flag_compa ){
        $parceros = array();
        foreach ($dataRead as $key => $value) {
             $OdooTbl->setLimit(5);
             $OdooTbl->clearParam();
              $OdooTbl->setNewParam("barcode","!=", $value["barcode"]); 
              $OdooTbl->setNewParam("categ_id","=", $value["categ_id"][0]); 
             $OdooTbl->setNewParam("active","=", true); 
             $dataSet = $varOdoo->DataSet($OdooTbl->productos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
             $dataRead2 = array(); 
             $dataRead2 = $varOdoo->DataRead($OdooTbl->productos, $dataSet , $fields); 
             if (sizeof($dataRead2)>0)
             $parceros = $dataRead2 ;
        }
        $datos['prodReemplazo'] =  $parceros ;
        $datos['NumprdReemplazo'] =  sizeof($parceros) ;
        }
        
         
       }
        catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break;
    CASE 'BUSCAR_MARCAS':
         $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
            
         $datos['error']='ok';
        IF ($varOdoo->checkAccess() === false ){
          $datos['error']= 'Error de coneccion a ODOO !!!'; 
            echo json_encode($datos);
            die();  
        }    
    $_flag_compa = false ;
      $OdooTbl->clearParam();
                
        if (isset($_all) && $_all === true ) {
            
            $OdooTbl->setNewParam("code","=", $_data); 
            $OdooTbl->setNewParam("display_name","=", $_data); 
            $OdooTbl->setNewParam("name","=", $_data); 
            $OdooTbl->setNewParam("categ_id","=", $_data);  
        } 
        if(isset($_categ) && $_categ === true  ){
          
            $OdooTbl->setNewParam("categ_id","=", $_data);     
           $datos['opc2'] = 'es categoria';
        }
        
         if (isset($_limit) && is_numeric($_limit)){
               $OdooTbl->setLimit($_limit);
              
         }
          $fields = [ "id","x_name","display_name" ];
         if (isset($_offset) && is_numeric($_offset)){
               $OdooTbl->setLimit($_offset);
         }
    
        $dataSet = $varOdoo->DataSet($OdooTbl->marcas,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
        //$datos['dataSet'] = $dataSet; 
       $dataRead = array();              
       $dataRead = $varOdoo->DataRead($OdooTbl->marcas, $dataSet , $fields);
       $datos['numdata'] = sizeof($dataRead);
        $datos['data'] = $dataRead; 
       
    
       }
        catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
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