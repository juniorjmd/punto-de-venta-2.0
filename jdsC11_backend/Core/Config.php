<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core;

/**
 * Description of config
 *
 * @author jdominguez
 */
class Config {
    //put your code here
    public function __construct() { 
        // Desactivar toda notificación de error
        //error_reporting(0);

// Notificar solamente errores de ejecución
error_reporting(E_ERROR | E_WARNING | E_PARSE);

        date_default_timezone_set("America/Bogota"); 
        define('DB_TYPE', 'mysql');          
      
        
        // echo 'si esta';
        define('PASS_INICIAL','jds_pass1');  
        //definicion  de coneccion a SAP 
       
        //define('URL_BASE','http://jds_mc.web'  );
        
        define('NOMBRE_SESSION','jds2020_PRINCIPAL_LOCAL'. date(DATE_ATOM)  );
        
        define('DESC_SUC_PRINCIPAL',sha1('JDS_SUCURSAL_PRINCIPAL')  );
        /**********************************************************************************/
       // define('APYKEYODOO', '26e83273e7a356255552a363ef521c3a3d04e238'   );
        define('APYKEYODOO', 'cobegisa2021'   );   
      //  define('URL_ODOO', 'https://cobegisa.odoo.com/'   ); 
        define('URL_ODOO', 'https://cobegisa-pruebas-0211-4434633.dev.odoo.com'   ); 
          define('DB_ODOO', 'cobegisa-pruebas-0211-4434633'   );
        define('USER_ODOO', 'juniorjmd@gmail.com'  );  
        /**********************************************************************************/
        
        
        
                /*local*/
         if ( strtoupper($_SERVER['HTTP_HOST'])=='LOCALHOST')   {
        define('DB_HOST', 'localhost'); 
        define('DB_NAME_INICIO', 'jds_mc_20180708');         
        define('DB_USER', 'root');
        define('DB_PASS', 'juniorjmd');
        define('URL_BASE','http://localhost/mad_colombia/'  );   
         /*local*/
        }ELSE{
        /*web*/            
        define('DB_HOST', 'jdpsoluciones.com'); 
        define('DB_NAME_INICIO', 'jdpsoluc_snbx_la11_v2');         
        define('DB_USER', 'jdpsoluc_c11_snbx');
        define('DB_PASS', 'Prom2001josdom*');
        define('URL_BASE','http://jdsc11.back.jdpsoluciones.com/'); 
            
            
            
            
      /*  define('DB_HOST', 'jdpsoluciones.com'); 
        define('DB_NAME_INICIO', 'jdpsoluc_car_wash');         
        define('DB_USER', 'jdpsoluc_car_wash');
        define('DB_PASS', 'Prom2001josdom*');
        define('URL_BASE','http://jdsc11.back.jdpsoluciones.com/');*/
         /*web*/
        
       }
        DEFINE("LOGO_USUARIO1", URL_BASE."/images/jds_ico.png");
        define("SERV_CORREO","");        
        //define ('PHP_OS', "Windows");
        ini_set('session_save_path', '/home/'.NOMBRE_SESSION.'/tmp');
        session_name(NOMBRE_SESSION);
        if(@session_start() == false){session_destroy();session_start(); }ELSE{
            
            foreach ($_SESSION as $key => $value) {
                IF (trim($key) !== 'pass')
                define(strtoupper($key) ,$value)  ;
            }
        }
        
    }
}
