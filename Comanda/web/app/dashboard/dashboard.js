// var servidor="http://bpdda.esy.es/comanda/backEnd/";
var servidor="http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/";
var role = 0;//client default
var folderOrderImages = "../../../backend/fotosPedidos/";
var tables = [];
var items = [];
var selectedOrderId = 0;
var statsType = '';

$(document).on("change", "#selectItems", function(e) {
    $("#newOrderInputs").html('');
    let itemIds = $("#selectItems").val();
    items.forEach(i => {
        if(itemIds.includes(i.id.toString()))
            $("#newOrderInputs").append("<input type='text' value='"+ i.name +"'></input><input placeholder='unidades' value='1' type='number' min='1' id ='itemunits"+i.id+"'>");
    });
    
});

function loadOrder(){
    // let photo = $("#loadedPhoto").val();   
    let tableId = tables.filter(function(x){return x.code == $("#tableCode").val();})[0].id;
    let itemIds = $("#selectItems").val();
    let selectedItems = [];
    itemIds.forEach(id => {
        let units = $('#itemunits'+id).val();
        selectedItems.push({id: id, units: units});
    });

    var data = new FormData();
    jQuery.each(jQuery('#loadedPhoto')[0].files, function(i, file) {
        data.append('foto', file);
    });
    data.append('request', JSON.stringify({tableId: tableId, items: selectedItems}));
    $.ajax({
        url: servidor+"Order/",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST'  
          
   })
   .then(function(response){	
    swal({
        title: 'Listo',
        text: 'El codigo del pedido es '+response.mensaje,
        type: "success",
        showCancelButton: false,
        cancelButtonClass: "btn-info",
        cancelButtonText: "cerrar"
    },function(isConfirm){
        if(isConfirm){
            getOrders();                }
    });

   },function(error){
       swal({
     title: "Error",
     text: "Hubo un error al ingresar el pedido",
     type: "error",
     showCancelButton: false,
     cancelButtonClass: "btn-info",
     cancelButtonText: "cerrar"
   });
   });
}

