<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$subplan_id = $_POST["subplan_id"];
$mensaje  = trim($_POST["mensaje"]);
$usuario_id = $_SESSION["usuario_id"];

$subplanClass = new Subplan();
$subplan = $subplanClass->obtenerSubplan($subplan_id);
$viaje_id = $subplan["viaje_id"];

if ($mensaje == "") {
    header("Location: /cotrip/plataforma/vista/comentarios_subplan.php?id=$subplan_id&err=vacio");
    exit;
}

$viajeClass = new Viaje();
if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$comentarioClass = new Comentario_subplan();
$comentarioClass->crear($subplan_id, $usuario_id, $mensaje);

header("Location: /cotrip/plataforma/vista/comentarios_subplan.php?id=$subplan_id");
exit;
