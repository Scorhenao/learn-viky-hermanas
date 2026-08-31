<?php
// Este controlador sirve para consultar datos y devolverlos en formato JSON.
// JSON es un formato que JavaScript puede leer facilmente.
session_start();
include("../connection/abrir_conexion.php");

// Avisamos al navegador que la respuesta sera JSON, no HTML.
header('Content-Type: application/json');

// Leemos que tabla pidieron en la URL.
// Ejemplo: gestion_tabla.php?tabla=areas
$tabla_data = isset($_GET['tabla']) ? $_GET['tabla'] : '';

// Lista de consultas permitidas.
// Esto es importante: no dejamos que el usuario escriba cualquier SQL.
$tablas_permitidas = [
    "areas" => "SELECT id_area, nombre_area FROM $tblAreas ORDER BY nombre_area",
    "tips" => "SELECT id_tip, titulo, enlace, id_area_fk FROM $tblTips ORDER BY titulo",
    "usuarios" => "SELECT id_usuario, nombre_completo, correo_electronico, perfil FROM $tblUsuarios ORDER BY nombre_completo"
];

// Si hay usuario conectado, tambien dejamos consultar sus rutinas y calendarios.
if (isset($_SESSION['id_usuario'])) {
    // intval convierte el id a numero para evitar datos peligrosos.
    $id_usuario_data = intval($_SESSION['id_usuario']);

    // Consulta de rutinas del usuario conectado.
    $tablas_permitidas["rutinas"] =
        "SELECT id_rutina, nota, dia_semana, tema, hora, id_area_fk
        FROM $tblRutinas
        WHERE id_usuario_fk = $id_usuario_data
        ORDER BY FIELD(dia_semana, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'), hora";

    // Consulta de eventos del usuario conectado.
    $tablas_permitidas["calendarios"] =
        "SELECT id_calendario, observacion, fecha_inicio, fecha_final
        FROM $tblCalendarios
        WHERE id_usuario_fk = $id_usuario_data
        ORDER BY fecha_inicio";
}

// Si pidieron una tabla que no esta permitida, respondemos con error.
if (!isset($tablas_permitidas[$tabla_data])) {
    echo json_encode(["error" => "Tabla no permitida o falta iniciar sesion"]);
    include("../connection/cerrar_conexion.php");
    exit;
}

// Ejecutamos la consulta permitida.
$resultado = mysqli_query($conexion, $tablas_permitidas[$tabla_data]);

// Guardamos todas las filas en un arreglo.
$datos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila;
}

// Enviamos el arreglo convertido a JSON.
echo json_encode($datos);
include("../connection/cerrar_conexion.php");
?>
