<?php
require_once '../../../../../php/helpers.php';  
require_once'../../../../../php/fpdf181/fpdf.php'; 
/*
index de sincronizacion de informacion desde y para odoo no agregar actions 
 * que no sean solo de sincronizacion. para cada tabla a actualizar realizar un proceso independiente, 
 * lo mismo si se quiere sincronizar mas de una base de datos con los datos de odoo, ten en cuenta que 
 * este proceso es delicado y asi la tabla sea la misma se debe realizar de manera independiente y siempre se 
 * debe retornar los datos de error cuando ocurra y se debe tener siempre una tabla de registros por actualizacion
 * donde se guarde un registro por cada actualizacion realizada con la tabla actualizada, hora y fecha de actualizacion
 * resultado de la operacion y descripcion del resultado para guardar el posible mensaje de error generado en odoo
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
   $json = file_get_contents('php://input');
   $_POST = json_decode($json, true); 
foreach ($_POST as $clave => $valor){
    $$clave = $valor;
}  
    
$OdooTbl = new Class_php\OdooDB(); 
switch ($action){ 
  
    case 'ACTUALIZAR_EXISTENCIA_POR_BODEGA':
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
        $cnt = array();
         $dataSetLocation = array()    ;    
        $conexion =\Class_php\DataBase::getInstance();
        $link = $conexion->getLink(); 
        $query = "update prd_actualizacion_existencias set ".
                 " estado = 2 , fecha_fin= now() , hora_fin = now()". 
                " where bodega = {$id_bodega_stock_asignada}";
        $consulta = $link->prepare($query);
        if ($consulta->execute()){
         //*************************
        $OdooTbl->clearParam();    
        $OdooTbl->setNewParam("id","=", $id_bodega_stock_asignada);  
        $OdooTbl->setLimit(1);
        $dataSetLocation = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
       // $datos['dataLocation'] = $varOdoo->DataRead($OdooTbl->bodegas, $dataSetLocation );
         
        $OdooTbl->clearParam();  
        $OdooTbl->setNewParam("location_id","=", $dataSetLocation);   
        $cnt =  $varOdoo->DataSet($OdooTbl->existencias,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit()); 
        $dataReadCnt = $varOdoo->DataRead($OdooTbl->existencias, $cnt  );
    
        
        if(is_array($dataReadCnt) && sizeof($dataReadCnt)>0){
              $query = "insert into  prd_actualizacion_existencias (bodega) ".
                 " value ({$id_bodega_stock_asignada})";
        $consulta = $link->prepare($query);
        if ($consulta->execute()){
            
           $_result =$conexion->funciones('getIdExistenciaActiva',[$id_bodega_stock_asignada]);
          //  $datos['$_result_funcion'] = $_result;
            if($_result['_result'] == 'ok'){ 
                 $_id_Actualizacion = $_result['datos'][0]['result'];
                foreach ($dataReadCnt as $ky => $value) {   
                    $query = "insert into  prd_existencias"
                            . " ( cod_producto, cantidad_inicial, codigoActualizacion) "
                            . " value ("
                            . "{$value['product_id'][0]} ,"
                            . "{$value['quantity']} ,"
                            . "{$_id_Actualizacion} "
                            . ")";
                     
                  //  $datos['insert'][$ky] = $query;       
                    $consulta = $link->prepare($query);
                   if(!$consulta->execute() )
                   {$datos['error']= 'Error al insertar las existencias (prd_existencias) !!!'; 
            echo json_encode($datos);
            die(); }
                }
            
            
            
            }else{
                   $datos['error']= 'Error al optener el id de la existencia activa !!!'; 
            echo json_encode($datos);
            die(); 
            }
          
        }}else{   http_response_code(500);
          $datos['error']= 'no existen existencias para actualizar !!!'; 
            echo json_encode($datos);
            die();  }
         }else{   http_response_code(500);
          $datos['error']= 'Error al actualizar la tabla prd_actualizacion_existencias !!!'; 
            echo json_encode($datos);
            die();  }}
        catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break;
    
    case 'ACTUALIZAR_LISTADO_DE_PRODUCTOS':
       $varOdoo = new Class_php\Odoo(null, null, null,null);
         $conexion =\Class_php\DataBase::getInstance();
        $link = $conexion->getLink(); 
         TRY{
            
         $datos['error']='ok';
        IF ($varOdoo->checkAccess() === false ){
            http_response_code(500);
          $datos['error']= 'Error de coneccion a ODOO !!!'; 
            echo json_encode($datos);
            die();  
        }    
        
    
      $OdooTbl->clearParam();  
        $OdooTbl->setNewParam("active","=", true);  
        
     
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
        //$datos['data'] = $dataRead; 
        $datos['parametros'] = $OdooTbl->getArrayParam();
         
   foreach ($dataRead as $DataValue) {
       $llaves =''; 
         $valores =''; 
         $updatelist =''; 
         $separador='';
         $separadorUpd = '';
        foreach ($DataValue as $key => $value) {
            $dato = $value ;
         if (is_array($value)){$dato = $value[0] ;}
         
         $llaves .= $separador."`".$key."`" ; 
    
        
         $valores .=  $separador.'"'.$dato.'"'; 
         
         if(trim($key)!='id') {$updatelist .=  $separadorUpd."`".$key.'` = "'.$dato.'"'; 
         $separadorUpd = ',';
         } 
         $separador = ',';
        }
        str_replace('/', '-', $valores);
           $query= "INSERT INTO prd_productos ($llaves ) VALUES ($valores) "
                   . "ON DUPLICATE KEY UPDATE $updatelist ;";
            $llaves = '';
            $valores='';
            $updatelist = '';
            $consulta = $link->prepare($query);
                   if(!$consulta->execute() )
                   {
                        $datos['$query']= $query;
                       $datos['error']= 'Error al actualizar los productos (prd_productos) !!!'; 
            echo json_encode($datos);
            die(); }
         }
         
         
         
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
    case 'UPDATE_VENTAS_LISTADO_ODOO':
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
             $OdooTbl->setNewParam("id","=", 3); 
           $dataSet = $varOdoo->DataSet($OdooTbl->ventas_linea,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit()); 
           
            $OdooTbl->clearDatoInsert();
            $OdooTbl->setNewDatoInsert('product_uom_qty', 2);
            $respUpdCnt = $varOdoo->Update($OdooTbl->ventas_linea,$OdooTbl->getDatosUpdate($dataSet)); 
                $datos['$respUpdCnt'] = $respUpdCnt;
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
    
    ////////////////////////////////////////////////////
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
    ////////////////////////////////////////////////////
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
    case 'CREAR_STOCK_PICKING':
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                
           if ( !isset($_documento) && trim($_documento) == "" && !is_numeric($_documento)){
                 $datos['error']= 'Error en el codigo del documento enviado !!!'; 
                 http_response_code(501);
                   echo json_encode($datos);
                   die();     
               }     
    
           $OdooTbl->clearParam();
            $OdooTbl->setLimit(1); 
              $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", "POS_JDS_MOVE"); 
             $OdooTbl->setNewParam("sequence_code","=", "POS_JDS_MOVE");  
           $_op_type_id = $varOdoo->DataSet($OdooTbl->tipo_s_p,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
    
         //  print_r($op_type_id)  ; 
           // se debe adquirir desde la base de datos con el cod del documento que se esta cerrando
           // de la tabla vw_documentos 
           $_loc_wh = 19 ;  $_loc_virt = 29 ;
           // se debe adquirir de la base de datos con el cod del documento que se esta cerrando 
           // de la tabla documentos_listado_productos
            $_move_ids = array( 147 ) ;
           
            $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $_loc_wh );  
            $loc_wh = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
            $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $_loc_virt ); 
            $loc_virt =  $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           
            $idMovimiento = $varOdoo->setSP($_op_type_id[0], $loc_wh[0], $loc_virt[0], $_move_ids) ;  
            $result = $varOdoo->confirmSP($idMovimiento); 
            $datos['cod_stock_picking'] = $idMovimiento;
            $OdooTbl->clearParam(); 
            $result = $varOdoo->comprobarSP($idMovimiento); 
            print_r($result);
            if ( $result  == 1){
            $result = $varOdoo->cerrarSP($idMovimiento);
             if ( $result  != 1){
             //   print_r($result);
                $datos['error']= 'Error de cerrarSP en  ODOO !!!'; 
                   echo json_encode($datos);
                   die();     
               }
            }else{
               $datos['error']= 'Error de comprobarSP en  ODOO !!!'; 
                echo json_encode($datos);
                die();     
            }
         }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'CREAR_STOCK_PICKING_FINAL':
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }
                
           if ( !isset($_documento) && trim($_documento) == "" && !is_numeric($_documento)){
                 $datos['error']= 'Error en el codigo del documento enviado !!!'; 
                 http_response_code(501);
                   echo json_encode($datos);
                   die();     
               }     
               
            $conexion =\Class_php\DataBase::getInstance();
            $link = $conexion->getLink();  
            $_llave_usuario = LLAVE_SESSION;
                 $where = " where orden = {$_documento} ";
               $_rst_documento  =$conexion->where('vw_obj_documentos', $where  );  
               //$datos['$_rst_documento'] = $_rst_documento ;
                if ( (sizeof($_rst_documento['datos']) > 0)){ 
                    $_doc_obj = json_decode( $_rst_documento['datos'][0]['objeto']) ;
                   // $datos['obj']  = $_doc_obj     ; 
    
                  //  $_loc_wh = 19 ;  $_loc_virt = 29 ;
                   $_loc_wh = $_doc_obj->idStockOdooPOS  ;  
                   $_loc_virt = $_doc_obj->idStockOdooVtl  ; 
                   // $datos['$_loc_wh']=  $_loc_wh  ;
                   // $datos['$_loc_virt']=  $_loc_virt ; 
                   if (sizeof($_doc_obj->listado ) < 1 ){
                        http_response_code(500);
                  $datos['error']= 'No existen productos facturados, no se puede cerrar la factura !!! ';
                  echo json_encode($datos);
                  die(); 
                   }else{
                     $cntMov = 0;
                     foreach ($_doc_obj->listado as  $value) {
                         $_move_ids[$cntMov] = $value ->id_stock_move_odoo ;
                         $cntMov++;
                     }
                     //$datos['$_move_ids']= $_move_ids ;
                     //  
                    // se debe adquirir de la base de datos con el cod del documento que se esta cerrando 
                    // de la tabla documentos_listado_productos 
                     $_cod_stock_p = $_doc_obj->cod_stock_p ;
                     $_cod_sec_int_stock_p = $_doc_obj->cod_sec_int_stock_p ;
                   }
                   
                   if ($_doc_obj -> cliente == "0" && strtoupper($_doc_obj ->validarCliente ) == 'SI'){ 
                        http_response_code(500);
                        $datos['error']= 'Cliente no supera las validaciones !!! ';
                        echo json_encode($datos);
                        die(); 
                   }} else{
                   http_response_code(500);
                  $datos['error']= 'Error al obtener los datos del documento enviado !!! ';
                  echo json_encode($datos);
                  die(); 
            }
           $OdooTbl->clearParam();
            $OdooTbl->setLimit(1); 
              $OdooTbl->setRelacionAND();
             $OdooTbl->setNewParam("barcode","=", $_cod_stock_p); 
             $OdooTbl->setNewParam("sequence_code","=", $_cod_sec_int_stock_p); 
          //   $OdooTbl->clearParam();
           $_op_type_id = $varOdoo->DataSet($OdooTbl->tipo_s_p,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           //$datos['tipo_s_p']['$_op_type_id'] = $_op_type_id ; 
           //$datos['tipo_s_p']['tabla'] = $OdooTbl->tipo_s_p ; 
           //$datos['tipo_s_p']['parametros'] = $OdooTbl->getArrayParam(); 
           $dataRPrd = $varOdoo->DataRead($OdooTbl->tipo_s_p, $_op_type_id  );  
           //$datos['tipo_s_p']['$dataRPrd'] =$dataRPrd;
          //  echo json_encode($datos);
           //       die(); 
         //  print_r($op_type_id)  ; 
           // se debe adquirir desde la base de datos con el cod del documento que se esta cerrando
           // de la tabla vw_documentos 
            $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $_loc_wh );  
            $loc_wh = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
            $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $_loc_virt ); 
            $loc_virt =  $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
            echo '----';            print_r($loc_virt);
                     echo '----';
           
            $idMovimiento = $varOdoo->setSP($_op_type_id[0], $loc_wh[0], $loc_virt[0], $_move_ids , $_doc_obj->orden) ; 
            print_r($idMovimiento);
            echo '---0';
            if ( is_array($idMovimiento) ){
             //   print_r($result);
                $datos['error']= 'Error al crear el stock picking ODOO !!!' .
                        'statusCode:'.$idMovimiento['faultCode'].'-'.$idMovimiento['faultString']; 
                   echo json_encode($datos);
                   die();     
               }else{
                   
                    $conexion =\Class_php\DataBase::getInstance();
                    $link = $conexion->getLink(); 
                     $query = "update documentos set ".
                         " documento_odoo = {$idMovimiento} , ".
                         " tipoDocumentoFinal = (SELECT id FROM  tipos_de_documentos where nombre = 'venta' )". 
                         " where orden = {$_doc_obj->orden}";
                         $consulta = $link->prepare($query);
                      if ($consulta->execute()){
                          $where = " where orden = {$_documento} ";
                           $_rst_documento  =$conexion->where('vw_obj_documentos', $where  );  
                           if ( (sizeof($_rst_documento['datos']) > 0)){ 
                              $_doc_obj = json_decode( $_rst_documento['datos'][0]['objeto']) ;
                              $cntMov = 0;
                              $cantVen = 0;
                               foreach ($_doc_obj->listado as  $value) {
                                   if($value ->estado_linea_venta == 'A')
                                   {  $_move_ids[$cntMov] = $value ->id_stock_move_odoo ;
                                   $cantVen += $value->cantidadVendida ;
                                   $cntMov++;}
                                }
                            $OdooTbl->clearDatoInsert();
                            
                             $OdooTbl->setNewDatoInsert("name", $_doc_obj->idDocumentoFinal  );
			 $OdooTbl->setNewDatoInsert("state", "sale" );
			 $OdooTbl->setNewDatoInsert("date_order", "2022-04-21 14,49,12" );
			 $OdooTbl->setNewDatoInsert("validity_date", false );
			 $OdooTbl->setNewDatoInsert("is_expired", false );
			 $OdooTbl->setNewDatoInsert("require_signature", false );
			 $OdooTbl->setNewDatoInsert("require_payment", false );
			 $OdooTbl->setNewDatoInsert("create_date", "2022-04-21 14,49,03" );
			 $OdooTbl->setNewDatoInsert("user_id", 2 );
			 $OdooTbl->setNewDatoInsert("partner_id", $_doc_obj->clienteobj[0]['id'] ); 
			 $OdooTbl->setNewDatoInsert("partner_invoice_id",  $_doc_obj->clienteobj[0]['id']);
			 $OdooTbl->setNewDatoInsert("partner_shipping_id", $_doc_obj->clienteobj[0]['id'] );
			$OdooTbl->setNewDatoInsert("pricelist_id",2);
			$OdooTbl->setNewDatoInsert("currency_id", 8 );
			$OdooTbl->setNewDatoInsert("analytic_account_id", false );
			$OdooTbl->setNewDatoInsert("order_line", $cntMov - 1 );
			$OdooTbl->setNewDatoInsert("invoice_count", 0); 
			$OdooTbl->setNewDatoInsert("invoice_status", "no");
			$OdooTbl->setNewDatoInsert("note", "venta realizada desde punto de venta externo");
			$OdooTbl->setNewDatoInsert("amount_untaxed", $_doc_obj->presioVenta); 
			$OdooTbl->setNewDatoInsert("amount_tax", 0); 
			$OdooTbl->setNewDatoInsert("amount_total", $_doc_obj->presioVenta); 
			$OdooTbl->setNewDatoInsert("currency_rate", 1); 
			$OdooTbl->setNewDatoInsert("payment_term_id", 4);
			$OdooTbl->setNewDatoInsert("fiscal_position_id", 2);
			$OdooTbl->setNewDatoInsert("company_id", 1);
			$OdooTbl->setNewDatoInsert("team_id", 1);
			$OdooTbl->setNewDatoInsert("amount_undiscounted", $_doc_obj->presioVenta); 
			$OdooTbl->setNewDatoInsert("type_name", "Sales Order" ) ;
			$OdooTbl->setNewDatoInsert("show_update_pricelist", true);
			$OdooTbl->setNewDatoInsert("sale_order_template_id", false );
			$OdooTbl->setNewDatoInsert("purchase_order_count", 0 ); 
			$OdooTbl->setNewDatoInsert("picking_policy", "direct" );
			$OdooTbl->setNewDatoInsert("warehouse_id", 5);
			$OdooTbl->setNewDatoInsert("picking_ids",$_move_ids );
			$OdooTbl->setNewDatoInsert("delivery_count", 2);
			$OdooTbl->setNewDatoInsert("expected_date", $_doc_obj->fecha);
			$OdooTbl->setNewDatoInsert("cart_quantity", $cantVen ) ;
			$OdooTbl->setNewDatoInsert("display_name",$_doc_obj->idDocumentoFinal );
			$OdooTbl->setNewDatoInsert("create_uid", 2 ) ;
			$OdooTbl->setNewDatoInsert("write_uid", 2 ) ;
			$OdooTbl->setNewDatoInsert("write_date", $_doc_obj->fecha);
			$OdooTbl->setNewDatoInsert("__last_update", $_doc_obj->fecha);
                            $respUpdCnt = $varOdoo->Create($OdooTbl->ventas,$OdooTbl->getDatosInsert()); 
                                $datos['$respUpdCnt'] = $respUpdCnt;
                          ///insertar las lineas de venta      
                                $cntMov = 0;
                            foreach ($_doc_obj->listado as  $value) {
                              if($value ->estado_linea_venta == 'A')
                               {$_move_ids[$cntMov] = $value ->id_stock_move_odoo ;
                                $OdooTbl->clearDatoInsert();
                                $OdooTbl->setNewDatoInsert("order_id", $respUpdCnt);
                            $OdooTbl->setNewDatoInsert("name", $value ->nombreProducto );
                            $OdooTbl->setNewDatoInsert("sequence", 10); 
                            $OdooTbl->setNewDatoInsert("invoice_status", "to invoice");
                            $OdooTbl->setNewDatoInsert("price_unit", $value->presioVenta);
                            $OdooTbl->setNewDatoInsert("price_subtotal", $value->valorTotal);
                            $OdooTbl->setNewDatoInsert("price_tax", 0);
                            $OdooTbl->setNewDatoInsert("price_total", $value->valorTotal);
                            $OdooTbl->setNewDatoInsert("price_reduce", $value->valorTotal);
                            $OdooTbl->setNewDatoInsert("tax_id",9);
                            $OdooTbl->setNewDatoInsert("price_reduce_taxinc", 0);
                            $OdooTbl->setNewDatoInsert("price_reduce_taxexcl", $value->presioVenta);
                            $OdooTbl->setNewDatoInsert("discount", 0);
                            $OdooTbl->setNewDatoInsert("product_id",$value ->nombreProducto );
                            $OdooTbl->setNewDatoInsert("product_template_id", $value ->nombreProducto );
                            $OdooTbl->setNewDatoInsert("product_updatable", false);
                            $OdooTbl->setNewDatoInsert("product_uom_qty", 2);
                            $OdooTbl->setNewDatoInsert("product_uom",1);
                            $OdooTbl->setNewDatoInsert("product_uom_category_id", 1);
                            $OdooTbl->setNewDatoInsert("product_uom_readonly", true); 
                            $OdooTbl->setNewDatoInsert("qty_delivered", $value->cant_real_descontada);
                            $OdooTbl->setNewDatoInsert("qty_delivered_manual", 0);
                            $OdooTbl->setNewDatoInsert("qty_to_invoice", 2);
                            $OdooTbl->setNewDatoInsert("qty_invoiced", 0);
                            $OdooTbl->setNewDatoInsert("untaxed_amount_invoiced", 0);
                            $OdooTbl->setNewDatoInsert("untaxed_amount_to_invoice", $value->presioVenta);
                            $OdooTbl->setNewDatoInsert("salesman_id", 9);
                            $OdooTbl->setNewDatoInsert("currency_id",8); 
                            $OdooTbl->setNewDatoInsert("company_id",1);  
                            $OdooTbl->setNewDatoInsert("qty_delivered_method", "stock_move");
                            $OdooTbl->setNewDatoInsert("product_packaging", false);
                            $OdooTbl->setNewDatoInsert("route_id", false);
                            $OdooTbl->setNewDatoInsert("move_ids", $value ->id_stock_move_odoo );
                            $OdooTbl->setNewDatoInsert("product_type", "product");
                            $OdooTbl->setNewDatoInsert("virtual_available_at_date", 0);
                            $OdooTbl->setNewDatoInsert("scheduled_date", false);
                            $OdooTbl->setNewDatoInsert("forecast_expected_date", false);
                            $OdooTbl->setNewDatoInsert("free_qty_today", 0);
                            $OdooTbl->setNewDatoInsert("qty_available_today", 0);
                            $OdooTbl->setNewDatoInsert("warehouse_id", 7);
                            $OdooTbl->setNewDatoInsert("qty_to_deliver", 0);
                            $OdooTbl->setNewDatoInsert("is_mto", false);
                            $OdooTbl->setNewDatoInsert("display_qty_widget", false);
                            $OdooTbl->setNewDatoInsert("name_short", $value ->nombreProducto );
                            $OdooTbl->setNewDatoInsert("linked_line_id", false); 
                            $OdooTbl->setNewDatoInsert("warning_stock", false);
                            $OdooTbl->setNewDatoInsert("display_name",$value ->nombreProducto );
                            $OdooTbl->setNewDatoInsert("create_uid",9);
                            $OdooTbl->setNewDatoInsert("create_date", $_doc_obj->fecha);
                            $OdooTbl->setNewDatoInsert("write_uid", 9);
                            $OdooTbl->setNewDatoInsert("write_date", $_doc_obj->fecha);
                            $OdooTbl->setNewDatoInsert("__last_update",$_doc_obj->fecha );
                             $respLineas[$cntMov] = $varOdoo->Create($OdooTbl->ventas_linea,$OdooTbl->getDatosInsert()); 
                             $cntMov++;
                             
                                   }
                            }
                          ///insertar las lineas de venta         
                             
                                
                           }
                           
                           
                           $datos['error']='ok';
                         //  actualizaCierresPagos
                        $actualizaDocPrd =  $conexion->procedimiento('actualizaCierresPagos', [$_documento]  );
                        if($actualizaDocPrd['_result'] === 'ok'){
                           $_rst_documento  =$conexion->where('vw_obj_documentos', $where  );  
                           if ( (sizeof($_rst_documento['datos']) > 0)){
                               $_doc_obj = json_decode( $_rst_documento['datos'][0]['objeto']) ;
                               $datos['data']['documentoFinal']  = $_doc_obj ;  
                            }
                         }else{
                            $datos['error']= 'Error de datos, no se pudo actulizar las tablas del cierre ' ;
                            http_response_code(500);
                            echo json_encode($datos);
                            die(); 
                        }
                      }else{  
                         $datos['error']= 'Error de datos, faltan uno o mas valores para la consulta ' ;
                        http_response_code(500);
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
    case 'FINALIZAR_STOCK_PICKING' :
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
               
            $result = $varOdoo->comprobarSP($cod_sp); 
            //print_r($result);
            if ( $result  == 1){
            $result = $varOdoo->cerrarSP($cod_sp);
             if ( $result  != 1){
             //   print_r($result);
                $datos['error']= 'Error de cerrarSP en  ODOO !!!'; 
                   echo json_encode($datos);
                   die();     
               }
            }else{
               $datos['error']= 'Error de comprobarSP en  ODOO !!!'; 
                echo json_encode($datos);
                die();     
            }
    
            // se añade el cierre del documento ---- en la base de datos    
         }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
        break;
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
    case 'STOCK_MOVE':
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }    
            IF (!isset($_cantidad) ||  !is_numeric($_cantidad) || $_cantidad <= 0){
              $datos['error']= 'Error en la cantidad del producto a facturar !!!'; 
                echo json_encode($datos);
                die();  
                } 
                
            IF (!isset($_cod_prd) && trim( $_cod_prd ) === '' ){
              $datos['error']= 'Error en el codigo del producto a facturar !!!'; 
                echo json_encode($datos);
                die();  
                }else{
                    $OdooTbl->clearParam();
                    $OdooTbl->setLimit(1);
                    $OdooTbl->setNewParam("id","=", $_cod_prd );
                    $dataPrd = $varOdoo->DataSet($OdooTbl->productos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
                }
                /*
           IF ((!isset($_precio_brt_prd) && trim( $_precio_brt_prd ) === '' )|| => "_precio_brt_prd" : producto.lst_price    , 
            (!isset($_precio_siniva_prd) && trim( $_precio_siniva_prd ) === '' )|| =>  "_precio_siniva_prd" : producto.precio_sin_iva    ,
            (!isset($_precio_iva_prd) && trim( $_precio_iva_prd ) === '' ) || =>  "_precio_iva_prd" : producto.valor_del_iva  , 
            (!isset($_iva_porc) && trim( $_iva_porc ) === '' )){ => "_iva_porc" : producto.impuestos.datos[0].amount  ,
                 *                  */
                IF ((!isset($_precio_brt_prd) && trim( $_precio_brt_prd ) === '' )||
                        (!isset($_precio_siniva_prd) && trim( $_precio_siniva_prd ) === '' )||
                        (!isset($_precio_iva_prd) && trim( $_precio_iva_prd ) === '' ) ||
                        (!isset($_descuento) && trim( $_descuento ) === '' ) ||
                        (!isset($_iva_porc) && trim( $_iva_porc ) === '' )){
                $datos['error']= 'Error en el precio del producto a facturar !!!'; 
                  echo json_encode($datos);
                  die();  
                  }
            $conexion =\Class_php\DataBase::getInstance();
            $link = $conexion->getLink();  
            $_llave_usuario = LLAVE_SESSION;
            $where = "  where  estadoCaja = '1'   and  usuarioEstadoCaja ="
                   . " (SELECT usuario FROM  session WHERE `key` = '{$_llave_usuario}' ) ";
               $_rst_asig =$conexion->where('vw_cajas', $where  );  
               
            if ( (sizeof($_rst_asig['datos']) > 0)){ 
                $id_bodega_stock_origen = $_rst_asig['datos'][0]['idBodegaStock']; 
                $id_bodega_stock_destino = $_rst_asig['datos'][0]['idBodegaVitual']; 
            } else{
                   http_response_code(500);
                  $datos['error']= 'Error al obtener las bodegas asignadas !!! ';
                  echo json_encode($datos);
                  die(); 
            }
        /*select * from  documentos where estado = 1 and  usuario  = 1; */
            $where = " where estado = 1 and  usuario  =   "
                   . " (SELECT usuario FROM  session WHERE `key` = '{$_llave_usuario}' ) ";
               $_rst_documento  =$conexion->where('documentos', $where  );  
                $datos['$_rst_documento'] = $_rst_documento ;
            if ( (sizeof($_rst_documento['datos']) > 0)){ 
               
                $id_documentoActual = $_rst_documento['datos'][0]['orden'];  
            } else{ 
                  $datos['error']= 'Error al obtener los datos del documento actual !!! ';
                  echo json_encode($datos);
                  die(); 
            }
          
            
             $datos['id_bodega_stock_origen'] = $id_bodega_stock_origen ; 
             $datos['id_bodega_stock_destino'] = $id_bodega_stock_destino ;
             
             //------------------------------------------------------------
             $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $id_bodega_stock_origen );  
           $loc_wh = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $loc_wh_r = array();
           $loc_wh_r = $varOdoo->DataRead($OdooTbl->bodegas, $loc_wh );
            $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $id_bodega_stock_destino ); 
           $loc_virt =  $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $loc_virt_r = array();
           $loc_virt_r = $varOdoo->DataRead($OdooTbl->bodegas,$loc_virt);
             //-------------------------------------------------------
             
    
               
                 $OdooTbl->clearParam();    
                $OdooTbl->setLimit(1);
                
                 $OdooTbl->setNewParam("product_id","=", $dataPrd ); 
                $OdooTbl->setNewParam("location_id","=", $loc_wh);  
                 $cntDS =  $varOdoo->DataSet($OdooTbl->existencias,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
    
                if (sizeof($cntDS) > 0 ){
                    /*$dataPrd*/
                   $cnt = $varOdoo->DataRead($OdooTbl->existencias, $cntDS  );
                    if (  $cnt[0][ "quantity"]  < $_cantidad ){
                       $datos['error']= 'Error la cantidad es superior a la existencias del producto enviado !!! ';
                        echo json_encode($datos);
                        die(); 
                    }
                    
                }else{
                  http_response_code(500);
                  $datos['error']= 'Error no hay existencias del producto enviado !!! ';
                  echo json_encode($datos);
                  die(); 
                }

            
           $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
    
            $datos['loc_wh'] = $loc_wh ; 
            $datos['loc_virt'] = $loc_virt ; 
             $fields = [ "id","price","price_extra","lst_price","default_code","code","partner_ref","active","barcode", 
            "display_name", "name",  "categ_id"  , "sales_count"  ];
            $dataRPrd = $varOdoo->DataRead($OdooTbl->productos, $dataPrd , $fields);  
            $datos['$dataRPrd'] = $dataRPrd;
            $cantidadVentdida = $dataRPrd[0]['sales_count'] + $_cantidad ; 
            $datos['$cantidadVentdida'] = $cantidadVentdida ;
            
            $_move_id_1 = $varOdoo->setStockMoves( $dataRPrd[0]['id'] , $_cantidad , $loc_wh_r[0]['id'] , $loc_virt_r[0]['id'] );
           // $_move_id_1 = $varOdoo->devolucionStockMoves( $dataRPrd[0]['id'] , $_cantidad , $loc_wh_r[0]['id'] , $loc_virt_r[0]['id'] );
            
            if (isset($_move_id_1['faultCode'])){
                 http_response_code(500);
                  $datos['error']= 'Error de cominicacion en odoo !!! ';
                  $datos['msg']= $_move_id_1['faultCode'].' - '. $_move_id_1['faultString'];
                  echo json_encode($datos);
                  die(); 
            }
            $OdooTbl->clearDatoInsert();
            $OdooTbl->setNewDatoInsert('sales_count', $cantidadVentdida);
            $respUpdCnt = $varOdoo->Update($OdooTbl->productos,$OdooTbl->getDatosUpdate($dataPrd)); 
                $datos['$respUpdCnt'] = $respUpdCnt;
            $datos['move_id_1'] = $_move_id_1 ;  
            /*ingreso de el producto al documento*/
              /*$dataPrd*/
            
            $link = $conexion->getLink();  
            $queryFinal =  "insert into documentos_listado_productos ( ".
                        "orden, tipoDocumento,idDocumento, idDocumentoFinal , ".
                        "idProducto, nombreProducto, presioVenta,  ".
                        "porcent_iva, presioSinIVa, IVA, cantidadVendida, ".
                        "descuento, valorTotal, usuario,fecha,  ".
                        "hora, cant_real_descontada , id_stock_move_odoo ) values  "
            . "({$_rst_documento['datos'][0]['orden']}, "
            . "{$_rst_documento['datos'][0]['tipoDocumentoFinal']} , "
            . "'{$dataRPrd[0]['barcode']}' ,'{$dataRPrd[0]['categ_id'][0]}', "
            . "{$dataRPrd[0]['id']} , '{$dataRPrd[0]['display_name']}' , "
            . "{$_precio_brt_prd} , {$_iva_porc} , {$_precio_siniva_prd} , {$_precio_iva_prd} ,"
            . "{$_cantidad} , {$_descuento} , ({$_precio_brt_prd} - {$_descuento} ) * {$_cantidad} ,"
            . "{$_rst_documento['datos'][0]['usuario'] } , now() , now() , {$_cantidad} , {$_move_id_1} "
            . " )"; 
            
            
            /* IF ((!isset($_precio_brt_prd) && trim( $_precio_brt_prd ) === '' )||
                        (!isset($_precio_siniva_prd) && trim( $_precio_siniva_prd ) === '' )||
                $_cantidad        (!isset($_precio_prd) && trim( $_precio_prd ) === '' ) ||
                        (!isset($_iva_porc) && trim( $_iva_porc ) === '' )){*/
            $consulta = $link->prepare($queryFinal);
            $datos['queryDocumento']  = $queryFinal;
            if ($consulta->execute()){
                $datos['error']='ok';  
                }else{ $datos['error']="Error al tratar de ingresar los valores a "
                . "la base de datos TABLA (documentos_listado_productos)";  
                echo json_encode($datos);
                 die();
                 
                }
                                
            /*fin del ingreso*/
    
         }
     catch (PDOException $e) {
           http_response_code(500);
        $datos['error']= 'Error de conexión: ' . $e->getMessage();
           
     }
    break; 
    case 'STOCK_MOVE_DEVOLUCION':
          $varOdoo = new Class_php\Odoo(null, null, null,null);
        // $datos['checkAccess'] =  $varOdoo->checkAccess();
         TRY{
               $datos['error']='ok';
            IF ($varOdoo->checkAccess() === false ){
              $datos['error']= 'Error de coneccion a ODOO !!!'; 
                echo json_encode($datos);
                die();  
                }    
            IF (!isset($_cantidad) ||  !is_numeric($_cantidad) || $_cantidad <= 0){
              $datos['error']= 'Error en la cantidad del producto a facturar !!!'; 
                echo json_encode($datos);
                die();  
                } 
                
                $_cantidad = $_cantidad * -1;
                
              IF (!isset($_cod_linea) && trim( $_cod_linea ) === ''  ){
              $datos['error']= 'Error en el codigo del producto a facturar !!!'; 
                echo json_encode($datos);
                die();  
                }
                
            IF (!isset($_cod_prd) && trim( $_cod_prd ) === ''  ){
              $datos['error']= 'Error en el codigo del producto a facturar !!!'; 
                echo json_encode($datos);
                die();  
                }else{
                    $OdooTbl->clearParam();
                    $OdooTbl->setLimit(1);
                    $OdooTbl->setNewParam("id","=", $_cod_prd );
                    $dataPrd = $varOdoo->DataSet($OdooTbl->productos,$OdooTbl->getArrayParam() , $OdooTbl->getArrayLimit());
                }
                /*
           IF ((!isset($_precio_brt_prd) && trim( $_precio_brt_prd ) === '' )|| => "_precio_brt_prd" : producto.lst_price    , 
            (!isset($_precio_siniva_prd) && trim( $_precio_siniva_prd ) === '' )|| =>  "_precio_siniva_prd" : producto.precio_sin_iva    ,
            (!isset($_precio_iva_prd) && trim( $_precio_iva_prd ) === '' ) || =>  "_precio_iva_prd" : producto.valor_del_iva  , 
            (!isset($_iva_porc) && trim( $_iva_porc ) === '' )){ => "_iva_porc" : producto.impuestos.datos[0].amount  ,
                 *                  */
                IF ((!isset($_precio_brt_prd) && trim( $_precio_brt_prd ) === '' )||
                        (!isset($_precio_siniva_prd) && trim( $_precio_siniva_prd ) === '' )||
                        (!isset($_precio_iva_prd) && trim( $_precio_iva_prd ) === '' ) ||
                        (!isset($_descuento) && trim( $_descuento ) === '' ) ||
                        (!isset($_iva_porc) && trim( $_iva_porc ) === '' )){
                $datos['error']= 'Error en el precio del producto a facturar !!!'; 
                  echo json_encode($datos);
                  die();  
                  }
            $conexion =\Class_php\DataBase::getInstance();
            $link = $conexion->getLink();  
            $_llave_usuario = LLAVE_SESSION;
            $where = "  where  estadoCaja = '1'   and  usuarioEstadoCaja ="
                   . " (SELECT usuario FROM  session WHERE `key` = '{$_llave_usuario}' ) ";
               $_rst_asig =$conexion->where('vw_cajas', $where  );  
               
            if ( (sizeof($_rst_asig['datos']) > 0)){ 
                $id_bodega_stock_origen = $_rst_asig['datos'][0]['idBodegaStock']; 
                $id_bodega_stock_destino = $_rst_asig['datos'][0]['idBodegaVitual']; 
            } else{
                   http_response_code(500);
                  $datos['error']= 'Error al obtener las bodegas asignadas !!! ';
                  echo json_encode($datos);
                  die(); 
            }
        /*select * from  documentos where estado = 1 and  usuario  = 1; */
            $where = " where estado = 1 and  usuario  =   "
                   . " (SELECT usuario FROM  session WHERE `key` = '{$_llave_usuario}' ) ";
               $_rst_documento  =$conexion->where('documentos', $where  );  
                $datos['$_rst_documento'] = $_rst_documento ;
            if ( (sizeof($_rst_documento['datos']) > 0)){ 
               
                $id_documentoActual = $_rst_documento['datos'][0]['orden'];  
            } else{ 
                  $datos['error']= 'Error al obtener los datos del documento actual !!! ';
                  echo json_encode($datos);
                  die(); 
            }
          
            
             $datos['id_bodega_stock_origen'] = $id_bodega_stock_origen ; 
             $datos['id_bodega_stock_destino'] = $id_bodega_stock_destino ;
             
             //------------------------------------------------------------
             $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $id_bodega_stock_origen );  
           $loc_wh = $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $loc_wh_r = $varOdoo->DataRead($OdooTbl->bodegas, $loc_wh );
            $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
            $OdooTbl->setNewParam("id", "=" , $id_bodega_stock_destino ); 
           $loc_virt =  $varOdoo->DataSet($OdooTbl->bodegas,$OdooTbl->getArrayParam(), $OdooTbl->getArrayLimit());
           $loc_virt_r =  $varOdoo->DataRead($OdooTbl->bodegas,$loc_virt);
             //------------------------------------------------------- 
                 $OdooTbl->clearParam();    
                $OdooTbl->setLimit(1); 
                 $OdooTbl->setNewParam("product_id","=", $dataPrd ); 
                $OdooTbl->setNewParam("location_id","=", $loc_wh);    
           $OdooTbl->clearParam();
            $OdooTbl->setLimit(1);
    
            $datos['loc_wh'] = $loc_wh ; 
            $datos['loc_virt'] = $loc_virt ; 
             $fields = [ "id","price","price_extra","lst_price","default_code","code","partner_ref","active","barcode", 
            "display_name", "name",  "categ_id"  ];
            $dataRPrd = $varOdoo->DataRead($OdooTbl->productos, $dataPrd , $fields);  
            $_move_id_1 = $varOdoo->devolucionStockMoves( $dataRPrd[0]['id'] , $_cantidad , $loc_wh_r[0]['id'] , $loc_virt_r[0]['id'] ); 
            
            if (isset($_move_id_1['faultCode'])){
                 http_response_code(500);
                  $datos['error']= 'Error de cominicacion en odoo !!! ';
                  $datos['msg']= $_move_id_1['faultCode'].' - '. $_move_id_1['faultString'];
                  echo json_encode($datos);
                  die(); 
            }
            
            $datos['move_id_1'] = $_move_id_1 ;  
            /*ingreso de el producto al documento*/
              /*$dataPrd*/
            
            $link = $conexion->getLink();  
            $queryFinal =  "insert into documentos_listado_productos ( ".
                        "orden, tipoDocumento,idDocumento, idDocumentoFinal , ".
                        "idProducto, nombreProducto, presioVenta,  ".
                        "porcent_iva, presioSinIVa, IVA, cantidadVendida, ".
                        "descuento, valorTotal, usuario,fecha,  ".
                        "hora, cant_real_descontada , id_stock_move_odoo , estado_linea_venta ) values  "
            . "({$_rst_documento['datos'][0]['orden']}, "
            . "{$_rst_documento['datos'][0]['tipoDocumentoFinal']} , "
            . "'{$dataRPrd[0]['barcode']}' ,'{$dataRPrd[0]['categ_id'][0]}', "
            . "{$dataRPrd[0]['id']} , '{$dataRPrd[0]['display_name']}' , "
            . "{$_precio_brt_prd} , {$_iva_porc} , {$_precio_siniva_prd} , {$_precio_iva_prd} ,"
            . "{$_cantidad} , {$_descuento} , ({$_precio_brt_prd} - {$_descuento} ) * {$_cantidad} ,"
            . "{$_rst_documento['datos'][0]['usuario'] } , now() , now() , {$_cantidad} , {$_move_id_1} "
            . ",'E' )"; 
            
            $queryActualiza ="update documentos_listado_productos set estado_linea_venta = 'E' where id = {$_cod_linea}" ; 
            //$_cod_prd
            
            
            /* IF ((!isset($_precio_brt_prd) && trim( $_precio_brt_prd ) === '' )||
                        (!isset($_precio_siniva_prd) && trim( $_precio_siniva_prd ) === '' )||
                $_cantidad        (!isset($_precio_prd) && trim( $_precio_prd ) === '' ) ||
                        (!isset($_iva_porc) && trim( $_iva_porc ) === '' )){*/
            $consulta = $link->prepare($queryFinal);
            $datos['queryDocumento']  = $queryFinal;
            $consulta2 = $link->prepare($queryActualiza);
            $datos['queryDocumentoAct']  = $queryActualiza;
            if ($consulta->execute() && $consulta2->execute()){
                $datos['error']='ok';  
                }else{ $datos['error']="Error al tratar de ingresar los valores a "
                . "la base de datos TABLA (documentos_listado_productos)";  
                echo json_encode($datos);
                 die();
                 
                }
                                
            /*fin del ingreso*/
    
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