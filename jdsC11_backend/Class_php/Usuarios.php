<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
namespace  Class_php ;
 
 

use Class_php\MenuSistema ;
/**
 * Description of Usuarios
 * @author jdominguez
 */
class Usuarios {
    //put your code here
    private  $ID;
    private  $Login;
    private  $Nombre1;
    private  $Nombre2;
    private  $Apellido1;
    private  $Apellido2;
    private  $nombreCompleto;
    private  $estado;
    private  $pass;
    private  $change;
    private $perfil;    
    private $id_perfil;
    private $mail;
    private $codRemision;
    protected $permisos = array();
    private  $array = array();
    private  $arrayClientes =array();
    private  $arrayClientesConNombres =array();
    
    private  $arrayOrganizacion =array();
    private  $arrayOrganizacionConNombres =array();
    
    private  $arrayClientesCentrosLogisticos =array();
    private  $arrayMenusPermisos = array();
    private  $arrayAreasDeControl = array();
    private  $arrayAreasDeControlConNombres = array();
    const TABLA   = 'usuarios';
    public function __construct($ID = null,$Login = null,$Nombre1 = null,$Nombre2 = '',$Apellido1 = null,$Apellido2 = '',$nombreCompleto = null,$estado = 'A',$pass = '' ,$mail = null, $change = 0 ,$codRemision = null  ) {
        $this->ID = $ID;
        $this->Login = $Login;
        $this->Nombre1 = $Nombre1;
        $this->Nombre2 = $Nombre2;
        $this->Apellido1 = $Apellido1;
        $this->Apellido2 = $Apellido2;
        $this->nombreCompleto = $nombreCompleto;
        $this->estado = $estado;
        $this->pass = $pass;  
        $this->change = $change;
        $this->mail = $mail;
        $this->codRemision = $codRemision;
        $this->setPermisosUsuario();
        $this->setClientesUsuario();
        $this->set_AreasVenta_Centros_log_Usuario();
        $this->setAreasControlUsuario();
        $this->setOrganizacionUsuario();
    }
    public function getCodRemision(){
        return $this->codRemision;
    } 
    public function getEstado(){
        return $this->estado;
    }
    public function getOrganizacionConNombres(){
        return $this->arrayOrganizacionConNombres;
    }
      public function getOrganizacion(){
        return $this->arrayOrganizacion;
    }
     public function getClientesConNombres(){
        return $this->arrayClientesConNombres;
    }
    public function createSelectClientes($textInicial = null , $valueInicial = null,$ID = NULL){
        $textInicial = is_null($textInicial)? "--TODOS LOS CLIENTES--" : $textInicial;
        $valueInicial = is_null($valueInicial)? "%" : $valueInicial;
        $ID = is_null($ID)? "ClientesAsignadosUsuario" : $ID;
        if ($ID == ''){$ID ='ClientesAsignadosUsuario'; }
        $this->setClientesUsuario();
        $arreglo = $this->arrayClientesConNombres; 
         $html = "<select class='form-control' style='height: 25px;font-size: 9px;' id='$ID'><option value='$valueInicial'>$textInicial</option>";
        foreach ($arreglo as $key => $value) {
            $html .= "<option value='{$value['id_cliente_SAP']}'>{$value['nombre_cliente_SAP']}</option>";
        }
        $html .="</select>"; 
        return $html;
    }
      public function createSelectCliente(){
        $this->setClientesUsuario();
        $arreglo = $this->arrayClientesConNombres; 
         $html = "<select class='form-control' style='height: 25px;font-size: 9px;' id='ClientesAsignadosUsuario'><option value=''>--SELECCIONE UN CLIENTE--</option>";
        foreach ($arreglo as $key => $value) {
            $html .= "<option value='{$value['id_cliente_SAP']}'>{$value['nombre_cliente_SAP']}</option>";
        }
        $html .="</select>"; 
        return $html;
    }
      public function createSelectAreaDeControl(){
        $this->setAreasControlUsuario();
        $arreglo = $this->arrayAreasDeControlConNombres; 
         $html = "<select class='form-control' style='height: 25px;font-size: 9px;' id='AreaDeControlAsignadosUsuario'><option value=''>--SELECCIONE UN AREA DE CONTROL--</option>";
        foreach ($arreglo as $key => $value) {
            $html .= "<option value='{$value['codAreasControl']}'>{$value['nomAreasControl']}</option>";
        }
        $html .="</select>"; 
        return $html;
    }
    /*$this->arrayAreasDeControl = array(); 
          $this->arrayAreasDeControlConNombres = array(); 
     * ,'WERKS'=> $value['WERKS']
                      ,'NAME1'=> $value['NAME1']*/
    
