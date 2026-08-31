<?php
// Este controlador maneja el CRUD de tips.
// En este proyecto los tips son videos generales de estudio.
session_start();

// Para crear, editar o eliminar tips se necesita iniciar sesion.
if (!isset($_SESSION['id_usuario'])) {
    header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
    exit;
}

if (!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['administrador', 'profesor'])) {
    header('Location:../../../../learn-viky/tips.php?error=permiso');
    exit;
}

function guardar_imagen_tip()
{
    // Si no seleccionaron imagen, devolvemos null para dejar el tip sin imagen nueva.
    if (!isset($_FILES['imagen_form']) || $_FILES['imagen_form']['error'] == UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES['imagen_form']['error'] != UPLOAD_ERR_OK) {
        return false;
    }

    $extension = strtolower(pathinfo($_FILES['imagen_form']['name'], PATHINFO_EXTENSION));
    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $extensiones_permitidas)) {
        return false;
    }

    // Esta carpeta queda publica dentro de learn-viky para poder mostrar la imagen en tips.php.
    $carpeta_destino = "../../uploads/tips/";
    if (!is_dir($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }

    $nombre_archivo = uniqid("tip_", true) . "." . $extension;
    $ruta_destino = $carpeta_destino . $nombre_archivo;

    if (!move_uploaded_file($_FILES['imagen_form']['tmp_name'], $ruta_destino)) {
        return false;
    }

    return "uploads/tips/" . $nombre_archivo;
}

// CREAR tip/video.
if (isset($_POST['btn-add-tip'])) {
    include("../connection/abrir_conexion.php");

    // Recibimos titulo, enlace del video y area.
    $titulo_data = trim($_POST['titulo_form']);
    $enlace_data = trim($_POST['enlace_form']);
    $id_area_data = $_POST['id_area_form'];
    $imagen_data = guardar_imagen_tip();

    if ($imagen_data === false) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/tips.php?error=imagen');
        exit;
    }

    $insertar_tip = $conexion->prepare(
        "INSERT INTO $tblTips (titulo, enlace, imagen, id_area_fk)
        VALUES (?, ?, ?, ?)"
    );
    $insertar_tip->bind_param("sssi", $titulo_data, $enlace_data, $imagen_data, $id_area_data);
    $insertar_tip->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.php?mensaje=creado');
    exit;
}

// ACTUALIZAR tip/video.
if (isset($_POST['btn-editar-tip'])) {
    include("../connection/abrir_conexion.php");

    // Recibimos el id del tip y los nuevos datos.
    $id_tip_data = $_POST['id_tip_form'];
    $titulo_data = trim($_POST['titulo_form']);
    $enlace_data = trim($_POST['enlace_form']);
    $id_area_data = $_POST['id_area_form'];
    $imagen_data = guardar_imagen_tip();

    if ($imagen_data === false) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/tips.php?error=imagen');
        exit;
    }

    if ($imagen_data === null) {
        $actualizar_tip = $conexion->prepare(
            "UPDATE $tblTips
            SET titulo = ?, enlace = ?, id_area_fk = ?
            WHERE id_tip = ?"
        );
        $actualizar_tip->bind_param("ssii", $titulo_data, $enlace_data, $id_area_data, $id_tip_data);
    } else {
        $actualizar_tip = $conexion->prepare(
            "UPDATE $tblTips
            SET titulo = ?, enlace = ?, imagen = ?, id_area_fk = ?
            WHERE id_tip = ?"
        );
        $actualizar_tip->bind_param("sssii", $titulo_data, $enlace_data, $imagen_data, $id_area_data, $id_tip_data);
    }

    $actualizar_tip->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.php?mensaje=editado');
    exit;
}

// ELIMINAR tip/video.
if (isset($_POST['btn-eliminar-tip'])) {
    include("../connection/abrir_conexion.php");

    $id_tip_data = $_POST['id_tip_form'];

    $eliminar_tip = $conexion->prepare("DELETE FROM $tblTips WHERE id_tip = ?");
    $eliminar_tip->bind_param("i", $id_tip_data);
    $eliminar_tip->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/tips.php?mensaje=eliminado');
    exit;
}
?>
