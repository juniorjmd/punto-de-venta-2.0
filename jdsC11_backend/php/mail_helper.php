<?php
function mail_externo_cargue_pedido ($id_sitio){
    $Ecof_moduloTitle_secundario = 'border-radius: 8px 0px 0px 0px; 
   border-top: 1px solid rgb(234, 128, 34);
     border-left: 1px solid rgb(234, 128, 34);    
    height: 30px;
    color: rgb(234, 128, 34);;
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(253, 238, 225);';
    $Ecof_moduloTitle_trc = ' border-top: 1px solid rgb(234, 128, 34);
     border-left: 1px solid rgb(234, 128, 34);    
    height: 30px;
    color: rgb(234, 128, 34);;
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(253, 238, 225);';
    $mcv_moduloTitle_secundario = 'border-radius: 8px 0px 0px 0px;
border: 0px solid rgb(234, 128, 34);
    height: 30px;
    color: rgb(255, 255, 255);
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(12, 132, 68);';
    $mcv_moduloTitle_trc = '
border: 0px solid rgb(234, 128, 34);
    height: 30px;
    color: rgb(255, 255, 255);
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(12, 132, 68);';
    $Ecof_ui_widget_content=' border: 1px solid #536675;
    background: #fcfdfd  ;
    color: #505253;
    font-size: 10px';
    $ui_widget_content = '
    border: 1px solid #536675;
    background: #fcfdfd ;
    color: #505253;
     font-size: 10px';
    $mcv_ui_widget_content =' border: 1px solid #536675;
    background: #fcfdfd ;
    color: #505253;
     font-size: 10px';
    $moduloTitle_principal = 'height: 30px;
    color: rgb(0, 133, 63);
    line-height: 25px;
    padding-left: 5px;
    font-size: 20px;';
    $mcv_ui_widget_header='
    border: 1px solid #536675;
    background-color: #338C44 ;
    color: #ffffff;
    font-weight: bold;
    font-size: 10px;
    border-collapse: separate; ';
    $Ecof_ui_widget_header = 'border: 1px solid #536675;
    background-color: #FFFFEA; 
    color: rgb(234, 128, 34);
    font-weight: bold;    
    font-size: 10px;
    border-collapse: separate;   ';
 
$clase1 = $id_sitio.'_moduloTitle_secundario';
$clase2 = $id_sitio.'_moduloTitle_trc';
$clase3 = $id_sitio.'_ui_widget_header';

return '<body style ="font-family: Verdana, Geneva, sans-serif; font-size: 12px;" ><table style="width: 100%;font-family: Verdana, Geneva, sans-serif;  font-size: 12px;">'.
       '<tr><td colspan="5" style="'.$moduloTitle_principal.'">Se acaba de realizar una Solicitud de Orden de cargue, para el destinatario %DESTINATARIO%.</td></tr>'
        .'<tr><td> &nbsp;</td><td colspan="4"><table style="width: 100%"><tr><td colspan="3" style="'.$$clase1.'">Información de la Solicitud de Orden de Cargue.</td></tr>'
        . '<tr><td>&nbsp;</td><td>Fecha:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;'.date('d/m/Y').'</td></tr><tr><td>&nbsp;</td><td>Solicitud de Orden de Cargue:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%SOLICITUD_NUM%</td></tr>'
        . '<tr><td>&nbsp;</td><td>Fecha Estimada de Recogida.:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%FECHA_RECOGIDA%</td></tr>'        
        .'<tr><td>&nbsp;</td><td>Transportador:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%TRANSPORTADOR%</td></tr>'
        .'<tr><td>&nbsp;</td><td>Placa:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%PLACA%</td></tr>'        
        .'<tr><td>&nbsp;</td><td>Cédula:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%CEDULA_CONDUCTOR%</td></tr>'
        .'<tr><td>&nbsp;</td><td>Elaborada por:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%ELABORADO_POR%</td></tr>'        
        . '<tr><td>&nbsp;</td><td colspan="3">Ante cualquier inquietud por favor comuniquese con la Gerencia Comercial (5) 3618212</td></tr></table></td></tr>'        
        . '<tr><td >&nbsp;</td><td><table style="width: 99%; font-size: 12px;"><td colspan="4" style="'.$$clase2.'">Detalle de la Solicitud de Orden de Cargue</td></tr>'
        .'<tr style="'.$$clase2.'" class="cabecerasTabla"> '
        .'<td align="center">PEDIDO</td><td align="center">CENTRO</td><td align="center">PUENTO DE EXPEDICION</td><td align="center">POSICIÓN</td><td align="center">MATERIAL</td>'
        . '<td align="center">CANT. A CARGAR</td><td>UM</td></tr>%LINEAS_MATERIALES%</table></td></tr></table> </body> ';
}
function mail_interno_cargue_pedido(){
return ' <body>    
<style> *{font-family: Verdana, Geneva, sans-serif;}</style>
<table style="font-size: 12px"><tr><td>Señores <br><span style="text-decoration: underline;font-weight: bold">MONOMEROS</span></td></tr><tr><td>&nbsp;</td></tr>
<tr><td>Se acaba de realizar una orden de cargue a través del portal comercial, para el destinatario <span style=" font-weight: bold">%DESTINATARIO%</span> </td></tr>
<tr><td>Fecha de creación: '.date('d/m/Y').'</td></tr>
<tr><td>Solicitud de Orden de Cargue: %SOLICITUD_NUM%</td></tr>
<tr><td>Fecha Estimada de Recogida: %FECHA_RECOGIDA%</td></tr>
<tr><td>Transportador: %TRANSPORTADOR%</td></tr>
<tr><td>Placa: %PLACA%</td></tr>
<tr><td>Cédula: %CEDULA_CONDUCTOR%</td></tr>
<tr><td>Usuario: %ELABORADO_POR%</td></tr>
    <tr><td>&nbsp;</td></tr>
                <tr><td><table   style=" font-size: 11px;border-collapse: collapse;border: 2px solid  #23282D; "  >
                            <tr  style="  background-color: #E4E4E4;font-weight: bold ">
                                <td style=" border: 1px solid  #23282D; padding: 2px;">Pedido</td>
                                <td style=" border: 1px solid  #23282D; padding: 2px;">Centro</td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px">Puesto de Expedición</td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px">Posición</td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px">Material </td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px">Descripción </td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px">Cant. a Cargar </td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px">UM</td>
                            </tr>
                            %LINEAS_MATERIALES%
            </table></td></tr>    
    <tr><td>&nbsp;</td></tr>    
    <tr><td>Gracias.</td></tr>
</table></body>';
}
function mail_externo ($id_sitio){
    $Ecof_moduloTitle_secundario = 'border-radius: 8px 0px 0px 0px; 
   border-top: 1px solid rgb(234, 128, 34);
     border-left: 1px solid rgb(234, 128, 34);    
    height: 30px;
    color: rgb(234, 128, 34);;
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(253, 238, 225);';
    $Ecof_moduloTitle_trc = ' border-top: 1px solid rgb(234, 128, 34);
     border-left: 1px solid rgb(234, 128, 34);    
    height: 30px;
    color: rgb(234, 128, 34);;
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(253, 238, 225);';
    $mcv_moduloTitle_secundario = 'border-radius: 8px 0px 0px 0px;
border: 0px solid rgb(234, 128, 34);
    height: 30px;
    color: rgb(255, 255, 255);
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(12, 132, 68);';
    $mcv_moduloTitle_trc = '
border: 0px solid rgb(234, 128, 34);
    height: 30px;
    color: rgb(255, 255, 255);
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(12, 132, 68);';
    $Ecof_ui_widget_content=' border: 1px solid #536675;
    background: #fcfdfd  ;
    color: #505253;
    font-size: 10px';
    $ui_widget_content = '
    border: 1px solid #536675;
    background: #fcfdfd ;
    color: #505253;
     font-size: 10px';
    $mcv_ui_widget_content =' border: 1px solid #536675;
    background: #fcfdfd ;
    color: #505253;
     font-size: 10px';
    $moduloTitle_principal = 'height: 30px;
    color: rgb(0, 133, 63);
    line-height: 25px;
    padding-left: 5px;
    font-size: 20px;';
    $mcv_ui_widget_header='
    border: 1px solid #536675;
    background-color: #338C44 ;
    color: #ffffff;
    font-weight: bold;
    font-size: 10px;
    border-collapse: separate; ';
    $Ecof_ui_widget_header = 'border: 1px solid #536675;
    background-color: #FFFFEA; 
    color: rgb(234, 128, 34);
    font-weight: bold;    
    font-size: 10px;
    border-collapse: separate;   ';
 
$clase1 = $id_sitio.'_moduloTitle_secundario';
$clase2 = $id_sitio.'_moduloTitle_trc';
$clase3 = $id_sitio.'_ui_widget_header';
return '<body style ="font-family: Verdana, Geneva, sans-serif; font-size: 12px;" ><table style="width: 100%;font-family: Verdana, Geneva, sans-serif;  font-size: 12px;">'.
       '<tr><td colspan="5" style="'.$moduloTitle_principal.'">Se acaba de realizar una Solicitud de Pedido, para el cliente %CLIENTE%.</td></tr>'
        .'<tr><td> &nbsp;</td><td colspan="4"><table style="width: 100%"><tr><td colspan="3" style="'.$$clase1.'">Información de la Solicitud de Pedido.</td></tr>'
        . '<tr><td>&nbsp;</td><td>Fecha:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%FECHA%</td></tr><tr><td>&nbsp;</td><td>Categoría del pedido:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%CATEGORIA%</td></tr>'
        . '<tr><td>&nbsp;</td><td>Solicitud de Pedido No.:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%SOL_PEDIDO%</td></tr><tr><td>&nbsp;</td><td>Elaborada por:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%ELABORADO_POR%</td></tr>'
        . '<tr><td>&nbsp;</td><td>Comentarios:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%COMENTARIOS%</td></tr><tr><td>&nbsp;</td><td>Nota:</td></tr><tr><td>&nbsp;</td><td colspan="3">%NOTA%</td></tr></table></td></tr>'
        . '<tr><td >&nbsp;</td><td><table style="width: 99%; font-size: 12px;"><td colspan="4" style="'.$$clase2.'">Detalle de la Solicitud de Pedido</td></tr><tr style="'.$$clase2.'" class="cabecerasTabla"> <td align="center">MATERIAL</td><td align="center">PRECIO UNITARIO</td><td align="center">CANTIDAD</td>'
        . '<td align="center">CENTRO / ALMACÉN</td></tr>%LINEAS_MATERIALES%</table></td></tr></table> </body> ';
}
function mail_interno(){
return ' <body>    
<style> *{font-family: Verdana, Geneva, sans-serif;}</style>
<table style="font-size: 12px"><tr><td>Señores <br><span style="text-decoration: underline;font-weight: bold">MONOMEROS</span></td></tr><tr><td>&nbsp;</td></tr>
<tr><td>Se acaba de realizar un pedido a través del portal comercial, para el cliente 
        <span style=" font-weight: bold">%CLIENTE%</span> </td></tr>
<tr><td>Fecha de creacion: '.date('d/m/Y').'</td></tr>
<tr><td>Solicitud de Pedido: %SOLICITUD_DE_PEDIDO%</td></tr>
<tr><td>Pedido: <span style="text-decoration: underline;">%NUM_PEDIDO_SAP%</span></td></tr>
<tr><td>Tipo de pedido: %TIPO_DE_PEDIDO%</td></tr>
<tr><td>Usuario: %USUARIO_CREADOR%</td></tr>
<tr><td>Comentarios: %COMENTARIOS%</td></tr>
    <tr><td>&nbsp;</td></tr>
<tr><td>Nota: %NOTA_RFC%</td></tr> 
    <tr><td>&nbsp;</td></tr>
                <tr><td><table   style=" font-size: 11px;border-collapse: collapse;border: 2px solid  #23282D; "  >
                            <tr  style="  background-color: #E4E4E4;font-weight: bold ">
                                <td style=" border: 1px solid  #23282D; padding: 2px;">Material</td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px">Precio Unitario</td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px"> Cantidad</td>
                                <td style=" border: 1px solid  #23282D;  padding: 2px"> Centro/Almacén</td>
                            </tr>
                            %LINEAS_MATERIALES%
            </table></td></tr>    
    <tr><td>&nbsp;</td></tr>    
    <tr><td>Por favor realizar la aprobación del mismo y la elaboración de la entrega, de acuerdo a las políticas de crédito de la compañía.</td></tr>
</table></body>';
}

