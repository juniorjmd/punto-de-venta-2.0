<?php

 
 
namespace Class_php; 
use Class_php\Ripcord\Ripcord ;

 
/**
 * Description of odoo
 *
 * @author junio
 */
class Odoo {
    
    private $pass  = APYKEYODOO;
    private $url = URL_ODOO;
    private $db = DB_ODOO ;
    private $userName = USER_ODOO;
    private $models;
    private $uid ; 
    private $common ; 
    
    function __construct  ($password = null, $url= null , $db= null , $username= null){
         
       if( !is_null($password)){
            $this->pass = $password ;  
        }
         if( !is_null($url)){
            $this->url = $url ;  
        }
         if( !is_null($db)){
            $this->db = $db ;  
        }
         if( !is_null($username)){
            $this->userName = $username ;   
        } 
   
    
     
      $this->common = Ripcord::client("{$this->url}/xmlrpc/2/common");
 
       $this->uid = $this->common->authenticate($this->db, $this->userName, $this->pass, array());

       $this->models =  Ripcord::client("{$this->url}/xmlrpc/2/object");

       

}
        function getDataConnect(){
            return array("usuario"=> $this->userName ,"pass"=> $this->pass ,"db"=> $this->db ,"url"=> $this->url ,"uid"=> $this->uid ,
                
                );
        }
        function getUrlCommon(){
         return "{$this->url}/xmlrpc/2/common" ;;
        }
/*[loc_wh] = models.execute_kw(db, uid, password, "stock.location", "search", [[["complete_name", "=", "WH2/Existencias"]]], {"limit": 1})
[loc_virt] = models.execute_kw(db, uid, password, "stock.location", "search", [[["complete_name", "=", "Partner Locations/Customers"]]], {"limit": 1})
print("Location IDs: %s, %s" % (loc_wh, loc_virt))*/
 
        function getLocWh(){
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass, 'stock.location', 'search',
                     
                     array(array()) 
                     
                     ); 
        }
        function Create( $table , $parametros  ){
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass,  $table, 'create', $parametros  ); 
        }
         function Update( $table , $parametros  ){
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass,  $table, 'write', $parametros  ); 
        }
        function DataSet( $table , $parametros , $limit){
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass,  $table, 'search', $parametros , $limit ); 
        }
        function setStockMoves( $prd_id , $prd_cnt, $loc_wh , $loc_virt , $venta = 'na'){
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass,  "stock.move", 'create', array(
                 array(
                     "product_id" =>   $prd_id ,
                    "location_id"=> $loc_wh ,
                    "location_dest_id"=> $loc_virt ,
                    "name" => "Move API POS JDS - {$venta}" ,
                    "product_uom_qty"=> $prd_cnt,
                    "quantity_done"=> $prd_cnt,
                    "product_uom"=> 1 , 
                    "state"=> "done"  
                 )
                 
             )  ); 
        }
        function devolucionStockMoves( $prd_id , $prd_cnt, $loc_wh , $loc_virt , $venta = 'na'){
             $prd_cnt = abs($prd_cnt );
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass,  "stock.move", 'create', array(
                 array(
                     "product_id" =>   $prd_id ,
                    "location_id"=> $loc_virt ,
                    "location_dest_id"=>  $loc_wh ,
                    "name" => "Move API POS JDS Devolucion - {$venta}" ,
                    "product_uom_qty"=> $prd_cnt,
                    "quantity_done"=> $prd_cnt,
                    "product_uom"=> 1 , 
                    "state"=> "done"  
                 )
                 
             )  ); 
        }
        
         function setSP( $_op_type_id ,$_loc_wh,  $_loc_virt, $_move_ids ,$venta = 'NA' ){
             $movimientos = array();
             foreach ($_move_ids as $key => $value) {
                array_push($movimientos, array(4, intval($value), 0));
             }
             //echo 'movimientos - '; print_r( $movimientos );
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass,  "stock.picking", 'create', [
                 array(
                     "picking_type_id" => $_op_type_id,
                    "location_id" => $_loc_wh,
                    "location_dest_id" => $_loc_virt,
                    "origin" =>  "API POS JDS - {$venta}",
                    "move_ids_without_package" =>    $movimientos  
                 )
                 ]
               ); 
        }
         function confirmSP( $id ){
              return $this->models->execute_kw($this->db, $this->uid, $this->pass,  "stock.picking", "action_confirm", [$id]) ;
        }
          function cancelSP( $id ){
             return $this->models->execute_kw($this->db, $this->uid, $this->pass,  "stock.picking", "action_cancel", [$id]) ;
        }
         function comprobarSP( $id ){
            return  $this->models->execute_kw($this->db, $this->uid, $this->pass,  "stock.picking", "action_assign", [$id]) ;
        }
        function cerrarSP( $id ){
           return   $this->models->execute_kw($this->db, $this->uid, $this->pass,  "stock.picking", "button_validate", [$id]) ;
        }
        
        //stock_quants = models.execute_kw(db, uid, password, "stock.quant", "search", [[["product_id", "=", product_id], ['location_id', '=', loc_wh]]], {"limit": 1})
        function DataRead($table,$dataSet , $fiels = array()){ 
            if (count($fiels)> 0){
          $_response = $this->models->execute_kw($this->db, $this->uid, $this->pass, $table , 'read', array($dataSet), 
                  array('fields' => $fiels ));
           
            }else{
            $_response = $this->models->execute_kw($this->db, $this->uid, $this->pass, $table , 'read', array($dataSet)
            ); }
            
            if(is_array($_response)){return $_response;
            }else{return array();}
            
        }
        
        function getLocVirt(){
             return  $this->models->execute_kw($this->db, $this->uid, $this->pass, 'stock.location', 'search',
                  array('read'), array('raise_exception' => false)); 
        } 
        function getCommonVersion(){
            return $this->common->version();
        }
        function checkAccess(){
            return  $this->models->execute_kw($this->db, $this->uid, $this->pass, 'res.partner', 'check_access_rights',
           array('read'), array('raise_exception' => false)); 
        } 
}
