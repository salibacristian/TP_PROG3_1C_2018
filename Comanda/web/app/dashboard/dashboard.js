// var servidor="http://bpdda.esy.es/comanda/backEnd/";
var servidor="http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/";

var dominioActual ='';
function dibujarTabla(lista){
    var rows = '';
    lista.forEach(c => {
        let icon = c.esParaDiscapacitados == 1? "<i class='fa fa-wheelchair' id='wheelchairIcon'></i>" : '';
        rows += "<tr onclick='cargarCocheraModal(" + c.id + ")'>" +
        "<td>" + c.piso + "</td>" +
        "<td>" + c.numero + "</td>" +
        "<td>" + c.dominio + "</td>" +        
        "<td>" + icon + "</td>" +
       " </tr>";
    });
    
        $("#cocherasEnUso").html(rows);

}

function ingresar(){

    let dominio = $("#dominio").val();
    let marca =$("#marca").val();
    let color = $("#color").val();
    let foto = $("#fotoCargada").val();
    let cocheraId = $("#selectCochera").val();
    $.ajax({
        type: "post",
       url: servidor+"Operacion/",
       data: {
           dominio: dominio,
           marca: marca, 
           color: color ,
           foto: foto,
           cocheraId: cocheraId 
       }
          
   })
   .then(function(retorno){		
       if(retorno.mensaje == 'Exito')
            swal(retorno.mensaje,'','success');
       else swal(retorno.mensaje,'','error');
   
   },function(error){
       swal({
     title: "Error",
     text: "Hubo un error al ingresar el vehiculo",
     type: "error",
     showCancelButton: false,
     cancelButtonClass: "btn-info",
     cancelButtonText: "cerrar"
   });
   });
}

function ingreso(){
    //buscar cocheras libres
    $.ajax({
        type: "get",
       url: servidor+"cocheras/",
       data: {
           libres: 1
       }
          
   })
   .then(function(retorno){		
    console.log(retorno);

    retorno.forEach(c => {
        $("#selectCocheras").append("<option value='"+ c.id +"'>"+c.numero+" piso "+c.piso+"</option>");
    });
        
        $("#popUpIngreso").modal();
   },function(error){
       swal({
        title: "Error",
        text: "Hubo un error al obtener las cocheras",
        type: "error",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    });
   });
   
    
}

function strToDate(dateString){
    let date = dateString.split(' ')[0];
    let hourAndMin = dateString.split(' ')[1];
    let day = date.split('/')[0];
    let month = date.split('/')[1];
    let year = date.split('/')[2];
    let hour = hourAndMin.split(':')[0];
    let min = hourAndMin.split(':')[1];

    return new Date(year,(month-1),day,hour,min);
}

function diff_hours(dt2, dt1) {
 var diff =(dt2.getTime() - dt1.getTime()) / 1000;
 diff /= (60 * 60);
 return Math.abs(Math.round(diff));
 
}

function traerOperacion(dominio){
    $.ajax({
        type: "get",
       url: servidor+"Operacion/operacion",
       data: {
           dominio: dominio
       }
          
   })
   .then(function(retorno){	
       if(retorno.dominio == dominio){
           dominioActual = dominio;
       let info = retorno.dominio + ' ' + retorno.marca + ' ' + retorno.color;
       let imgUrl = "../../../backEnd/fotosVehiculos/" + retorno.foto;
    $(".operacionInfo").html(info); 
    $(".vehiculoImg").html("<img alt='Sin foto' src=" + imgUrl + "></img>"); 

    let date = strToDate(retorno.fecha_hora_ingreso);
    $(".operacionIngreso").html(retorno.fecha_hora_ingreso); 
    var hours = diff_hours(new Date(),date);
    $(".tiempoAcum").html(hours + " horas");     
         
       }
   $("#spinnerGif").hide();
   $("#cocheraModalBody").show();
   
   },function(error){
    $("#spinnerGif").hide();
    $("#cocheraModalBody").show();
       swal({
        title: "Error",
        text: "Hubo un error al cargar la operacion",
        type: "error",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    });
   });
}

function cargarCocheraModal(cocheraId){
    $("#spinnerGif").show();
    $("#cocheraModalBody").hide();
    var cocheras = JSON.parse(localStorage.getItem('cocheras'));
    var cochera = cocheras.filter(function(c){
        return c.id == cocheraId;
    })[0];
    let icon = cochera.esParaDiscapacitados == 1? " <i class='fa fa-wheelchair' id='wheelchairIcon'></i>" : '';
    $(".numeroCochera").html(cochera.numero + " Piso " + cochera.piso + icon);

    $("#popUpCochera").modal();

    traerOperacion(cochera.dominio);

}

function cargarCocheras(param){
    $.ajax({
        type: "get",
       url: servidor+"cocheras/",
       data: {
           libres: param
       }
          
   })
   .then(function(retorno){		
    console.log(retorno);
    localStorage.setItem("cocheras",JSON.stringify(retorno));
    if(retorno.length > 0){
        dibujarTabla(retorno);
    }
    else{
        swal('Ninguna cochera está en uso','','info');
    }
   },function(error){
       swal({
        title: "Error",
        text: "Hubo un error al obtener las cocheras",
        type: "error",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    });
   });
}

function buscarPorDominio(){
    let dominio = $('#lookup').val().toUpperCase();
    let tabla = $('.tableCochera').get( 0 );
    tr = tabla.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(dominio) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }

}

function retirar(){
    // localStorage.getItem('dominio');
    $.ajax({
        type: "put",
       url: servidor+"Operacion/",
       data: {
           dominio: dominioActual
       }
          
   })
   .then(function(retorno){		
     console.log(retorno);
     if(retorno.mensaje == 'Exito'){
        swal(retorno.mensaje,'El importe es $'+retorno.importe,'success');
     }
     else swal(retorno.mensaje,'','error');
    
   },function(error){
       swal({
        title: "Error",
        text: "Hubo un error al retirar el vehiculo",
        type: "error",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    });
   });
}

$(document).ready(function() {
    let user = JSON.parse(localStorage.getItem('usrComanda'));
    // let img = user.foto != null? "<img class='porfileImg' src='" + folderEmployeeImgaes + user.foto + "'></img>" : "<span class='glyphicon glyphicon-user'></span>";
    $("#usr").html(user.mail);

    $(document).on("click", "#app__logout", function(e) {
        $.ajax({
            type: "get",
           url: servidor+"logout/"
              
       });            
        //location.href = "http://bpdda.esy.es/Comanda/web/app/login/login.html";      
        location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/login/login.html";      
    });
    // cargarCocheras(0);
});