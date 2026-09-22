<?php
// Iniciamos la sesion para poder acceder a los datos guardados en $_SESSION.
session_start();


session_destroy();


header('Location:../../../../learn-viky/iniciar_sesion.html?mensaje=sesion_cerrada');
exit;
?>
