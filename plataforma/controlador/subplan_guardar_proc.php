<?php
require_once("../../sistema/inc/include_classes.php");
session_start();

$viaje_id    = $_POST["viaje_id"];
$titulo      = $_POST["titulo"];
$descripcion = $_POST["descripcion"];
$fecha       = $_POST["fecha"];
$precio      = $_POST["precio"];
$lugar       = $_POST["lugar"];

$subplanClass = new Subplan();

$imagen_final = null;

/* ============ Subida de imagen ============ */
if (!empty($_FILES["imagen"]["tmp_name"])) {

    $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
    $nombre = time() . "_" . bin2hex(random_bytes(3)) . "." . $ext;

    $ruta = $_SERVER["DOCUMENT_ROOT"] . "/cotrip/uploads/subplanes/" . $viaje_id . "/";

    if (!is_dir($ruta)) mkdir($ruta, 0777, true);

    move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta . $nombre);

    $imagen_final = "/cotrip/uploads/subplanes/" . $viaje_id . "/" . $nombre;
}

/* ============ Guardar subplan ============ */

$subplan_id = $subplanClass->crearSubplan(
    $viaje_id,
    $titulo,
    $descripcion,
    $fecha,
    $precio,
    $lugar,
    $imagen_final
);

header("Location: /cotrip/plataforma/vista/viaje_dashboard.php?id=" . $viaje_id);
exit;
