<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$viaje_id   = $_POST["viaje_id"];
$usuario_id = $_SESSION["usuario_id"];

$viajeClass   = new Viaje();
$archivoClass = new Archivo_viaje();

$viaje = $viajeClass->obtenerViaje($viaje_id);

// 1) Solo usuarios que pueden acceder al viaje
if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

// 2) Solo si el viaje ya ha empezado
$hoy = date("Y-m-d");
if ($hoy < $viaje["fecha_inicio"]) {
    header("Location: /cotrip/plataforma/vista/galeria_viaje.php?id=$viaje_id&msg=viaje_no_empezado");
    exit;
}

// 3) Comprobar que hay archivo
if (!isset($_FILES["foto"]) || $_FILES["foto"]["error"] !== 0) {
    header("Location: /cotrip/plataforma/vista/galeria_viaje.php?id=$viaje_id&msg=error_fichero");
    exit;
}

// 4) Validar extensión
$extensionesPermitidas = ["jpg", "jpeg", "png", "webp"];
$nombreOriginal = $_FILES["foto"]["name"];
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

if (!in_array($ext, $extensionesPermitidas)) {
    header("Location: /cotrip/plataforma/vista/galeria_viaje.php?id=$viaje_id&msg=formato_no_valido");
    exit;
}

// 5) Crear nombre único y mover archivo
$nombreNuevo = "viaje_".$viaje_id."_".time()."_".rand(1000,9999).".".$ext;

// ruta web que se guardará en BD
$ruta_web = "/cotrip/uploads/viajes/".$nombreNuevo;
// ruta física para mover el archivo
$ruta_fisica = $_SERVER['DOCUMENT_ROOT'].$ruta_web;

if (!is_dir($_SERVER['DOCUMENT_ROOT']."/cotrip/uploads/viajes")) {
    mkdir($_SERVER['DOCUMENT_ROOT']."/cotrip/uploads/viajes", 0777, true);
}

move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta_fisica);

// 6) Guardar en BD como tipo 'foto'
$archivoClass->agregarArchivo($viaje_id, $usuario_id, $ruta_web, "foto");

header("Location: /cotrip/plataforma/vista/galeria_viaje.php?id=$viaje_id&msg=ok");
exit;
