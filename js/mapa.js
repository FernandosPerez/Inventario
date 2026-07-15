let map;
let lugares = []; // Inicializamos el arreglo vacío

async function fetchLugares() {
  try {
    const response = await fetch("funciones/chequeos.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded;charset=utf-8",
      },
      body: "op=4", // Cambia esto si necesitas enviar parámetros adicionales
    });

    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.statusText}`);
    }

    const data = await response.json();

    // Verificar si la respuesta tiene un error
    if (data.error) {
      console.error("Error del servidor:", data.error);
      return [];
    }

    lugares = await data;
  } catch (error) {
    console.error("Error al obtener los datos del servidor:", error);
    return [];
  }
}

async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");

  map = new Map(document.getElementById("map"), {
    enableHighAccuracy: true,
    zoom: 10,
    center: { lat: 19.713551, lng: -99.973572 },
    mapId: "DEMO_MAP_ID",
  });

  await fetchLugares();

  if (lugares && lugares.length > 0) {
    lugares.forEach((element) => {
      const contentString = `
        <div id="content">
          <h5>${element.nombre}</h5>
          <p>${element.hora}</p>
        </div>
      `;

      const infowindow = new google.maps.InfoWindow({
        content: contentString,
      });

      const marker = new google.maps.Marker({
        map,
        position: element.coords,
        title: element.nombre,
      });

      marker.addListener("click", () => {
        infowindow.open(map, marker);
      });
    });
  } else {
    console.warn("El arreglo de lugares está vacío.");
  }




  
}

initMap();

/*
function actualizarContenido() {
  initMap();
  console.log("se ha refrescado el contenido");
}

window.onload = function() {
  const intervalo = 5000;
  setInterval(actualizarContenido, intervalo);
}; */