<?php
require_once '../../../../php/helpers.php';  
//echo print_r(FILTRAR_DATOS_RFC_EASYSALE_CLIENTES());
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
  //  ECHO $nomClass.'<BR>';
    require_once $nomClass;
 });

new Core\Config();  
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('status' => false));
    exit;
}
$json = file_get_contents('php://input');
   $_POST = json_decode($json, true); 
$_nombre_tabla = $_POST['tabla'] ;

$anio = date('Y');
$mes = date('m');
$path = URL_BASE_UPLOAP."csv_tablas/$anio/$mes/";
$path_aux = "../../../files_up/csv_tablas/$anio/$mes/";
$_resultado_proceso = array();
if (isset($_FILES['file'])) {
    $originalName = $_FILES['file']['name'];
    $auxExt = pathinfo($originalName, PATHINFO_EXTENSION);
    $ext = '.'.pathinfo($originalName, PATHINFO_EXTENSION);
    
    
    $generatedName = md5($_FILES['file']['tmp_name']).$ext;
    $filePath = $path.$generatedName;
    $filePath_aux = $path_aux.$generatedName;
       
    if (!is_writable($path_aux)) {
        if(!mkdir($path_aux, 0777, true)) { 
         echo json_encode(array(
            'status' => false,
            'msg'    => 'error en la ruta del archivo.'
        ));
        exit;
    }
        
    }
    $auxExt = strtoupper($auxExt);
     if (trim($auxExt) === 'CSV' ) {}else{
         
         echo json_encode(array(
            'status' => false,
            'msg'    => 'error en la extencion del archivo.',
            'extencion'  => '--'.$auxExt.'--'
        ));
        exit;
        }  
    
    $pdo = Class_php\DataBase::getInstance(); 
    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath_aux)) {  
        //echo $filePath.' - '.$_nombre_tabla;
        $_resultado_proceso =   ingresarDatosCSV($filePath,$_nombre_tabla );
        if($_resultado_proceso['procesado']){
            echo json_encode(array(
                'status' => true,
                'extencion' => $ext, 
                'generatedName' => $generatedName,
                'datos' => $_resultado_proceso['datos'],
                'numDatos'=> $_resultado_proceso['numdata'],
                'resultado'=>$_resultado_proceso,
                'tabla'=>$_nombre_tabla
        ));}else{
              echo json_encode(array(
                'status' => false,
                'generatedName' => $generatedName,
                'msg'    => 'Error al procesar el archivo.'
        ));
        }
            
    }else{
            echo json_encode(array(
                'status' => false,
                'generatedName' => $generatedName
            ));
        }
        
}else{
    echo json_encode(
        array('status' => false, 'msg' => 'Ningun archivo uploaded.')
    );
    exit;
}