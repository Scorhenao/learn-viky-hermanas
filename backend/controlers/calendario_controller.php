<?php
// Este controlador maneja el CRUD del calendario:
// Crear, leer, actualizar y eliminar eventos.
session_start();

// Esta funcion convierte la fecha del formulario al formato que entiende MySQL.
function preparar_fecha_mysql($fecha_form)
{
    // Si la fecha viene vacia, devolvemos null para guardar NULL en la DB.
    if ($fecha_form == '') {
        return null;
    }

    // El input datetime-local envia fechas asi: 2026-08-28T08:00.
    // MySQL trabaja mejor asi: 2026-08-28 08:00:00.
    $fecha = str_replace("T", " ", $fecha_form);

    // Si no trae segundos, se los agregamos.
    if (strlen($fecha) == 16) {
        $fecha = $fecha . ":00";
    }

    return $fecha;
}

// LEER: FullCalendar llama este bloque con ?accion=listar.
if (isset($_GET['accion']) && $_GET['accion'] == 'listar') {
    include("../connection/abrir_conexion.php");

    // La respuesta sera JSON, no HTML.
    header('Content-Type: application/json');

    // Si nadie ha iniciado sesion, el calendario queda vacio.
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode([]);
        include("../connection/cerrar_conexion.php");
        exit;
    }
    

    // Consultamos solo los eventos del usuario conectado.
    $id_usuario_data = $_SESSION['id_usuario'];
    $consulta_calendarios = $conexion->prepare(
        "SELECT id_calendario, observacion, fecha_inicio, fecha_final
        FROM $tblCalendarios
        WHERE id_usuario_fk = ?"
    );
    $consulta_calendarios->bind_param("i", $id_usuario_data);
    $consulta_calendarios->execute();
    $resultado_calendarios = $consulta_calendarios->get_result();

    // FullCalendar necesita las claves id, title, start y end.
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

// CREAR: se ejecuta cuando se envia el formulario de nuevo evento.
if (isset($_POST['btn-add-calendario'])) {
    include("../connection/abrir_conexion.php");

    // Sin sesion no sabemos de quien es el evento.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Datos que vienen del formulario.
    $observacion_data = trim($_POST['observacion_form']);
    $fecha_inicio_data = preparar_fecha_mysql($_POST['fecha_inicio_form']);
    $fecha_final_data = preparar_fecha_mysql($_POST['fecha_final_form']);
    $id_usuario_data = $_SESSION['id_usuario'];

    // Si no escribieron fecha inicial, no se puede guardar.
    if ($fecha_inicio_data == null) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/calendario.php?error=fecha');
        exit;
    }

    // Guardamos el evento en la tabla calendarios.
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

// ACTUALIZAR: se ejecuta cuando se envia un formulario de editar evento.
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

    // Editamos solo si el evento pertenece al usuario conectado.
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

// ELIMINAR: se ejecuta con el boton eliminar.
if (isset($_POST['btn-eliminar-calendario'])) {
    include("../connection/abrir_conexion.php");

    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    $id_calendario_data = $_POST['id_calendario_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    // Eliminamos solo el evento del usuario conectado.
    $eliminar_calendario = $conexion->prepare("DELETE FROM $tblCalendarios WHERE id_calendario = ? AND id_usuario_fk = ?");
    $eliminar_calendario->bind_param("ii", $id_calendario_data, $id_usuario_data);
    $eliminar_calendario->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/calendario.php?mensaje=eliminado');
    exit;
}
?>
