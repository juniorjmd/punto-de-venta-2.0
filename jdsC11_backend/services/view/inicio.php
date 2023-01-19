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
<script src="view/js/inicio.js" type="text/javascript"></script>
 
  
<div class="panel panel-default" >  
   
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody> 
  
  
      <td class="moduloCuerpo_1"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="30">
        <tbody>
        <tr>
          <td>
            <table width="100%" id="Table7" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0">
              <tbody>
              <tr>
                <td width="100%" height="350" valign="top">
                    
                                    
                    <p align="justify" class="parrafo"> El													Área de 
                  Clientes Externos													vía Internet ofrece múltiples 
                  herramientas para el procesamiento y consulta de 
                  													la información de nuestros													 <span class="parrafo" id="lblCliente">clientes</span>. El 
                  													sistema ofrece una interfaz web para la captura 
                  de órdenes de clientes, así 													como diferentes 
                  consultas y reportes sobre el flujo de documentos 
                  comerciales.</p>
                  <?php  echo $user->getDescripcionMenuUsuario('administracion');?>   							 </li></ul>
                  <br></td></tr>
              </tbody></table></td></tr></tbody></table>
      
      </td></tr></tbody></table>
        </div>
    