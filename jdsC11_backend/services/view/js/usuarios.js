 var T_find = new Object();

T_find.CargadorContenidos = function(){
    this.cargador_eventos();
};
T_find.CargadorContenidos.prototype = {
    cargador_eventos: function()	
    {  
      busquedasDinamicas({action:'GET_LISTADO_USUARIOS',url:'view/action/action.php',tipoTabla:4,idMod :'usuarios'
        ,funcion1:function(){ //boton editar...
                                       var padre ;
                                       padre = $(this).parent();  
                                       padre.find('#padre').val();                                         
                                       $('#ID').val(padre.find('#ID').val()); 
                                       $('#Login').val(padre.find('#Login').val()); 
                                       $('#Nombre1').val(padre.find('#Nombre1').val()); 
                                       $('#Nombre2').val(padre.find('#Nombre2').val()); 
                                       $('#Apellido1').val(padre.find('#Apellido1').val()); 
                                       $('#Apellido2').val(padre.find('#Apellido2').val()); 
                                       $('#estado').val(padre.find('#estado').val()); 
                                       $('#pass').val(padre.find('#pass').val()) ; 
                                       $('#cod_remision').val(padre.find('#cod_remision').val()); 
                                       $('#mail').val(padre.find('#mail').val()); 
                                       cargarTablasAreasVenta_usuario(  $('#ID').val() ); 
                                       cargarTablasClientes_usuario( $('#ID').val()); 
                                    },funcion2:function(){},funcion3:function(){}});  
        ///////////////////////////////////////////////// 
      $('#btnCancelar').click(function(){
        cargarTablas(); 
        $('#tablaDeDatos').find(" [type='text']").val('')        
        $('#tablaDeDatos').find(" [type='password']").val('')  
        $('#tablaDeDatos').find(" [type='email']").val('')
        $('#PadreId').val('0')
        var datosAjax = {action:'ELIMINAR_DATOS_TMP_USUARIO_CLIENTES'  };        
        $.ajax({
        url: 'view/action/action.php',  
        type: 'POST',
        async: false,
        dataType: "json",
        data: datosAjax	,
         error: function(xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.status));
                alert(JSON.stringify(ajaxOptions));  
                alert(JSON.stringify(thrownError));},
        beforeSend: function(){        //    alert(JSON.stringify(datosAjax))          
                                }, 
        statusCode: {
                404: function() { alert( "pagina no encontrada" ); },
                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                         },
        success: function(objeto_json) {
            // //console.log(JSON.stringify(objeto_json))
          $('#tablaResultado').show()
               var msg = '';  
             var error=objeto_json["error"]; 
             switch(error){
                 case  'ok':  
                     $('#numClientesRelacionados').val('0')
                 break;
                 
             default  :
                 msg = 'ERROR '+error+' ';
                    alert(msg);
                break; 
             }
        }	
    });
     var datosAjax = {action:'ELIMINAR_DATOS_TMP_USUARIO_AREA_DE_VENTA'  };        
        $.ajax({
        url: 'view/action/action.php',  
        type: 'POST',
        async: false,
        dataType: "json",
        data: datosAjax	,
         error: function(xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.status));
                alert(JSON.stringify(ajaxOptions));  
                alert(JSON.stringify(thrownError));},
        beforeSend: function(){        //    alert(JSON.stringify(datosAjax))          
                                }, 
        statusCode: {
                404: function() { alert( "pagina no encontrada" ); },
                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                         },
        success: function(objeto_json) {
            // //console.log(JSON.stringify(objeto_json))
          $('#tablaResultado').show()
               var msg = '';  
             var error=objeto_json["error"]; 
             switch(error){
                 case  'ok':  
                     $('#numAreasRelacionados').val('0')
                 break;
                 
             default  :
                 msg = 'ERROR '+error+' ';
                    alert(msg);
                break; 
             }
        }	
    });
    var datosAjax = {action:'ELIMINAR_DATOS_TMP_USUARIO_AREA_DE_CONTROL'  };        
        $.ajax({
        url: 'view/action/action.php',  
        type: 'POST',
        async: false,
        dataType: "json",
        data: datosAjax	,
         error: function(xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.status));
                alert(JSON.stringify(ajaxOptions));  
                alert(JSON.stringify(thrownError));},
        beforeSend: function(){        //    alert(JSON.stringify(datosAjax))          
                                }, 
        statusCode: {
                404: function() { alert( "pagina no encontrada" ); },
                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                         },
        success: function(objeto_json) {
            // //console.log(JSON.stringify(objeto_json))
               var msg = '';  
             var error=objeto_json["error"]; 
             switch(error){
                 case  'ok':  
                     $('#numAreaDeControl').val('0')
                 break;
                 
             default  :
                 msg = 'ERROR '+error+' ';
                    alert(msg);
                break; 
             }
        }	
    });
      }); 
      $('#resetPass').click(function(){
           $('#pass').val('');
       });
      $('#btnIngresar').click(function(){
      var ejecutar = true;
      
      if ($('#Nombre1').val()===""){alert('Debe agregar el primer nombre!'); $('#Nombre1').focus();ejecutar =false; return ;}
     // if ($('#Apellido1').val()==""){alert('Debe agregar el primer apellido!');ejecutar =false; $('#Apellido1').focus(); return ;}
      if ($('#Login').val()===""){alert('Debe agregar un nickname!');ejecutar =false; $('#Login').focus(); return ;}
      if ($('#mail').val()===""){alert('Debe agregar un correo electronico!');ejecutar =false; $('#mail').focus(); return ;}
      if (!validarEmail($('#mail').val())){alert('Debe agregar un correo electronico valido!');ejecutar =false; $('#mail').focus(); return ;}
     
    
          
          if (ejecutar){ 
      var datosAjax = {action:'GUARDAR_DATOS_USUARIO',
         ID : $('#ID').val() 
         ,Login : $('#Login').val() 
         ,Nombre1 : $('#Nombre1').val()
         ,Nombre2 : $('#Nombre2').val() 
         ,Apellido1 : $('#Apellido1').val() 
         ,Apellido2 : $('#Apellido2').val() 
         ,estado : $('#estado').val() 
         ,pass : $('#pass').val() 
         ,mail : $('#mail').val() };        
      $.ajax({
        url: 'view/action/action.php',  
        type: 'POST',
        async: false,
        dataType: "json",
        data: datosAjax	,
         error: function(xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.status));
                alert(JSON.stringify(ajaxOptions));  
                alert(JSON.stringify(thrownError));},
        beforeSend: function() {
          console.log(JSON.stringify(datosAjax))
                $('#tablaResultado').hide()
                }, 
        statusCode: {
                404: function() { alert( "pagina no encontrada" ); },
                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                         },
        success: function(objeto_json) {
           console.log(JSON.stringify(objeto_json))
          $('#tablaResultado').show(); 
               var msg = '';  
             var error=objeto_json["error"]; 
             switch(error){
                 case  'ok': 
                      alert('Datos ingresados con exito!');
                     $('#btnCancelar').trigger('click');
                 break;
                 case  'not ok': 
                      msg = 'ERROR no se pudo ingresar la información en la base de datos. ';
                    alert(msg);
                 break;
             default  :
                 msg = 'ERROR '+error+' ';
                    alert(msg);
                break; 
             }
        }	
    });}
  }); 
   }
};

