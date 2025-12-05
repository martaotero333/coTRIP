<?php
$pagina_publica = true;
require_once($_SERVER['DOCUMENT_ROOT']."/cotrip/sistema/inc/include_classes.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cotrip/sistema/inc/sesiones_cotrip.php");

$token = $_GET['token'] ?? null;

if (!$token) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$invClass = new Invitacion();
$viajeClass = new Viaje();

// obtener invitación
$inv = $invClass->obtenerPorToken($token);
if (!$inv) {
    echo "Invitación no encontrada.";
    exit;
}

$viaje_id = $inv['viaje_id'];
$email_invitado = $inv["email_invitado"];
$estado_actual = $inv['estado'];

/* ============================================================
   1. SI YA HAY SESIÓN → procesar normal
============================================================ */
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];

    if ($estado_actual === "aceptada") {
        if (!$viajeClass->yaEsParticipante($viaje_id, $usuario_id)) {
            $viajeClass->agregarParticipante($viaje_id, $usuario_id);
        }
        header("Location: /cotrip/plataforma/vista/ver_invitacion.php?token=$token&msg=ya_aceptada");
        exit;
    }

    if ($estado_actual === "rechazada") {
        header("Location: /cotrip/plataforma/vista/ver_invitacion.php?token=$token&msg=ya_rechazada");
        exit;
    }

    if ($estado_actual === "pendiente") {

        // insertar participante
        if (!$viajeClass->yaEsParticipante($viaje_id, $usuario_id)) {
            $viajeClass->agregarParticipante($viaje_id, $usuario_id);
        }

        // marcar invitación como aceptada
        $invClass->aceptar($inv['id'], $usuario_id);

        /* ============================================================
           GENERAR GASTO PRECIO BASE
        ============================================================= */
        $gastoClass = new Gasto();
        $viaje = $viajeClass->obtenerViaje($viaje_id);
        $gastoClass->agregarGasto($viaje_id, $usuario_id, "Precio base del viaje", $viaje["precio_base"]);
        /* ============================================================ */

        header("Location: /cotrip/plataforma/vista/ver_invitacion.php?token=$token&msg=aceptada");
        exit;
    }
}

/* ============================================================
   2. SIN SESIÓN → COMPROBAR SI EL EMAIL YA EXISTE
============================================================ */
$db = new DB();
$pdo = $db->pdo;

$stmt = $pdo->prepare("SELECT usuario_id FROM autenticacion WHERE email = ?");
$stmt->execute([$email_invitado]);
$existe = $stmt->fetch();

/* ============================================================
   2A. SI YA EXISTE EL EMAIL → IR A LOGIN
============================================================ */
if ($existe) {
    $_SESSION['token_invitacion'] = $token;
    header("Location: /cotrip/login.php?email_prellenado=".$email_invitado."&redirect=invitacion&token=".$token);
    exit;
}

/* ============================================================
   2B. SI NO EXISTE → IR A REGISTRO CON EMAIL PRELLENADO
============================================================ */
$_SESSION['token_invitacion'] = $token;

header("Location: /cotrip/registro.php?email=".$email_invitado."&inv=1");
exit;




