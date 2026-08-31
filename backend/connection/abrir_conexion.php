<?php
// Este archivo se incluye cada vez que necesitamos hablar con la base de datos.
// Aqui guardamos los datos de conexion en variables para no repetirlos en todos los controladores.

// Servidor donde esta MySQL. En XAMPP casi siempre es localhost.
$host = "localhost";

// Nombre de la base de datos creada con database.sql.
$basededatos = "learn_viky_db";

// Usuario y clave de MySQL. En XAMPP normalmente el usuario es root y la clave esta vacia.
$usuariodb = "root";
$clavedb = "";

// Nombres de las tablas. Asi, si algun dia cambia el nombre de una tabla,
// solo se cambia aqui y no en todos los archivos.
$tblUsuarios = "usuarios";
$tblCalendarios = "calendarios";
$tblRutinas = "rutinas";
$tblTips = "tips";
$tblAreas = "areas";
$tblUsuariosPorAreas = "usuario_por_area";

// Creamos la conexion usando mysqli.
$conexion = new mysqli($host, $usuariodb, $clavedb, $basededatos);

// Si la conexion falla, detenemos el programa y mostramos el error.
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

// Esto permite guardar bien caracteres especiales como tildes y la letra ene.
$conexion->set_charset("utf8mb4");
?>
