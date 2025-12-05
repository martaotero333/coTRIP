<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$viaje_id   = $_POST["viaje_id"];
$estrellas  = (int) $_POST["estrellas"];
$usuario_id = $_SESSION["usuario_id"];

$viajeClass = new Viaje();
$valoracionClass = new Valoracion();

$viaje = $viajeClass->obtenerViaje($viaje_id);

// 1. Solo usuarios del viaje
if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

// 2. Solo si el viaje ya empezó
$hoy = date("Y-m-d");
if ($hoy < $viaje["fecha_inicio"]) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

// 3. Guardar valoración
$valoracionClass->guardarValoracion($viaje_id, $usuario_id, $estrellas);

// 4. Volver al dashboard
header("Location: /cotrip/plataforma/vista/viaje_dashboard.php?id=".$viaje_id);
exit;
