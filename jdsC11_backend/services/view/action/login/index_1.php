<?php
require_once '../../../../php/helpers.php';   
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
    require_once $nomClass;
 });
new Core\Config();   
header("Content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: * "); 
//  $json ='{"action": "ef2e1d89937fba9f888516293ab1e19e7ed789a5","_usuario": "juniorjmd","_password": "sicae12345" }';
//$json ='{"action": "16770d92a6a82ee846f7ff23b4c8ad05b69fba03","_llaveSession": "9bdec4a309de1f18821ce09f6c7f7b9d69812832"}';
 //  var_dump(json_decode($json, true));
    
//   $_POST = json_decode($json, true);      

//$action = $_POST['action'];
//$nomTableCabecera = $_POST['cabeceras']; 

 http_response_code(200);
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {  
    echo'error';
die();
}    
$headers = apache_request_headers();

foreach ($headers as $header => $value) { 
   // echo "<{$header}>" ;
   if ( trim($header)==='Autorizacion;'){
       define('LLAVE_SESSION', TRIM($value));
     //  echo 'si definio la llave';
   }
    
} 

//echo "llave de session = " . LLAVE_SESSION;

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
   
 $json = file_get_contents('php://input');
   $_POST = json_decode($json, true);   
   
foreach ($_POST as $clave => $valor){
    $$clave = $valor;
}
    
switch ($action){
    case 'ef2e1d89937fba9f888516293ab1e19e7ed789a5'://login
    
         $_password =  sha1($_password); 
    $_llave = $_usuario.date("Ymdhms");
    $_llave = sha1($_llave); 
       TRY {
       $conexion =Class_php\DataBase::getInstance(); 
       $link = $conexion->getLink();
       $consulta = $link->prepare("call  sp_login(:_usuario, :_pass, :_llave )");
        $consulta->bindParam(':_usuario', $_usuario);
         $consulta->bindParam(':_pass', $_password);
         $consulta->bindParam(':_llave', $_llave); 
         // echo "call  sp_login('$_usuario', '$_password', '$_llave' )";
         $consulta->execute();
         
         $array =  $consulta->fetchAll(); 
        $datos['data']['_result'] = $array[0]['_result'] ;
         $datos['data']['usuario'] =array();
    
                 
  switch ($array[0]['_result'] ) {
      case '-1':
          http_response_code(504);
           $datos['error'] = "usuario o clave invalidos";

          break;
      case '100':
          http_response_code(200);
      $datos['data']['usuario'] = array(
                 "llave_session" => $array[0]['llave_session'],
                 "cod_registro" => $array[0]['cod_registro'],
                 "descripcion" => $array[0]['descripcion'],
                 "perfil" => $array[0]['perfil'],
                 "cod_persona" => $array[0]['cod_persona'],
                 "cod_identificacion" => $array[0]['cod_identificacion'],
                 "nombre" => $array[0]['nombre'],
                 "direccion" => $array[0]['direccion'],
                 "tel1" => $array[0]['tel1'],
                 "tel2" => $array[0]['tel2'],
                 "f_nacimiento" => $array[0]['f_nacimiento'],
                 "lugar_nacimiento" => $array[0]['lugar_nacimiento'] );
                 $datos['error'] ='ok';
                 
        define('NOMBRE_SESSION_GEN',$_usuario );    
         ini_set('session_save_path', '/home/'.NOMBRE_SESSION_GEN.'/tmp');
        
        if(@session_start() !== false){  
            session_destroy(); 
      
            
        } 
            session_name(NOMBRE_SESSION_GEN); 
            session_start(); 
        $_SESSION['usuario1']= $_usuario; 
        
        $_SESSION['access'][NOMBRE_SESSION_GEN] = true; 
        $_SESSION['llave'] = $array[0]['llave_session']; 
        //if($_SESSION['access']==false)
      
      break;
      case '-2':
            http_response_code(506);
           $datos['error'] = "error al intentar crear la session";
          break;
  }
        
           }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
    
      case '16770d92a6a82ee846f7ff23b4c8ad05b69fba03' : //verificar llave de session
      
          if (isset($_llaveSession)){
              
              http_response_code(200); 
                TRY {
       $conexion =Class_php\DataBase::getInstance(); 
       //($tabla,$where = null)
       $where = "where `key` = '$_llaveSession' ";
               $_result =$conexion->where('session', $where);
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
                session_name($_nombreSession); 
                session_start();  
                
                if ($_SESSION['access'][$_nombreSession] == false){
                     http_response_code(502);
                      $datos['error']= 'La llave de session pertenece a una session diferente a la actual.';
                }else{
                    if ($_estado == 'A') {
                       $datos['error']= 'llave session correcta, session actualmente activa.';
                    }else{
                       http_response_code(502);
                      $datos['error']= 'La session ha expirado.'; 
                      $_SESSION['access'][$_nombreSession] = false;   
                    }
                }
                    
            }else{
                http_response_code(501);
               $datos['error']= 'La llave de session no es valida';
            }
      
       }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
          }
          else{
             http_response_code(500); 
              $datos['error']= 'Debe enviar una llave de session';
          }
      break;
      case 'c332258e69e38f18450f9a48c65c89d9e436c561'://cierre de session
       if (isset($_llaveSession)){
              
              http_response_code(200); 
                TRY {
       $conexion =Class_php\DataBase::getInstance(); 
       //($tabla,$where = null)
       $where = "where `key` = '$_llaveSession' ";
               $_result =$conexion->where('session', $where);
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
      
                
                if ($_SESSION['access'][$_nombreSession] == false){
                     http_response_code(502); 
                      $datos['error']= 'La llave de session pertenece a una session ya expirada.';
                }else{
                    $queryFinal = "update session set "
                            . "estado = (SELECT idestado FROM  estado_registro where estado = 'I')"
                            . " where key = '$_llaveSession' ";
                    $consulta = $link->prepare($queryFinal);
                    $datos['queryFinal'] = $queryFinal;
                    if ($consulta->execute()){
                      $datos['error']='ok';  
                      $_SESSION['access'][$_nombreSession] = false;         
                $_SESSION = array();
                session_destroy();
                    }else{
                        $datos['error']="Error al tratar de ingresar los valores a la base de datos tabla : session "; }
      
                }
                $_SESSION['access'][$_nombreSession] = false;         
                $_SESSION = array();
                session_destroy();
                    
            }else{
                http_response_code(501);
               $datos['error']= 'La llave de session no es valida';
            }
      
       }
      catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
          }
          else{
             http_response_code(500); 
              $datos['error']= 'Debe enviar una llave de session';
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