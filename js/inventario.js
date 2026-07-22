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
    ajax.open("POST", "funciones/inventario.php", true);
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


//<------------------------------------------------------   INVENTARIO  ----------------------------------------------------->


function agregarArticulo() {
  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/inventario.php", true);
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

function previewFotoArticulo(input) {
    const preview = document.getElementById('preview_foto_articulo');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}"
                style="max-height:120px; border-radius:6px; border:1px solid #ddd; padding:3px;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function guardarArticulo() {
    const nombre    = document.getElementById('nombre').value;
    const marca     = document.getElementById('marca').value;
    const categoria = document.getElementById('categoria').value;
    const medida    = document.getElementById('medida').value;
    const fotoInput = document.getElementById('foto_articulo');

    if (!nombre || !marca) {
        Swal.fire({
            title: "Datos faltantes",
            text: "Por favor, complete todos los campos",
            icon: "warning"
        });
        return;
    }

    // FormData en lugar de urlencoded para poder enviar el archivo
    const formData = new FormData();
    formData.append('op',       18);
    formData.append('nombre',   nombre);
    formData.append('marca',    marca);
    formData.append('categoria', categoria);
    formData.append('medida',   medida);

    if (fotoInput && fotoInput.files[0]) {
        formData.append('foto', fotoInput.files[0]);
    }

    const ajax = new XMLHttpRequest();
    ajax.open("POST", "funciones/inventario.php", true);
    // ⚠️ NO poner setRequestHeader Content-Type con FormData
    // el navegador lo pone solo con el boundary correcto para multipart
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            if (ajax.responseText == 1) {
                Swal.fire({
                    title: "Artículo agregado",
                    text: "Se agregó el artículo correctamente",
                    icon: "success"
                });
                $('#addProspectModal').modal('hide');
                setTimeout(() => { location.reload(true); }, 2000);
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Algo anda mal",
                    icon: "error"
                });
            }
        }
    };
    ajax.send(formData); // se manda el FormData directamente
}

