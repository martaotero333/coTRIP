<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");


$gasto_id  = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$nuevo_estado = isset($_GET["estado"]) ? (int)$_GET["estado"] : 0;
$viaje_id_param = isset($_GET["viaje"]) ? (int)$_GET["viaje"] : 0;

$usuario_id = $_SESSION["usuario_id"];

$viajeClass = new Viaje();


$db = new DB();
$pdo = $db->pdo;

$stmt = $pdo->prepare("SELECT * FROM viajes_gastos WHERE id = :id");
$stmt->bindParam(":id", $gasto_id);
$stmt->execute();
$gasto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$gasto) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id = $gasto["viaje_id"];


$viaje = $viajeClass->obtenerViaje($viaje_id);

if (!$viaje || $viaje["usuario_id"] != $usuario_id) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}


$nuevo_estado = ($nuevo_estado == 1) ? 1 : 0;

$update = $pdo->prepare("UPDATE viajes_gastos SET pagado = :estado WHERE id = :id");
$update->bindParam(":estado", $nuevo_estado);
$update->bindParam(":id", $gasto_id);
$update->execute();


header("Location: /cotrip/plataforma/vista/pagos_viaje.php?id=".$viaje_id);
exit;
