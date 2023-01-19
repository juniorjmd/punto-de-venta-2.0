<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Class_php;


use PDO;

/**
 * Description of ¨Perfiles
 *
 * @author juniorjmd
 */
class Perfiles {
    private   $Perf_ID ;
    private $Perf_Nombre ;
    private $estado ;
    private $recursos = array();
    static $TABLA = 'perfiles';

    public function __construct($Perf_ID = null,      $Perf_Nombre = null,      $estado = null) {
        $this->Perf_ID =  is_null($Perf_ID) ? "" : $Perf_ID; 
        $this->Perf_Nombre =  is_null($Perf_Nombre) ? "" : $Perf_Nombre; 
        $this->estado =  is_null($estado) ? "" : $estado;
    }
     public function guardar(){
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($this->Perf_ID) /*Modifica*/ {
           
         $consulta = $link->prepare("call `sp_crear_editar_perfil`(:Perf_ID, :Perf_Nombre, :estado, :user  )");
         $consulta->bindParam(':Perf_ID', $this->Perf_ID);
         $consulta->bindParam(':Perf_Nombre', $this->Perf_Nombre);
         $consulta->bindParam(':estado', $this->estado); 
         $consulta->bindParam(':user', $_SESSION["usuario_logeado"] );
         $consulta->execute(); 
      }else /*Inserta*/ {
          $consulta = $link->prepare("call `sp_crear_editar_perfil`('',:Perf_Nombre,    :estado,  :user  )");
       // $consulta->bindParam(':Perf_ID', $this->Perf_ID);
         $consulta->bindParam(':Perf_Nombre', $this->Perf_Nombre);
         $consulta->bindParam(':estado', $this->estado); 
         $consulta->bindParam(':user', $_SESSION["usuario_logeado"] );
         $consulta->execute();
         $this->Perf_ID = $conexion->getlastInsertId();
      }
      
           
      $conexion = null;
  
      $array =  $consulta->fetchAll(); 
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
                $result ='not ok';  
           break;           
       }
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
   }   
   
    public function guardar_recurso(array $id_recurso){
        $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      //   $link->beginTransaction();
      try { 
         $_result = $conexion->eliminarDato('perfil_recurso',  $this->Perf_ID,'id_perfil');
         switch ($_result[0]['result']){
           case '100':
         foreach ($id_recurso as $value) {
        // echo '<br>'."call `sp_insert_recurso_perfil`('$this->Perf_ID', '$value', '{$_SESSION["usuario_logeado"]}' )" ;
         $consulta = $link->prepare("call `sp_insert_recurso_perfil`(:Perf_ID, :id_recurso,  :user  )");
         $consulta->bindParam(':Perf_ID', $this->Perf_ID);
         $consulta->bindParam(':id_recurso', $value); 
         $consulta->bindParam(':user', $_SESSION["usuario_logeado"] );
         $consulta->execute(); 
            $array =  $consulta->fetchAll(); 
            switch ($array[0]['result']){
                case '100':
                case '101':              
                     $result ='ok';  
                break;
                case '-1':
                     $result ='not ok';  
                     break 1;   
                break;           
              }
              
            }
            if ($result == 'ok'){           
           //      $link->commit();
            }else{ 
           //     $link->rollBack();
            }
              $result ='ok';  
           break;
           case '-1':
                $result ='_table';  
           break;   
           case '-2':
                $result ='_dato';  
           break;   
           case '-3':
                $result ='_COLUMNA';  
           break;   
           default :
                $result = $_result[0]['result'];
           break;
       } 
       
       
       $conexion = null;
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
   }
   public function listarRecursos(){
        try {            
            $conexion =DataBase::getInstance();
            $link = $conexion->getLink();
            $consulta = $link->prepare("SELECT * FROM  vw_perfil_recurso  where id_perfil =  $this->Perf_ID  ");
            $result['query'] = "SELECT * FROM  vw_perfil_recurso  where id_perfil =  $this->Perf_ID   ";
            $consulta->execute();
            $registros = $consulta->fetchAll();
            $result['error']='';
            $result['datos'] = $registros;
            $result['filas'] = sizeof( $registros) ;
            return $result ;
        } catch (PDOException $e) {
               $result['error']='Error de conexión: ' . $e->getMessage();
               $result['datos'] =null;
               return $result ;
               exit;
        }
    }
   public function guardarRelacion($id_usuario,$idRelacion = null){
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($idRelacion) /*Modifica*/ { 
         $consulta = $link->prepare("call `sp_crear_relacion_user_perfil`(:idRelacion, :user_id, :perfil_id, :user  )");
         $consulta->bindParam(':idRelacion', $idRelacion);
         $consulta->bindParam(':user_id', $id_usuario);
         $consulta->bindParam(':perfil_id', $this->Perf_ID); 
         $consulta->bindParam(':user', $_SESSION["usuario_logeado"] );
         $consulta->execute(); 
      }else /*Inserta*/ {
          $consulta = $link->prepare("call `sp_crear_relacion_user_perfil`('', :user_id, :perfil_id, :user )");
       // $consulta->bindParam(':Perf_ID', $this->Perf_ID);
         $consulta->bindParam(':user_id', $id_usuario);
         $consulta->bindParam(':perfil_id', $this->Perf_ID); 
         $consulta->bindParam(':user', $_SESSION["usuario_logeado"] );
         $consulta->execute();
         $this->Perf_ID = $conexion->getlastInsertId();
      }
      
           
      $conexion = null;
  
      $array =  $consulta->fetchAll(); 
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
                $result ='not ok';  
           break;           
       }
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
   }
     public function recuperarTodos($where = NULL){
        try {            
            $conexion =DataBase::getInstance();
            $link = $conexion->getLink();
            $consulta = $link->prepare('SELECT * FROM ' . self::$TABLA . ' '.$where.'    ');
            $result['query'] = 'SELECT * FROM  ' . self::$TABLA . ' '.$where.'    ';
            $consulta->execute();
            $registros = $consulta->fetchAll();
            $result['error']='';
            $result['datos'] = $registros;
            $result['filas'] = sizeof( $registros) ;
            return $result ;
        } catch (PDOException $e) {
               $result['error']='Error de conexión: ' . $e->getMessage();
               $result['datos'] =null;
               return $result ;
               exit;
        }
    }
     public function recuperarRelacionesUsuarios($where = NULL){
        try {            
            $conexion =DataBase::getInstance();
            $link = $conexion->getLink();
            $consulta = $link->prepare('SELECT * FROM vw_relacion_user_perfiles '.$where.'    ');
            $result['query'] = 'SELECT * FROM  vw_relacion_user_perfiles '.$where.'    ';
            $consulta->execute();
            $registros = $consulta->fetchAll();
            $result['error']='';
            $result['datos'] = $registros;
            $result['filas'] = sizeof( $registros) ;
            return $result ;
        } catch (PDOException $e) {
               $result['error']='Error de conexión: ' . $e->getMessage();
               $result['datos'] =null;
               return $result ;
               exit;
        }
    }
    public function eliminarPerfil()
    { 
    TRY {
        
      $conexion =DataBase::getInstance();  
      $usuariosPerfiil = $conexion->where('relacion_user_perfiles',' where perfil_id = '.$this->Perf_ID);
           
       if (sizeof($usuariosPerfiil) > 0 ){
         $result = 'No se puede eliminar el perfil ya que esta asignado a  uno o mas usuarios';  
       }
       else{
           $_result = $conexion->eliminarDato(self::$TABLA,  $this->Perf_ID,'Perf_ID');
      switch ($_result[0]['result']){
           case '100':
                $result ='ok';  
           break;
           case '-1':
                $result ='_table';  
           break;   
           case '-2':
                $result ='_dato';  
           break;   
           case '-3':
                $result ='_COLUMNA';  
           break;   
           default :
                $result = $_result[0]['result'];
           break;
       } 
       }
      
      } catch (PDOException $e) {
        return 'Error de conexión: ' . $e->getMessage();
        exit;
     } 
     return $result;
    }
    
}
