<?php 
require_once '../../php/helpers.php'; 
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
     ECHO $nomClass.'<BR>';
    require_once $nomClass;
 });

new Core\Config();
?>

