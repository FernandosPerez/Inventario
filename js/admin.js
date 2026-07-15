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


function Add() {
    //alert("hola");
    divResultado = document.getElementById("datosModal");
    ajax = objetoAjax();
    ajax.open("POST", "funciones/admin.php", true);
    ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
        //mostrar resultados en esta capa
        //divResultado.innerHTML = ajax.responseText;
      }
    };
    ajax.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded;charset=utf-8"
    );
    ajax.send("op=1");
}


//---------------------------------------------INDEX ADMIN--------------------------------------------//






//---------------------------------------------PLANTELES--------------------------------------------//


function agregarPlantel(){
   divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=4");
}

function plantel(id){
window.location.href=window.location.href+"?plantel="+id;

}

function regresar(){
  history.back();
}


function guardarPlantel() {
  const nombre = document.getElementById('nombre').value;
  const ubicacion = document.getElementById('ubicacion').value;
  const razon = document.getElementById('razon').value;
  const nomen = document.getElementById('nomen').value;

  if (!nombre || !ubicacion || !razon || !nomen) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Plantel agregado correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=5&nombre=" + nombre + "&ubicacion=" + ubicacion+ "&razon=" + razon+ "&nomen=" + nomen);

}




function actualizarPlantel(id) {
  const nombre = document.getElementById('nombre').value;
  const ubicacion = document.getElementById('ubicacion').value;
  const razon = document.getElementById('razon').value;
  const nomen = document.getElementById('nomen').value;

  if (!nombre || !ubicacion || !razon || !nomen) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

  let status=0;
  
  if ($('#status').is(":checked")) {
     status = 1;
  }
  else {
    status = 0;
  }

  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Datos actualizados",
              text: "Plantel actualizdo correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=6&nombre=" + nombre + "&ubicacion=" + ubicacion+ "&razon=" + razon+ "&nomen=" + nomen+ "&status=" + status+ "&id=" + id);

}

//-------------------------------------------  planes  -------------------------------------------------------

function planesPlantel(id){
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;

new DataTable('#tconceptos', {
        responsive: true,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados"
        }
    });


    $("#tconceptos_wrapper").removeClass("form-inline");
    $("#tconceptos_wrapper").addClass("w-100");
      
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=8&id="+id);
}

function agregarConcepto(id){
divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;      
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=9&id="+id+"&tipo=1");
}


function guardarConcepto(plantel){

  const nombre = document.getElementById('nombre').value;
  const descuento = document.getElementById('descuento').value;
  const monto = document.getElementById('monto').value;
  const concurrencia = document.getElementById('concurrencia').value;

  if (!nombre || !monto ) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Concepto agregado correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

             setTimeout(() => {
              location.reload(true);
            }, 2000);
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=16&id="+plantel+"&nombre="+nombre+"&descuento="+descuento+"&monto="+monto+"&concurrencia="+concurrencia);

}

function agregarPlan(plantel){
   divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=14&plantel=" + plantel);
}



function guardarPlan(plantel){
  
  const nombre = document.getElementById('nombre').value;
  const dia = document.getElementById('dia').value;
  const nivel = document.getElementById('nivel').value;
  const modalidad = document.getElementById('modalidad').value;

  if (!nombre || !dia || !nivel || !modalidad) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }



  if (dia>=32 || dia <=0) {
   
    Swal.fire({
      title: "Cambiar el dia",
      text: "No es un dia válido",
      icon: "warning"
    });
    return;
  }



  const checkboxes = document.querySelectorAll('input[name="concepto"]:checked');
      const conceptos = Array.from(checkboxes).map(checkbox => checkbox.value);
      

      if(conceptos.length==0){
        Swal.fire({
      title: "Datos faltantes",
      text: "Ingresa al menos un concepto",
      icon: "warning"
    });
    return;
    }

  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Plan agregado correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=15&nombre=" + nombre + "&dia=" + dia+ "&nivel=" + nivel+ "&modalidad=" + modalidad+ "&plantel=" + plantel+ "&conceptos=" + conceptos.join("|"));
}


//---------------------------------------------USUARIOS--------------------------------------------//

