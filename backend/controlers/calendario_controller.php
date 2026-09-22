<?php
// Este controlador maneja el CRUD del calendario:
// Crear, leer, actualizar y eliminar eventos.
session_start();


function preparar_fecha_mysql($fecha_form)
{
   
    if ($fecha_form == '') {
        return null;
    }

    
    $fecha = str_replace("T", " ", $fecha_form);

 
    if (strlen($fecha) == 16) {
        $fecha = $fecha . ":00";
    }

    return $fecha;
}

if (isset($_GET['accion']) && $_GET['accion'] == 'listar') {
    include("../connection/abrir_conexion.php");

  
    header('Content-Type: application/json');

    
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode([]);
        include("../connection/cerrar_conexion.php");
        exit;
    }
    

    
    $id_usuario_data = $_SESSION['id_usuario'];
    $consulta_calendarios = $conexion->prepare(
        "SELECT id_calendario, observacion, fecha_inicio, fecha_final
        FROM $tblCalendarios
        WHERE id_usuario_fk = ?"
    );
    $consulta_calendarios->bind_param("i", $id_usuario_data);
    $consulta_calendarios->execute();
    $resultado_calendarios = $consulta_calendarios->get_result();

  
    $eventos = [];
    while ($calendario = $resultado_calendarios->fetch_assoc()) {
        $eventos[] = [
            "id" => $calendario['id_calendario'],
            "title" => $calendario['observacion'],
            "start" => str_replace(" ", "T", $calendario['fecha_inicio']),
            "end" => $calendario['fecha_final'] ? str_replace(" ", "T", $calendario['fecha_final']) : null
        ];
    }

  
    echo json_encode($eventos);
    include("../connection/cerrar_conexion.php");
    exit;
}


if (isset($_POST['btn-add-calendario'])) {
    include("../connection/abrir_conexion.php");


    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    
    $observacion_data = trim($_POST['observacion_form']);
    $fecha_inicio_data = preparar_fecha_mysql($_POST['fecha_inicio_form']);
    $fecha_final_data = preparar_fecha_mysql($_POST['fecha_final_form']);
    $id_usuario_data = $_SESSION['id_usuario'];

    
    if ($fecha_inicio_data == null) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/calendario.php?error=fecha');
        exit;
    }

    
    $insertar_calendario = $conexion->prepare(
        "INSERT INTO $tblCalendarios (observacion, fecha_inicio, fecha_final, id_usuario_fk)
        VALUES (?, ?, ?, ?)"
    );
    $insertar_calendario->bind_param("sssi", $observacion_data, $fecha_inicio_data, $fecha_final_data, $id_usuario_data);

    if (!$insertar_calendario->execute()) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/calendario.php?error=crear');
        exit;
    }

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/calendario.php?mensaje=creado');
    exit;
}


if (isset($_POST['btn-editar-calendario'])) {
    include("../connection/abrir_conexion.php");

    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    $id_calendario_data = $_POST['id_calendario_form'];
    $observacion_data = trim($_POST['observacion_form']);
    $fecha_inicio_data = preparar_fecha_mysql($_POST['fecha_inicio_form']);
    $fecha_final_data = preparar_fecha_mysql($_POST['fecha_final_form']);
    $id_usuario_data = $_SESSION['id_usuario'];

    if ($fecha_inicio_data == null) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/calendario.php?error=fecha');
        exit;
    }


    $actualizar_calendario = $conexion->prepare(
        "UPDATE $tblCalendarios
        SET observacion = ?, fecha_inicio = ?, fecha_final = ?
        WHERE id_calendario = ? AND id_usuario_fk = ?"
    );
    $actualizar_calendario->bind_param("sssii", $observacion_data, $fecha_inicio_data, $fecha_final_data, $id_calendario_data, $id_usuario_data);
    $actualizar_calendario->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/calendario.php?mensaje=editado');
    exit;
}


if (isset($_POST['btn-eliminar-calendario'])) {
    include("../connection/abrir_conexion.php");

    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    $id_calendario_data = $_POST['id_calendario_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    
    $eliminar_calendario = $conexion->prepare("DELETE FROM $tblCalendarios WHERE id_calendario = ? AND id_usuario_fk = ?");
    $eliminar_calendario->bind_param("ii", $id_calendario_data, $id_usuario_data);
    $eliminar_calendario->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/calendario.php?mensaje=eliminado');
    exit;
}
?>
