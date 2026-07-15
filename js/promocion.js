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
    ajax.open("POST", "funciones/promocion.php", true);
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

function agregarProspectos() {
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/promocion.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {
      //mostrar resultados en esta capa
      divResultado.innerHTML = ajax.responseText;

        const intervalo = setInterval(progress, 500);

        // Detener después de 5 minutos (300,000 ms)
        setTimeout(() => {
          clearInterval(intervalo);
          //console.log("Se detuvo la ejecución.");
        }, 400000);
    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=1");
}

function datos(id) {
  divResultado = document.getElementById("datosModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/promocion.php", true);
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




function progress(){

  let progreso = 0;

  const nombre = document.getElementById('nombre').value;
  const apellidoPaterno = document.getElementById('apellidoPaterno').value;
  const apellidoMaterno = document.getElementById('apellidoMaterno').value;
  const campus = document.getElementById('campus').value;
  const nivel = document.getElementById('nivel').value;
  const carrera = document.getElementById('carrera').value;
  const modalidad = document.getElementById('modalidad').value;
  const ciclo = document.getElementById('ciclo').value;
  const canal = document.getElementById('canal').value;
  const difusion = document.getElementById('difusion').value;
  const telefono = document.getElementById('telefono').value;
  const correo = document.getElementById('correo').value;
  const cp = document.getElementById('1').value;
  const calle = document.getElementById('2').value;
  const localidad = document.getElementById('3').value;
  const municipio = document.getElementById('4').value;
  const estado = document.getElementById('5').value;
  const numext = document.getElementById('6').value;
  const numint = document.getElementById('7').value;
 
  const array = [nombre, apellidoPaterno, apellidoMaterno,campus,nivel,carrera,modalidad,ciclo,canal,difusion,telefono,correo,cp,calle,localidad,municipio,estado,numext,numint];


  array.forEach((element) => {
  
  
  if(!element || element=="" || element==0){
    //console.log("elemento vacio");
    //progreso --;
  }else{
    progreso +=5.30;
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



function guardarProspecto() {
  const nombre = document.getElementById('nombre').value;
  const apellidoPaterno = document.getElementById('apellidoPaterno').value;
  const apellidoMaterno = document.getElementById('apellidoMaterno').value;
  const campus = document.getElementById('campus').value;
  const nivel = document.getElementById('nivel').value;
  const carrera = document.getElementById('carrera').value;
  const modalidad = document.getElementById('modalidad').value;
  const ciclo = document.getElementById('ciclo').value;
  const canal = document.getElementById('canal').value;
  const difusion = document.getElementById('difusion').value;
  const telefono = document.getElementById('telefono').value;
  const correo = document.getElementById('correo').value;
  const cp = document.getElementById('1').value;
  const calle = document.getElementById('2').value;
  const localidad = document.getElementById('3').value;
  const municipio = document.getElementById('4').value;
  const estado = document.getElementById('5').value;
  const numext = document.getElementById('6').value;
  const numint = document.getElementById('7').value;
 
  const array = [nombre, apellidoPaterno, apellidoMaterno,campus,nivel,carrera,modalidad,ciclo,canal,difusion,telefono,correo,cp,calle,localidad,municipio,estado,numext,numint];
array.forEach((element) => {
  
  
  if(!element || element=="" || element==0){
    //console.log("elemento vacio");
     Swal.fire({
      title: "Verifica",
      text: "Faltan datos, revisa nuevamente.",
      icon: "error"
    });
    return;
  }else{

  }
    }); 



  const ajax = new XMLHttpRequest();
  ajax.open("POST", "funciones/promocion.php", true);
  ajax.onreadystatechange = function () {
      if (ajax.readyState == 4 && ajax.status == 200) {

        if(ajax.responseText==1){
            Swal.fire({
              title: "Completado",
              text: "Plantel agregado correctamente",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');
            document.getElementById('prospectForm').reset(); 

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
  ajax.send("op=2&nombre="+nombre+"&apellidoPaterno="+apellidoPaterno+"&apellidoMaterno="+apellidoMaterno+"&campus="+campus+"&nivel="+nivel+"&carrera="+carrera+"&modalidad="+modalidad+"&ciclo="+ciclo+"&canal="+canal+"&difusion="+difusion+"&telefono="+telefono+"&correo="+correo+"&cp="+cp+"&calle="+calle+"&localidad="+localidad+"&municipio="+municipio+"&estado="+estado+"&numext="+numext+"&numint="+numint);
}

function codigoPostal(){

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
    ajax.open("POST", "funciones/promocion.php", true);
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
    ajax.send("cp="+cod+"&op=3");
  }

}

function listas1(){
  
  const nivel = document.getElementById('nivel').value;
  const campus = document.getElementById('campus').value;

  modalidad = document.getElementById("modalidad");
  
    divResultado = document.getElementById("carrera");
    ajax = objetoAjax();
    ajax.open("POST", "funciones/promocion.php", true);
    ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
        //mostrar resultados en esta capa
        divResultado.innerHTML = ajax.responseText;
        modalidad.innerHTML="";
        modalidad.value=0;
      }
    };
    ajax.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded;charset=utf-8"
    );
    ajax.send("op=4&nivel="+nivel+"&campus="+campus);

  /*
  if (nivel==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete los campos",
      icon: "warning"
    });
    return;
  }else{

    
    const carrera = document.getElementById('carreras');

    // Verifica si está deshabilitado
    if (carrera.disabled) {
      console.log('El botón está deshabilitado.');
      carrera.removeAttribute("disabled");
    } else {
      carrera.setAttribute("disabled");
      console.log('El botón está habilitado.');
    }

    

  }

  */
}


//agregar animacion onst element = divResultado;
//element.classList.add('animate__animated', 'animate__fadeIn');
function listas2(){

  const carrera = document.getElementById('carrera').value;
  const campus = document.getElementById('campus').value;

    divResultado = document.getElementById("modalidad");
    ajax = objetoAjax();
    ajax.open("POST", "funciones/promocion.php", true);
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
    ajax.send("op=5&nivel="+nivel+"&carrera="+carrera+"&campus="+campus);
}


function prospecto(id){

  divResultado = document.getElementById("data");
    ajax = objetoAjax();
    ajax.open("POST", "funciones/promocion.php", true);
    ajax.onreadystatechange = function () {
      if (ajax.readyState == 4) {
        //mostrar resultados en esta capa
        

      divResultado.innerHTML = ajax.responseText;

      contenedor = document.getElementById("contenedor-"+id);
      const element = contenedor;
      
      element.classList.add('animate__animated', 'animate__fadeIn');

      }
    };
    ajax.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded;charset=utf-8"
    );
    ajax.send("op=6&id="+id);

}