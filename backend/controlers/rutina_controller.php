<?php
// Este controlador crea, edita y elimina rutinas de estudio.
// Usa la sesion para saber a que usuario pertenece cada rutina.
session_start();


function normalizar_dia_semana($dia_semana)
{
    
    if (strpos($dia_semana, 'rcoles') !== false) {
        return 'Miercoles';
    }

   
    if (strpos($dia_semana, 'bado') !== false) {
        return 'Sabado';
    }


    return $dia_semana;
}


if (isset($_POST['btn-add'])) {
    include("../connection/abrir_conexion.php");

  
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

   
    $nota_data = trim($_POST['notas_form']);
    $dia_semana_data = normalizar_dia_semana($_POST['dia_semana_form']);
    $tema_data = trim($_POST['tema_form']);
    $hora_data = $_POST['hora_form'];
    $id_area_data = $_POST['id_area_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    
    $insertar_rutina = $conexion->prepare(
        "INSERT INTO $tblRutinas (nota, dia_semana, tema, hora, id_area_fk, id_usuario_fk)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

   
    $insertar_rutina->bind_param("ssssii", $nota_data, $dia_semana_data, $tema_data, $hora_data, $id_area_data, $id_usuario_data);

   
    if (!$insertar_rutina->execute()) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/rutinas.php?error=crear');
        exit;
    }

   
    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/rutinas.php?mensaje=creada');
    exit;
}


if (isset($_POST['btn-editar'])) {
    include("../connection/abrir_conexion.php");

  
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    
    $id_rutina_data = $_POST['id_rutina_form'];
    $nota_data = trim($_POST['notas_form']);
    $dia_semana_data = normalizar_dia_semana($_POST['dia_semana_form']);
    $tema_data = trim($_POST['tema_form']);
    $hora_data = $_POST['hora_form'];
    $id_area_data = $_POST['id_area_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    
    $actualizar_rutina = $conexion->prepare(
        "UPDATE $tblRutinas
        SET nota = ?, dia_semana = ?, tema = ?, hora = ?, id_area_fk = ?
        WHERE id_rutina = ? AND id_usuario_fk = ?"
    );
    $actualizar_rutina->bind_param("ssssiii", $nota_data, $dia_semana_data, $tema_data, $hora_data, $id_area_data, $id_rutina_data, $id_usuario_data);
    $actualizar_rutina->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/rutinas.php?mensaje=editada');
    exit;
}


if (isset($_POST['btn-eliminar'])) {
    include("../connection/abrir_conexion.php");

    
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

  
    $id_rutina_data = $_POST['id_rutina_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    
    $eliminar_rutina = $conexion->prepare("DELETE FROM $tblRutinas WHERE id_rutina = ? AND id_usuario_fk = ?");
    $eliminar_rutina->bind_param("ii", $id_rutina_data, $id_usuario_data);
    $eliminar_rutina->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/rutinas.php?mensaje=eliminada');
    exit;
}
?>
