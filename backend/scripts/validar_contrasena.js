// Leemos los parametros que vienen en la URL.
// Ejemplo: registrarse.html?error=contrasena
const params = new URLSearchParams(window.location.search);

// Buscamos el parrafo donde se va a mostrar el mensaje de error.
const mensajeError = document.getElementById('mensaje-error');

// Si el backend mando error=terminos, mostramos este mensaje.
if (params.get('error') === 'terminos') {
    mensajeError.textContent = 'Debes aceptar los terminos y condiciones.';
}

// Si el backend mando error=contrasena, mostramos este mensaje.
if (params.get('error') === 'contrasena') {
    mensajeError.textContent = 'Las contrasenas no son iguales.';
}