function agregarUsuario() {
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;


        miFormulario = document.querySelector('#prospectForm');
        miFormulario.telefono.addEventListener('keypress', function (e){
          if (!soloNumeros(event)){
            e.preventDefault();
          }
        })

        //Solo permite introducir numeros.
        function soloNumeros(e){
            var key = e.charCode;
            return key >= 48 && key <= 57;
        }

        // Ejecutar cada 2 segundos
        const intervalo = setInterval(progress, 500);

        // Detener después de 5 minutos (300,000 ms)
        setTimeout(() => {
          clearInterval(intervalo);
          //console.log("Se detuvo la ejecución.");
        }, 300000);

    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=2");
}

function datos(id) {
  divResultado = document.getElementById("datosModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=1&id="+id);
}


function soloNumeros(e){
    var key = e.charCode;
    return key >= 48 && key <= 57;
}



function guardarUsuario() {
  const nombre = document.getElementById('nombre').value;
  const apellidoPaterno = document.getElementById('apellidoPaterno').value;
  const apellidoMaterno = document.getElementById('apellidoMaterno').value;
  const rol = document.getElementById('rol').value;
  const campus = document.getElementById('campus').value;
  const usuario = document.getElementById('usuario').value;
  const telefono = document.getElementById('telefono').value;
  const correo = document.getElementById('correo').value;
  const cp = document.getElementById('1').value;
  const calle = document.getElementById('2').value;
  const localidad = document.getElementById('3').value;
  const municipio = document.getElementById('4').value;
  const estado = document.getElementById('5').value;
  const numext = document.getElementById('6').value;
  const numint = document.getElementById('7').value;

  if (!nombre || !apellidoPaterno || !apellidoMaterno || !rol || !campus || !telefono || !correo || !cp || !calle || !numext || !numint || rol==0 || campus==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
}

  let p1=0;
  let p2=0;
  let p3=0;
  let p4=0;
  let p5=0;
  let p6=0;
  let p7=0;
  let p8=0;
  let p9=0;
  let p10=0;
  let p11=0;
  let p12=0;
  let p13=0;
  let p14=0;
  let p15=0;

  if ($('#p1').is(":checked")) {
     p1 = 1;
  }
  else {
    p1 = 0;
  }

  if ($('#p2').is(":checked")) {
    p2 = 1;
 }
 else {
   p2 = 0;
 }

 if ($('#p3').is(":checked")) {
  p3 = 1;
}
else {
 p3 = 0;
}

if ($('#p4').is(":checked")) {
  p4 = 1;
}
else {
 p4 = 0;
}

if ($('#p5').is(":checked")) {
  p5 = 1;
}
else {
 p5 = 0;
}

if ($('#p6').is(":checked")) {
  p6 = 1;
}
else {
 p6 = 0;
}

if ($('#p7').is(":checked")) {
  p7 = 1;
}
else {
 p7 = 0;
}

if ($('#p8').is(":checked")) {
  p8 = 1;
}
else {
 p8 = 0;
}

if ($('#p9').is(":checked")) {
  p9 = 1;
}
else {
 p9 = 0;
}

if ($('#p10').is(":checked")) {
  p10 = 1;
}
else {
 p10 = 0;
}

if ($('#p11').is(":checked")) {
  p11 = 1;
}
else {
 p11 = 0;
}

if ($('#p12').is(":checked")) {
  p12 = 1;
}
else {
 p12 = 0;
}

if ($('#p13').is(":checked")) {
  p13 = 1;
}
else {
 p13 = 0;
}

if ($('#p14').is(":checked")) {
  p14 = 1;
}
else {
 p14 = 0;
}

if ($('#p15').is(":checked")) {
  p15 = 1;
}
else {
 p15 = 0;
}
  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==0){


            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
            
          }else{

             Swal.fire({
              title: "Completado",
              text: "Usario agregado correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

             setTimeout(() => {
              location.reload(true);
            }, 5000);
            
          }


          
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=3&nombre=" + nombre + "&apellidoPaterno=" + apellidoPaterno + "&apellidoMaterno=" + apellidoMaterno + 
            "&rol=" + rol + "&campus=" + campus + "&usuario=" + usuario + "&telefono=" + telefono + 
            "&correo=" + correo + "&p1=" +p1+ "&p2=" +p2+ "&p3=" +p3+ "&p4=" +p4+ "&p5=" +p5+ "&p6=" +p6+ "&p7=" +p7+ "&p8=" +p8+ "&p9=" +p9+"&p10=" +p10+"&p11=" +p11+"&p12=" +p12+"&p13=" +p13+ "&p14=" +p14+"&p15=" +p15+"&cp=" +cp+"&calle=" +calle+"&numext=" +numext+"&numint=" +numint+"&localidad=" +localidad+"&municipio=" +municipio+"&estado=" +estado);

}

