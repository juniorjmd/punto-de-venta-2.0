<?php

namespace  Class_php ;

use PDO;

class DataBase  {
    /*define('DB_TYPE', 'mysql');*/
   private $servidor=   DB_HOST;
   private $usuario=    DB_USER;
   private $password=   DB_PASS;
   private $base_datos= DB_NAME_INICIO;
   private $DB_TYPE = DB_TYPE;
   protected $link;
   private $stmt;
   private $array;
   private $NumDatos;

   static $_instance;

   /*La función construct es privada para evitar que el objeto pueda ser creado mediante new*/
   private function __construct($servidor=null, $usuario= null,$password=null ,$base_datos = null ){
     /*  if (!is_null($servidor)){ $this->servidor=$servidor;}
       if (!is_null($usuario)){$this->usuario=$usuario;}
       if (!is_null($password)){$this->password=$password;}
       if (!is_null($base_datos)){ $this->base_datos = $base_datos;}*/
       
       if (!is_null($servidor)){ $this->servidor=$servidor;}else{
            if ( defined('N_HOST') && trim(N_HOST) != ''  ){
                $this->servidor= N_HOST;
              
            } 
       }
       if (!is_null($usuario)){$this->usuario=$usuario;}else{
            if ( defined('N_USUARIODB') && trim(N_USUARIODB) != ''  ){
                $this->usuario = N_USUARIODB;
            } 
       }
       if (!is_null($password)){$this->password=$password;}else{
            if ( defined('N_CLAVEDB') && trim(N_CLAVEDB) != ''  ){
               $this->password = N_CLAVEDB;
                 
            } 
       }
       if (!is_null($base_datos)){ $this->base_datos = $base_datos;}else{
            if ( defined('N_DATABASE')  && trim(N_DATABASE) != ''  ){
                  $this->base_datos =  N_DATABASE;
            } 
       }
       
      //echo  $this->servidor;
      //echo $this->base_datos;
       
      if ($this->conectar())
      { return true;}else{return false;}
   }

   /*Evitamos el clonaje del objeto. Patrón Singleton*/
   private function __clone(){ }
 public function where_union ($tabla,$where = null,$tablau,$whereu = null,$columnas = null,$limit = null){
      $columnas  = is_null($columnas)? "*" : $columnas;
      $limit  = is_null($limit)? "" : $limit;
       $query = "select $columnas from $tabla $where $limit union "
               . "select $columnas from $tablau $whereu $limit"; 
       $consulta = $this->link->prepare($query);
         $consulta->execute(); 
         $array['datos'] =  $consulta->fetchAll();
         $array['query'] = $query;
         return $array;
 }
    public  function where($tabla,$where = null,$columnas = null,$limit = null  , $order = array() ){ 
      $columnas  = is_null($columnas)? "*" : $columnas;
      $limit  = is_null($limit)? "" : $limit;
      $orderby = '';
      if (sizeof($order) > 0 ){
          $orderby = 'ORDER BY';
          $coma = ' ';
          foreach ($order as $key => $value) {
              $orderby.=$coma . $value[0] . ' '. $value[1] ; 
          }
      }
       $query = "select $columnas from $tabla $where $orderby  $limit"; 
       
       $consulta = $this->link->prepare($query);
         $consulta->execute(); 
         $array['datos'] =  $consulta->fetchAll();
         $array['query'] = $query;
         return $array;
   }
   public  function procedimiento($procedimiento,$parametros = array()){  
       $procedimiento= trim($procedimiento);
       $query = "CALL `{$procedimiento}`("; 
       $coma =""; 
       foreach ($parametros as $key => $value) {
            if(is_numeric($value)){ 
                 // $_TOTAL_PAGO = str_replace('.', ',',$_TOTAL_PAGO ); 
                $query .= "$coma $value "; 
                
            }else{  $query .= "$coma '$value' "; } 
            $coma =","; 
       }
       $query .= ")";
       //echo $query;
       $consulta = $this->link->prepare($query);
        
           $array['query'] = $query; 
       if ($consulta->execute()){
           $array['datos'] =  $consulta->fetchAll();
           $array['_result'] = 'ok';
           
       }
           else{ $array['_result'] = 'error';} 
       
       return $array;
   }
   
    public  function funciones($procedimiento,$parametros = array()){  
       $procedimiento= trim($procedimiento);
       $query = "select `{$procedimiento}`("; 
       $coma =""; 
       foreach ($parametros as $key => $value) {
            if(is_numeric($value)){ 
                 // $_TOTAL_PAGO = str_replace('.', ',',$_TOTAL_PAGO ); 
                $query .= "$coma $value "; 
                
            }else{  $query .= "$coma '$value' "; } 
            $coma =","; 
       }
       $query .= ") as result ";
       //echo $query;
       $consulta = $this->link->prepare($query);
        
           $array['query'] = $query; 
       if ($consulta->execute()){
           $array['datos'] =  $consulta->fetchAll();
           $array['_result'] = 'ok';
           
       }
           else{ $array['_result'] = 'error';} 
       
       return $array;
   }
   
   
   public  function truncateTable($tabla){  
       $consulta = $this->link->prepare("call `truncate_table`( :TABLA   )");
      $consulta->bindParam(':TABLA', $tabla); 
         $consulta->execute(); 
         $array =  $consulta->fetchAll();
         return $array;
   }

   /*Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos*/
   public static function getInstance($servidor=null, $usuario= null,$password=null ,$base_datos = null){
      if (!(self::$_instance instanceof self)){ 
         self::$_instance=new self();
      }
      return self::$_instance;
   }
    public function getLink(){
        return $this->link;
    }
    public function getlastInsertId(){
        return $this->link->lastInsertId() ;
    }
    public function eliminarDato($tabla ,$dato , $columna){
        $consulta = $this->link->prepare("call `sp_eliminar_elemento`(:USER , :TABLA , :DATO , :COLUMNA )");
        $usuario = $_SESSION["usuario_logeado"];
        if ($usuario == ''){$usuario = '1';}
        //echo "call `sp_eliminar_elemento`('{$usuario}' ,'$tabla', '$dato' , '$columna' )";
         $consulta->bindParam(':USER', $_SESSION["usuario_logeado"] );
         $consulta->bindParam(':TABLA', $tabla);
         $consulta->bindParam(':DATO', $dato);
         $consulta->bindParam(':COLUMNA', $columna);
         $consulta->execute(); 
         $array =  $consulta->fetchAll();
         return $array;
    }
    /*Realiza la conexión a la base de datos.*/
   private function conectar(){
        $CAD =$this->DB_TYPE.':host='.$this->servidor.';dbname='.$this->base_datos;
      $this->link= new PDO( $CAD, $this->usuario ,$this->password  );
      $this->link->query("SET NAMES 'utf8'");
   } 
}