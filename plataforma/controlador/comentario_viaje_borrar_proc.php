<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$id = $_GET["id"];
$viaje_id = $_GET["viaje"];
$usuario_id = $_SESSION["usuario_id"];

$comentarioClass = new Comentario_viaje();
$viajeClass = new Viaje();

$c = $comentarioClass->obtenerPorId($id);
$viaje = $viajeClass->obtenerViaje($viaje_id);

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

header("Location: /cotrip/plataforma/vista/comentarios_viaje.php?id=$viaje_id");
exit;