function actualizarUsuario(id) {
  const nombre = document.getElementById('nombre').value;
  const apellidoPaterno = document.getElementById('apellidoPaterno').value;
  const apellidoMaterno = document.getElementById('apellidoMaterno').value;
  const rol = document.getElementById('rol').value;
  const campus = document.getElementById('campus').value;
  const usuario = document.getElementById('usuario').value;
  const telefono = document.getElementById('telefono').value;
  const correo = document.getElementById('correo').value;
  //const password = document.getElementById('password').value;
  const cp = document.getElementById('1').value;
  const calle = document.getElementById('2').value;
  const localidad = document.getElementById('3').value;
  const municipio = document.getElementById('4').value;
  const estado = document.getElementById('5').value;
  const numext = document.getElementById('6').value;
  const numint = document.getElementById('7').value;

  if (!nombre || !apellidoPaterno || !apellidoMaterno || !rol || !campus || !telefono || !correo || !cp || !calle || !numext || !numint ) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
}


  let p1=0;
  let p2=0;
  let p3=0;
  let p4=0;
  let p5=0;
  let p6=0;
  let p7=0;
  let p8=0;
  let p9=0;
  let p10=0;
  let p11=0;
  let p12=0;
  let p13=0;
  let p14=0;
  let p15=0;

  if ($('#p1').is(":checked")) {
     p1 = 1;
  }
  else {
    p1 = 0;
  }

  if ($('#p2').is(":checked")) {
    p2 = 1;
 }
 else {
   p2 = 0;
 }

 if ($('#p3').is(":checked")) {
  p3 = 1;
}
else {
 p3 = 0;
}

if ($('#p4').is(":checked")) {
  p4 = 1;
}
else {
 p4 = 0;
}

if ($('#p5').is(":checked")) {
  p5 = 1;
}
else {
 p5 = 0;
}

if ($('#p6').is(":checked")) {
  p6 = 1;
}
else {
 p6 = 0;
}

if ($('#p7').is(":checked")) {
  p7 = 1;
}
else {
 p7 = 0;
}

if ($('#p8').is(":checked")) {
  p8 = 1;
}
else {
 p8 = 0;
}

if ($('#p9').is(":checked")) {
  p9 = 1;
}
else {
 p9 = 0;
}

if ($('#p10').is(":checked")) {
  p10 = 1;
}
else {
 p10 = 0;
}

if ($('#p11').is(":checked")) {
  p11 = 1;
}
else {
 p11 = 0;
}

if ($('#p12').is(":checked")) {
  p12 = 1;
}
else {
 p12 = 0;
}

if ($('#p13').is(":checked")) {
  p13 = 1;
}
else {
 p13 = 0;
}

if ($('#p14').is(":checked")) {
  p14 = 1;
}
else {
 p14 = 0;
}

