<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$id        = $_GET["id"];
$viaje_id  = $_GET["viaje"];
$usuario_id = $_SESSION["usuario_id"];

$archivoClass = new Archivo_viaje();
$viajeClass   = new Viaje();

$archivo = $archivoClass->obtenerArchivo($id);
$viaje   = $viajeClass->obtenerViaje($viaje_id);

if (!$archivo || !$viaje) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}


if ($viaje["usuario_id"] != $usuario_id) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}


$ruta_fisica = $_SERVER['DOCUMENT_ROOT'].$archivo["ruta"];
if (file_exists($ruta_fisica)) {
    unlink($ruta_fisica);
}


$archivoClass->borrarArchivo($id);

header("Location: /cotrip/plataforma/vista/galeria_viaje.php?id=$viaje_id&msg=borrada");
exit;
