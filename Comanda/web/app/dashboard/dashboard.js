// var servidor="http://bpdda.esy.es/comanda/backEnd/";
var servidor="http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/";
var role = 0;//client default
var folderOrderImages = "../../../backend/fotosPedidos/";
var tables = [];
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
    		
    items.forEach(i => {
        $("#selectItems").append("<option value='"+ i +"'>"+i.name+"</option>");
    });
        
        $("#newOrderDialog").modal();
        
       
}

function loadMainTable(param){
    $.ajax({
        type: "get",
        url: servidor+"Table/"         
   })
   .then(function(response){
    if(response.length > 0){
        if(response.indexOf('Expired token') == -1){	
            tables = response;
            if(role){//usr
                getOrders();
            }
            else{//client
                getLetter();
            }
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

function getOrders(param){
    $.ajax({
        type: "get",
        url: servidor+"Order/"         
   })
   .then(function(response){
    if(response.length > 0){
        if(response.indexOf('Expired token') == -1){	
            localStorage.setItem("mainList",JSON.stringify(response));
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
        drawTable(response);
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

function drawTable(data){
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
            let tableCode = tables.filter(function(x){ return x.id == d.tableId;})[0].code;
            rows += "<tr onclick='openOrderDialog("+ d.id +")'>" +
            "<td>" + d.code + "</td>" +
            "<td>" + tableCode + "</td>" +
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
        rows += "<tbody>";
        let orderIds = [];
        data.forEach(d => {
            if(!orderIds.includes(d.orderId)){
                orderIds.push(d.orderId);      //agrego codigo          
                rows += "<tr onclick='openOrderItemsDialog("+ d.orderId +")'>" +
                "<td>" + d.orderCode + "</td>"+
                " </tr>";
            }                  
        });
        rows += "</tbody>"; 
    }
    $("#mainTable").html(rows);
}

function openOrderItemsDialog(orderId){    
    let mainList = JSON.parse(localStorage.getItem("mainList"));
    let selectedRows = mainList.filter(function(x){return x.orderId == orderId;});
    let rows = "";
    rows += "<ul>";
    selectedRows.forEach(r => {    
        "<li>" + r.itemName + " unidades: "+ r.units +"</li>";    
    });
    rows += "</ul>";
    $(document).on("click", "#takeOrderButton", function(e) {
        putOrder(orderId,"");        
    });
    $("#itemsAndUnits").html(rows);  
    $("#orderItemsDialog").modal();  
}

function openOrderDialog(orderId){
    if(role != 1 && role != 2){
        $("#deliveryOrderButton").hide();
        $("#payOrderButton").hide();
        $("#cancelOrderButton").hide();
    }
    $(document).on("click", "#deliveryOrderButton", function(e) {
        putOrder(orderId,"deliver/");        
    });
    $(document).on("click", "#payOrderButton", function(e) {
        putOrder(orderId,"pay/");        
    });
    $(document).on("click", "#cancelOrderButton", function(e) {
        putOrder(orderId,"cancel/");        
    });
    $("#orderDialog").modal();        
}

function putOrder(orderId, action){
    $.ajax({
        type: "put",
        url: servidor+"Order/"+action,
        data: {
            orderId: orderId
        }        
    })
    .then(function(response){
        if(response.mensaje || response.indexOf('Expired token') == -1){
            swal(response.mensaje,'','success');
            getOrders();
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

function loadItems(){
    $.ajax({
        type: "get",
        url: servidor+"Client/"          
   })
   .then(function(response){
       items = response;
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

function openFinishOrderDialog(){
    swal({
        title: "Finalizar Orden",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        inputPlaceholder: "codigo de la orden"
      }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "" || inputValue.length < 5) {
          swal.showInputError("el codigo tiene 5 caracteres");
          return false
        }
        $.ajax({
            type: "get",
            url: servidor+"Order/byCode/",
            data: {
                orderCode: inputValue
            }        
       }).then(function(order){
            putOrder(order.id,"finish/");        
        });
      });
}

$(document).ready(function() {
    let user = JSON.parse(localStorage.getItem('usrComanda'));
    if(user){
        $("#usr").html(user.email);
        role = user.role;        
    }

    if(role != 1){
        $('#liStats').hide();
        $('#liUsrs').hide();       
        $('#liExcel').hide();       
        if(role != 2){
            $('#liTables').hide();
            $('#liNewOrder').hide();
        }
        if(role != 3)
            $('#liFinishOrder').hide();
        
    }
    
    $(document).on("click", "#app__logout", function(e) {
        $.ajax({
            type: "get",
           url: servidor+"logout/"
              
       });      
       localStorage.removeItem('usrComanda');      
        //location.href = "http://bpdda.esy.es/Comanda/web/app/login/login.html";      
        location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/login/login.html";      
    });
    loadItems();
    loadMainTable();
});