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
     
        $('#tablaDeDatos').find(" [type='text']").val('')       
      }); 
       
      $('#btnIngresar').click(function(){
      var ejecutar = true;
      var arrayRecursos = new Array();
      var contador = 0;
      $('*[data-tipo="recurso"]').each(function(){
          if ($(this).prop('checked') == true){
              arrayRecursos[contador]=$(this).data('recurso_id');
              contador++;
          }
          
      })
     // console.log(arrayRecursos +' - ' + arrayRecursos.length);
      if (arrayRecursos.length <= 0){alert('Debe seleccionar mínimo un recurso!');  ejecutar =false; return ;} 
      if (ejecutar){ 
      var datosAjax = {action:'GUARDAR_RECURSOS_PERFILES',
            Perf_ID : $('#perfil_id').val() ,
            arrayRecursos : arrayRecursos
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
                 msg = 'ERROR  '+error+' ';
                    alert(msg);
                break; 
             }
        }	
    });}
  })
       cargarPerfiles();  
       cargarOpenClose() 
      $('#listadoRecursos').find('input').click(function(){
           var padre = $(this).parent();
           var check =  $(this).prop('checked')
           padre.find(':input').each(function(){
               $('.'+$(this).attr('class')).prop('checked',check); 
             $('#'+$(this).attr('class')+' input').each(function(){
                 $('.'+$(this).attr('class')).prop('checked',check);                  
             } )
              // $('#'+$(this).attr('class')+' input').trigger('click');
           })
       })
      $('#perfil_id').change(function(){
          $('input').prop('checked',false);
          if($(this).val()!='')
          {var datosAjax = {action:'GET_LISTADO_RECURSOS_PERFILES' ,Perf_ID: $(this).val()   };      
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
                            //alert(JSON.stringify(objeto_json))
                               var msg = '';  
                             var error=objeto_json["error"];  
                             if (error ==''){
                                msg = '';  
                                 var datosTabla = objeto_json['datos'];
                                 var auxOptio = '';
                                 var auxDato;
                                for(var i=0; i<datosTabla.length;i++){
                                    auxDato=datosTabla[i];
                                    $('.menu_'+auxDato.id_recurso_sistema).prop('checked',true);
                                 }
                            }else{
                                    msg = 'ERROR  '+error+' ';
                                    alert(msg);
                                }
                        }	
                });
          }
      })
    }
}
 
function cargarOpenClose(){
       $('.nodoAbierto').find('span').unbind('click').click(function(){
          var padre =  $(this).parent();
          padre.find('ul').hide()
           padre.removeClass('nodoAbierto').addClass('nodoCerrado')
           cargarOpenClose()
      })
      $('.nodoCerrado').find('span').unbind('click').click(function(){
          var padre =  $(this).parent();
          padre.find('ul').show()
           padre.removeClass('nodoCerrado').addClass('nodoAbierto')
           cargarOpenClose()
      })
}
function cargarMenus(){ 
     var datosAjax = {action:'GET_LISTADO_PERFILES'      };      
     alert(JSON.stringify(datosAjax));
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
                         //console.log(JSON.stringify(objeto_json))
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
                                    msg = 'ERROR  '+error+' ';
                                    alert(msg);
                                }
                                 
                        }	
                });
}
function cargarPerfiles(){
   // var idMod =  'menuSistema';
     var datosAjax = {action:'GET_LISTADO_PERFILES'  };      
      //alert(JSON.stringify(datosAjax));
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
                          // console.log(JSON.stringify(objeto_json))
                               var msg = '';  
                             var error=objeto_json["error"]; 
                             if (objeto_json['error'] ==''){
                                 msg = '';  
                                 var datosTabla = objeto_json['datos'];
                                 var auxOptio = '';
                                 var auxDato;
                                 $('#perfil_id').html('<option value="">SELECCIONE UN PERFIL</option>  ')
                                 for(var i=0; i<datosTabla.length;i++){
                                    auxDato=datosTabla[i];
                                    auxOptio=$('<option>');
                                    auxOptio.attr('value',auxDato.Perf_ID)
                                    auxOptio.html(" "+auxDato.Perf_Nombre+"  ")
                                    $('#perfil_id').append(auxOptio)
                                 }                             
                            }else{
                                    msg = 'ERROR  '+error+' ';
                                    alert(msg);
                                }
                                 
                        }	
                });
}  
 
 
