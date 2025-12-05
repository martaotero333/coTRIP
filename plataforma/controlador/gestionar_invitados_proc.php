<?php
require_once($_SERVER['DOCUMENT_ROOT']."/cotrip/sistema/inc/include_classes.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cotrip/sistema/inc/sesiones_cotrip.php");

$viaje_id = $_POST["viaje_id"];
$email = trim($_POST["email_invitado"]);

$invClass = new Invitacion();

$token = bin2hex(random_bytes(32));

$invClass->crearInvitacion($viaje_id, $email, $token);

header("Location: /cotrip/plataforma/vista/viaje_gestionar_invitados.php?viaje_id=$viaje_id&ok=1");
exit;
