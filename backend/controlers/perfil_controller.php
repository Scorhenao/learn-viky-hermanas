<?php
// Este controlador permite actualizar perfil, cambiar contrasena y borrar cuenta.
session_start();
require_once __DIR__ . '/../security/perfil_csrf.php';


if (isset($_POST['btn-actualizar-perfil'])) {
    include("../connection/abrir_conexion.php");

    
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

   
    $id_usuario_data = $_SESSION['id_usuario'];

    
    $nombre_completo_data = trim($_POST['nombre_completo_form']);
    $correo_electronico_data = trim($_POST['correo_electronico_form']);

    
    $consulta_correo = $conexion->prepare(
        "SELECT id_usuario FROM $tblUsuarios
        WHERE correo_electronico = ? AND id_usuario != ?"
    );
    $consulta_correo->bind_param("si", $correo_electronico_data, $id_usuario_data);
    $consulta_correo->execute();
    $resultado_correo = $consulta_correo->get_result();

    
    if ($resultado_correo->num_rows > 0) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/perfil.php?error=correo');
        exit;
    }

    
    $actualizar_usuario = $conexion->prepare(
        "UPDATE $tblUsuarios
        SET nombre_completo = ?, correo_electronico = ?
        WHERE id_usuario = ?"
    );
    $actualizar_usuario->bind_param("ssi", $nombre_completo_data, $correo_electronico_data, $id_usuario_data);

    if (!$actualizar_usuario->execute()) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/perfil.php?error=actualizar');
        exit;
    }

    
    $_SESSION['nombre_completo'] = $nombre_completo_data;
    $_SESSION['correo_electronico'] = $correo_electronico_data;

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=actualizado');
    exit;
}


if (isset($_POST['btn-cambiar-contrasena'])) {
    include("../connection/abrir_conexion.php");

    
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

   
    $id_usuario_data = $_SESSION['id_usuario'];
    $contrasena_data = $_POST['contrasena_form'];
    $contrasena_validada_data = $_POST['contrasena_validada_form'];

  
    if ($contrasena_data != $contrasena_validada_data) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/perfil.php?error=contrasena');
        exit;
    }

    
    $contrasena_segura = password_hash($contrasena_data, PASSWORD_DEFAULT);

    
    $actualizar_contrasena = $conexion->prepare("UPDATE $tblUsuarios SET contrasena = ? WHERE id_usuario = ?");
    $actualizar_contrasena->bind_param("si", $contrasena_segura, $id_usuario_data);
    $actualizar_contrasena->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=contrasena_actualizada');
    exit;
}


if (isset($_POST['btn-eliminar-perfil'])) {
    include("../connection/abrir_conexion.php");

    
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    $id_usuario_data = $_SESSION['id_usuario'];

    
    $eliminar_usuario = $conexion->prepare("DELETE FROM $tblUsuarios WHERE id_usuario = ?");
    $eliminar_usuario->bind_param("i", $id_usuario_data);
    $eliminar_usuario->execute();

    
    session_destroy();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/registrarse.html?mensaje=cuenta_eliminada');
    exit;
}
?>