function openNewOrderDialog(){    		
    items.forEach(i => {
        $("#selectItems").append("<option value='"+ i.id +"'>"+i.name+"</option>");
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
                title: "Expiro la sesion",
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
                title: "Expiro la sesion",
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
        drawTable('');
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

function openClientDialog(){
    $("#clientDialog").modal();
}

function getOrderForClient(){   
    let tableCode = $('#clientTableCode').val();
    let orderCode = $('#clientOrderCode').val();
    $.ajax({
        type: "get",
        url: servidor+"Client/order/",
        data: {
            tableCode: tableCode,
            orderCode: orderCode
        }         
   })
   .then(function(response){
        $("#clientDialog").modal('hide');
       openMyOrderDialog(response);
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

function openMyOrderDialog(data){
    if(data.order.status == 4){
        $('#survey').show();
        selectedOrderId = data.order.id;
    }
    let row = "<table class='table'><tr>"; 
    row += "<td>"+data.order.code+"</td>"+
        "<td>"+getStatus(data.order.status)+"</td>"+
        (data.order.status == 1?"<td><b>Tiempo estimado:</b> "+data.remainingTime+" min.</td>":"")+        
        (data.order.status == 1?"<td><b>Tiempo restante:</b> "+data.remainingTime+" min.</td>":"")+
        "<td><a onclick='toggleMyOrder()'>ocultar</a></td>";        
    row += "</tr></table>";
    $('#myOrder').html(row);
    $('#toggleOrderBtn').show();
    $('#myOrder').show(1000);
}

function toggleMyOrder(){
    $('#myOrder').toggle(1000);
}

function openSurveyDialog(){
    $("#surveyDialog").modal();
}

function openStatsDialog(type){
    statsType = type;
    $("#statsDialog").modal();
}

function getStats(){
    let validFrom = $('#validFrom').val();
    let validTo = $('#validTo').val();
    $.ajax({
        type: "get",
       url: servidor+"Stats/"+statsType+"/",
       data:{
           fromDate: validFrom,
           toDate: validTo
       }
    })
    .then(function(response){
    // console.log(response);
        switch (statsType) {
            case 'download':          
             //location.href = "http://bpdda.esy.es/Comanda/backEnd/results.xls";      
            location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/results.xls";       
                break;
            case 'unitsSales':swal('Ventas','Lo que mas se vendio fue '+response['top'].name+' en un total de '+response['top'].units+' unidades. Lo que menos se vendio fue '+response['bottom'].name+' con '+response['bottom'].units+' unidades','info');
            break;
            case 'tableTotalAmount':swal('Mesas','La mesa que mas facturo fue '+response[0].code+' en un total de $'+response[0].amount+'. La que menos facturo fue '+response[1].code+' con $'+response[1].amount,'info');
            break;
            case 'billing':swal('Facturacion total','desde '+validFrom+' hasta '+validTo+': $'+response[0].amount,'info');
            break;
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

function saveSurvey(){
    let waiterPoints = $('#waiterPoints').val();
    let producerPoints = $('#producerPoints').val();
    let tablePoints = $('#tablePoints').val();
    let comment = $('#comment').val();

    $.ajax({
        type: "put",
        url: servidor+"Client/",
        data: {
            waiterPoints:waiterPoints,
            producerPoints:producerPoints,
            tablePoints:tablePoints,
            comment:comment,
            orderId: selectedOrderId
        }         
   })
   .then(function(response){	
        swal('Listo','','success');    
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

function getLetter(param){
    $.ajax({
        type: "get",
        url: servidor+"Client/"         
   })
   .then(function(response){		
    localStorage.setItem("mainList",JSON.stringify(response));
    if(response.length > 0){
        drawLetter(response);
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

function drawLetter(data){
    let rows = "<ul>";
    for (let sector = 0; sector < 4; sector++) {//foreach sectorId
        rows += "<li>"+getSectorName(sector)+"</li>";
        rows +="<ul>";
        data.forEach(d => {
            if(d.sectorId == sector){
                rows +="<li>"+d.name+" $"+d.amount+"</li>"
            }
        });
        rows +="</ul>";
    }
    rows +="</ul>";
    $("#letter").html(rows);
}

function drawTable(data){
    if(data == ''){$("#mainTable").html(data); return;}

    var rows = '';

    if(role == 1 || role == 2){//admin or waiter               
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
            rows += "<tr onclick='openOrderDialog("+ d.id +","+d.status+")'>" +
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
        rows += "<li>" + r.itemName + " unidades: "+ r.units +"</li>";    
    });
    rows += "</ul>";
    $(document).on("click", "#takeOrderButton", function(e) {
        putOrder(orderId,"");        
    });
    $("#itemsAndUnits").html(rows);  
    $("#orderItemsDialog").modal();  
}

function openOrderDialog(orderId,status){
    if(role != 1 && role != 2){
        $("#deliveryOrderButton").hide();
        $("#payOrderButton").hide();
        $("#cancelOrderButton").hide();
    }
    if(status >= 2)
        $("#cancelOrderButton").hide();
    if(status != 2)
        $("#deliveryOrderButton").hide();
    if(status != 4)
         $("#payOrderButton").hide();   
    
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
            swal({
                title: response.mensaje,
                type: "success",
                showCancelButton: false,
                cancelButtonClass: "btn-info",
                cancelButtonText: "cerrar"
            },function(isConfirm){
                if(isConfirm){
                    getOrders();                }
            });
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

function getSectorName(sectorId){
    let rv = "";
    switch (sectorId) {
        case 0:   rv = "Tragos y Vinos";       
        break;
        case 1:   rv = "Cerveza Artesanal";       
        break;  
        case 2:   rv = "Cocina";       
        break;
        case 3:   rv = "Postres";       
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
            putOrder(order[0].id,"finish/");        
        });
      });
}

$(document).ready(function() {
    let user = JSON.parse(localStorage.getItem('usrComanda'));
    if(user){
        $("#usr").html(user.email);
        role = user.role;        
    }
    else{
        $('#lookup').hide();    
        $('#clientZone').show(500);    
        
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