<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$id = $_GET["id"];
$subplan_id = $_GET["subplan"];
$usuario_id = $_SESSION["usuario_id"];

$comentarioClass = new Comentario_subplan();
$subplanClass = new Subplan();
$viajeClass = new Viaje();

$c = $comentarioClass->obtenerPorId($id);
$subplan = $subplanClass->obtenerSubplan($subplan_id);
$viaje = $viajeClass->obtenerViaje($subplan["viaje_id"]);

$esAnfitrion = ($viaje["usuario_id"] == $usuario_id);

if (!$c) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

if ($c["usuario_id"] != $usuario_id && !$esAnfitrion) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$comentarioClass->borrar($id);

header("Location: /cotrip/plataforma/vista/comentarios_subplan.php?id=$subplan_id");
exit;
