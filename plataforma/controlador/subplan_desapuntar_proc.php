<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$subplan_id = $_POST["subplan_id"];
$usuario_id = $_SESSION["usuario_id"];

$subplanClass = new Subplan();
$viajeClass = new Viaje();

$subplan = $subplanClass->obtenerSubplan($subplan_id);

if (!$subplan) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id = $subplan["viaje_id"];

if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

if ($subplanClass->usuarioApuntado($subplan_id, $usuario_id)) {
    $subplanClass->desapuntar($subplan_id, $usuario_id);
}

header("Location: /cotrip/plataforma/vista/subplan_detalle.php?id=".$subplan_id."&msg=desapuntado");
exit;
