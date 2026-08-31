<?php
// Este controlador crea, edita y elimina rutinas de estudio.
// Usa la sesion para saber a que usuario pertenece cada rutina.
session_start();

// Esta funcion arregla algunos nombres de dias que pueden llegar con tildes
// o con caracteres raros por la codificacion del HTML.
function normalizar_dia_semana($dia_semana)
{
    // Si el texto contiene "rcoles", asumimos que es Miercoles.
    if (strpos($dia_semana, 'rcoles') !== false) {
        return 'Miercoles';
    }

    // Si el texto contiene "bado", asumimos que es Sabado.
    if (strpos($dia_semana, 'bado') !== false) {
        return 'Sabado';
    }

    // Si no necesita arreglo, devolvemos el mismo dia.
    return $dia_semana;
}

// Crear rutina: entra aqui cuando el formulario envia el boton btn-add.
if (isset($_POST['btn-add'])) {
    include("../connection/abrir_conexion.php");

    // Si no hay usuario en sesion, lo mandamos al login.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Recibimos los datos del formulario.
    $nota_data = trim($_POST['notas_form']);
    $dia_semana_data = normalizar_dia_semana($_POST['dia_semana_form']);
    $tema_data = trim($_POST['tema_form']);
    $hora_data = $_POST['hora_form'];
    $id_area_data = $_POST['id_area_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    // Insertamos la rutina y la relacionamos con el area y el usuario.
    $insertar_rutina = $conexion->prepare(
        "INSERT INTO $tblRutinas (nota, dia_semana, tema, hora, id_area_fk, id_usuario_fk)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    // ssssii = 4 textos y 2 numeros enteros.
    $insertar_rutina->bind_param("ssssii", $nota_data, $dia_semana_data, $tema_data, $hora_data, $id_area_data, $id_usuario_data);

    // Si no se pudo guardar, volvemos con error.
    if (!$insertar_rutina->execute()) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/rutinas.php?error=crear');
        exit;
    }

    // Si se guardo bien, volvemos a rutinas con mensaje.
    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/rutinas.php?mensaje=creada');
    exit;
}

// Editar rutina: entra aqui cuando el formulario envia el boton btn-editar.
if (isset($_POST['btn-editar'])) {
    include("../connection/abrir_conexion.php");

    // Solo un usuario conectado puede editar sus rutinas.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Recibimos los nuevos datos de la rutina.
    $id_rutina_data = $_POST['id_rutina_form'];
    $nota_data = trim($_POST['notas_form']);
    $dia_semana_data = normalizar_dia_semana($_POST['dia_semana_form']);
    $tema_data = trim($_POST['tema_form']);
    $hora_data = $_POST['hora_form'];
    $id_area_data = $_POST['id_area_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    // Actualizamos solo si la rutina pertenece al usuario conectado.
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

// Eliminar rutina: entra aqui cuando el formulario envia el boton btn-eliminar.
if (isset($_POST['btn-eliminar'])) {
    include("../connection/abrir_conexion.php");

    // Revisamos que haya sesion iniciada.
    if (!isset($_SESSION['id_usuario'])) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=sesion');
        exit;
    }

    // Recibimos el id de la rutina y el id del usuario conectado.
    $id_rutina_data = $_POST['id_rutina_form'];
    $id_usuario_data = $_SESSION['id_usuario'];

    // Eliminamos solo si la rutina pertenece al usuario conectado.
    $eliminar_rutina = $conexion->prepare("DELETE FROM $tblRutinas WHERE id_rutina = ? AND id_usuario_fk = ?");
    $eliminar_rutina->bind_param("ii", $id_rutina_data, $id_usuario_data);
    $eliminar_rutina->execute();

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/rutinas.php?mensaje=eliminada');
    exit;
}
?>
