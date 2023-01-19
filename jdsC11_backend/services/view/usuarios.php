<?php
require_once '../../php/helpers.php'; 
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
    //ECHO $nomClass.'<BR>';
    require_once $nomClass;
 });
new Core\Config();
$user = cargaDatosUsuarioActual();
if ($_SESSION['access'] ==false){
    header('Location: ../');
}
  
?>
<link href="../../css/vistas.css" rel="stylesheet" type="text/css"/>
<script src="view/js/usuarios.js" type="text/javascript"></script> 
 
    <div class="panel panel-default" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody> 
  
  
  <tr> 
      <td >Creación y edición de usuarios</td></tr></tbody></table>
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
                        <div class="col-md-2 col-sm-12" style="display:none"><div class="form-group">
                            <label for="email">Cód. usuario : </label>
                            <input type="text" class="form-control" id="ID" readonly="true">
                        </div></div> 
                        
                         <div class="col-md-2 col-sm-12"><div class="form-group">
                            <label for="email">Pri. nombre: </label>
                            <input type="text" class="form-control" id="Nombre1"  >
                        </div></div>
                        <div class="col-md-2 col-sm-12"><div class="form-group">
                            <label for="email">Seg. nombre:</label>
                            <input type="text" class="form-control" id="Nombre2">
                        </div></div> 
                        <div class="col-md-2 col-sm-12"> 
                            <div class="form-group">
                                    <label for="email">Pri. Apellido:</label>
                                    <input type="text" class="form-control" id="Apellido1">
                                  </div> 
                        </div>  
                        <div class="col-md-2 col-sm-12"> 
                            <div class="form-group">
                                    <label for="email">Seg. Apellido:</label>
                                    <input type="text" class="form-control" id="Apellido2">
                                  </div> 
                        </div> 
                        
                        <!--
                          <div class="col-md-3 col-sm-12">
                              <div class="btn-group">
               <button type="button" class=" btn btn-info " data-toggle="modal" data-target="#myModal" id="btn_mostrar_clientes_usuario">
                   <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Clientes
</button>  <button type="button" class=" btn btn-info " data-toggle="modal" data-target="#myModalAreasVentasUsuario" id="btn_mostrar_areasVenta_usuario">
                   <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Vent.
</button>
                </div></div> 
                         <div class="col-md-1 col-sm-12"><div class="form-group">
                            <button type="button" class=" btn btn-info " data-toggle="modal" data-target="#myModalAreasDeControl" id="btn_mostrar_Areas_De_Control">
                   <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> A. Control
</button> 
                        </div></div> -->
                    </div>
                     <div class="row" style="width:100%"> 
                      
                        <div class="col-md-2 col-sm-12"><div class="form-group">
                            <label for='Nickname'>Nickname :</label>
                            <input type="text" class="form-control" id="Login">
                        </div></div> 
               
                        <div class="col-md-2 col-sm-12"><div class="form-group">
                            <label for="email">Password :</label>
                             <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="button" title="restaurar contraseña" id='resetPass'>
                                   <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                                  </button>
                                </span>
                                 <input type="password" class="form-control" id="pass"  readonly="true" >
                              </div>
                            
                        </div></div> 
                          <div class="col-md-4 col-sm-12">
                       <label for='Nickname'>correo electronico :</label>
                            <input type="email" class="form-control" id="mail" autocomplete="off">
                </div>
                    <div class="col-md-2 col-sm-12">
                    <label for='Nickname'>cod. remis. :</label>
                    <input type="text" class="form-control" id="cod_remision" readonly="true">
                </div>
              <div class="col-md-2 col-sm-12"><div class="form-group">
                            <label for="email">Estado</label> 
                            <select  class="form-control" id="estado">
                                <option value="A">Activo</option>
                                <option value="I">Inactivo</option>
                                
                            </select>
                        </div></div>
           
         </div>
            <div class="row" style="width:100%"> 
            
            <div class="col-md-12">
                        <div style="width:100%;text-align: center">
                             <input type="submit" name="btnIngresar" value="Ingresar" id="btnIngresar" class="<?php echo trim($_SESSION["style"]);?>_btnIngresar" >&nbsp;
                             <input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" class="<?php echo trim($_SESSION["style"]);?>_btnIngresar" >
                        </div>
            </div>                         
            </div>
                <div class="row" style="width:100%" id="tablaResultado">
                    <?php echo cargarTablaLIstar('usuarios',true,true);?>
                </div>
                <div class="row" style="width:100%" id="tablaResultado">
                      <?php// echo cargarTablaLIstar('SAP_rfc_clientes',true,true);?>
                </div>
            </div>
          </td>
          </tr></tbody></table>                  
          </td></tr></tbody></table>
    </td>
  </tr>