if ($('#p15').is(":checked")) {
  p15 = 1;
}
else {
 p15 = 0;
}


  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4 ) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Usario agregado correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

             setTimeout(() => {
              location.reload(true);
            }, 2000);
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }


          
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=7&nombre=" + nombre + "&apellidoPaterno=" + apellidoPaterno + "&apellidoMaterno=" + apellidoMaterno + 
            "&rol=" + rol + "&campus=" + campus + "&usuario=" + usuario + "&telefono=" + telefono + 
            "&correo=" + correo + "&p1=" +p1+ "&p2=" +p2+ "&p3=" +p3+ "&p4=" +p4+ "&p5=" +p5+ "&p6=" +p6+ "&p7=" +p7+ "&p8=" +p8+ "&p9=" +p9+"&p10=" +p10+"&p11=" +p11+"&p12=" +p12+"&p13=" +p13+ "&p14=" +p14+"&p15=" +p15+"&cp=" +cp+"&calle=" +calle+"&numext=" +numext+"&numint=" +numint+"&localidad=" +localidad+"&municipio=" +municipio+"&estado=" +estado+"&id=" +id);

}

  async function triggerFileInput() {
const { value: file } = await Swal.fire({
  title: "Seleccione una imagen",
  input: "file",
  inputAttributes: {
    "accept": "image/*",
    "aria-label": "Upload your profile picture"
  }
});
if (file) {
  const reader = new FileReader();
  reader.onload = (e) => {
    Swal.fire({
      title: "Se actualizo la foto de perfil",
      imageUrl: e.target.result,
      imageAlt: "The uploaded picture"
    });
  };
  reader.readAsDataURL(file);
}
    }


    

function codigo(){

	cod=$('#1').val();

  if(cod.length<=4){
    Swal.fire({
      title: "Verifica",
      text: "Código postal  invalido",
      icon: "error"
    });
  }else{
    var col="";
    //divResultado = document.getElementById("pros");
    ajax = objetoAjax();
    ajax.open("POST", "funciones/admin.php", true);
    ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
        //divResultado.innerHTML = ajax.responseText;
		arreglo=ajax.responseText;

		var seccion = arreglo.split('|');

    $("#4").val(seccion[1]);
    $("#5").val(seccion[2]);

		var colonias = seccion[0].split(',');

    
		colonias.forEach(element => {
			if(element==""){

			}else{

		    var coloniaformateada = "";


        var resfor = element.split(' ');


        resfor.forEach(element2 => {
        if(element2==""){

        }else{

          coloniaformateada += element2+"-";

        }
        
      });

        col += "<option value="+coloniaformateada+">"+element+"</option>";
        var coloniaformateada = "";

			}
			
		});
    divResultado = document.getElementById("3");
    divResultado.innerHTML = col;
		
      }
    };
    ajax.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded;charset=utf-8"
    );
    ajax.send("cp="+cod+"&op=29");
  }

}



function desactivarUsuario(id){

  
Swal.fire({
  title: "Desactivar",
  text: "Estas seguro que deseas desactivar este usuario?",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#2e59d9",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, desactivar!"
}).then((result) => {
  if (result.isConfirmed) {

    ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      Swal.fire({
      title: "Desactivado!",
      text: "Se ha desactivado el usuario correctamente.",
      icon: "success"
    });

            setTimeout(() => {
              location.reload(true);
            }, 2000);
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=30&id=" + id);

  }
});
  
}


function activarUsuario(id){

  
Swal.fire({
  title: "Activar",
  text: "Estas seguro que deseas activar este usuario?",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#2e59d9",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, activar!"
}).then((result) => {
  if (result.isConfirmed) {

    ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      Swal.fire({
      title: "Activado!",
      text: "Usuario activado correctamente.",
      icon: "success"
    });

            setTimeout(() => {
              location.reload(true);
            }, 2000);
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=31&id=" + id);

  }
});
  
}

function correo(contenido){
 ajax = objetoAjax();
  ajax.open("POST", "librerias/procesamiento/correoprospecto.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //return(ajax.responseText);
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("contenido="+contenido);
  
}

//<------------------------------------------------------   INVENTARIO  ----------------------------------------------------->


function agregarArticulo() {
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;


        miFormulario = document.querySelector('#prospectForm');
        miFormulario.telefono.addEventListener('keypress', function (e){
          if (!soloNumeros(event)){
            e.preventDefault();
          }
        })

        //Solo permite introducir numeros.
        function soloNumeros(e){
            var key = e.charCode;
            return key >= 48 && key <= 57;
        }
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=17");
}


function guardarArticulo() {
  const nombre = document.getElementById('nombre').value;
  const marca = document.getElementById('marca').value;
  const categoria = document.getElementById('categoria').value;
  const medida = document.getElementById('medida').value;

  if (!nombre || !marca) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Articulo agregado",
              text: "Se agrego el articulo correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=18&nombre=" + nombre + "&categoria=" + categoria+ "&medida=" + medida+ "&marca=" + marca);

}

