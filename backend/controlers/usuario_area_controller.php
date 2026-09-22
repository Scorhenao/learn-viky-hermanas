<?php
// Este controlador relaciona usuarios con areas favoritas o de interes.
session_start();


if (isset($_POST['btn-add-usuario-area'])) {
    include("../connection/abrir_conexion.php");

   
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

   
    $id_usuario_data = $_SESSION['id_usuario'];
    $id_area_data = $_POST['id_area_form'];

   
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


if (isset($_POST['btn-eliminar-usuario-area'])) {
    include("../connection/abrir_conexion.php");

   
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

   
    $id_usuario_data = $_SESSION['id_usuario'];
    $id_area_data = $_POST['id_area_form'];

  
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
