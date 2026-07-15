function objetoAjax() {
    var xmlhttp = false;
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  
    if (!xmlhttp && typeof XMLHttpRequest != "undefined") {
      xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
  }

  
function Login(){
    usuario = $("#cuenta").val();
    pass=$("#psw").val();

    ajax = objetoAjax();
    //divResultado = document.getElementById('main');
    ajax.open("POST","include/validacion.php",true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            if(ajax.responseText=="1"){
                Swal.fire({
                    title: "Loggeado",
                    text: "Session iniciada con éxito",
                    icon: "success",
                    showConfirmButton:false
                  });
                  setTimeout(() => {
                    location.reload();
                  }, "1000");
            }else{
                Swal.fire({
                    title: "Error",
                    text: "corrige tus datos",
                    icon: "error"
                  });
            }
        }
    }
    ajax.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded;charset=utf-8"
      );
    ajax.send("usr="+usuario+"&pass="+pass+"&op=1");
}

function salir(){
  ajax = objetoAjax();
  ajax.open("POST","include/validacion.php",true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {

        if(ajax.responseText=="1"){
              Swal.fire({
                  title: "Cerrando sessión...",
                  text: "Session finalizada",
                  icon: "success",
                  showConfirmButton:false
                });
                setTimeout(() => {
                  window.location.href="index.php";
                }, "1000");
        }
      }
  }
  ajax.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded;charset=utf-8"
    );
  ajax.send("op=2");
}