function mail_externo_nueva_pass ($id_sitio){
    $Ecof_moduloTitle_secundario = 'border-radius: 8px 0px 0px 0px; 
   border-top: 1px solid rgb(234, 128, 34);
     border-left: 1px solid rgb(234, 128, 34);    
    height: 30px;
    color: rgb(234, 128, 34);;
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(253, 238, 225);';
    $Ecof_moduloTitle_trc = ' border-top: 1px solid rgb(234, 128, 34);
     border-left: 1px solid rgb(234, 128, 34);    
    height: 30px;
    color: rgb(234, 128, 34);;
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(253, 238, 225);';
    $mcv_moduloTitle_secundario = 'border-radius: 8px 0px 0px 0px;
border: 0px solid rgb(234, 128, 34);
    height: 30px;
    color: rgb(255, 255, 255);
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(12, 132, 68);';
    $mcv_moduloTitle_trc = '
border: 0px solid rgb(234, 128, 34);
    height: 30px;
    color: rgb(255, 255, 255);
    line-height: 25px;
    padding-left: 5px;
    font-size: 17px;
    background-color: rgb(12, 132, 68);';
    $Ecof_ui_widget_content=' border: 1px solid #536675;
    background: #fcfdfd  ;
    color: #505253;
    font-size: 10px';
    $ui_widget_content = '
    border: 1px solid #536675;
    background: #fcfdfd ;
    color: #505253;
     font-size: 10px';
    $mcv_ui_widget_content =' border: 1px solid #536675;
    background: #fcfdfd ;
    color: #505253;
     font-size: 10px';
    $moduloTitle_principal = 'height: 30px;
    color: rgb(0, 133, 63);
    line-height: 25px;
    padding-left: 5px;
    font-size: 20px;';
    $mcv_ui_widget_header='
    border: 1px solid #536675;
    background-color: #338C44 ;
    color: #ffffff;
    font-weight: bold;
    font-size: 10px;
    border-collapse: separate; ';
    $Ecof_ui_widget_header = 'border: 1px solid #536675;
    background-color: #FFFFEA; 
    color: rgb(234, 128, 34);
    font-weight: bold;    
    font-size: 10px;
    border-collapse: separate;   ';
 
$clase1 = $id_sitio.'_moduloTitle_secundario';
$clase2 = $id_sitio.'_moduloTitle_trc';
$clase3 = $id_sitio.'_ui_widget_header'; 
return '<body style ="font-family: Verdana, Geneva, sans-serif; font-size: 12px;" ><table style="width: 100%;font-family: Verdana, Geneva, sans-serif;  font-size: 12px;">'.
       '<tr><td colspan="5" style="'.$moduloTitle_principal.'">Se Acaba de Realizar la Restauracion de Contraseña en su Cuenta, para el cliente %CLIENTE%.</td></tr>'
        .'<tr><td> &nbsp;</td><td colspan="4"><table style="width: 100%"><tr><td colspan="3" style="'.$$clase1.'">Información del cambio .</td></tr>'
        . '<tr><td>&nbsp;</td><td>Fecha:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%FECHA%</td></tr><tr><td>&nbsp;</td><td>Usuario:</td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;%USUARIO%</td></tr>' 
        .'<tr><td>&nbsp;</td><td>Nueva Contraseña:</td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%PASS%</td></tr></table></td></tr>'
        . '</table> </body> ';
}
 