function cargarTablas(  idPadre ){   
    var idMod =  'usuarios';//usuarios_cont_Load_gif
     var datosAjax = {action:'GET_LISTADO_USUARIOS',idUsuario : idPadre,cabeceras:idMod};     
     $('#'+idMod+'_cont_Load_gif').show(function(){
                        $.ajax({
                        url: 'view/action/action.php',  
                        type: 'POST',
                        async: false,
			dataType: "json",
                        data: datosAjax	,
                         error: function(xhr, ajaxOptions, thrownError) {
                                alert(JSON.stringify(xhr.status));
                                alert(JSON.stringify(ajaxOptions));  
                                alert(JSON.stringify(thrownError));},
                        beforeSend: function() {
				 ////console.log(JSON.stringify(datosAjax))
                                $('#tablaResultado').hide();
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                          $('#tablaResultado').show();
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             if (objeto_json['error'] === ''){ 
                               
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac'+idMod,'tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,4,15,false,false,false,false,
                               function(){ //boton editar...
                                       var padre ;
                                       padre = $(this).parent(); 
                                       padre.find('#padre').val();                                         
                                       $('#ID').val(padre.find('#ID').val()); 
                                       $('#Login').val(padre.find('#Login').val()); 
                                       $('#Nombre1').val(padre.find('#Nombre1').val()); 
                                       $('#Nombre2').val(padre.find('#Nombre2').val()); 
                                       $('#Apellido1').val(padre.find('#Apellido1').val()); 
                                       $('#Apellido2').val(padre.find('#Apellido2').val()); 
                                       $('#estado').val(padre.find('#estado').val()); 
                                       $('#pass').val(padre.find('#pass').val()) ; 
                                       $('#cod_remision').val(padre.find('#cod_remision').val()); 
                                       $('#mail').val(padre.find('#mail').val()); 
                                       /*cargarTablasAreasVenta_usuario(  $('#ID').val() )
                                       cargarTablasClientes_usuario( $('#ID').val())
                                       cargarTablasAreasDeControl_usuario($('#ID').val());*/
                                    },false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                            $('#'+idMod+'_cont_Load_gif').hide()  ;    
                        }	
                });});
}
/* 
function cargarTablasAreasDeVentaPorCentroLogistico(  idPadre ){   
    var idMod =  'tabla_SAP_rfc_Areasdeventa';
    $('#busqueda'+idMod).find('#busqueda_dinamica_20170510').val('');
      $('#busqueda'+idMod).find('#col_busqueda_dinamica_20170510').val('*');
     var datosAjax = {action:'GET_LISTADO_AREAS_DE_VENTAS_CON_CENTRO_LOG',cod_usuario : idPadre,cabeceras:idMod};   
     $('#'+idMod+'_cont_Load_gif').show(function(){
                   $.ajax({
                        url: 'view/action/action.php',  
                        type: 'POST',
                        async: false,
			dataType: "json",
                        data: datosAjax	,
                         error: function(xhr, ajaxOptions, thrownError) {
                             alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                        beforeSend: function() {                                 
                           
                                $('#tablaResultado').hide()
                                    //console.log('datos enviados datosAjax: '+JSON.stringify(datosAjax))
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) { 
                             //console.log('datos recibidos objeto_json: '+JSON.stringify(objeto_json))
                          $('#tablaResultado').show()
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             if (objeto_json['error'] ==''){ 
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac'+idMod,'tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,0,25,false,false,false,false,
                              function(){ //boton editar...
                                       var padre = $(this) 
                                       var datosJson = { 
                                            SPART : padre.find('#SPART').val(),
                                            VKORG : padre.find('#VKORG').val(),
                                            VTWEG : padre.find('#VTWEG').val(),
                                            ID_cliente :idPadre,
                                            estado:'A',
                                            action:'GUARDAR_DATOS_USUARIO_AREAS_DE_VENTA'
                                       }
                                       $.ajax({
                                            url: 'view/action/action.php',  
                                            type: 'POST',
                                            async: false,
                                            dataType: "json",
                                            data: datosJson	,
                                             error: function(xhr, ajaxOptions, thrownError) {
                                                    alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                                            beforeSend: function() { 
                                                    $('#tablaResultado').hide()
                                                    }, 
                                            statusCode: {
                                                    404: function() { alert( "pagina no encontrada" ); },
                                                    408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                                             },
                                            success: function(new_objeto_json) {
                                                   ////console.log('datos recibidos new_objeto_json: '+JSON.stringify(new_objeto_json))
                                                    cargarTablasAreasVenta_usuario(  idPadre ) 
                                                    //quitar al padre del llamado. 
                                                    if (new_objeto_json['error'] =='ok'){ 
                                                                           padre.remove()
                                                                       }else{
                                                                           alert(new_objeto_json['error'])
                                                                       }
                                            }});
                                    } 
                                    ,false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                              $('#'+idMod+'_cont_Load_gif').hide()    
                        }	
                });})
}

function cargarTablasClientes(  idPadre ){   
    var idMod =  'SAP_clientes_con_mail';
     $('#busqueda'+idMod).find('#busqueda_dinamica_20170510').val('');
      $('#busqueda'+idMod).find('#col_busqueda_dinamica_20170510').val('*');
     var datosAjax = {action:'GET_LISTADO_CLIENTES_CON_MAIL_RELACIONADO',idUsuario : idPadre,cabeceras:idMod};       
           $('#'+idMod+'_cont_Load_gif').show(function(){
               $.ajax({
                        url: 'view/action/action.php',  
                        type: 'POST',
                        async: false,
			dataType: "json",
                        data: datosAjax	,
                         error: function(xhr, ajaxOptions, thrownError) {
                             alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                        beforeSend: function() {
				// //console.log(JSON.stringify(datosAjax))                                 
                            $('#'+idMod+'_cont_Load_gif').show()
                                $('#tablaResultado').hide()
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                            // console.log(JSON.stringify(objeto_json))
                          $('#tablaResultado').show()
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             if (objeto_json['error'] ==''){ 
                              //  $('#tablaResultado').html(  )                               
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac'+idMod,'tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,0,25,false,false,false,false,
                               function(){ //boton editar...
                                   
                                 // alert( this.datosParaEnvio.nombreCabecera)
                                       var padre = $(this) 
                                       var datosJson = { KUNNR : padre.find('#id_cliente_SAP').val(),
                                           FULLNAME : padre.find('#nom_cliente_SAP').val(),
                                           ID_cliente :idPadre,
                                           estado:'A',
                                           action:'GUARDAR_DATOS_USUARIO_CLIENTES'
                                       }
                                       var loadinId = '#'+this.datosParaEnvio.nombreCabecera+'_cont_Load_gif'
                                       $.ajax({
                                            url: 'view/action/action.php',  
                                            type: 'POST',
                                            async: false,
                                            dataType: "json",
                                            data: datosJson	,
                                             error: function(xhr, ajaxOptions, thrownError) {
                                                    alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                                            beforeSend: function() {
                                              //    //console.log(JSON.stringify(datosJson))
                                                   $(loadinId).show()
                                                   $('#tablaResultado').hide()
                                                    }, 
                                            statusCode: {
                                                    404: function() { alert( "pagina no encontrada" ); },
                                                    408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                                             },
                                            success: function(new_objeto_json) {
                                                  //console.log(JSON.stringify(new_objeto_json))
                                                    cargarTablasClientes_usuario(  idPadre )                                                      
                                                    if (new_objeto_json['error'] =='ok'){ 
                                                                           padre.remove() }
                                                                       $(loadinId).hide()
                                                                       
                                            }});
                                    },false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                              $('#'+idMod+'_cont_Load_gif').hide()   
                        }	
                });
                
            });
}

function cargarTablasClientes_usuario(  idPadre ){   
    var idMod =  'relacion_cliente_usuarios';
    $('#busqueda'+idMod).find('#busqueda_dinamica_20170510').val('');
      $('#busqueda'+idMod).find('#col_busqueda_dinamica_20170510').val('*');
     var datosAjax = {action:'GET_LISTADO_CLIENTES_POR_USUARIO',idUsuario : idPadre,cabeceras:idMod};   
     var contenedor_load = '#'+idMod+'_cont_Load_gif'
     $(contenedor_load).show(function(){                 
                $.ajax({
                        url: 'view/action/action.php',  
                        type: 'POST',
                        async: false,
			dataType: "json",
                        data: datosAjax	,
                         error: function(xhr, ajaxOptions, thrownError) {
                                alert(JSON.stringify(xhr.status));
                                alert(JSON.stringify(ajaxOptions));  
                                alert(JSON.stringify(thrownError));},
                        beforeSend: function() {
				// //console.log(JSON.stringify(datosAjax))
                                $('#tablaResultado').hide()
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                           // //console.log(JSON.stringify(objeto_json))
                          $('#tablaResultado').show()
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             $('#num_mail_principal_cliente').val('0')
                             if (objeto_json['error'] ==''){ 
                              //  $('#tablaResultado').html(  )    
                             $('#numClientesRelacionados').val(objeto_json['filas'])
                             var datos =objeto_json['datos'];
                                var i = 0;
                                for (i = 0 ; i< objeto_json['filas'];i++)
                                {   var numero =  parseInt($('#num_mail_principal_cliente').val())                                    
                                    $('#num_mail_principal_cliente').val(numero + parseInt(datos[i].num_mail_principal));
                                }
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac'+idMod,'tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,1,15,false,false,false,false,
                               function(){ //boton editar...
                                  //alert( this.datosParaEnvio.nombreCabecera)
                                       var padre ;
                                       padre = $(this).parent()  
                                       var datoEliminar = padre.find('#id_cliente_SAP').val()
                                       if (idPadre !=''){datoEliminar = padre.find('#id_relacion').val()} 
                                       var datosJson = { dato_eliminar : datoEliminar , 
                                           id_usuario : idPadre, 
                                           action:'ELIMINAR_DATOS_USUARIO_CLIENTES'
                                       }
                                       var eliminar = true
                                        if(idPadre != "" && $('#numClientesRelacionados').val()== '1'){eliminar =  false;}
                                       // eliminar =  false;
                                       if(eliminar){
                                       $.ajax({
                                            url: 'view/action/action.php',  
                                            type: 'POST',
                                            async: false,
                                            dataType: "json",
                                            data: datosJson	,
                                             error: function(xhr, ajaxOptions, thrownError) {
                                                    alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                                            beforeSend: function() {
                                                    //console.log(JSON.stringify(datosJson))
                                                   $('#'+idMod+'_cont_Load_gif').show()
                                                   $('#tablaResultado').hide()
                                                    }, 
                                            statusCode: {
                                                    404: function() { alert( "pagina no encontrada" ); },
                                                    408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                                             },
                                            success: function(new_objeto_json) {
                                                   //  console.log(JSON.stringify(new_objeto_json))
                                                    cargarTablasClientes_usuario(  idPadre )                                                      
                                                    if (new_objeto_json['error'] ==''){ 
                                                                           padre.remove() }
                                            }});
                                        }else{alert('El usuario no debe quedar sin ninguna relacion con clientes')}
                                       
                                    },false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                               $(contenedor_load).hide()   
                        }	
                }                       );
                }); 
}

function cargarTablasAreasVenta_usuario(  idPadre ){   
    var idMod =  'relacion_usuario_area_venta';
    $('#busqueda'+idMod).find('#busqueda_dinamica_20170510').val('');
      $('#busqueda'+idMod).find('#col_busqueda_dinamica_20170510').val('*');
     var datosAjax = {action:'GET_LISTADO_AREAS_DE_VENTA_POR_USUARIO',idUsuario : idPadre,cabeceras:idMod};  
     $('#'+idMod+'_cont_Load_gif').show(function(){
     $.ajax({
                        url: 'view/action/action.php',  
                        type: 'POST',
                        async: false,
			dataType: "json",
                        data: datosAjax	,
                         error: function(xhr, ajaxOptions, thrownError) {
                                alert(JSON.stringify(xhr.status));
                                alert(JSON.stringify(ajaxOptions));  
                                alert(JSON.stringify(thrownError));},
                        beforeSend: function() {
				 //console.log(JSON.stringify(datosAjax))
                            $('#'+idMod+'_cont_Load_gif').show()
                                $('#tablaResultado').hide()
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                        //    //console.log(JSON.stringify(objeto_json))
                          $('#tablaResultado').show()
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             if (objeto_json['error'] ==''){ 
                              //  $('#tablaResultado').html(  )    
                             $('#numAreasRelacionados').val(objeto_json['filas'])
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac'+idMod,'tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,1,15,false,false,false,false,
                               function(){ //boton editar...
                                       var padre ;
                                       padre = $(this).parent()  
                                       // concat(  VKORG, VTWEG, SPART) ='ECONVPE' 
                                       
                                       var datoEliminar = padre.find('#VKORG').val()+padre.find('#VTWEG').val()+padre.find('#SPART').val()
                                       if (idPadre !=''){datoEliminar = padre.find('#id_relacion').val()} 
                                       var datosJson = { dato_eliminar : datoEliminar , 
                                           id_usuario : idPadre, 
                                           action:'ELIMINAR_DATOS_USUARIO_AREAS_DE_VENTA'
                                       }
                                       var eliminar = true
                                        if(idPadre != "" && $('#numAreasRelacionados').val()== '1'){eliminar =  false;}
                                       if(eliminar){
                                       $.ajax({
                                            url: 'view/action/action.php',  
                                            type: 'POST',
                                            async: false,
                                            dataType: "json",
                                            data: datosJson	,
                                             error: function(xhr, ajaxOptions, thrownError) {
                                                    alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                                            beforeSend: function() {
                                                 //   //console.log(JSON.stringify(datosJson))
                                                   $('#'+idMod+'_cont_Load_gif').show()
                                                   $('#tablaResultado').hide()
                                                    }, 
                                            statusCode: {
                                                    404: function() { alert( "pagina no encontrada" ); },
                                                    408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                                             },
                                            success: function(new_objeto_json) {
                                                     ////console.log(JSON.stringify(new_objeto_json))
                                                    cargarTablasAreasVenta_usuario(  idPadre )                                                      
                                                    if (new_objeto_json['error'] =='ok'){ 
                                                                           padre.remove() }
                                            }});
                                        }else{alert('El usuario no debe quedar sin ninguna relacion con clientes')}
                                       
                                    },false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                            $('#'+idMod+'_cont_Load_gif').hide()     
                        }	
                });})
}

function cargarTablasAreasDeControl(  idPadre ){
    var idMod =  'areas_de_control';
    $('#busqueda'+idMod).find('#busqueda_dinamica_20170510').val('');
      $('#busqueda'+idMod).find('#col_busqueda_dinamica_20170510').val('*');
    var datosAjax = {action:'GET_LISTADO_AREAS_DE_CONTROL',idUsuario : idPadre,cabeceras:idMod};       
     $('#'+idMod+'_cont_Load_gif').show(function(){   
       $.ajax({
                        url: 'view/action/action.php',  
                        type: 'POST',
                        async: false,
			dataType: "json",
                        data: datosAjax	,
                         error: function(xhr, ajaxOptions, thrownError) {
                                alert(JSON.stringify(xhr.status));
                                alert(JSON.stringify(ajaxOptions));  
                                alert(JSON.stringify(thrownError));},
                        beforeSend: function() {
				// console.log(JSON.stringify(datosAjax))
                                                            
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                           console.log(JSON.stringify(objeto_json))
                          $('#tablaResultado').show()
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             $('#numAreaDeControl').val('0')
                             if (objeto_json['error'] ==''){ 
                              //  $('#tablaResultado').html(  )    
                             $('#numAreaDeControl').val(objeto_json['filas'])
                             var datos =objeto_json['datos'];
                                var i = 0;
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac'+idMod,'tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,0,15,false,false,false,false,
                               function(){ //boton editar...
                                  // alert( this.datosParaEnvio.nombreCabecera) 
                                        var padre = $(this) 
                                        var datosJson = { ACC : padre.find('#ACC').val(),
                                               DENOMINACION : padre.find('#DENOMINACION').val(),
                                               ID_cliente :idPadre,
                                               estado:'A',
                                               action:'GUARDAR_DATOS_USUARIO_AREAS_DE_CONTROL'
                                           }
                                        $.ajax({
                                                url: 'view/action/action.php',  
                                                type: 'POST',
                                                async: false,
                                                dataType: "json",
                                                data: datosJson	,
                                                 error: function(xhr, ajaxOptions, thrownError) {
                                                        alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                                                beforeSend: function() {
                                                      console.log(JSON.stringify(datosJson)) 
                                                       $('#tablaResultado').hide()
                                                        }, 
                                                statusCode: {
                                                        404: function() { alert( "pagina no encontrada" ); },
                                                        408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                                                 },
                                                success: function(new_objeto_json) {
                                                      console.log(JSON.stringify(new_objeto_json))
                                                        cargarTablasAreasDeControl_usuario(  idPadre )                                                      
                                                        if (new_objeto_json['error'] =='ok'){ 
                                                                               padre.remove() }
                                                                           $('#'+idMod+'_cont_Load_gif').hide()
                                                }});
                                    },false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                               $('#'+idMod+'_cont_Load_gif').hide()  
                        }	
                }                       );
     });          
}
function cargarTablasAreasDeControl_usuario(  idPadre ){   
    var idMod =  'relacion_areasDeControl_usuarios';
    $('#busqueda'+idMod).find('#busqueda_dinamica_20170510').val('');
      $('#busqueda'+idMod).find('#col_busqueda_dinamica_20170510').val('*');
     var datosAjax = {action:'GET_LISTADO_AREAS_DE_CONTROL_POR_USUARIO',idUsuario : idPadre,cabeceras:idMod};       
     $('#'+idMod+'_cont_Load_gif').show(function(){                 
                $.ajax({
                        url: 'view/action/action.php',  
                        type: 'POST',
                        async: false,
			dataType: "json",
                        data: datosAjax	,
                         error: function(xhr, ajaxOptions, thrownError) {
                                alert(JSON.stringify(xhr.status));
                                alert(JSON.stringify(ajaxOptions));  
                                alert(JSON.stringify(thrownError));},
                        beforeSend: function() {
				// //console.log(JSON.stringify(datosAjax))
                                                           $('#tablaResultado').hide()
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                           // //console.log(JSON.stringify(objeto_json))
                          $('#tablaResultado').show()
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             $('#numAreaDeControl').val('0')
                             if (objeto_json['error'] ==''){ 
                              //  $('#tablaResultado').html(  )    
                             $('#numAreaDeControl').val(objeto_json['filas'])
                             var datos =objeto_json['datos'];
                                var i = 0;
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac'+idMod,'tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,1,15,false,false,false,false,
                               function(){ //boton editar...
                                  // alert( this.datosParaEnvio.nombreCabecera)
                                       var padre ;
                                       padre = $(this).parent()  
                                       var datoEliminar = padre.find('#codAreasControl').val()
                                       if (idPadre !=''){datoEliminar = padre.find('#id').val()} 
                                       var datosJson = { dato_eliminar : datoEliminar , 
                                           id_usuario : idPadre, 
                                           action:'ELIMINAR_DATOS_USUARIOS_AREAS_DE_CONTROL'
                                       }
                                       var eliminar = true
                                        if(idPadre != "" && $('#numAreaDeControl').val()== '1'){eliminar =  false;}
                                       // eliminar =  false;
                                       if(eliminar){
                                           $('#'+idMod+'_cont_Load_gif').show(function(){
                                               $.ajax({
                                            url: 'view/action/action.php',  
                                            type: 'POST',
                                            async: false,
                                            dataType: "json",
                                            data: datosJson	,
                                             error: function(xhr, ajaxOptions, thrownError) {
                                                    alert(JSON.stringify(xhr.status)+'  -  '+JSON.stringify(ajaxOptions)+'  -  '+JSON.stringify(thrownError));},
                                            beforeSend: function() {
                                                   // //console.log(JSON.stringify(datosJson))
                                                   
                                                   $('#tablaResultado').hide()
                                                    }, 
                                            statusCode: {
                                                    404: function() { alert( "pagina no encontrada" ); },
                                                    408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                                             },
                                            success: function(new_objeto_json) {
                                                    console.log(JSON.stringify(new_objeto_json))
                                                    cargarTablasClientes_usuario(  idPadre )                                                      
                                                    if (new_objeto_json['error'] =='ok'){ 
                                                                           padre.remove() }
                                                                 $('#'+idMod+'_cont_Load_gif').hide()
                                            }});
                                           })
                                       
                                        }else{alert('El usuario no debe quedar sin ninguna area de control')}
                                       
                                    },false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                               $('#'+idMod+'_cont_Load_gif').hide()  
                        }	
                }                       );
                }); 
}
*/