</tbody></table></div>
     
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <input type="hidden" value="0" id='numClientesRelacionados'/>
        <input type="hidden" value="0" id='num_mail_principal_cliente'/>
        <h4 class="modal-title">Clientes relacionados al usuario</h4>
      </div>
      <div class="modal-body">
          <div class="row" style="width:100%" id="tablaResultado">
           <div class="col-md-1 col-sm-12">
               <button id='btn_cargarNuevosClientes' type="button" class=" btn btn-info btn-lg" data-toggle="modal" data-target="#modalClientesSAP">
                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Clientes
                </button>
           </div></div>
        <div class="row" style="width:100%" id="tablaResultado">
                     
                        <?php echo cargarTablaLIstar('relacion_cliente_usuarios',false,true);?>
                    </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" >Close</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<div id="modalClientesSAP" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <input type="hidden" value="0" id='numClientesRelacionados'/>
        <h4 class="modal-title">Clientes relacionados al usuario</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="width:100%" id="tablaResultado">
                     
                      <?php echo cargarTablaLIstar('SAP_clientes_con_mail',true);?>
                    </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" >Close</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
    
<div id="myModalAreasVentasUsuario" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <input type="hidden" value="0" id='numAreasRelacionados'/>
        <h4 class="modal-title">Areas de venta relacionados al usuario</h4>
      </div>
      <div class="modal-body"><div class="row" style="width:100%" id="tablaResultado">
           <div class="col-md-1 col-sm-12">
               <button id='btn_cargarNuevasAreasVenta' type="button" class=" btn btn-info btn-lg" data-toggle="modal" data-target="#myModalAreasVentas">
                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Areas
                </button>
           </div></div>
        <div class="row" style="width:100%" id="tablaResultado">
                     <div class="col-md-2 col-sm-12"> </div><div class="col-md-8 col-sm-12"> 
                      <?php echo cargarTablaLIstar('relacion_usuario_area_venta',false,true);?>
                    </div></div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" >Close</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>    
    
<div id="myModalAreasVentas" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button> 
        <h4 class="modal-title">Areas de venta existentes</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="width:100%" id="tablaResultado">
                     <div class="col-md-2 col-sm-12"> </div><div class="col-md-8 col-sm-12"> 
                      <?php echo cargarTablaLIstar('tabla_SAP_rfc_Areasdeventa');?>
                    </div></div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" >Close</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div> 
       
<div id="myModalAreasDeControl" class="modal fade" role="dialog">
    
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <input type="hidden" value="0" id='numAreaDeControl'/>
        
        <h4 class="modal-title">Areas de control relacionados al usuario</h4>
      </div>
      <div class="modal-body">
          <div class="row" style="width:100%" id="tablaResultado">
           <div class="col-md-1 col-sm-12">
               <button id='btn_cargarNuevasAreasDeControl' type="button" class=" btn btn-info btn-lg" data-toggle="modal" data-target="#myModalNuevaAreasDeControl">
                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> A. Control
                </button>
           </div></div>
        <div class="row" style="width:100%" id="tablaResultado">
                     
                        <?php echo cargarTablaLIstar('relacion_areasDeControl_usuarios',false,true);?>
                    </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" >Close</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div> 
    
<div id="myModalNuevaAreasDeControl" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button> 
        <h4 class="modal-title">Nuevas Areas de control   </h4>
      </div>
      <div class="modal-body">
        <div class="row" style="width:100%" id="tablaResultado">
                     <div class="col-md-2 col-sm-12"> </div><div class="col-md-8 col-sm-12"> 
                      <?php echo cargarTablaLIstar('areas_de_control');?>
                    </div></div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" >Close</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div> 