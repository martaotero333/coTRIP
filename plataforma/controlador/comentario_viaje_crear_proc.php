<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$viaje_id = $_POST["viaje_id"];
$mensaje  = trim($_POST["mensaje"]);
$usuario_id = $_SESSION["usuario_id"];

if ($mensaje == "") {
    header("Location: /cotrip/plataforma/vista/comentarios_viaje.php?id=$viaje_id&err=vacio");
    exit;
}

$viajeClass = new Viaje();
if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$comentarioClass = new Comentario_viaje();
$comentarioClass->crear($viaje_id, $usuario_id, $mensaje);

header("Location: /cotrip/plataforma/vista/comentarios_viaje.php?id=$viaje_id");
exit;