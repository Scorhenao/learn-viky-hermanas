<?php
// Este controlador permite actualizar perfil, cambiar contrasena y borrar cuenta.
session_start();

// Actualizar datos del perfil.
if (isset($_POST['btn-actualizar-perfil'])) {
    include("../connection/abrir_conexion.php");

    // Si no hay usuario conectado, no se puede actualizar nada.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Tomamos el usuario conectado desde la sesion.
    $id_usuario_data = $_SESSION['id_usuario'];

    // Recibimos los datos nuevos que llegaron desde perfil.php.
    $nombre_completo_data = trim($_POST['nombre_completo_form']);
    $correo_electronico_data = trim($_POST['correo_electronico_form']);

    // Antes de actualizar, revisamos que el correo no lo tenga otro usuario.
    $consulta_correo = $conexion->prepare(
        "SELECT id_usuario FROM $tblUsuarios
        WHERE correo_electronico = ? AND id_usuario != ?"
    );
    $consulta_correo->bind_param("si", $correo_electronico_data, $id_usuario_data);
    $consulta_correo->execute();
    $resultado_correo = $consulta_correo->get_result();

    // Si el correo ya existe en otra cuenta, volvemos con error.
    if ($resultado_correo->num_rows > 0) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/perfil.php?error=correo');
        exit;
    }

    // Actualizamos los datos del usuario en la tabla usuarios.
    $actualizar_usuario = $conexion->prepare(
        "UPDATE $tblUsuarios
        SET nombre_completo = ?, correo_electronico = ?
        WHERE id_usuario = ?"
    );
    $actualizar_usuario->bind_param("ssi", $nombre_completo_data, $correo_electronico_data, $id_usuario_data);

    // Si la actualizacion falla, volvemos con error.
    if (!$actualizar_usuario->execute()) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/perfil.php?error=actualizar');
        exit;
    }

    // Tambien actualizamos la sesion para que muestre los datos nuevos.
    $_SESSION['nombre_completo'] = $nombre_completo_data;
    $_SESSION['correo_electronico'] = $correo_electronico_data;

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=actualizado');
    exit;
}

// Cambiar contrasena del usuario.
if (isset($_POST['btn-cambiar-contrasena'])) {
    include("../connection/abrir_conexion.php");

    // Solo se puede cambiar contrasena si hay sesion iniciada.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Recibimos la nueva contrasena y su confirmacion.
    $id_usuario_data = $_SESSION['id_usuario'];
    $contrasena_data = $_POST['contrasena_form'];
    $contrasena_validada_data = $_POST['contrasena_validada_form'];

    // Las dos contrasenas deben ser iguales.
    if ($contrasena_data != $contrasena_validada_data) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/perfil.php?error=contrasena');
        exit;
    }

    // Protegemos la contrasena antes de guardarla.
    $contrasena_segura = password_hash($contrasena_data, PASSWORD_DEFAULT);

    // Guardamos la nueva contrasena.
    $actualizar_contrasena = $conexion->prepare("UPDATE $tblUsuarios SET contrasena = ? WHERE id_usuario = ?");
    $actualizar_contrasena->bind_param("si", $contrasena_segura, $id_usuario_data);
    $actualizar_contrasena->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=contrasena_actualizada');
    exit;
}

// Borrar la cuenta del usuario conectado.
if (isset($_POST['btn-eliminar-perfil'])) {
    include("../connection/abrir_conexion.php");

    // Sin sesion no sabemos que cuenta borrar.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    $id_usuario_data = $_SESSION['id_usuario'];

    // Borramos el usuario. La base de datos tambien borra sus rutinas y eventos
    // porque las relaciones tienen ON DELETE CASCADE.
    $eliminar_usuario = $conexion->prepare("DELETE FROM $tblUsuarios WHERE id_usuario = ?");
    $eliminar_usuario->bind_param("i", $id_usuario_data);
    $eliminar_usuario->execute();

    // Cerramos la sesion porque la cuenta ya no existe.
    session_destroy();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/registrarse.html?mensaje=cuenta_eliminada');
    exit;
}
?>
