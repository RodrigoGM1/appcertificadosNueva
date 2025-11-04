const formResultado = document.getElementById('formResultado');
const input = document.createElement('input');
const boton = document.createElement('button');

function leerQr(decodedText, decodedResult) {
    console.log(`Scan result: ${decodedText}`, decodedResult);

    input.value = decodedText;
    input.name = "manifiesto";
    boton.textContent = "Buscar";

    formResultado.appendChild(input);
    formResultado.appendChild(boton);
}

var html5QrcodeScanner = new Html5QrcodeScanner(
	"reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(leerQr);

function activarCamra(){
    var video = document.getElementById("camaraOn");
    console.log(video);
	navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMEdia;
    if (navigator.getUserMedia) {
        navigator.getUserMedia({video: true}, handleVideo, videoError);
    }
    function handleVideo(stream) {
        video.src = window.URL.createObjectURL(stream);
    }
    function videoError(e) {
        alert("La camara No esta funcionando Permita el acceso")
    }
}