function movInv(campus,articulo){
$('#addProspectModal').modal('show');

divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/inventario.php", true);
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
  ajax.open("POST", "funciones/inventario.php", true);
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

// REEMPLAZA la función filtrar() en inventario.js por esta:
function filtrar() {
    const almacen    = document.getElementById('almacen').value;
    const existencias = document.getElementById('existencias').value;
    const foto       = document.getElementById('foto').value;

    // El select de plantel puede estar disabled (no-admin), leer con fallback
    const plantelEl = document.getElementById('plantel');
    const plantel   = plantelEl.disabled
        ? plantelEl.value   // aunque esté disabled, el PHP ya fuerza el campus
        : plantelEl.value;

    window.location.href = `inventario.php?almacen=${almacen}&plantel=${plantel}&existencias=${existencias}&foto=${foto}`;
}

function ingresoArticulo(campus,articulo){
const cantidad = document.getElementById('icantidad').value;
const icomentarios = document.getElementById('icomentarios').value;

 if (!cantidad || icomentarios==0 || cantidad==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }


  ajax = objetoAjax();
  ajax.open("POST", "funciones/inventario.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      //console.log(ajax.responseText);


            Swal.fire({
              title: "Stock Modificado",
              text: "Ingreso de material correcto",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            // setTimeout(() => {
            //   location.reload(true);
            // }, 2000);
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
const usuariofinal = document.getElementById('usuariofinal').value;
const ecomentarios = document.getElementById('ecomentarios').value;


 if (!ecantidad || ecomentarios==0 || ecantidad==0 || usuariofinal==0) {
   
    Swal.fire({
      title: "Datos faltantes",
      text: "Por favor, complete todos los campos",
      icon: "warning"
    });
    return;
  }

ajax = objetoAjax();
  ajax.open("POST", "funciones/inventario.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      if(ajax.responseText==2){
        Swal.fire({
              title: "Sin stock suficiente",
              text: "No puedes retirar una cantidad mayor de la existente",
              icon: "error"
            });
      }else{
            Swal.fire({
              title: "Stock Modificado",
              text: "Salida de material correcta",
              icon: "success"
            });

            $('#addProspectModal').modal('hide');

            // setTimeout(() => {
            //   location.reload(true);
            // }, 2000);
      }

           

    }
  };
  ajax.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=utf-8"
  );
  ajax.send("op=22&campus=" + campus+ "&articulo=" + articulo+ "&cantidad=" + ecantidad+ "&comentarios=" + ecomentarios+"&usuariofinal="+usuariofinal);
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
  ajax.open("POST", "funciones/inventario.php", true);
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

            // setTimeout(() => {
            //   location.reload(true);
            // }, 2000);
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
  ajax.open("POST", "funciones/inventario.php", true);
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
  ajax.open("POST", "funciones/inventario.php", true);
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

function abrir() {
  document.getElementById("archivo").click();
}

function previsualizarFoto(input) {
  var preview = document.getElementById('previstaFoto');
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
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
  ajax.open("POST", "funciones/inventario.php", true);
  ajax.onreadystatechange = function () {
    if (ajax.readyState == 4) {

      Swal.fire({
      title: "Eliminado!",
      text: "Se ha eliminado el articulo.",
      icon: "success"
    });

    $('#addProspectModal').modal('hide');

            if (typeof table !== 'undefined') {
              sessionStorage.setItem('dtInventarioPage', table.page());
            }
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

function guardarActualizacionArticulo(articulo) {
    const nombre      = document.getElementById('nombre').value;
    const marca       = document.getElementById('marca').value;
    const categoria   = document.getElementById('categoria').value;
    const medida      = document.getElementById('medida').value;
    const comentarios = document.getElementById('comentarios').value;
    const fotoInput   = document.getElementById('foto_articulo');

    if (!nombre || !marca || !comentarios) {
        Swal.fire({
            title: "Datos faltantes",
            text: "Por favor, complete todos los campos",
            icon: "warning"
        });
        return;
    }

    const formData = new FormData();
    formData.append('op',          28);
    formData.append('articulo',    articulo);
    formData.append('nombre',      nombre);
    formData.append('marca',       marca);
    formData.append('categoria',   categoria);
    formData.append('medida',      medida);
    formData.append('comentarios', comentarios);

    // Solo adjuntar si el usuario seleccionó una foto
    if (fotoInput && fotoInput.files.length > 0) {
        formData.append('foto', fotoInput.files[0]);
    }

    const ajax = new XMLHttpRequest();
    ajax.open("POST", "funciones/inventario.php", true);
    // ← SIN setRequestHeader: FormData pone el boundary correcto solo
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            if (ajax.responseText == 1) {
                Swal.fire({
                    title: "Artículo modificado",
                    text: "Se modificó el artículo correctamente",
                    icon: "success"
                });

                $('#addProspectModal').modal('hide');

                if (typeof table !== 'undefined') {
                    sessionStorage.setItem('dtInventarioPage', table.page());
                }
                setTimeout(() => {
                    location.reload(true);
                }, 2000);

            } else {
                Swal.fire({
                    title: "Error",
                    text: ajax.responseText,
                    icon: "error"
                });
            }
        }
    };
    ajax.send(formData);
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



function movInvCampus(campus,articulo){
$('#addProspectModal').modal('show');

divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/inventario.php", true);
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
  ajax.send("op=29&articulo=" + articulo+"&campus=" + campus );
}



function articuloCampus(id){
$('#addProspectModal').modal('show');

  divResultado = document.getElementById("agregarModal");
  ajax = objetoAjax();
  ajax.open("POST", "funciones/inventario.php", true);
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
  ajax.send("op=30&articulo=" + id);
}


async function historialInventario() {
Swal.fire({
    title: "Busqueda de acuses de Movimientos",
    html: `
    <div class="text-center">
    <img src="./img/svg/search.svg" class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 20rem;">
    </div>
<div class="row w-100" >

<div class="col-md-6">
<div class="form-group justify-content-center">
  <label for="finicio" >Fecha de inicio:</label>
  <input type="date" id="finicio" class="form-control">
</div>
</div>

<div class="col-md-6">
<div class="form-group justify-content-center">
  <label for="ffin" >Fecha final:</label>
  <input type="date" id="ffin" class="form-control">
  </div>
</div>

</div>
        `,
    focusConfirm: true,
    allowOutsideClick: true,
  preConfirm: () => {


      var finicio = document.getElementById("finicio").value;
      var ffin = document.getElementById("ffin").value;


      if(!finicio || !ffin){
        //alert("Coloca un rango de fechas válido")

        if(!finicio){
          if ($("#ffin").hasClass('border border-danger')) {
              $("#ffin").removeClass("border border-danger");
          }else{
            if(!ffin){
              $("#ffin").addClass("border border-danger");
            }else{
              $("#ffin").removeClass("border border-danger");
            }
          }

          //clase de la animación

          if ($("#finicio").hasClass('border border-danger')) {
          }else{
            $("#finicio").addClass("border border-danger");
          }
         

          if ($("#finicio").hasClass('animate__bounceIn')) {
            $("#finicio").removeClass("animate__bounceIn");
            $("#finicio").addClass("animate__bounceIn");

          }else{
            $("#finicio").addClass("animate__bounceIn");
          }
         


          Swal.showValidationMessage('Ingresa la fecha inicial');
            return false;

        }
        
        if(!ffin){
          if ($("#finicio").hasClass('border border-danger')) {
              $("#finicio").removeClass("border border-danger");
          }else{
            if(!finicio){
              $("#finicio").addClass("border border-danger");
            }else{
              $("#finicio").removeClass("border border-danger");
            }
          }

          //clase de la animación

          if ($("#ffin").hasClass('border border-danger')) {
          }else{
            $("#ffin").addClass("border border-danger");
          }

          if ($("#ffin").hasClass('animate__bounceIn')) {
            $("#ffin").removeClass("animate__bounceIn");
            $("#ffin").addClass("animate__bounceIn");

          }else{
            $("#ffin").addClass("animate__bounceIn");
          }
          Swal.showValidationMessage('Ingresa la fecha final');
            return false;
        }
        
      }


    return ; // Allow closing and pass the name as the result
  }
}).then((result) => {
  if (result.isConfirmed) {
    
    divResultado = document.getElementById("impresion");

      divResultado.innerHTML = '<iframe id="ulum_tur_d" name="ulum_tur_d" style="height:auto; width:100%; border: 2px gray solid; visibility: hidden;" src="funciones/pdf/inventario_historial.php?finicio=' +finicio.value +'&ffin='+ffin.value+'"></iframe>';
      window.frames["ulum_tur_d"].focus();
      setTimeout(function () { window.frames["ulum_tur_d"].print(); }, 200);
      setTimeout(function () { window.frames["ulum_tur_d"].close(); }, 200);
  }
});

}

async function recibos() {
Swal.fire({
    title: "Recibos",
    html: `
    <div class="text-center">
    <img src="./img/svg/search.svg" class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 20rem;">
    </div>
<div class="row w-100" >

<div class="col-md-6">
<div class="form-group justify-content-center">
  <label for="finicio" >Fecha de inicio:</label>
  <input type="date" id="finicio" class="form-control">
</div>
</div>

<div class="col-md-6">
<div class="form-group justify-content-center">
  <label for="ffin" >Fecha final:</label>
  <input type="date" id="ffin" class="form-control">
  </div>
</div>

</div>
        `,
    focusConfirm: true,
    allowOutsideClick: true,
  preConfirm: () => {


      var finicio = document.getElementById("finicio").value;
      var ffin = document.getElementById("ffin").value;


      if(!finicio || !ffin){
        //alert("Coloca un rango de fechas válido")

        if(!finicio){
          if ($("#ffin").hasClass('border border-danger')) {
              $("#ffin").removeClass("border border-danger");
          }else{
            if(!ffin){
              $("#ffin").addClass("border border-danger");
            }else{
              $("#ffin").removeClass("border border-danger");
            }
          }

          //clase de la animación

          if ($("#finicio").hasClass('border border-danger')) {
          }else{
            $("#finicio").addClass("border border-danger");
          }
         

          if ($("#finicio").hasClass('animate__bounceIn')) {
            $("#finicio").removeClass("animate__bounceIn");
            $("#finicio").addClass("animate__bounceIn");

          }else{
            $("#finicio").addClass("animate__bounceIn");
          }
         


          Swal.showValidationMessage('Ingresa la fecha inicial');
            return false;

        }
        
        if(!ffin){
          if ($("#finicio").hasClass('border border-danger')) {
              $("#finicio").removeClass("border border-danger");
          }else{
            if(!finicio){
              $("#finicio").addClass("border border-danger");
            }else{
              $("#finicio").removeClass("border border-danger");
            }
          }

          //clase de la animación

          if ($("#ffin").hasClass('border border-danger')) {
          }else{
            $("#ffin").addClass("border border-danger");
          }

          if ($("#ffin").hasClass('animate__bounceIn')) {
            $("#ffin").removeClass("animate__bounceIn");
            $("#ffin").addClass("animate__bounceIn");

          }else{
            $("#ffin").addClass("animate__bounceIn");
          }
          Swal.showValidationMessage('Ingresa la fecha final');
            return false;
        }
        
      }


    return ; // Allow closing and pass the name as the result
  }
}).then((result) => {
  if (result.isConfirmed) {
    
    divResultado = document.getElementById("impresion");

      divResultado.innerHTML = '<iframe id="ulum_tur_d" name="ulum_tur_d" style="height:auto; width:100%; border: 2px gray solid; visibility: hidden;" src="funciones/pdf/recibos.php?finicio=' +finicio.value +'&ffin='+ffin.value+'"></iframe>';
      window.frames["ulum_tur_d"].focus();
      setTimeout(function () { window.frames["ulum_tur_d"].print(); }, 200);
      setTimeout(function () { window.frames["ulum_tur_d"].close(); }, 200);
  }
});

}