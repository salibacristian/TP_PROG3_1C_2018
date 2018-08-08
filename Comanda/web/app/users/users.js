 var servidor="http://bpdda.esy.es/Comanda/backEnd/";
//var servidor="http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/";

$(document).on("change", "#selectedRol", function(e) {
    $('#selectedSector').prop("disabled", $('#selectedRol').val() != 3);
    $('#selectedSector').val(null);
    
});

function loadUser(){
    let usrName = $("#usrName").val();   
    let usrEmail = $("#usrEmail").val();   
    let usrPass = $("#usrPass").val();   
    let selectedRol = $("#selectedRol").val();   
    let selectedSector = $("#selectedSector").val();
    if(!usrName || !usrEmail || !usrPass || !selectedRol){ 
        swal('Complete los campos','','error');return;
    }
    if(selectedRol == 3 && selectedSector == null){ 
        swal('Error','Operativo debe pertenecer a un sector','error');return;
    }
    let data = {
        name:usrName,
        email:usrEmail,
        password:usrPass,
        role:selectedRol,
        sectorId:selectedSector        
    };
    $.ajax({
        url: servidor+"User/",
        data: data,
        type: 'POST'  
          
   })
   .then(function(response){	
    swal({
        title: 'Listo',
        type: "success",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    },function(isConfirm){
        if(isConfirm){
            getUsers();                }
    });

   },function(error){
       swal({
     title: "Error",
     text: "Hubo un error al ingresar el usuario",
     type: "error",
     showCancelButton: false,
     cancelButtonClass: "btn-info",
     cancelButtonText: "cerrar"
   });
   });
}

function openNewUserDialog(){  		
    $('#selectedSector').val(null);         
    $("#newUserDialog").modal();         
}

function getUsers(){
    $.ajax({
        type: "get",
        url: servidor+"User/"         
   })
   .then(function(response){
    if(response.length > 0){
        if(response.indexOf('Expired token') == -1){	
                drawTable(response);
            
        }
        else{
            swal({
                title: "Expiro la sesion",
                type: "info",
                showCancelButton: false,
                cancelButtonClass: "btn-info",
                cancelButtonText: "cerrar"
            },function(isConfirm){
                if(isConfirm){
                    location.href = "http://bpdda.esy.es/Comanda/web/app/login/login.html";      
                    //location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/login/login.html";      
                }
            });
        }
    }
    else{
        swal('Error','','error');
    }
   },function(error){
       swal({
        title: "Error",
        text: "Hubo un error al cargar el listado",
        type: "error",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    });
   });
   
}

function drawTable(data){
    if(data == ''){$("#mainTable").html(data); return;}

    var rows = '';

    rows += "<thead><tr>" +
    "<th>Nombre</th>" +
    "<th>Email</th>" +
    "<th>Suspendido</th>" +
    "<th>Sector</th>"+
    "<th>Rol</th>"+    
    "<th></th>"+   
    "<th></th>";    

    rows += "</tr></thead><tbody>";
    data.forEach(d => {
        let isSuspended = parseInt(d.isSuspended);
        let sectorId = parseInt(d.sectorId);
        let role = parseInt(d.role);
        //como puede ser que solo en la nube me traiga los values int como string?
        rows += "<tr>" +
        "<td>" + d.name + "</td>" +
        "<td>" + d.email + "</td>" +
        "<td>" + (isSuspended? "SI":"NO") + "</td>" +        
        "<td>" + getSector(sectorId) + "</td>" +      
        "<td>" + getRole(role) + "</td>" +       
        "<td><a onclick='remove("+d.id+")'><i title='eliminar' class='fa fa-trash'></i></a></td>"+       
        (isSuspended?"<td><a onclick='suspend("+d.id+","+0+")'><i title='habilitar' class='fa fa-unlock-alt'></i></a></td>"
        :"<td><a onclick='suspend("+d.id+","+1+")'><i title='suspender' class='fa fa-lock'></i></a></td>");     
        rows +=" </tr>";
    });    

    rows += "</tbody>";     
    $("#mainTable").html(rows);
}

function remove(id){
    $.ajax({
        url: servidor+"User/",
        data: {id: id, status: 1},
        type: 'delete'  
          
   })
   .then(function(response){	
    swal({
        title: 'Listo',
        type: "success",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    },function(isConfirm){
        if(isConfirm){
            getUsers();                }
    });

   },function(error){
       swal({
     title: "Error",
     type: "error",
     showCancelButton: false,
     cancelButtonClass: "btn-info",
     cancelButtonText: "cerrar"
   });
   });
}

function suspend(id,status){
    $.ajax({
        url: servidor+"User/",
        data: {id: id, status: status},
        type: 'put'  
          
   })
   .then(function(response){	
    swal({
        title: 'Listo',
        type: "success",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    },function(isConfirm){
        if(isConfirm){
            getUsers();                }
    });

   },function(error){
       swal({
     title: "Error",
     type: "error",
     showCancelButton: false,
     cancelButtonClass: "btn-info",
     cancelButtonText: "cerrar"
   });
   });
}

function getSector(sectorId){
    let rv = "";
    switch (sectorId) {
        case 0:   rv = "Barra Tragos Vinos";       
        break;
        case 1:   rv = "Barra Choperas";       
        break;  
        case 2:   rv = "Cocina";       
        break;
        case 3:   rv = "CandyBar";       
        break;       
    }
    return rv;
}

function getRole(role){
    let rv = "";
    switch (role) {
        case 1:   rv = "Administrador";       
        break;  
        case 2:   rv = "Mozo";       
        break;
        case 3:   rv = "Operativo";       
        break;       
    }
    return rv;
}

function downloadLogs(){
       location.href = "http://bpdda.esy.es/Comanda/backend/ingresos.txt";      
       //location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/backend/ingresos.txt"; 
}

$(document).ready(function() {
    let user = JSON.parse(localStorage.getItem('usrComanda'));
    if(user){
        $("#usr").html(user.email);      
    }   
    
    
    $(document).on("click", "#app__logout", function(e) {
        $.ajax({
            type: "get",
           url: servidor+"logout/"
              
       });      
       localStorage.removeItem('usrComanda');      
        location.href = "http://bpdda.esy.es/Comanda/web/app/login/login.html";      
        //location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/login/login.html";      
    });

    getUsers();
});