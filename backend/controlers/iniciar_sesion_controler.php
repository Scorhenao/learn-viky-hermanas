<?php
// session_start permite guardar datos del usuario mientras navega por la pagina.
session_start();

// Este controlador solo funciona si se envio el formulario de iniciar sesion.
if (isset($_POST['boton_iniciar_sesion'])) {
    // Abrimos conexion a la base de datos.
    include("../connection/abrir_conexion.php");

    // Recibimos el nombre y la contrasena escritos en el formulario.
    $nombre_completo_data = trim($_POST['nombre_completo_form']);
    $contrasena_data = $_POST['contrasena_form'];

    // Buscamos un usuario con ese nombre.
    $consulta_usuario = $conexion->prepare(
        "SELECT id_usuario, nombre_completo, correo_electronico, contrasena, perfil
        FROM $tblUsuarios
        WHERE nombre_completo = ?
        LIMIT 1"
    );
    $consulta_usuario->bind_param("s", $nombre_completo_data);
    $consulta_usuario->execute();
    $resultado_usuario = $consulta_usuario->get_result();

    // Si no encontro ningun usuario, volvemos al login con error.
    if ($resultado_usuario->num_rows == 0) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=usuario');
        exit;
    }

    // Convertimos el resultado en un arreglo para poder usar sus datos.
    $usuario = $resultado_usuario->fetch_assoc();

    // password_verify compara la contrasena escrita con la contrasena protegida de la DB.
    $contrasena_correcta = password_verify($contrasena_data, $usuario['contrasena']);

    // Esto ayuda si antes se guardo alguna contrasena sin password_hash.
    // Si coincide, dejamos entrar y de una vez la actualizamos protegida.
    if (!$contrasena_correcta && $contrasena_data == $usuario['contrasena']) {
        $contrasena_correcta = true;
        $nueva_contrasena = password_hash($contrasena_data, PASSWORD_DEFAULT);
        $actualizar_contrasena = $conexion->prepare("UPDATE $tblUsuarios SET contrasena = ? WHERE id_usuario = ?");
        $actualizar_contrasena->bind_param("si", $nueva_contrasena, $usuario['id_usuario']);
        $actualizar_contrasena->execute();
    }

    // Si la contrasena esta mal, volvemos al login.
    if (!$contrasena_correcta) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=contrasena');
        exit;
    }

    // Guardamos datos importantes en la sesion.
    // Gracias a esto otras paginas saben que usuario esta conectado.
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
    $_SESSION['correo_electronico'] = $usuario['correo_electronico'];
    $_SESSION['perfil'] = $usuario['perfil'];

    // Cerramos conexion y mandamos al perfil.
    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=sesion_iniciada');
    exit;
}
?>
