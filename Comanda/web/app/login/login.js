$(document).ready(function() {
  
  var animating = false,
      submitPhase1 = 1100,
      submitPhase2 = 400,
      logoutPhase1 = 800,
      $login = $(".login"),
      $app = $(".app");
  
  function ripple(elem, e) {
    $(".ripple").remove();
    var elTop = elem.offset().top,
        elLeft = elem.offset().left,
        x = e.pageX - elLeft,
        y = e.pageY - elTop;
    var $ripple = $("<div class='ripple'></div>");
    $ripple.css({top: y, left: x});
    elem.append($ripple);
  };
  
  $(document).on("click", ".login__submit", function(e) {
    // if (animating) return;
    // animating = true;
    // var that = this;
    // ripple($(that), e);
    // $(that).addClass("processing");
    // setTimeout(function() {
    //   $(that).addClass("success");
    //   setTimeout(function() {
    //     $app.show();
    //     $app.css("top");
    //     $app.addClass("active");
    //   }, submitPhase2 - 70);
    //   setTimeout(function() {
    //     $login.hide();
    //     $login.addClass("inactive");
    //     animating = false;
    //     $(that).removeClass("success processing");
    //   }, submitPhase2);
    // }, submitPhase1);

    singin();
  });
  
  $(document).on("click", ".app__logout", function(e) {
    if (animating) return;
    $(".ripple").remove();
    animating = true;
    var that = this;
    $(that).addClass("clicked");
    setTimeout(function() {
      $app.removeClass("active");
      $login.show();
      $login.css("top");
      $login.removeClass("inactive");
    }, logoutPhase1 - 120);
    setTimeout(function() {
      $app.hide();
      animating = false;
      $(that).removeClass("clicked");
    }, logoutPhase1);
  });
  
});

 var servidor="http://bpdda.esy.es/comanda/backEnd/";
//var servidor="http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/";


function singin()
{
	var _correo=$("#usr").val();
	var _clave=$("#pass").val();
	$.ajax({
		 type: "post",
		url: servidor+"login/",
		data: {
	        email: _correo,
	        password: _clave 
    	}
   		
	})
	.then(function(retorno){		
		if (typeof(Storage) !== "undefined") {
    		localStorage.setItem('tokenComanda', retorno.token);
    		localStorage.setItem('usrComanda', JSON.stringify(retorno.user));
		} else {
		   console.log("Sorry! No Web Storage support..");
    }		
    if(retorno.session == _correo)
    {
      location.href = "http://bpdda.esy.es/Comanda/web/app/dashboard/dashboard.html";
      //location.href = "http://localhost:8080/TP_PROG3_1C_2018/Comanda/web/app/dashboard/dashboard.html";
    }
    swal({
      title: retorno.mensaje,
      type: "info",
      showCancelButton: false,
      confirmButtonClass: "btn-info",
      confirmButtonText: "cerrar"
    });
	},function(error){
		swal({
      title: "Error",
      text: "Hubo un error al iniciar sesion",
      type: "error",
      showCancelButton: false,
      cancelButtonClass: "btn-info",
      cancelButtonText: "cerrar"
    });
	});
	
}
