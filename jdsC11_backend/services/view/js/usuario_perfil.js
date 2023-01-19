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
      if ($('#perfil_id').val()==""){alert('Debe seleccionar un perfil de usuario!'); $('#perfil_id').focus();ejecutar =false; return ;} 
      if (ejecutar){ 
      var datosAjax = {action:'GUARDAR_RELACION_PERFILES_USUARIO',
          idRelacion :$('#idRelacion').val(),          
            user_id : $('#user_id').val() ,
            perfil_id : $('#perfil_id').val()                 
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
      cargarPerfiles();
      cargarTablas(); 
       
    }

}
function cargarPerfiles(){
    var idMod =  'menuSistema';
     var datosAjax = {action:'GET_LISTADO_PERFILES' ,    };      
     // alert(JSON.stringify(datosAjax));
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
				}, 
                        statusCode: {
                                404: function() { alert( "pagina no encontrada" ); },
                                408: function() {alert( "Tiempo de espera agotado. la peticion no se realizo " ); } 
                                         },
                        success: function(objeto_json) {
                         //   alert(JSON.stringify(objeto_json))
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             if (objeto_json['error'] ==''){
                                 msg = '';  
                                 var datosTabla = objeto_json['datos'];
                                 var auxOptio = '';
                                 var auxDato;
                                 $('#perfil_id').html('<option value="">SELECCIONE UN PERFIL</option>')
                                 for(var i=0; i<datosTabla.length;i++){
                                    auxDato=datosTabla[i];
                                    auxOptio=$('<option>');
                                    auxOptio.attr('value',auxDato.Perf_ID)
                                    auxOptio.html(" "+auxDato.Perf_Nombre+"  ")
                                    $('#perfil_id').append(auxOptio)
                                 }
                             
                            }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                                 
                        }	
                });
}
function cargarTablas(  idPadre ){   
    var idMod =  'perfiles_usuarios';
     var datosAjax = {action:'GET_LISTADO_PERFILES_USUARIOS',idPerfil : idPadre,cabeceras:idMod};    
     $('#'+ idMod+'_cont_Load_gif').show(function(){
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
                               
                              var cargadorLista= new cont_table.CargadorContenidos(objeto_json,objeto_json["cabeceras"],'normalNewPac','tablasLista'+idMod,'indiceLista'+idMod,'listarTabla'+idMod,4,15,false,false,false,false,
                               function(){ //boton editar...
                                       var padre ; 
                                       padre = $(this).parent() 
                                       padre.find('#padre').val();                                         
                                       $('#idRelacion').val(padre.find('#idRelacion').val())
                                       $('#user_id').val(padre.find('#user_id').val())
                                       $('#nombreCompleto').val(padre.find('#nombreCompleto').val())
                                       $('#perfil_id').val(padre.find('#perfil_id').val()) 
                                     },false,false,false,false,false);
                                   // $('.imagen1').attr('src','')
                                   // $('.imagen2').attr('src','')
                                }else{
                                    msg = 'ERROR '+error+' ';
                                    alert(msg);
                                }
                              $('#'+ idMod+'_cont_Load_gif').hide()   
                        }	
                });});
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
