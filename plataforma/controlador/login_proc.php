<?php
$pagina_publica = true;
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$auth = new Autenticacion();

$email = $_POST["email"];
$password = $_POST["password"];

$redirect = $_POST["redirect"];
$token    = $_POST["token"];

$usuario = $auth->login($email, $password);

if (!$usuario) {
    echo "Error: datos incorrectos";
    exit;
}

$_SESSION["usuario_id"] = $usuario["id"];

/* ============================================================
   1. SI NO VIENE DE INVITACIÓN → login normal
   ANTES: redirigía a mis_viajes.php
   AHORA: redirige al INDEX de la plataforma (/cotrip/index.php)
============================================================ */
if ($redirect != "invitacion") {
    header("Location: /cotrip/index.php");
    exit;
}

/* ============================================================
   2. SI VIENE DE INVITACIÓN → aceptar automáticamente
============================================================ */

$_SESSION["token_invitacion"] = $token;

$invClass = new Invitacion();
$viajeClass = new Viaje();

$inv = $invClass->obtenerPorToken($token);

if ($inv && $inv['estado'] === 'pendiente') {

    $invClass->aceptar($inv['id'], $usuario["id"]);

    if (!$viajeClass->yaEsParticipante($inv['viaje_id'], $usuario["id"])) {
        $viajeClass->agregarParticipante($inv['viaje_id'], $usuario["id"]);
    }
}

header("Location: /cotrip/plataforma/vista/ver_invitacion.php?token=".$token."&msg=aceptada");
exit;





