<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$subplan_id = $_POST["subplan_id"];
$usuario_id = $_SESSION["usuario_id"];

$subplanClass = new Subplan();
$viajeClass = new Viaje();

// obtener subplan
$subplan = $subplanClass->obtenerSubplan($subplan_id);

if (!$subplan) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id = $subplan["viaje_id"];

// comprobar permisos
if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

// evitar duplicados
if (!$subplanClass->usuarioApuntado($subplan_id, $usuario_id)) {
    
    // APUNTAR AL SUBPLAN
    $subplanClass->apuntar($subplan_id, $usuario_id);

    /* ============================================================
       AÑADIDO — GENERAR GASTO POR APUNTARSE AL SUBPLAN
    ============================================================= */
    $gastoClass = new Gasto();

    $concepto = "Subplan: " . $subplan["titulo"];
    $cantidad = $subplan["precio"];

    $gastoClass->agregarGasto($viaje_id, $usuario_id, $concepto, $cantidad);
    /* ============================================================
    ============================================================= */
}

header("Location: /cotrip/plataforma/vista/subplan_detalle.php?id=".$subplan_id."&msg=ok");
exit;
