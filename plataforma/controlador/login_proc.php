<?php
$pagina_publica = true;
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$auth = new Autenticacion();

$email    = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

$redirect = $_POST["redirect"] ?? "";
$token    = $_POST["token"] ?? "";

$usuario = $auth->login($email, $password);

if (!$usuario) {
    echo "Error: datos incorrectos";
    exit;
}

$_SESSION["usuario_id"] = $usuario["id"];


if ($redirect != "invitacion") {
    header("Location: /cotrip/index.php");
    exit;
}


$_SESSION["token_invitacion"] = $token;

$invClass   = new Invitacion();
$viajeClass = new Viaje();
$gastoClass = new Gasto();

$inv = $invClass->obtenerPorToken($token);

if ($inv && $inv['estado'] === 'pendiente') {

    
    $invClass->aceptar($inv['id'], $usuario["id"]);

    
    if (!$viajeClass->yaEsParticipante($inv['viaje_id'], $usuario["id"])) {
        $viajeClass->agregarParticipante($inv['viaje_id'], $usuario["id"]);
    }

   
    $viaje = $viajeClass->obtenerViaje($inv['viaje_id']);

    if ($viaje) {
       
        $check = $gastoClass->getDB()->prepare(
            "SELECT id FROM viajes_gastos WHERE viaje_id = ? AND usuario_id = ?"
        );
        $check->execute([$inv['viaje_id'], $usuario["id"]]);

        if (!$check->fetch()) {
            
            $gastoClass->agregarGasto(
                $inv['viaje_id'],
                $usuario["id"],
                "Precio base del viaje",
                $viaje["precio_base"]
            );
        }
    }
}


header("Location: /cotrip/plataforma/vista/ver_invitacion.php?token=".$token."&msg=aceptada");
exit;






