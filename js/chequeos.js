const funcionInit = () => {
	if (!"geolocation" in navigator) {
		return alert("Tu navegador no soporta el acceso a la ubicación. Intenta con otro");
	}

	const $latitud = document.querySelector("#latitud"),
		$longitud = document.querySelector("#longitud")
		//$enlace = document.querySelector("#enlace");


	const onUbicacionConcedida = ubicacion => {
		//console.log("Tengo la ubicación: ", ubicacion);
		const coordenadas = ubicacion.coords;
		$latitud.innerText = coordenadas.latitude;
		$longitud.innerText = coordenadas.longitude;
		//$enlace.href = `https://www.google.com/maps/@${coordenadas.latitude},${coordenadas.longitude},20z`;
	}
	const onErrorDeUbicacion = err => {

		$latitud.innerText = "Error obteniendo ubicación: " + err.message;
		$longitud.innerText = "Error obteniendo ubicación: " + err.message;
		//console.log("Error obteniendo ubicación: ", err);
	}

	const opcionesDeSolicitud = {
		enableHighAccuracy: true, // Alta precisión
		maximumAge: 0, // No queremos caché
		timeout: 5000 // Esperar solo 5 segundos
	};

	$latitud.innerText = "Cargando...";
	$longitud.innerText = "Cargando...";
	navigator.geolocation.getCurrentPosition(onUbicacionConcedida, onErrorDeUbicacion, opcionesDeSolicitud);

};
document.addEventListener("DOMContentLoaded", funcionInit);


function activar(){
	var html5QrcodeScanner = new Html5QrcodeScanner(
		"reader", { fps: 30, qrbox: 250 });
	var lastResult, countResults = 0;
	function onScanSuccess(qrCodeMessage) {
		if (qrCodeMessage !== lastResult) {
			++countResults;
			lastResult = qrCodeMessage;
			texto			= lastResult;

      var latitud = $('#latitud').text();
      var longitud = $('#longitud').text();
      
                       //divResultado = document.getElementById('respuesta');
                        ajax=objetoAjax();
                        ajax.open("POST", "funciones/chequeos.php",true);
                        ajax.onreadystatechange=function() {
                          if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            //divResultado.innerHTML = ajax.responseText
                            if(ajax.responseText==0){
                              Swal.fire({
                                title: "ERROR",
                                text: "No se capturo correctamente el codigo QR",
                                icon: "error"
                                });
                            }else if(ajax.responseText==1 || ajax.responseText==2 ){
                              Swal.fire({
                                title: "Capturado",
                                text: "Registro capturado correctamente",
                                icon: "success"
                                });

								setTimeout(() => {
									window.location.reload();
								}, 3000);
                            }
                          }
                        }
                        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=utf-8");
                        ajax.send("texto="+texto+"&coordenadas="+latitud+"|"+longitud+ 
                              "&op=1");
			html5QrcodeScanner.clear();
		}
	}
	html5QrcodeScanner.render(onScanSuccess);

 // btnchk = document.getElementById('html5-qrcode-button-camera-permission');
 // btnchk.click();

}




function unidad(eco){
	var html5QrcodeScanner = new Html5QrcodeScanner(
		"reader", { fps: 30, qrbox: 250 });
	var lastResult, countResults = 0;
	function onScanSuccess(qrCodeMessage) {
		if (qrCodeMessage !== lastResult) {
			++countResults;
			lastResult = qrCodeMessage;
			texto			= lastResult;

                        ajax=objetoAjax();
                        ajax.open("POST", "funciones/chequeos.php",true);
                        ajax.onreadystatechange=function() {
                          if (ajax.readyState==4) {
							console.log(ajax.responseText);
                            if(ajax.responseText==0){
                              Swal.fire({
                                title: "ERROR",
                                text: "este codigo QR no pertenece a una unidad",
                                icon: "error"
                                });
                            }else if(ajax.responseText==1){
                              Swal.fire({
                                title: "Unidad Registrada",
                                text: "se registro unidad",
                                icon: "success"
                                });
                            }else if(ajax.responseText==2){
								Swal.fire({
								  title: "Unidad cambiada",
								  text: "se cambio la unidad",
								  icon: "success"
								  });
							  }
                          }
                        }
                        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=utf-8");
                        ajax.send("texto="+texto+
                              "&op="+eco);
			html5QrcodeScanner.clear();
		}
	}
	html5QrcodeScanner.render(onScanSuccess);

  btnchk = document.getElementById('html5-qrcode-button-camera-permission');
  btnchk.click();

}