      public function createSelectCentosLog(){
        $this->setClientesUsuario();
        $arreglo = $this->arrayClientesCentrosLogisticos; 
           
         $html = "<select class='form-control' style='height: 25px;font-size: 9px;' id='CentosLogAsignadosUsuario'><option value='%'>--TODOS LOS CENTROS--</option>";
         
             $WERKSArrays = array();
        foreach ($arreglo as $key => $value) {
            if( !in_array($value['WERKS'],$WERKSArrays )){
            $html .= "<option value='{$value['WERKS']}'>{$value['NAME1']}</option>";
        array_push ($WERKSArrays,$value['WERKS']);}
        }
        $html .="</select>"; 
        return $html;
    }
    
    public function getArrayCentosLog(){
        $this->setClientesUsuario();
        $arreglo = $this->arrayClientesCentrosLogisticos; 
       $newArray = array();
       $i = 0;
        foreach ($arreglo as $key => $value) {
            $newArray[$i]=$value['WERKS'];
            $i++;
           
        }        
        return $newArray;
    }
    
    public function getUserLoguin(){
        return $this->Login  ;
    }
    public function getUserArray()
    {
        $this->array['ID']= $this->ID  ;
        $this->array['Login']=$this->Login  ;
        $this->array['Nombre1']=$this->Nombre1 ;
        $this->array['Nombre2']=$this->Nombre2  ;
        $this->array['Apellido1']=$this->Apellido1  ;
        $this->array['Apellido2']=$this->Apellido2  ;
        $this->array['nombreCompleto']=$this->nombreCompleto  ;
        $this->array['estado']=$this->estado  ;
        $this->array['pass']=$this->pass  ; 
        $this->array['change']=$this->change  ; 
        $this->array['mail']=$this->mail  ; 
        $this->array['codRemision']=$this->codRemision  ; 
        
        return $this->array;        
    }
    public function getId()
    {return $this->ID;}
        public function getNombre1()
    {return $this->Nombre1;}
        public function getNombre2()
    {return $this->Nombre2;}
        public function getLasName()
    {return $this->Apellido1;}
      public function getLasName2()
    {return $this->Apellido2;}
      public function getFullname()
        {return $this->nombreCompleto;}
    public function getArrayAreasDeControlConNombres()
        {return $this->arrayAreasDeControlConNombres;}
    public function getArrayAreasDeControl()
    {return $this->arrayAreasDeControl;}
    private function getPerfilUsuario(){
         $conexion =DataBase::getInstance();  
        $usuariosPerfiil = $conexion->where('vw_relacion_user_perfiles',' where user_id = '.$this->ID);
           
       if (sizeof($usuariosPerfiil) > 0 ){
        foreach ($usuariosPerfiil as $key => $value) {
            $this->perfil = $value['Perf_Nombre'] ;
            $this->id_perfil= $value['perfil_id'] ;
        }
      }
    }
    //vw_relacion_usuario_centros_logisticos
    private function setAreasControlUsuario(){
        //$arrayAreasDeControl = array();
   // private  $arrayAreasDeControlConNombres
         $conexion =DataBase::getInstance();  
        $usuariosPerfiil = $conexion->where('relacion_usuario_areas_de_control',' where codUsuario = '.$this->ID);
         $this->arrayAreasDeControl = array(); 
          $this->arrayAreasDeControlConNombres = array(); 
         $i = 0;
       if (sizeof($usuariosPerfiil) > 0 ){
        foreach ($usuariosPerfiil as $key => $value) {            
            //$this->arrayAreasDeControl[$i] = $value['id_cliente_SAP'] ;
              $this->arrayAreasDeControlConNombres[$i] = array('codAreasControl'=>$value['codAreasControl'],'nomAreasControl'=>$value['nomAreasControl']);
            $i++;
        }
      }
    }
private function setClientesUsuario(){
         $conexion =DataBase::getInstance();  
        $usuariosPerfiil = $conexion->where('vw_relacion_usuario_clientes',' where id_usuario = '.$this->ID);
         $this->arrayClientes = array(); 
          $this->arrayClientesConNombres = array(); 
         $i = 0;
       if (sizeof($usuariosPerfiil) > 0 ){
        foreach ($usuariosPerfiil as $key => $value) {
            
            $this->arrayClientes[$i] = $value['id_cliente_SAP'] ;
              $this->arrayClientesConNombres[$i] = array('id_cliente_SAP'=>$value['id_cliente_SAP'],'nombre_cliente_SAP'=>$value['nombre_cliente_SAP'],'estado'=>$value['nombre_cliente_SAP'],'nameEstado'=> $value['nameEstado']);
            $i++;
        }
      }
    }
 private function setOrganizacionUsuario(){
         $conexion =DataBase::getInstance();   
           
       $link = $conexion->getLink();       
          $consulta = $link->prepare("SELECT distinct   relacion_usuario_area_venta.VKORG , ifnull( VTEXTVKORG,relacion_usuario_area_venta.VKORG)AS VTEXTVKORG FROM clientes.relacion_usuario_area_venta " 
                                   . "left join clientes.vw_SAP_rfc_Areasdeventa on relacion_usuario_area_venta.VKORG = vw_SAP_rfc_Areasdeventa.VKORG "
                                   . "WHERE cod_usuario = '{$this->ID}' ");
        $query = "SELECT distinct   relacion_usuario_area_venta.VKORG ,ifnull( VTEXTVKORG,relacion_usuario_area_venta.VKORG)AS VTEXTVKORG   FROM clientes.relacion_usuario_area_venta " 
                                   . "left join clientes.vw_SAP_rfc_Areasdeventa on relacion_usuario_area_venta.VKORG = vw_SAP_rfc_Areasdeventa.VKORG "
                                   . "WHERE cod_usuario = '{$this->ID}' "; 
       $consulta->execute();
       $registros = $consulta->fetchAll();  
         $this->arrayOrganizacion = array(); 
          $this->arrayOrganizacionConNombres = array(); 
         $i = 0;
       if (sizeof($registros) > 0 ){
        foreach ($registros as $key => $value) {            
            $this->arrayOrganizacion[$i] = $value['VKORG'] ;
              $this->arrayOrganizacionConNombres[$i] = array('VKORG'=>$value['VKORG'],'VTEXTVKORG'=>$value['VTEXTVKORG']);
            $i++;
        }
      }
    }
       
    
    
    
    private function set_AreasVenta_Centros_log_Usuario(){
         $conexion =DataBase::getInstance();  
        $usuariosPerfiil = $conexion->where('vw_relacion_usuario_centros_logisticos',' where cod_usuario = '.$this->ID);
        $i = 0;
        $this->arrayClientesCentrosLogisticos = array();
        $arrayVerificacion = array();
       if (sizeof($usuariosPerfiil) > 0 ){
        foreach ($usuariosPerfiil as $key => $value) {    
            if (!in_array($value['VKORG'].$value['VTWEG'].$value['SPART'], $arrayVerificacion)){ 
                  $arrayVerificacion[$i] = $value['VKORG'].$value['VTWEG'].$value['SPART'] ;
              $this->arrayClientesCentrosLogisticos[$i] = array('cod_usuario'=>$value['cod_usuario'],
                  'VKORG'=>$value['VKORG'],'VTWEG'=>$value['VTWEG'],'SPART'=> $value['SPART']
                      ,'WERKS'=> $value['WERKS']
                      ,'NAME1'=> $value['NAME1']);
            $i++;
        }
        }
      }
    }
    public function getClientesCentrosLogisticos() {
        $this->set_AreasVenta_Centros_log_Usuario();
       return $this->arrayClientesCentrosLogisticos;
    } 
    public function getClientesUsuario(){
        return $this->arrayClientes;
    }
     public function getClientesUsuarioConNOmbre(){
        return $this->arrayClientesConNombres;
    }
    public function buscarClienteUsuario($idUsuarioSAP){
        $arrayRetorno = array(); 
        foreach ($this->arrayClientesConNombres as $key => $value) {  
            if($value['id_cliente_SAP'] == $idUsuarioSAP){
                $arrayRetorno = $value;
            }
        }  
        return $arrayRetorno;
    }
    public function setPermisosUsuario(){
       $this->getPerfilUsuario();
        $this->permisos = array();
        $perfil =  new Perfiles($this->id_perfil);
        $arrayRecursos  = $perfil->listarRecursos();
        
         foreach ($arrayRecursos['datos'] as $key => $value) {
         array_push ($this->permisos ,$value['id_recurso_sistema'] );  
         $this->arrayMenusPermisos[$value['nombreMenu']][$value['id_recurso_sistema']]=true;
       
        }
           
    }
    public function getArrayPermisos($nomModulo){
        if ($nomModulo)
            return $this->arrayMenusPermisos[$nomModulo];
        return $this->arrayMenusPermisos;
        
    }
    public function getDescripcionMenuUsuario($nombreMenuPadre){
        //aqui se debe mezclar los permisos con el menu que se quiere mostrar
        //por ahora se muestra todo el menu que pertenecera al modulo 
        $idMenuPadre =  MenuSistema::get_id_padre_by_name($nombreMenuPadre );
       // echo'<br>'. $idMenuPadre; 
        
        return MenuSistema::Crea_descripcion_menu($idMenuPadre,$this->permisos );
    }
    public function getMenuUsuario($nombreMenuPadre){
        //aqui se debe mezclar los permisos con el menu que se quiere mostrar
        //por ahora se muestra todo el menu que pertenecera al modulo 
        $idMenuPadre =  MenuSistema::get_id_padre_by_name($nombreMenuPadre );
       // echo'<br>'. $idMenuPadre; 
        
        return MenuSistema::Crea_menu($idMenuPadre,$this->permisos );
    }
    
      
      public function guardar($changue = null){  
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($this->ID && $this->ID  != '') /*Modifica*/ {
          if ($changue){
             $consulta = $link->prepare("call  sp_Crear_editar_usuario(:ID, :LOGIN, :NOMBRE1, :NOMBRE2, :LNAME1, :LNAME2, :ESTADO, :USERREG, :PASS, 'P' , :MAIL)");
             if ($_SESSION["usuario_logeado"] == ''){
                $_SESSION["usuario_logeado"] = $this->Login;
             }
             
             
          }else{
          //call  sp_Crear_editar_usuario('', '', '', '', '', '', '', '', '', '');
         $consulta = $link->prepare("call  sp_Crear_editar_usuario(:ID, :LOGIN, :NOMBRE1, :NOMBRE2, :LNAME1, :LNAME2, :ESTADO, :USERREG, :PASS, 'U' , :MAIL)");
        }
         $consulta->bindParam(':ID', $this->ID);
         $consulta->bindParam(':LOGIN', $this->Login);
         $consulta->bindParam(':NOMBRE1', $this->Nombre1);
         $consulta->bindParam(':NOMBRE2', $this->Nombre2);
         $consulta->bindParam(':LNAME1', $this->Apellido1);
         $consulta->bindParam(':LNAME2', $this->Apellido2);
         $consulta->bindParam(':ESTADO', $this->estado);
         $consulta->bindParam(':USERREG', $_SESSION["usuario_logeado"] );
         $consulta->bindParam(':PASS', $this->pass); 
          $consulta->bindParam(':MAIL', $this->mail); 
         $consulta->execute();
         
       // echo "call  sp_Crear_editar_usuario('$this->ID', '$this->Login' ,  '$this->Nombre1' ,  '$this->Nombre2', '$this->Apellido1',  '$this->Apellido2' ,  '$this->estado' ,  '{$_SESSION["usuario_logeado"]}' ,  '{$this->pass}','P', '$this->mail' )<br>";
  
         
      }else /*Inserta*/ { 
          $consulta = $link->prepare("call  sp_Crear_editar_usuario('',  :LOGIN ,  :NOMBRE1 ,  :NOMBRE2 ,  :LNAME1 ,  :LNAME2 ,  :ESTADO ,  :USERREG ,  :PASS,'I', :MAIL )");
      //   $consulta->bindParam(':ID', $this->ID);
         $consulta->bindParam(':LOGIN', $this->Login);
         $consulta->bindParam(':NOMBRE1', $this->Nombre1);
         $consulta->bindParam(':NOMBRE2', $this->Nombre2);
         $consulta->bindParam(':LNAME1', $this->Apellido1);
         $consulta->bindParam(':LNAME2', $this->Apellido2);
         $consulta->bindParam(':ESTADO', $this->estado);
         $consulta->bindParam(':USERREG', $_SESSION["usuario_logeado"] );
         $consulta->bindParam(':PASS', $this->getHash(PASS_INICIAL)); 
         $consulta->bindParam(':MAIL', $this->mail); 
         // echo "call  sp_Crear_editar_usuario('', '$this->Login' ,  '$this->Nombre1' ,  '$this->Nombre2', '$this->Apellido1',  '$this->Apellido2' ,  '$this->estado' ,  '{$_SESSION["usuario_logeado"]}' ,  '{$this->getHash(PASS_INICIAL)}','I', ' $this->mail' )<br>";
         $consulta->execute();
          $this->ID = $conexion->getlastInsertId(); 
      }
     
      $conexion = null;
       $array =  $consulta->fetchAll();
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
               $result ='el nickname ya se encuentra registrado en la base de datos, verifique!!!'; 
               break;
           case '-2':
                $result ='not ok';  
           break;           
       }
       return $result;
      } 
      catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
      $conexion = null;
   }
   //crear areas de control usuario
   
   public function guardarAreasDeControlUsuario($_id_relacion = null,$_codAreasControl, $_nomAreasControl ){  
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
       if($_id_relacion) /*Modifica*/ { 
         $consulta = $link->prepare("call  sp_Crear_editar_AreasDeControl_usuario(:_id_relacion, :ID,  :_codAreasControl, :_nomAreasControl,   :usuarioIngresado)");
           $consulta->bindParam(':_id_relacion', $_id_relacion);
           $consulta->bindParam(':ID', $this->ID);
         $consulta->bindParam(':_codAreasControl', $_codAreasControl);
         $consulta->bindParam(':_nomAreasControl', $_nomAreasControl); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute();
      }else /*Inserta*/ {
          $consulta = $link->prepare("call  sp_Crear_editar_AreasDeControl_usuario('',   :ID, :_codAreasControl, :_nomAreasControl,  :usuarioIngresado)");
        //$consulta->bindParam(':_id_relacion', $_id_relacion);
          $consulta->bindParam(':ID', $this->ID);
         $consulta->bindParam(':_codAreasControl', $_codAreasControl);
         $consulta->bindParam(':_nomAreasControl', $_nomAreasControl); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
          //$this->ID = $conexion->getlastInsertId(); 
      }
      $conexion = null;
       $array =  $consulta->fetchAll();
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
           case '-2':
                $result ='not ok';  
           break;           
       }
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
      $conexion = null;
   }
 //areas de control temporal    
   public function guardarTmpAreasDeControlUsuario($_id_relacion = null,$_codAreasControl, $_nomAreasControl ){  
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($_id_relacion) /*Modifica*/ { 
         $consulta = $link->prepare("call  sp_Crear_editar_tmp_AreasDeControl_usuario(:_id_relacion, 0,  :_codAreasControl, :_nomAreasControl,   :usuarioIngresado)");
           $consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_codAreasControl', $_codAreasControl);
         $consulta->bindParam(':_nomAreasControl', $_nomAreasControl); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute();
      }else /*Inserta*/ {
          $consulta = $link->prepare("call  sp_Crear_editar_tmp_AreasDeControl_usuario('', 0,   :_codAreasControl, :_nomAreasControl,  :usuarioIngresado)");
        //$consulta->bindParam(':_id_relacion', $_id_relacion);
          $consulta->bindParam(':_codAreasControl', $_codAreasControl);
         $consulta->bindParam(':_nomAreasControl', $_nomAreasControl); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
          //$this->ID = $conexion->getlastInsertId(); 
      }
       //echo "call  sp_Crear_editar_tmp_AreasDeControl_usuario('$_id_relacion',0,'$_codAreasControl','$_nomAreasControl' ,'{$_SESSION["usuario_logeado"]}')<br>";
       $array =  $consulta->fetchAll();
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
           case '-2':
                $result ='not ok';  
           break;           
       }
       $conexion = null;
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
      $conexion = null;
   } 
     
