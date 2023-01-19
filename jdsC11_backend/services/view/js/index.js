 /*
CSS Browser Selector v0.2.7
Rafael Lima (http://rafael.adm.br)
http://rafael.adm.br/css_browser_selector
License: http://creativecommons.org/licenses/by/2.5/
Contributors: http://rafael.adm.br/css_browser_selector#contributors
*/
var css_browser_selector = function() {
var ua=navigator.userAgent.toLowerCase(),
is=function(t){return ua.indexOf(t) != -1;},
h=document.getElementsByTagName('html')[0],
b=(!(/opera|webtv/i.test(ua))&&/msie (\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?'gecko ff2':is('firefox/3')?'gecko ff3':is('gecko/')?'gecko':is('opera/9')?'opera opera9':/opera (\d)/.test(ua)?'opera opera'+RegExp.$1:is('konqueror')?'konqueror':is('applewebkit/')?'webkit safari':is('mozilla/')?'gecko':'',
os=(is('x11')||is('linux'))?' linux':is('mac')?' mac':is('win')?' win':'';
var c=b+os+' js'; h.className += h.className?' '+c:c;
}();
  
$(document).ready(function(){        
      	$('#CambioPass').click(function(e){ 
             e.preventDefault()
             $('#cargarDatos').load('view/'+$(this).data('url'),function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success"){
                        var cargadorLista= new T_find.CargadorContenidos();}
                        if(statusTxt == "error")
                               alert("Error: " + xhr.status + ": " + xhr.statusText);
                                });              
         });
         $('.menuHijo').click(function(e){ 
             e.preventDefault()
             if( $(this).data('url') != ''){
                 $('#cargarDatos').load('view/'+$(this).data('url'),function(responseTxt, statusTxt, xhr){
                     
                 if(statusTxt == "success") 
                    var cargadorLista= new T_find.CargadorContenidos();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);
                    });
                }
              
         });
         $('#cargarDatos').load('view/inicio.php',function(responseTxt, statusTxt, xhr){
              
            if(statusTxt == "success")
             //   alert("External content loaded successfully!");
            var cargadorLista= new T_find.CargadorContenidos();
            if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
             });
         $('#salidaSegura').click(function(){
             var confimar = confirm('Esta seguro que desea salir?')
             if (confimar){
                 window.location.href ='../'
             }
         })
        //  $('#cargarDatos').niceScroll({cursorborder:"",cursorr:"#a6c9e2"});
   })//mcv Ecof

