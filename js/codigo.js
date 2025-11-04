'use strict'

const iconoBarras = document.querySelector("#iconoBarras");
const menuInicio = document.querySelector("#menuInicio");
const span = document.querySelectorAll("span");

const botonUsuarios = document.querySelector(".botonUsuarios");
const ventanaModal = document.querySelector("#ventanaModal");
const cerrar = document.querySelector(".cerrar");

function ocultar(){
    menuInicio.classList.toggle("despelgar");
    for(var i = 0; i < span.length; i++){
        span[i].classList.toggle("oculto");
    }
}

function abrirModal(){
    ventanaModal.classList.add("modalDialogotarget");
}

function ocultarModal(){
    ventanaModal.classList.remove("modalDialogotarget");
}

iconoBarras.addEventListener("click", ocultar);
botonUsuarios.addEventListener("click", abrirModal);
cerrar.addEventListener("click", ocultarModal);

window.addEventListener("click", (e) => {
    if(e.target == ventanaModal){
        ventanaModal.classList.remove("modalDialogotarget");
    }
});