//fin areas de control
         public function guardarClienteUsuario($_id_relacion = null,$_id_cliente_SAP, $_nombre_cliente_SAP, $_estado){  
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($_id_relacion) /*Modifica*/ { 
         $consulta = $link->prepare("call  sp_Crear_editar_cliente_usuario(:_id_relacion, :_id_usuario, :_id_cliente_SAP, :_nombre_cliente_SAP, :_estado, :usuarioIngresado)");
         $consulta->bindParam(':_id_usuario', $this->ID);
         $consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_id_cliente_SAP', $_id_cliente_SAP);
         $consulta->bindParam(':_nombre_cliente_SAP', $_nombre_cliente_SAP);
         $consulta->bindParam(':_estado', $_estado); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute();
      }else /*Inserta*/ {
          $consulta = $link->prepare("call  sp_Crear_editar_cliente_usuario('', :_id_usuario, :_id_cliente_SAP, :_nombre_cliente_SAP, :_estado, :usuarioIngresado)");
         $consulta->bindParam(':_id_usuario', $this->ID);
         //$consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_id_cliente_SAP', $_id_cliente_SAP);
         $consulta->bindParam(':_nombre_cliente_SAP', $_nombre_cliente_SAP);
         $consulta->bindParam(':_estado', $_estado); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
          //$this->ID = $conexion->getlastInsertId(); 
      }
      $conexion = null;
       $array =  $consulta->fetchAll();
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
           case '-2':
                $result ='not ok';  
           break;           
       }
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
      $conexion = null;
   }
   
      public function guardarAreaVentaUsuario($_id_relacion = null,$_VKORG, $_VTWEG,$_SPART){  
      try { 
        $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($_id_relacion) /*Modifica*/ { 
         $consulta = $link->prepare("call  sp_Crear_editar_areaVenta_usuario(:_id_relacion,:_id_usuario, :_VKORG ,  :_VTWEG, :_SPART,  :usuarioIngresado)");
         $consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_id_usuario', $this->ID);
         $consulta->bindParam(':_VKORG', $_VKORG);
         $consulta->bindParam(':_VTWEG', $_VTWEG);
         $consulta->bindParam(':_SPART', $_SPART); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
      }else /*Inserta*/ { 
          $consulta = $link->prepare("call  sp_Crear_editar_areaVenta_usuario('', :_id_usuario, :_VKORG ,  :_VTWEG, :_SPART,   :usuarioIngresado)");
        $consulta->bindParam(':_id_usuario', $this->ID);
         $consulta->bindParam(':_VKORG', $_VKORG);
         $consulta->bindParam(':_VTWEG', $_VTWEG);
         $consulta->bindParam(':_SPART', $_SPART); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
      //   echo "call  sp_Crear_editar_areaVenta_usuario('', '$this->ID', '$_VKORG' ,  '$_VTWEG', '$_SPART',   '{$_SESSION["usuario_logeado"]}')";
          //$this->ID = $conexion->getlastInsertId(); 
      } 
      $conexion = null;
       $array =  $consulta->fetchAll();
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
           case '-2':
                $result ='not ok';  
           break; 
       default :
                $result =$array[0]['result']; 
           break;
       }
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
      $conexion = null;
   }
   
      public function guardarTmpClienteUsuario($_id_relacion = null,$_id_cliente_SAP, $_nombre_cliente_SAP, $_estado){  
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($_id_relacion) /*Modifica*/ { 
         $consulta = $link->prepare("call  sp_Crear_editar_tmp_cliente_usuario(:_id_relacion,   :_id_cliente_SAP, :_nombre_cliente_SAP, :_estado, :usuarioIngresado)");
           $consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_id_cliente_SAP', $_id_cliente_SAP);
         $consulta->bindParam(':_nombre_cliente_SAP', $_nombre_cliente_SAP);
         $consulta->bindParam(':_estado', $_estado); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute();
      }else /*Inserta*/ {
          $consulta = $link->prepare("call  sp_Crear_editar_tmp_cliente_usuario('',   :_id_cliente_SAP, :_nombre_cliente_SAP, :_estado, :usuarioIngresado)");
        //$consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_id_cliente_SAP', $_id_cliente_SAP);
         $consulta->bindParam(':_nombre_cliente_SAP', $_nombre_cliente_SAP);
         $consulta->bindParam(':_estado', $_estado); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
          //$this->ID = $conexion->getlastInsertId(); 
      }
    //  echo "call  sp_Crear_editar_tmp_cliente_usuario('','$_id_cliente_SAP','$_nombre_cliente_SAP','$_estado','{$_SESSION["usuario_logeado"]}')<br>";
       $array =  $consulta->fetchAll();
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
           case '-2':
                $result ='not ok';  
           break;           
       }
       $conexion = null;
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
      $conexion = null;
   } 
     
      public function guardarTmpAreaVentaUsuario($_id_relacion = null,$_VKORG, $_VTWEG,$_SPART ){  
      try { 
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
      if($_id_relacion) /*Modifica*/ { 
         $consulta = $link->prepare("call  sp_Crear_editar_tmp_areaVenta_usuario(:_id_relacion,'00', :_VKORG ,  :_VTWEG, :_SPART,  :usuarioIngresado)");
         $consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_VKORG', $_VKORG);
         $consulta->bindParam(':_VTWEG', $_VTWEG);
         $consulta->bindParam(':_SPART', $_SPART); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
      }else /*Inserta*/ {
          $consulta = $link->prepare("call  sp_Crear_editar_tmp_areaVenta_usuario('','00', :_VKORG ,  :_VTWEG, :_SPART,   :usuarioIngresado)");
        //$consulta->bindParam(':_id_relacion', $_id_relacion);
         $consulta->bindParam(':_VKORG', $_VKORG);
         $consulta->bindParam(':_VTWEG', $_VTWEG);
         $consulta->bindParam(':_SPART', $_SPART); 
         $consulta->bindParam(':usuarioIngresado', $_SESSION["usuario_logeado"] ); 
         $consulta->execute(); 
          //$this->ID = $conexion->getlastInsertId(); 
      } 
      // echo "call  sp_Crear_editar_tmp_areaVenta_usuario('','00', '$_VKORG' ,  '$_VTWEG', '$_SPART', '{$_SESSION["usuario_logeado"]}')<br>";
       $array =  $consulta->fetchAll();
       switch ($array[0]['result']){
           case '100':
           case '101':
                $result ='ok';  
           break;
           case '-1':
           case '-2':
                $result ='not ok';  
           break;           
       }
       $conexion = null;
       return $result;
      } catch (PDOException $e) {
    return 'Error de conexión: ' . $e->getMessage();
    exit;
}
      $conexion = null;
   }
   
    
    public function getHash($hash){
        return sha1($hash);           
    }       
    public function setNewPass($newPass){
        $continue = $this->guardarNewPass($newPass);
        if ($continue){
        return $this->verificarLogin($this->Login,$newPass);}
        else{
            return false;
        }
    }
    private function guardarNewPass($newPass){
        $conexion = DataBase::getInstance(); 
        $link = $conexion->getLink();
        if($this->ID) /*Modifica*/ { 
             $consulta = $link->prepare("call  sp_change_pass(:ID, :USERREG, :PASS )");
             $consulta->bindParam(':ID', $this->ID);
             $consulta->bindParam(':USERREG', $_SESSION["usuario_logeado"] );
             $consulta->bindParam(':PASS', $newPass); 
            // echo "call  sp_change_pass($this->ID, {$_SESSION["usuario_logeado"]}, $newPass)";
             $consulta->execute();
             $conexion = null; 
             return true;
          }
          return false;
      
  
   }
   public function setFechaIngresoUsuario(){
        $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
        $consulta2 = $link->prepare("call clientes.sp_registro_ultimo_ingreso( '$this->ID' );");
        //echo "<br>call clientes.sp_registro_ultimo_ingreso( '$this->ID'  );";
        $consulta2->execute();
   }

   public static function verificarLogin($Login,$pass = null){
       global $datos;
       $pass = is_null($pass)?'':$pass;
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();
       $consulta = $link->prepare("call sp_verificar_usuarios_permisos( :LOGIN ,  :PASS );");
       $datos[':LOGIN'] = $Login;
       $datos[':PASS'] = $pass;
       $consulta->bindParam(':LOGIN', $Login);
       $consulta->bindParam(':PASS', $pass);
       $consulta->execute();
       $registro = $consulta->fetch();
       if($registro){           
          return new self($registro['ID'],$registro['Login'], $registro['Nombre1'],$registro['Nombre2'],$registro['Apellido1'], $registro['Apellido2'],$registro['nombreCompleto'],$registro['estado'], $registro['pass'],$registro['mail'], $registro['change'],$registro['cod_remision'] );
       }else{
          return false;
       }
    }
     public static function recuperarAreasVentasTmp(){
      try {  
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();      
       $consulta = $link->prepare('SELECT distinct * FROM  aux_relacion_usuario_area_venta     ');
       $result['query'] = 'SELECT distinct * FROM aux_relacion_usuario_area_venta   ';
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
    public static function recuperarClientesTmp(){
      try {  
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();      
       $consulta = $link->prepare('SELECT * FROM vw_Aux_relacion_usuario_clientes    ORDER BY nombre_cliente_SAP ');
       $result['query'] = 'SELECT * FROM vw_Aux_relacion_usuario_clientes   ORDER BY nombre_cliente_SAP ';
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
    public static function recuperarAreasDeControlTmp(){
      try {  
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();      
       $consulta = $link->prepare('SELECT * FROM aux_relacion_usuario_areas_de_control    ');
       $result['query'] = 'SELECT * FROM aux_relacion_usuario_areas_de_control     ';
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
     public static function recuperarTodosAreasVentas($where = NULL){
        try {  
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();      
       $consulta = $link->prepare('SELECT * FROM vw_relacion_usuario_area_venta  '.$where.'  ');
       $result['query'] = 'SELECT * FROM vw_relacion_usuario_area_venta '.$where.'   ';
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
    public static function recuperarTodosClientes($where = NULL){
        try {  
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();      
       $consulta = $link->prepare('SELECT * FROM vw_relacion_usuario_clientes  '.$where.' ORDER BY nombre_cliente_SAP ');
       $result['query'] = 'SELECT * FROM vw_relacion_usuario_clientes '.$where.' ORDER BY nombre_cliente_SAP ';
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
    
     public static function recuperarTodosAreasDeControl($where = NULL){
        try {  
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();      
       $consulta = $link->prepare('SELECT * FROM relacion_usuario_areas_de_control  '.$where.' ORDER BY nomAreasControl ');
       $result['query'] = 'SELECT * FROM relacion_usuario_areas_de_control '.$where.' ORDER BY nomAreasControl ';
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
    public static function recuperarTodos($where = NULL){
        try {  
       $conexion =DataBase::getInstance();
       $link = $conexion->getLink();      
       $consulta = $link->prepare('SELECT * FROM ' . self::TABLA . ' '.$where.' ORDER BY Nombre1 ,Apellido1 ');
       $result['query'] = 'SELECT * FROM ' . self::TABLA . ' '.$where.' ORDER BY Nombre1 ,Apellido1 ';
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
     public static function eliminaRelacionAreaDeVentaTemporal(){
        TRY {
       $conexion =DataBase::getInstance(); 
      $tabla ='aux_relacion_usuario_area_venta';
      $_result =$conexion->truncateTable($tabla);
      switch ($_result[0]['result']){
           case '100':
                $result ='ok';  
           break;
           case '-1':
                $result ='_table';  
           break;  
       default :
                $result = $_result[0]['result'];
           break;
       }
      } catch (PDOException $e) {
        return 'Error de conexión: ' . $e->getMessage();
        exit;
     }
     return $result; 
    }
     public static function eliminaRelacionAreaDeControlTemporal(){
        TRY {
       $conexion =DataBase::getInstance(); 
      $tabla ='aux_relacion_usuario_areas_de_control';
      $_result =$conexion->truncateTable($tabla);
      switch ($_result[0]['result']){
           case '100':
                $result ='ok';  
           break;
           case '-1':
                $result ='_table';  
           break;  
       default :
                $result = $_result[0]['result'];
           break;
       }
      } catch (PDOException $e) {
        return 'Error de conexión: ' . $e->getMessage();
        exit;
     }
     return $result; 
    }
    public static function eliminaRelacionTemporal(){
        TRY {
       $conexion =DataBase::getInstance(); 
      $tabla ='Aux_relacion_usuario_clientes';
      $_result =$conexion->truncateTable($tabla);
      switch ($_result[0]['result']){
           case '100':
                $result ='ok';  
           break;
           case '-1':
                $result ='_table';  
           break;  
       default :
                $result = $_result[0]['result'];
           break;
       }
      } catch (PDOException $e) {
        return 'Error de conexión: ' . $e->getMessage();
        exit;
     }
     return $result; 
    }
      public  function eliminarRelacionCliente($dato){
        TRY {
       $conexion =DataBase::getInstance(); 
       if ($this->ID && $this->ID != ''){
           $tabla ='relacion_usuario_clientes';
           $columna = 'id_relacion';
       }else {$tabla ='Aux_relacion_usuario_clientes';
           $columna = 'id_cliente_SAP';} 
      $_result =$conexion->eliminarDato($tabla,  $dato,$columna);
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
      } catch (PDOException $e) {
        return 'Error de conexión: ' . $e->getMessage();
        exit;
     }
     return $result;
    }
    public  function eliminarRelacionAreaDeVenta($dato){
        TRY {
       $conexion =DataBase::getInstance(); 
       if ($this->ID && $this->ID != ''){
           $tabla ='relacion_usuario_area_venta';
           $columna = 'id_relacion';
       }else {$tabla ='aux_relacion_usuario_area_venta';
            $columna = 'concat(  VKORG, VTWEG, SPART)';}
           
      $_result =$conexion->eliminarDato($tabla,  $dato,$columna);
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
      } catch (PDOException $e) {
        return 'Error de conexión: ' . $e->getMessage();
        exit;
     }
     return $result;
    }
    public  function eliminarRelacionAreaDeControl($dato){
        TRY {
       $conexion =DataBase::getInstance(); 
       if ($this->ID && $this->ID != ''){
           $tabla ='relacion_usuario_areas_de_control';
           $columna = 'id';
       }else {$tabla ='aux_relacion_usuario_areas_de_control';
            $columna = 'codAreasControl';}
           
      $_result =$conexion->eliminarDato($tabla,  $dato,$columna);
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
      } catch (PDOException $e) {
        return 'Error de conexión: ' . $e->getMessage();
        exit;
     }
     return $result;
    }
}
