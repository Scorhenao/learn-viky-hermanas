<?php
// Iniciamos la sesion para poder acceder a los datos guardados en $_SESSION.
session_start();

// Borramos la sesion completa. Esto hace que el usuario quede desconectado.
session_destroy();

// Despues de cerrar sesion, mandamos al usuario al login.
header('Location:../../../../learn-viky/iniciar_sesion.html?mensaje=sesion_cerrada');
exit;
?>
