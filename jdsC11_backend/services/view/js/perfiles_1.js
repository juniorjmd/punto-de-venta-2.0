 var T_find = new Object();

T_find.CargadorContenidos = function(){
    this.cargador_eventos();
}
T_find.CargadorContenidos.prototype = {
    cargador_eventos: function()	
    {  
      $('.mcv_btnIngresar').addClass('button') 
      $('.Ecof_btnIngresar').addClass('button_eco') 
      
      $('#btnCancelar').click(function(){
        cargarTablas(); 
        $('#tablaDeDatos').find(" [type='text']").val('')       
      }); 
       
      $('#btnIngresar').click(function(){
      var ejecutar = true;
      if ($('#Nombre').val()==""){alert('Debe agregar el nombre!'); $('#Nombre').focus();ejecutar =false; return ;} 
      if (ejecutar){ 
      var datosAjax = {action:'GUARDAR_DATOS_PERFILES',
            Perf_ID : $('#ID').val() ,
            Perf_Nombre : $('#Nombre').val() ,
            estado : $('#estado').val()                   
              };        
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
            // alert(JSON.stringify(datosAjax))
                $('#tablaResultado').hide()
                }, 
        statusCode: {
                404: function() { alert( "pagina no encontrada" ); },
                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                         },
        success: function(objeto_json) {
          //  alert(JSON.stringify(objeto_json))
          $('#tablaResultado').show()
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
  })
      
      cargarTablas(); 
       
    }

}

function cargarTablas(  idPadre ){   
    var idMod =  'perfiles';
     var datosAjax = {action:'GET_LISTADO_PERFILES',idPerfil : idPadre,cabeceras:idMod};      
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
				 //alert(JSON.stringify(datosAjax))
                                $('#tablaResultado').hide()
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                          //  alert(JSON.stringify(objeto_json))
                          $('#tablaResultado').show()
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             if (objeto_json['error'] ==''){ 
                              //  $('#tablaResultado').html(  ) 
                               
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac','tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,2,15,false,false,false,false,
                               function(){ //boton editar...
                                       var padre ; 
                                       padre = $(this).parent().parent() 
                                     var eliminar =  confirm('Realmente desea eliminar el perfil <<"'+padre.find('#Perf_Nombre').val()+'">>!' );
                                     if (eliminar){
                                         eliminarRegistro(padre.find('#Perf_ID').val())
                                     }
                                     
                               },false,function(){ //boton editar...
                                       var padre ; 
                                       padre = $(this).parent().parent()
                                       padre.find('#padre').val();                                         
                                       $('#ID').val(padre.find('#Perf_ID').val())
                                       $('#estado').val(padre.find('#estado').val())
                                       $('#Nombre').val(padre.find('#Perf_Nombre').val())
                                       
                                       
                                    },false,false,false);
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
function eliminarRegistro(Perf_ID){
   
      var datosAjax = {action:'VERIFICAR_Y_ELIMINAR_PERFIL', Perf_ID : Perf_ID   };        
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
            // alert(JSON.stringify(datosAjax))
                $('#tablaResultado').hide()
                }, 
        statusCode: {
                404: function() { alert( "pagina no encontrada" ); },
                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                         },
        success: function(objeto_json) {
         // alert(JSON.stringify(objeto_json))
          $('#tablaResultado').show()
               var msg = '';  
             var error=objeto_json["error"]; 
             switch(error){
                  case 'ok' :
                         msg = 'Menú eliminado con exito!'; 
                         cargarTablas();
                     break;
                 case '_table':
                        msg = 'ERROR error al enviar el nombre de la tabla ';
                     break;
                     case '_dato':
                          msg = 'ERROR error al enviar el identificador en la tabla del registro ';
                     break;
                     case '_COLUMNA':
                          msg = 'ERROR error al envia el nombre de la columna identificadora del registro. ';
                     break;
                     default:
                         msg = 'ERROR '+error+' ';
                         cargarTablas();
                     break;
             }
              alert(msg)
        }	
    }); 
}
