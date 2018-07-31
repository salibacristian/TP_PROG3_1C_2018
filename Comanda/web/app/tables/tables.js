// var servidor="http://bpdda.esy.es/comanda/backEnd/";
var servidor="http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/";
var role = 0;//client default

function getTables(param){
    $.ajax({
        type: "get",
        url: servidor+"Table/"         
   })
   .then(function(response){
    if(response.length > 0){
        if(response.indexOf('Expired token') == -1){	
            // localStorage.setItem("mainList",JSON.stringify(response));
            drawTable(response);
        }
        else{
            swal({
                title: "Expiro el token",
                type: "info",
                showCancelButton: false,
                cancelButtonClass: "btn-info",
                cancelButtonText: "cerrar"
            },function(isConfirm){
                if(isConfirm){
                    //location.href = "http://bpdda.esy.es/Comanda/web/app/login/login.html";      
                    location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/login/login.html";      
                }
            });
        }
    }
    else{
        swal('Error','Hubo un error al cargar las mesas','error');
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
    var rows = '';     
    data.forEach(d => {
        if(role == 2 && d.status == 1)
            rows += "<tr class='danger'>";
        else
            rows += "<tr>";
        rows +="<td>";
        if(role == 1 && d.status != 0)
            rows += "<a title='CERRAR MESA' onclick='closeTable("+ d.id +")'><i style='color:red;' class='fa fa-window-close'></i></a>";
        rows += "</td>" +           
        "<td>" + d.code + "</td>" +           
        "<td>" + getStatus(d.status) + "</td>" +        
        " </tr>";
    });    

    $("#tBody").html(rows);
}

function closeTable(tableId){
    $.ajax({
        type: "put",
        url: servidor+"Table/",
        data: {
            id: tableId,
        }        
    })
    .then(function(response){
        if(response.mensaje || response.indexOf('Expired token') == -1){	
            swal('Mesa cerrada','','success');
            getTables();
        }
        else{
            swal({
                title: "Expiro el token",
                type: "info",
                showCancelButton: false,
                cancelButtonClass: "btn-info",
                cancelButtonText: "cerrar"
            },function(isConfirm){
                if(isConfirm){
                    //location.href = "http://bpdda.esy.es/Comanda/web/app/login/login.html";      
                    location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/login/login.html";      
                }
            });
        }      
    },function(error){
        swal({
        title: "Error",
        text: "Hubo un error al cerrar la mesa",
        type: "error",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    });
    });
}

function getStatus(status){
    let rv = "";
    switch (status) {
        case 0:   rv = "Cerrada";       
        break;
        case 1:   rv = "Esperando";       
        break;  
        case 2:   rv = "Comiendo";       
        break;
        case 3:   rv = "Pagando";       
        break;
    }
    return rv;
}

$(document).ready(function() {
    let user = JSON.parse(localStorage.getItem('usrComanda'));
    if(user){
        $("#usr").html(user.email);
        role = user.role;        
    }

    $(document).on("click", "#goBack", function(e) {      
        //location.href = "http://bpdda.esy.es/Comanda/web/app/dashboard/dashboard.html";      
        location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/dashboard/dashboard.html";      
    });
    getTables();
});