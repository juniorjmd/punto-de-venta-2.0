<?php
require_once '../../../../../php/helpers.php';  
require_once'../../../../../php/fpdf181/fpdf.php'; 
/*
index de eliminacion en odoo no agregar actions que no sean solo de eliminacion de registro en odoo
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
    case 'ELIMINAR_STOCK_PICKING' :
        
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }    
                if (!isset($cod_sp) || !is_numeric($cod_sp) || $cod_sp <= 0){
                     $datos['error']= 'Error en el codigo del stock picking a cerrar en ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
               $OdooTbl->clearParam(); 
              $varOdoo->cancelSP($cod_sp); 
    
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