<?php
require_once '../../php/helpers.php'; 
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
    //ECHO $nomClass.'<BR>';
    if (file_exists($nomClass))
    {require_once $nomClass;}
    else {
        $nomClass =  '../'. str_replace('\\','/',$nombre_clase ).'.php' ;
         if (file_exists($nomClass))
            {require_once $nomClass;}
         else {
             $nomClass =    str_replace('\\','/',$nombre_clase ).'.php' ;
         if (file_exists($nomClass))
            {require_once $nomClass;}
         }
    }
 });
new Core\Config();
$user = cargaDatosUsuarioActual();
if ($_SESSION['access']==false){ 
    header('Location: ../');
}
$conexion =  Class_php\DataBase::getInstance();
 $link = $conexion->getLink();
$query= $link->prepare('SELECT idtipoRecurso, nombre_recurso FROM tipoRecurso;');
$query->execute(); 
$OPTION_TIPO_RECURSO =  "<option value=''>Seleccionar</oprion>" ;
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $OPTION_TIPO_RECURSO .= "<option value='{$row['idtipoRecurso']}'>{$row['nombre_recurso']}</oprion>" ;			
}
$conexion = null; 
?>
<link href="../../css/vistas.css" rel="stylesheet" type="text/css"/>
<script src="view/js/recursos.js" type="text/javascript"></script> 

 
    
     <div class="panel panel-default" > 
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody> 
  
  
  <tr> 
      <td >Creación y edición de recursos por menu.</td></tr></tbody></table>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody> 
      <tr>
    
    
    <td class="moduloCuerpo_1">
        <table width="100%" border="0" cellspacing="0" cellpadding="30">
        <tbody>
        <tr>
          <td> 
              <table width="100%"   id="tablaDeDatos" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0">
              <tbody>
              <tr>
                <td width="100%" height="350" valign="top">
                <div width="100%" align="center">
                     <div class="row" style="width:100%"> 
                         <div class="col-md-1 col-sm-12"></div>
                        <div class="col-md-1 col-sm-12">
                            <div class="form-group">
                            <label for="email">Id  </label>
                            <input type="text" class="form-control" id="ID_recurso" readonly="true">
                        </div></div> 
                        <div class="col-md-4 col-sm-12"><div class="form-group">
                            <label for="email">Menú del sistema : </label> 
                            <select class="form-control" id="menuDelSistema">
                                
                            </select>
                        </div></div> 
                        
                         <div class="col-md-2 col-sm-12"><div class="form-group">
                            <label for="email">Tipo Recurso: </label>
                           <select class="form-control" id="tipoRecurso">
                                <?php echo $OPTION_TIPO_RECURSO ; ?>
                            </select>
                             </div></div>   
                        <div class="col-md-4 col-sm-12"><div class="form-group">
                            <label for="email">Nombre recurso: </label>
                            <input type="text" class="form-control" id="nombreRecurso"  >
                            </div></div> </div>
                        <div class="row" style="width:100%"> 
                        <div class="col-md-3 col-sm-12"></div>
                        <div class="col-md-3 col-sm-12"><div class="form-group">
                            <label for="email">id en el sistema :</label>
                            <input type="text" class="form-control" id="id_sistema">
                        </div></div>   
              <div class="col-md-2 col-sm-12"><div class="form-group">
                            <label for="email">Estado</label> 
                            <select  class="form-control" id="estado">
                                <option value="A">Activo</option>
                                <option value="I">Inactivo</option>
                                
                            </select>
                  </div></div></div>
                    <div class="row" style="width:100%"> 
                          <div class="col-md-12"><div style="width:100%;text-align: center">
                             <input type="submit" name="btnIngresar" value="Ingresar" id="btnIngresar" class="<?php echo trim($_SESSION["style"]);?>_btnIngresar" >&nbsp;
                             <input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" class="<?php echo trim($_SESSION["style"]);?>_btnIngresar" >
                                     </div></div></div>
                         
                    </div>
                    <div class="row" style="width:100%" id="tablaResultado">
                     
                        <?php echo cargarTablaLIstar('recursos',false,true);?>
                    </div>
                    
                </div></td></tr></tbody></table>
        
    </td></tr></tbody></table>
        </div>
    

