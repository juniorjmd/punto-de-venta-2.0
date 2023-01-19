<?php
require_once '../../php/helpers.php'; 
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
    //ECHO $nomClass.'<BR>';
    require_once $nomClass;
 });
new Core\Config();
$user = cargaDatosUsuarioActual();
if ($_SESSION['access']==false){
    header('Location: ../');
}
?>
<link href="../../css/vistas.css" rel="stylesheet" type="text/css"/>
<script src="view/js/perfil_recurso.js" type="text/javascript"></script> 

    
    
     <div class="panel panel-default" > 
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody> 
  
  
  <tr> 
      <td >Creación y edición de la relacion entre perfiles y recursos</td></tr></tbody></table>
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
                        <div class="col-md-2 col-sm-12"></div>
                        <div class="col-md-3 col-sm-12"><div class="form-group">
                            <label for="email">Perfil</label> 
                            <select  class="form-control" id="perfil_id">
                             </select>
                        </div></div>
                        <div class="col-md-4 col-sm-12">
                            <label for="email">Recursos del sistema</label> 
                            <div style=" width: 100%" id="listadoRecursos">
                                <?php //print_r($datos['datos']);  
                                   $datos = Class_php\MenuSistema::get_html_recursos_by_menu('0');
                                    print_r($datos); 
                                     
                                ?>
                            </div>
                        </div><div class="col-md-12"><div style="width:100%;text-align: center">
                             <input type="submit" name="btnIngresar" value="Ingresar" id="btnIngresar" class="<?php echo trim($_SESSION["style"]);?>_btnIngresar" >&nbsp;
                             <input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" class="<?php echo trim($_SESSION["style"]);?>_btnIngresar" >
                                     </div></div>
                    </div>
            </div>
                </td>
                       </tr></tbody></table>                  
          </td></tr></tbody></table>
    </td></tr></tbody></table>
        </div>
    