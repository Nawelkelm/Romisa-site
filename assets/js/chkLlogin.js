//Ejecutando funciones
document.getElementById("btn__iniciar-sesion").addEventListener("click", iniciarSesion);
document.getElementById("btn__registrarse").addEventListener("click", register);
window.addEventListener("resize", anchoPage);

//Declarando variables
var formulario_login = document.querySelector(".formulario__login");
var formulario_register = document.querySelector(".formulario__register");
var contenedor_login_register = document.querySelector(".contenedor__login-register");
var caja_trasera_login = document.querySelector(".caja__trasera-login");
var caja_trasera_register = document.querySelector(".caja__trasera-register");

    //FUNCIONES

function anchoPage(){

    if (window.innerWidth > 850){
        caja_trasera_register.style.display = "block";
        caja_trasera_login.style.display = "block";
    }else{
        caja_trasera_register.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.display = "none";
        formulario_login.style.display = "block";
        contenedor_login_register.style.left = "0px";
        formulario_register.style.display = "none";   
    }
}

anchoPage();


    function iniciarSesion(){   
        if (window.innerWidth > 850){
            formulario_login.style.display = "block";
            contenedor_login_register.style.left = "10px";
            formulario_register.style.display = "none";
            caja_trasera_register.style.opacity = "1";
            caja_trasera_login.style.opacity = "0";
        }else{
            formulario_login.style.display = "block";
            contenedor_login_register.style.left = "0px";
            formulario_register.style.display = "none";
            caja_trasera_register.style.display = "block";
            caja_trasera_login.style.display = "none";
        }
    }

    function register(){
        if (window.innerWidth > 850){
            formulario_register.style.display = "block";
            contenedor_login_register.style.left = "410px";
            formulario_login.style.display = "none";
            caja_trasera_register.style.opacity = "0";
            caja_trasera_login.style.opacity = "1";
        }else{
            formulario_register.style.display = "block";
            contenedor_login_register.style.left = "0px";
            formulario_login.style.display = "none";
            caja_trasera_register.style.display = "none";
            caja_trasera_login.style.display = "block";
            caja_trasera_login.style.opacity = "1";
        }
}
// Seleccionar los formularios
const formularioLogin = document.querySelector(".formulario__login");
const formularioRegister = document.querySelector(".formulario__register");

// Función para mostrar mensajes de error
function mostrarError(campo, mensaje) {
    const errorDiv = campo.parentElement.querySelector(".error");
    if (!errorDiv) {
        const nuevoErrorDiv = document.createElement("div");
        nuevoErrorDiv.className = "error";
        nuevoErrorDiv.style.color = "red";
        nuevoErrorDiv.style.fontSize = "12px";
        nuevoErrorDiv.innerText = mensaje;
        campo.parentElement.appendChild(nuevoErrorDiv);
    }
}

// Función para limpiar mensajes de error
function limpiarErrores(formulario) {
    const errores = formulario.querySelectorAll(".error");
    errores.forEach(error => error.remove());
}

// Validar formato del correo electrónico
function validarEmail(email) {
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regexEmail.test(email);
}
 
// Validar el formulario de login
formularioLogin.addEventListener("submit", function (event) {
    limpiarErrores(formularioLogin);
    const email = formularioLogin.querySelector('input[name="email"]');
    const password = formularioLogin.querySelector('input[name="password"]');
    let valido = true;

    // Validar correo
    if (!email.value.trim()) {
        mostrarError(email, "El correo electrónico es obligatorio.");
        valido = false;
    } else if (!validarEmail(email.value)) {
        mostrarError(email, "El formato del correo no es válido.");
        valido = false;
    }

    // Validar contraseña
    if (!password.value.trim()) {
        mostrarError(password, "La contraseña es obligatoria.");
        valido = false;
    }

    if (!valido) {
        event.preventDefault(); // Evitar el envío del formulario
    }
});

// Validar el formulario de registro
formularioRegister.addEventListener("submit", function (event) {
    limpiarErrores(formularioRegister);
    const nombre = formularioRegister.querySelector('input[name="nombre"]');
    const email = formularioRegister.querySelector('input[name="email"]');
    const password = formularioRegister.querySelector('input[name="password"]');
    let valido = true;

    // Validar nombre
    if (!nombre.value.trim()) {
        mostrarError(nombre, "El nombre completo es obligatorio.");
        valido = false;
    }

    // Validar correo
    if (!email.value.trim()) {
        mostrarError(email, "El correo electrónico es obligatorio.");
        valido = false;
    } else if (!validarEmail(email.value)) {
        mostrarError(email, "El formato del correo no es válido.");
        valido = false;
    }

    // Validar contraseña
    if (!password.value.trim()) {
        mostrarError(password, "La contraseña es obligatoria.");
        valido = false;
    } else if (password.value.length < 6) {
        mostrarError(password, "La contraseña debe tener al menos 6 caracteres.");
        valido = false;
    }

    if (!valido) {
        event.preventDefault(); // Evitar el envío del formulario
    }
});
