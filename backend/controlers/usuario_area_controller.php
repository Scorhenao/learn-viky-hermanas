<?php
// Este controlador relaciona usuarios con areas favoritas o de interes.
session_start();

// Agregar un area al usuario conectado.
if (isset($_POST['btn-add-usuario-area'])) {
    include("../connection/abrir_conexion.php");

    // Si no hay sesion, no sabemos a que usuario agregarle el area.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Tomamos el usuario de la sesion y el area del formulario.
    $id_usuario_data = $_SESSION['id_usuario'];
    $id_area_data = $_POST['id_area_form'];

    // INSERT IGNORE evita error si esa relacion ya existe.
    $insertar_usuario_area = $conexion->prepare(
        "INSERT IGNORE INTO $tblUsuariosPorAreas (id_usuario_fk, id_area_fk)
        VALUES (?, ?)"
    );
    $insertar_usuario_area->bind_param("ii", $id_usuario_data, $id_area_data);
    $insertar_usuario_area->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=area_agregada');
    exit;
}

// Quitar un area del usuario conectado.
if (isset($_POST['btn-eliminar-usuario-area'])) {
    include("../connection/abrir_conexion.php");

    // Verificamos que haya usuario conectado.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Tomamos los ids necesarios.
    $id_usuario_data = $_SESSION['id_usuario'];
    $id_area_data = $_POST['id_area_form'];

    // Borramos la relacion entre ese usuario y esa area.
    $eliminar_usuario_area = $conexion->prepare(
        "DELETE FROM $tblUsuariosPorAreas
        WHERE id_usuario_fk = ? AND id_area_fk = ?"
    );
    $eliminar_usuario_area->bind_param("ii", $id_usuario_data, $id_area_data);
    $eliminar_usuario_area->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=area_eliminada');
    exit;
}
?>
