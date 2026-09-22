<?php
// session_start permite guardar datos del usuario mientras navega por la pagina.
session_start();


if (isset($_POST['boton_iniciar_sesion'])) {
   
    include("../connection/abrir_conexion.php");

   
    $nombre_completo_data = trim($_POST['nombre_completo_form']);
    $contrasena_data = $_POST['contrasena_form'];

   
    $consulta_usuario = $conexion->prepare(
        "SELECT id_usuario, nombre_completo, correo_electronico, contrasena, perfil
        FROM $tblUsuarios
        WHERE nombre_completo = ?
        LIMIT 1"
    );
    $consulta_usuario->bind_param("s", $nombre_completo_data);
    $consulta_usuario->execute();
    $resultado_usuario = $consulta_usuario->get_result();

    
    if ($resultado_usuario->num_rows == 0) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=usuario');
        exit;
    }

 
    $usuario = $resultado_usuario->fetch_assoc();

    
    $contrasena_correcta = password_verify($contrasena_data, $usuario['contrasena']);

    
    if (!$contrasena_correcta && $contrasena_data == $usuario['contrasena']) {
        $contrasena_correcta = true;
        $nueva_contrasena = password_hash($contrasena_data, PASSWORD_DEFAULT);
        $actualizar_contrasena = $conexion->prepare("UPDATE $tblUsuarios SET contrasena = ? WHERE id_usuario = ?");
        $actualizar_contrasena->bind_param("si", $nueva_contrasena, $usuario['id_usuario']);
        $actualizar_contrasena->execute();
    }

   
    if (!$contrasena_correcta) {
        include("../connection/cerrar_conexion.php");
        header('Location:../../../../learn-viky/iniciar_sesion.html?error=contrasena');
        exit;
    }

  
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
    $_SESSION['correo_electronico'] = $usuario['correo_electronico'];
    $_SESSION['perfil'] = $usuario['perfil'];

    include("../connection/cerrar_conexion.php");
    header('Location:../../../../learn-viky/perfil.php?mensaje=sesion_iniciada');
    exit;
}
?>