function movInv(campus,articulo){
$('#addProspectModal').modal('show');

divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=19&articulo=" + articulo+"&campus=" + campus );
}

function stockCampus(articulo){
  const campus = document.getElementById('corigen').value;
  
  divResultado = document.getElementById("tcantidad");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      divResultado.value = ajax.responseText;

    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=20&campus=" + campus+ "&articulo=" + articulo);
}


function ingresoArticulo(campus,articulo){
const cantidad = document.getElementById('icantidad').value;
const icomentarios = document.getElementById('icomentarios').value;

 if (!cantidad || !icomentarios || cantidad==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }


  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      console.log(ajax.responseText);


            Swal.fire({
              title: "Stock Modificado",
              text: "Ingreso de material correcto",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=21&campus=" + campus+ "&articulo=" + articulo+ "&cantidad=" + cantidad+ "&comentarios=" + icomentarios);
}



function egresoArticulo(campus,articulo){
const ecantidad = document.getElementById('ecantidad').value;
const ecomentarios = document.getElementById('ecomentarios').value;


 if (!ecantidad || !ecomentarios || ecantidad==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      if(ajax.responseText==2){
        Swal.fire({
              title: "Error",
              text: "no puedes retirar esa cantidad",
              icon: "error"
            });
      }else{
            Swal.fire({
              title: "Stock Modificado",
              text: "Salida de material correcta",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);
      }

           

    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=22&campus=" + campus+ "&articulo=" + articulo+ "&cantidad=" + ecantidad+ "&comentarios=" + ecomentarios);
}



function transferenciaArticulo(articulo){
const corigen = document.getElementById('corigen').value;
const creceptor = document.getElementById('creceptor').value;
const cantidad = document.getElementById('tcantidad').value;
const tcomentarios = document.getElementById('tcomentarios').value;


 if (!cantidad || !tcomentarios || cantidad==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }


ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      if(ajax.responseText==2){
        Swal.fire({
              title: "Error",
              text: "no puedes retirar esa cantidad",
              icon: "error"
            });
      }else{
            Swal.fire({
              title: "Stock Modificado",
              text: "Salida de material correcta",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);
      }  

    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=23&corigen=" + corigen+"&creceptor=" + creceptor+ "&articulo=" + articulo+ "&cantidad=" + cantidad+ "&comentarios=" + tcomentarios);
}


function articulo(id){
$('#addProspectModal').modal('show');

  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;

    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=24&articulo=" + id);
}

function actualizarArticulo(id){

  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=27&articulo=" + id);
}


function articuloFoto(articulo){

 divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;

    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=25&articulo=" + articulo);

}
 

function abrir() {
  var file = document.getElementById("archivo").click();
  
}

function eliminarArticulo(articulo){

Swal.fire({
  target:document.getElementById("agregarModal"),
  title: "Eliminar",
  text: "Estas seguro que deseas eliminar el articulo?",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#2e59d9",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, eliminar!"
}).then((result) => {
  if (result.isConfirmed) {

    ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      Swal.fire({
      title: "Eliminado!",
      text: "Se ha eliminado el articulo.",
      icon: "success"
    });

    $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=26&articulo=" + articulo);

  }
});

}

function guardarActualizacionArticulo(articulo){
  const nombre = document.getElementById('nombre').value;
  const marca = document.getElementById('marca').value;
  const categoria = document.getElementById('categoria').value;
  const medida = document.getElementById('medida').value;
  const comentarios = document.getElementById('comentarios').value;

  if (!nombre || !marca) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Articulo modificado",
              text: "Se agrego el modifico correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              //text: "Algo anda mal",
              text: ajax.responseText,
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=28&nombre=" + nombre + "&categoria=" + categoria+ "&medida=" + medida+ "&marca=" + marca+ "&articulo=" + articulo+ "&comentarios=" + comentarios);

}

function progress(){

  let progreso = 0;

  const nombre = document.getElementById('nombre').value;
  const apellidoPaterno = document.getElementById('apellidoPaterno').value;
  const apellidoMaterno = document.getElementById('apellidoMaterno').value;
  const rol = document.getElementById('rol').value;
  const campus = document.getElementById('campus').value;
  const usuario = document.getElementById('usuario').value;
  const telefono = document.getElementById('telefono').value;
  const correo = document.getElementById('correo').value;
  const cp = document.getElementById('1').value;
  const calle = document.getElementById('2').value;
  const localidad = document.getElementById('3').value;
  const municipio = document.getElementById('4').value;
  const estado = document.getElementById('5').value;
  const numext = document.getElementById('6').value;
  const numint = document.getElementById('7').value;

  const array = [nombre, apellidoPaterno, apellidoMaterno, rol,campus,usuario,telefono,correo,cp,calle,localidad,municipio,estado,numext,numint];


  array.forEach((element) => {
  
  
  if(!element || element=="" || element==0){
    //console.log("elemento vacio");
    //progreso --;
  }else{
    progreso +=6.66;
  }
    }); 

    //console.log(progreso);

    const barra = document.getElementById("progress");

      

    if(progreso <=30 ){
  barra.setAttribute("class", "progress-bar progress-bar-striped progress-bar-animated bg-danger");
    }else if(progreso>=31 && progreso<= 55){
  barra.setAttribute("class", "progress-bar progress-bar-striped progress-bar-animated bg-info");

    }else if(progreso>=56 && progreso<= 99){
  barra.setAttribute("class", "progress-bar progress-bar-striped progress-bar-animated bg-warning");

    }else{
      barra.setAttribute("class", "progress-bar progress-bar-striped progress-bar-animated bg-success");

    }

  barra.setAttribute("style", `width:${progreso}%`);
  
}


//------------------------------------planes de estudio---------------------------------------//


function agregarciclo(){
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=32");
}


function agregarcarrera(){
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=34");
}


function guardarciclo(){

  
  const tduracion = document.getElementById('tduracion').value;
  const finicio = document.getElementById('finicio').value;
  const ffinal = document.getElementById('ffinal').value;
  const nomen = document.getElementById('nomen').value;
  const dia = document.getElementById('guardarciclov').value;

  if (!finicio || !ffinal || !nomen || tduracion==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
          if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Ciclo agregado correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

           setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=33&finicio=" + finicio+"-01" + "&ffinal=" + ffinal+"-"+dia+"&nomen=" + nomen+"&tduracion=" + tduracion);  

}

function iniciociclo(){

    const sdur = document.getElementById('tduracion').value;
  if(sdur == 0){


    const element = document.getElementById('finicio');
    const element1 = document.getElementById('ffinal');
    const element2 = document.getElementById('nomen');

    element.value="";
    element1.value="";
    element2.value="";
  }else{
    const element = document.getElementById('finicio');
    const element1 = document.getElementById('ffinal');
    const element2 = document.getElementById('nomen');

    element.value="";
    element1.value="";
    element2.value="";

    // Remove the 'disabled' attribute
    element.removeAttribute('disabled');
  }

}


function finciclo(){

  const inicio = document.getElementById('finicio').value;
  const tduracion = document.getElementById('tduracion').value;


   const sdur = document.getElementById('tduracion').value;
  if(sdur == 0){

    Swal.fire({
              title: "Error",
              text: "selecciona el tipo de duración",
              icon: "error"
            });
    return;
  }else{

     const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
        //console.log(ajax.responseText);

        var seccion = ajax.responseText.split('|'); 

        var seccionp = seccion[0].split('-'); 

        var fformat = seccionp[0]+"-"+seccionp[1];

        document.getElementById('guardarciclov').value = seccionp[2];
        document.getElementById('ffinal').value = fformat;
        document.getElementById('nomen').value = seccion[1];
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=35&inicio=" + inicio+"-01" + "&tduracion=" + tduracion);

  }
  
  
}


function cuardarcarrera(){
  const nivel = document.getElementById('nivel').value;
  const carrera = document.getElementById('carrera').value.toUpperCase();
  const rvoe = document.getElementById('rvoe').value.toUpperCase();
  const ciclos = document.getElementById('ciclos').value.toUpperCase();
  const clasif = document.getElementById('clasif').value.toUpperCase();
  const t_duracion = document.getElementById('t_duracion').value.toUpperCase();

  if(!carrera || !rvoe || nivel==0 || ciclos==0 || clasif==0 || t_duracion==0){

    Swal.fire({
              title: "Error",
              text: "Llena todos los datos que se solicitan",
              icon: "error"
            });
    return;
  }else{

     const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
         if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Carrera agregada correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

           setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
  };
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  ajax.send("op=36&nivel="+nivel+"&carrera=" + carrera+ "&rvoe=" + rvoe+ "&ciclos=" + ciclos+ "&clasif=" + clasif+ "&t_duracion=" + t_duracion);

  }
}


function vnivel(val){
  
  if(val==0){
const element2 = document.getElementById('carrera');

    element.value="";
  }else{

  }
  
}


function infocarrera(carrera){
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      const element = divResultado;
      element.classList.add('animate__animated', 'animate__fadeIn');
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=37&carrera="+carrera);
}

function datoscampuscarrera(campus,carrera){
     //alert(`El campus es:${campus} y la carrera:${carrera}!`);

   divResultado = document.getElementById(`kjh${campus}-${carrera}`);
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      const element = divResultado;
      element.classList.add('animate__animated', 'animate__fadeIn');
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=38&campus="+campus+"&carrera="+carrera);
  
}

function contarCheckboxes(carrera,plantel,nivel,duracion,ciclos) {

      // Selecciona todos los checkboxes con la clase "miCheckbox"
      const checkboxes = document.querySelectorAll(`input[name="chk-${carrera}-${plantel}"]`);
      
       const seleccionados = [];

      // Iteramos sobre los checkboxes
      checkboxes.forEach((checkbox) => {

        let elid = checkbox.id;
        const nombre = elid.split("-");
        if (checkbox.checked) {
          

          seleccionados.push(`${nombre[3]}-1`); // Agregamos el valor si está marcado
        }else{
          seleccionados.push(`${nombre[3]}-0`); // Agregamos el valor si está marcado
        }
      });

//alert(`Checkboxes marcados: ${seleccionados.join(', ')}`);
      
       

      /* Mostramos los resultados
      if (seleccionados.length > 0) {
        alert(`Checkboxes marcados: ${seleccionados.join(', ')}`);
      } else {
        alert('No hay checkboxes marcados.');
      } 
        */
      divResultado = document.getElementById("agregarModal");
      ajax = objetoAjax();
      ajax.open("POST", "funciones/admin.php", true);
      ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
          //console.log(ajax.responseText);
          datoscampuscarrera(plantel,carrera);
        }
      };
      ajax.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded;charset=utf-8"
      );
      ajax.send("op=39&carrera="+carrera+"&plantel="+plantel+"&nivel="+nivel+"&duracion="+duracion+"&ciclos="+ciclos+"&datos="+`${seleccionados.join(', ')}`); 

}




function agregarNivel(){
   divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=40");
}


function guardarNivel(){


  const nombre = document.getElementById('nombre').value;

  if (!nombre) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete los campos",
      icon: "warning"
    });
    return;
  }



  ajax = objetoAjax();
    ajax.open("POST", "funciones/admin.php", true);
    ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
        if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Nivel agregado correctamente",
              icon: "success"
            });

            $('#exampleModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
    };
    ajax.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded;charset=utf-8"
    );
    ajax.send("op=41&nombre="+nombre);
}


function agregarModalidad(){

divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/admin.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=42");

}


function guardarModalidad(){


  const nombre = document.getElementById('nombre').value;

  if (!nombre) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete los campos",
      icon: "warning"
    });
    return;
  }



  ajax = objetoAjax();
    ajax.open("POST", "funciones/admin.php", true);
    ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
        if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Modalidad agregada correctamente",
              icon: "success"
            });

            $('#exampleModal').modal('hide');

            setTimeout(() => {
              location.reload(true);
            }, 2000);

            
          }else{
            Swal.fire({
              title: "Error",
              text: "Algo anda mal",
              icon: "error"
            });
          }
      }
    };
    ajax.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded;charset=utf-8"
    );
    ajax.send("op=43&nombre="+nombre);
}


//--------------------------------------- PROMOCIÓN -----------------------------------------//

