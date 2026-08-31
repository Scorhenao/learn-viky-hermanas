<?php
// Este controlador recibe los datos del formulario de registro.
// Solo entra aqui si se presiono el boton llamado btn-registrarse.
if (isset($_POST['btn-registrarse'])) {
    // Abrimos la conexion a MySQL.
    include("../connection/abrir_conexion.php");

    // Recibimos los datos que vienen desde registrarse.html.
    // trim() quita espacios al inicio y al final.
    $nombre_completo_data = trim($_POST['nombre_completo_form']);
    $correo_electronico_data = trim($_POST['correo_electronico_form']);
    $contrasena_data = $_POST['contrasena_form'];
    $contrasena_validada_data = $_POST['contrasena_validada_form'];
    $perfil_data = $_POST['perfil_form'];
    $cedula_data = null;
    $certificacion_diploma_data = null;

    $perfiles_permitidos = ['estudiante', 'profesor', 'administrador'];
    if (!in_array($perfil_data, $perfiles_permitidos)) {
        include("../connection/cerrar_conexion.php");
        header('Location: ../../../../learn-viky/registrarse.html?error=perfil');
        exit;
    }

    // Validamos que las dos contrasenas sean iguales.
    if ($contrasena_data != $contrasena_validada_data) {
        header('Location: ../../../../learn-viky/registrarse.html?error=contrasena');
        exit;
    }

    // Validamos que el usuario haya aceptado los terminos.
    if (!isset($_POST['terminos_form']) || $_POST['terminos_form'] != 1) {
        header('Location: ../../../../learn-viky/registrarse.html?error=terminos');
        exit;
    }

    if ($perfil_data == 'profesor') {
        $cedula_data = trim($_POST['cedula_form'] ?? '');

        if ($cedula_data == '' || !isset($_FILES['certificacion_diploma_form']) || $_FILES['certificacion_diploma_form']['error'] != UPLOAD_ERR_OK) {
            include("../connection/cerrar_conexion.php");
            header('Location: ../../../../learn-viky/registrarse.html?error=profesor');
            exit;
        }

        $extension = strtolower(pathinfo($_FILES['certificacion_diploma_form']['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['pdf', 'jpg', 'jpeg', 'png'];

        if (!in_array($extension, $extensiones_permitidas)) {
            include("../connection/cerrar_conexion.php");
            header('Location: ../../../../learn-viky/registrarse.html?error=archivo');
            exit;
        }

        $carpeta_destino = "../../uploads/certificaciones/";
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true);
        }

        $nombre_archivo = uniqid("diploma_", true) . "." . $extension;
        $ruta_destino = $carpeta_destino . $nombre_archivo;

        if (!move_uploaded_file($_FILES['certificacion_diploma_form']['tmp_name'], $ruta_destino)) {
            include("../connection/cerrar_conexion.php");
            header('Location: ../../../../learn-viky/registrarse.html?error=archivo');
            exit;
        }

        $certificacion_diploma_data = "uploads/certificaciones/" . $nombre_archivo;
    }

    // Buscamos si ya existe un usuario con ese correo.
    // El signo ? es un espacio seguro para poner datos del usuario.
    $consulta_correo = $conexion->prepare("SELECT id_usuario FROM $tblUsuarios WHERE correo_electronico = ?");
    $consulta_correo->bind_param("s", $correo_electronico_data);
    $consulta_correo->execute();
    $resultado_correo = $consulta_correo->get_result();

    // Si ya existe ese correo, no dejamos registrar otra cuenta igual.
    if ($resultado_correo->num_rows > 0) {
        include("../connection/cerrar_conexion.php");
        header('Location: ../../../../learn-viky/registrarse.html?error=correo');
        exit;
    }

    // password_hash protege la contrasena antes de guardarla.
    // Asi no queda escrita como texto normal en la base de datos.
    $contrasena_segura = password_hash($contrasena_data, PASSWORD_DEFAULT);

    // Preparamos el INSERT para guardar el usuario nuevo.
    $insertar_usuario = $conexion->prepare(
        "INSERT INTO $tblUsuarios (nombre_completo, correo_electronico, contrasena, perfil, cedula, certificacion_diploma)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    // ssssss significa que vamos a enviar 6 datos tipo string/texto.
    $insertar_usuario->bind_param("ssssss", $nombre_completo_data, $correo_electronico_data, $contrasena_segura, $perfil_data, $cedula_data, $certificacion_diploma_data);

    // Ejecutamos el INSERT. Si falla, volvemos al registro con error.
    if (!$insertar_usuario->execute()) {
        include("../connection/cerrar_conexion.php");
        header('Location: ../../../../learn-viky/registrarse.html?error=registro');
        exit;
    }

    // Cerramos conexion y mandamos al usuario a iniciar sesion.
    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/iniciar_sesion.html?mensaje=registrado');
    exit;
}
?>
