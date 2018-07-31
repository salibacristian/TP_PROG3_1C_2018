// var servidor="http://bpdda.esy.es/comanda/backEnd/";
var servidor="http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/";
var role = 0;//client default
var folderOrderImages = "../../../backend/fotosPedidos/";
var tables = [
    {
        id:1,
        code:'00000'
    },
    {
        id:2,
        code:'000001'
    },
    {
        id:3,
        code:'00002'
    },
    {
        id:4,
        code:'00003'
    },
    {
        id:5,
        code:'00004'
    },
    {
        id:6,
        code:'00005'
    },
    {
        id:7,
        code:'00006'
    },
    {
        id:8,
        code:'00007'
    },
    {
        id:9,
        code:'00008'
    },
    {
        id:10,
        code:'00009'
    },
    {
        id:11,
        code:'00010'
    }
    
];
var items = [];

$(document).on("change", "#selectItems", function(e) {
    $("#newOrderInputs").html('');
    let items = $("#selectItems").val();
    items.forEach(i => {
        if(i)
        $("#newOrderInputs").append("<input type='text' value='"+ i.name +"></input>");
    });
    
});

function loadOrder(){
    let photo = $("#loadedPhoto").val();   
    let tableId = tables.filter(function(x){return x.code == $("#tableCode").val();})[0].id;
    let items = $("#selectItems").val();
    $.ajax({
        type: "post",
       url: servidor+"Order/",
       data: {
            foto: photo,
            request:{
                tableId: tableId, 
                items: itemIds
            }
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

function openNewOrderDialog(){
    $.ajax({
        type: "get",
        url: servidor+"Client/"          
   })
   .then(function(response){
       items = response;		
    response.forEach(i => {
        $("#selectItems").append("<option value='"+ i +"'>"+i.name+"</option>");
    });
        
        $("#newOrderDialog").modal();
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

function loadMainTable(param){
    if(role){//usr
        getOrders();
    }
    else{//client
        getLetter();
    }
}

function getOrders(param){
    $.ajax({
        type: "get",
        url: servidor+"Order/"         
   })
   .then(function(response){
    if(response.length > 0){
        if(response.indexOf('Expired token') == -1){	
            localStorage.setItem("mainList",JSON.stringify(response));
            drawTable(response,role);
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
        swal('No hay pedidos!','','info');
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

function getLetter(param){
    $.ajax({
        type: "get",
        url: servidor+"Client/"         
   })
   .then(function(response){		
    localStorage.setItem("mainList",JSON.stringify(response));
    if(response.length > 0){
        drawTable(response,role);
    }
    else{
        swal('Error','No se encontro la informacion de la carta','error');
    }
   },function(error){
       swal({
        title: "Error",
        text: "No se encontro la informacion de la carta",
        type: "error",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    });
   });
}

function drawTable(data,role){
    var rows = '';
    if(!role){//client
    } 
    else if(role == 1 || role == 2){//admin or waiter               
        rows += "<thead><tr>" +
        "<th>Codigo</th>" +
        "<th>Mesa</th>" +
        "<th>Foto</th>" +
        "<th>Estado</th>" +
        "<th>Tiempo Estimado</th>";
        if(role == 1){
            rows +="<th>Tiempo Real</th>" +
            "<th>Monto</th>" +
            "<th>Comentarios</th>" +
            "<th>Pts. Mesa</th>" +
            "<th>Pts. Moso</th>" +
            "<th>Pts. Cocinero</th>" +
            "<th>Creado</th>" +
            "<th>Tomado</th>" +
            "<th>Finalizado</th>";
        }
        rows += "</tr></thead><tbody>";
        data.forEach(d => {
            let img = "<img class='tableImg' alt='sin foto' src='" + folderOrderImages + d.imgUrl + "'></img>";
            rows += "<tr onclick='openOrderDialog("+ d.id +")'>" +
            "<td>" + d.code + "</td>" +
            "<td>" + tables[d.tableId - 1] + "</td>" +
            "<td>" + img + "</td>" +        
            "<td>" + getStatus(d.status) + "</td>" +
            "<td>" + (d.estimatedTime?d.estimatedTime:'') + "</td>";
            if(role == 1){
                rows += "<td>" + (d.realTime?d.realTime:'') + "</td>" +
                "<td>" + (d.amount?d.amount:'') + "</td>" +
                "<td>" + (d.comment?d.comment:'') + "</td>" +        
                "<td>" + (d.tablePoints?d.tablePoints:'') + "</td>" +
                "<td>" + (d.waiterPoints?d.waiterPoints:'') + "</td>" +
                "<td>" + (d.producerPoints?d.producerPoints:'') + "</td>" +
                "<td>" + d.createdDate + "</td>" +
                "<td>" + (d.takenDate?d.takenDate:'') + "</td>" +
                "<td>" + (d.finishDate?d.finishDate:'') + "</td>";
            }
            rows +=" </tr>";
        });
        rows += "</tbody>";        
    }
    else {//producer

    }
    $("#mainTable").html(rows);
}

function getTableCode(tableId){
    let rv = "";
    switch (tableId) {
        case 1:   rv = "00000";       
            break;  
            case 2:   rv = "00001";       
            break;
            case 3:   rv = "00002";       
            break;
            case 4:   rv = "00003";       
            break;
            case 5:   rv = "00004";       
            break;
            case 6:   rv = "00005";       
            break;
            case 7:   rv = "00006";       
            break;
            case 8:   rv = "00007";       
            break;
            case 9:   rv = "00008";       
            break;
            case 10:   rv = "00009";       
            break;
            case 11:   rv = "00010";       
            break;  
    }
    return rv;
}

function getStatus(status){
    let rv = "";
    switch (status) {
        case 0:   rv = "Pendiente";       
        break;
        case 1:   rv = "En proceso";       
        break;  
        case 2:   rv = "Finalizado";       
        break;
        case 3:   rv = "Cancelado";       
        break;
        case 4:   rv = "Entregado";       
        break;           
    }
    return rv;
}

function searchOrder(){
    let orderCode = $('#lookup').val().toUpperCase();
    let tabla = $('.mainTable').get(0);
    tr = tabla.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(orderCode) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }

}

$(document).ready(function() {
    let user = JSON.parse(localStorage.getItem('usrComanda'));
    if(user){
        $("#usr").html(user.email);
        role = user.role;        
    }
    if(role != 1 && role != 2)//admin or waiter
        $('#liTables').hide();

    $(document).on("click", "#app__logout", function(e) {
        $.ajax({
            type: "get",
           url: servidor+"logout/"
              
       });      
       localStorage.removeItem('usrComanda');      
        //location.href = "http://bpdda.esy.es/Comanda/web/app/login/login.html";      
        location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/login/login.html";      
    });
    loadMainTable();
});