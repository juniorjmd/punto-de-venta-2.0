<?php
require_once '../../../../../php/helpers.php';  
require_once'../../../../../php/fpdf181/fpdf.php'; 
/*
index de creacion en odoo no agregar actions que no sean solo de insercion o actualizacion en odoo
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