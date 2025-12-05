<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];
$viaje_id   = $_POST["viaje_id"];

$titulo     = trim($_POST["titulo"]);
$cantidad   = floatval($_POST["cantidad"]);
$categoria  = trim($_POST["categoria"]);
$fecha      = $_POST["fecha"];
$descripcion= trim($_POST["descripcion"]);

$viajeClass = new Viaje();

// solo participantes del viaje pueden añadir gastos
if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$gastosClass = new Gastos();
$gastosClass->crearGasto($viaje_id, $usuario_id, $titulo, $cantidad, $categoria, $fecha, $descripcion);

header("Location: /cotrip/plataforma/vista/gastos.php?viaje_id=".$viaje_id."&msg=ok");
exit;
