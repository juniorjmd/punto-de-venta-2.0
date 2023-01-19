<?php

namespace  Class_php ;
 

class OdooDB  {
    
    public $bodegas ;
    public $tipDocumentos ;
    public $ventas ;
    public $ventas_linea;
    public $tipCompania ;
    public $productos ;
    public $prdTemplate ;
    public $marcas ;
    public $sinParametros ;
    public $parametros;
    public $DatosInsert;
    public $existencias;
    public $tipo_s_p;
    public $cliente ;
    public $categorias ;
    public $impuestos ;
    public $identificacion_tipo ;
    public $categorias_prd;
    public $empleados;
    // stock.picking.type



   public function __construct(){
       $this->ventas = 'sale.order';
       $this->ventas_linea = 'sale.order.line';
       $this->impuestos = 'account.tax';
       $this->tipDocumentos = 'l10n_latam.identification.type';
       $this->cliente = 'res.partner';
       $this->marcas = 'x_marcas';
       $this->bodegas = 'stock.location';
       $this->existencias = 'stock.quant';
       $this->productos  = 'product.product';
       $this->prdTemplate  = 'product.template';
       $this->tipo_s_p  = 'stock.picking.type'; 
       $this->tipCompania = 'res.company';
       $this->categorias = 'res.partner.category';
       $this->categorias_prd = 'product.category';
       $this->titulo = 'res.partner.title';
       $this->empleados = 'hr.employee';
       $this->country = 'res.country';
       $this->city = 'l10n_co_cei.city';
       $this->State = 'res.country.state';
       

       $this->sinParametros = array(array());
       $this->limit = array();
       $this->DatosInsert = [];
       $this->identificacion_tipo ='l10n_latam.identification.type';
       
   }
   public function setNewDatoInsert($colum  , $value){
       
       if(is_array($value)){
           if(sizeof($value)>0){
               $dato = $value[0] ; 
           }else{
               $dato = false ; 
           }
           
       }else{
           $dato = $value ; 
       }
         $this->DatosInsert[ $colum ]= $dato ;
   }
    public function clearDatoInsert(){
      $this->DatosInsert = array();
   }
    public function getDatosUpdate($id_update) {
       return array( $id_update ,$this->DatosInsert);
   } 
   public function getDatosInsert() {
       return array($this->DatosInsert);
   }
   public function setNewParam($colum , $relacion , $value){ 
    // echo"<br>ingresa parametros {$colum} , {$relacion} , {$value} . ";   
       if(is_bool($value)){
           switch ($value){
               case true:
                   array_push( $this->parametros , array($colum , $relacion , true));
                   break;
               case false:
                  // echo'es false';
                   $a[0] =$colum ;
                   $a[1] =$relacion ;
                   $a[2] = false ;
                  // print_r($a);
                   array_push( $this->parametros , $a);
                   break;
           }
       }
      else{array_push( $this->parametros , array($colum , $relacion , $value));}
   }
   public function setRelacionOR(){         
     /*  if (isset($this->parametros[0]) && is_array($this->parametros[0])){
            array_push( $this->parametros , $this->parametros[0]);}*/
       array_push( $this->parametros , '|' );
    //   $this->parametros[0] = '|';
   }
    public function setRelacionAND(){         
     /*  if (isset($this->parametros[0]) && is_array($this->parametros[0])){
            array_push( $this->parametros , $this->parametros[0]);}*/
       array_push( $this->parametros , '&' );
    //   $this->parametros[0] = '|';
   }
   public function setLimit($tamanio){
       if (is_numeric($tamanio)){
          // array_push($this->limit , array('limit'=>$tamanio ));
           $this->limit['limit'] = $tamanio ;
       }
   }
   public function clearArrayLimit(){
       
          $this->limit = array();
       
   }
   public function setOffset($tamanio){
       if (is_numeric($tamanio)){
          $this->limit['offset'] = $tamanio ;
       }
   }
   public function getArrayLimit() {
       return $this->limit;
   }
    public function getArrayParam(){
       return array($this->parametros);
   }

   public function clearParam(){ 
      // echo 'borra los parametros';
        $this->parametros = array();
        $this->clearArrayLimit();
   }
 

}