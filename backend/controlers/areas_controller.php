<?php
// Este controlador maneja las areas de estudio:
// Matematicas, Fisica, Quimica, Filosofia, etc.


if (isset($_POST['btn-add-area'])) {
    include("../connection/abrir_conexion.php");

    
    $nombre_area_data = trim($_POST['nombre_area_form']);

    
    $insertar_area = $conexion->prepare("INSERT INTO $tblAreas (nombre_area) VALUES (?)");
    $insertar_area->bind_param("s", $nombre_area_data);
    $insertar_area->execute();

    
    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.html?mensaje=area_creada');
    exit;
}


if (isset($_POST['btn-editar-area'])) {
    include("../connection/abrir_conexion.php");

   
    $id_area_data = $_POST['id_area_form'];
    $nombre_area_data = trim($_POST['nombre_area_form']);

    
    $actualizar_area = $conexion->prepare("UPDATE $tblAreas SET nombre_area = ? WHERE id_area = ?");
    $actualizar_area->bind_param("si", $nombre_area_data, $id_area_data);
    $actualizar_area->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.html?mensaje=area_editada');
    exit;
}


if (isset($_POST['btn-eliminar-area'])) {
    include("../connection/abrir_conexion.php");

  
    $id_area_data = $_POST['id_area_form'];

    
  
    $eliminar_area = $conexion->prepare("DELETE FROM $tblAreas WHERE id_area = ?");
    $eliminar_area->bind_param("i", $id_area_data);
    $eliminar_area->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.html?mensaje=area_eliminada');
    exit;
}
?>
