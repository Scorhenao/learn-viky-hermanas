<?php
// Este controlador maneja las areas de estudio:
// Matematicas, Fisica, Quimica, Filosofia, etc.

// Crear area nueva.
if (isset($_POST['btn-add-area'])) {
    include("../connection/abrir_conexion.php");

    // Recibimos el nombre del area desde el formulario.
    $nombre_area_data = trim($_POST['nombre_area_form']);

    // Guardamos el area en la tabla areas.
    $insertar_area = $conexion->prepare("INSERT INTO $tblAreas (nombre_area) VALUES (?)");
    $insertar_area->bind_param("s", $nombre_area_data);
    $insertar_area->execute();

    // Volvemos a tips porque alli se usan las areas.
    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.html?mensaje=area_creada');
    exit;
}

// Editar area existente.
if (isset($_POST['btn-editar-area'])) {
    include("../connection/abrir_conexion.php");

    // Recibimos el id del area y su nuevo nombre.
    $id_area_data = $_POST['id_area_form'];
    $nombre_area_data = trim($_POST['nombre_area_form']);

    // Actualizamos el nombre del area.
    $actualizar_area = $conexion->prepare("UPDATE $tblAreas SET nombre_area = ? WHERE id_area = ?");
    $actualizar_area->bind_param("si", $nombre_area_data, $id_area_data);
    $actualizar_area->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.html?mensaje=area_editada');
    exit;
}

// Eliminar area.
if (isset($_POST['btn-eliminar-area'])) {
    include("../connection/abrir_conexion.php");

    // Recibimos el id del area que se quiere borrar.
    $id_area_data = $_POST['id_area_form'];

    // Borramos el area de la base de datos.
    // Ojo: si un tip o rutina usa esta area, MySQL puede impedir borrarla.
    $eliminar_area = $conexion->prepare("DELETE FROM $tblAreas WHERE id_area = ?");
    $eliminar_area->bind_param("i", $id_area_data);
    $eliminar_area->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.html?mensaje=area_eliminada');
    exit;
}
?>
