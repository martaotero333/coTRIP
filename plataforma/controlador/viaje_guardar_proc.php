<?php
require_once("../../sistema/inc/include_classes.php");
session_start();

$usuario_id    = $_SESSION["usuario_id"];
$titulo        = $_POST["titulo"];
$destino       = $_POST["destino"];
$descripcion   = $_POST["descripcion"];
$fecha_inicio  = $_POST["fecha_inicio"];
$fecha_fin     = $_POST["fecha_fin"];
$precio_base   = $_POST["precio_base"];

$viajeClass = new Viaje();

$imagenFinal = null;


if (isset($_FILES["imagen"]) && $_FILES["imagen"]["tmp_name"] != "") {

    $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
    $nombre_archivo = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;

    
}


$viaje_id = $viajeClass->crearViaje(
    $usuario_id,
    $titulo,
    $descripcion,
    $destino,
    $fecha_inicio,
    $fecha_fin,
    $precio_base,
    null
);


if (isset($_FILES["imagen"]) && $_FILES["imagen"]["tmp_name"] != "") {

    $ruta = $_SERVER["DOCUMENT_ROOT"] . "/cotrip/uploads/viajes/" . $viaje_id . "/";
    if (!is_dir($ruta)) mkdir($ruta, 0777, true);

    $destinoFinal = $ruta . $nombre_archivo;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $destinoFinal);

    $urlImagen = "/cotrip/uploads/viajes/$viaje_id/$nombre_archivo";

    
    $pdo = (new DB())->pdo;
    $stmt = $pdo->prepare("UPDATE viajes SET imagen=? WHERE id=?");
    $stmt->execute([$urlImagen, $viaje_id]);
}


header("Location: /cotrip/plataforma/controlador/viaje_dashboard_proc.php?id=$viaje_id");
exit;
