<?php
require_once '../php/helpers.php'; 
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../'. str_replace('\\','/',$nombre_clase ).'.php' ;
    //ECHO $nomClass.'<BR>';
    require_once $nomClass;
 });
new Core\Config();
$user = cargaDatosUsuarioActual();
if ($_SESSION['access']==false){ 
    header('Location: ../');
}  
$permisos = $user->getArrayPermisos('administracion');
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
<head>
    <title>JDS - MadColombia</title>
<link rel="icon" type="image/png" href="<?php echo URL_BASE; ?>/images/jds_ico.png" sizes="64x64">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <script src="../vendor/jquery.js" type="text/javascript"></script>
    <link href="../vendor/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="../vendor/bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css"/>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script src="../vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
    <link href="../css/portada2.css" rel="stylesheet" type="text/css"/>
    <link href="../css/vistas.css" rel="stylesheet" type="text/css"/>
    <link href="../css/load_style.css" rel="stylesheet" type="text/css"/>
    <script src="../vendor/wp-nicescroll/jquery.nicescroll.min.js" type="text/javascript"></script>
    <script src="../vendor/listas.js" type="text/javascript"></script>
    <script src="../vendor/funciones.js" type="text/javascript"></script>
    <script src="../vendor/busquedasEnListasDinamicas.js" type="text/javascript"></script>
    <style>
        body{font-family: Verdana, Geneva, sans-serif; }
        .container{width:100%;height: 98%}
        span{font-size:11px; }     
    </style>
    <script src="view/js/index.js" type="text/javascript"></script>
</head>
<body>
    <div class="container"  > 
    <div class="panel panel-default"> 
    <div class="panel-header">   
        <table id='cabecera'>
            <tr class="tr_cabecera"> 
                <td rowspan="3">
                    <div> <img id="logoPrincipal" src="../images/usuario_logo.jpeg"  class="img-responsive" alt="logo_empresa" width="150px" height="100px"/>
        </div> </td>
        <td class='headerCenter'>&nbsp;
        </td>
            </td><td class="headerRight"><a id='salidaSegura' class="linkCabecera" href="#" >
                <img src="../images/ico-salida.gif" alt=""/>Salida Segura</a>
            <a  class="linkCabecera" href="../Principal/" >
                <img style="height:30px; margin-left: 70%"   src="../images/regresar.png" alt="regresar.png"/></a>
        <a  class="menuHijo" href="#" data-url="inicio.php" >
                <img style="height:30px; margin-left: "   src="../images/inicio_1.png" alt="inicio.png"/></a></td></tr>
        <tr class="tr_cabecera"><td style='height:30px'><div class="<?php echo trim($_SESSION["style"]);?>_head-saludo">
                         Hola,&nbsp;<?php echo $user->getNombre1()." ".$user->getLasName();?>! </div> </td>
                        <td><span ><img src="../images/ico-sessions.gif" alt=""/><?php echo date('d/m/Y  H:m:s'); ?></span></td></tr>
            <tr class="tr_cabecera"><td>&nbsp;</td><td></td></tr>
        </table>

    </div> 
    <div class="panel-body">
    <div class="row">
        <div class="col-md-12"> </div>
        <!--inicio menu lateral-->

        <div class="col-md-2"  >
        <div class='menuLateral' >
             <?php echo $user->getMenuUsuario('administracion');?>   
            
              </div>
            </div>  
        <!--fin menu lateral-->
   <div class="col-md-10 " id="cargarDatos"  >
        
    </div>

<!-- footer-->    
<!--footer parte lateral izquierda, solo es un espacio para alinear el footer con la parte donde se muestran los modulos-->   
<div class="col-md-2"></div>

<!--footer parte lateral derecha, footer alineado con la parte donde se muestran los modulos--> 
<div class="col-md-10">  
       <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tbody> 
        <tr>
            <td class="footer_view"  >Powered by Creativos asesores Todos los derechos reservados Copyright 2010 © </td></tr></tbody></table> 
</div> 
<!--fin del  footer-->  </div>


    </div>


        </div></div>
    <div id="modalContainer"></div>
    
     <div id="contenedor_espera_respuesta" style=" padding: 10px; color: rgb(255, 255, 255); font-weight: bold; position: absolute; opacity: 0.5; background-color: rgb(102, 102, 102); z-index: 5;   top: -50px; width: 100%; height: 148%; display: none; " >
    <div   align="center" style="margin-top:20%">
        <table><tr><td><span id='msg_pantalla'  style="float: left; font-size: 20px ">Procesando la informacion ...  </span>  </td><td>
         
<div class="loadingDiv" id="esperaMarcas" style="height:20px; width:100px;  ">
    <div id="blockG_1" class="facebook_blockG"></div>
    <div id="blockG_2" class="facebook_blockG"></div>
    <div id="blockG_3" class="facebook_blockG"> </div></div></td></tr></table>
     </div>
</div>
</body>
</html>
