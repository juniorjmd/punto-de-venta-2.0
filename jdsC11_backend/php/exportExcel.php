<?php 
    header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
    header("Content-Disposition: filename=".$_POST['nombre_reporte'].".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    include 'helpers.php';
    echo utf8_decode($_POST['datos_a_enviar']);

    