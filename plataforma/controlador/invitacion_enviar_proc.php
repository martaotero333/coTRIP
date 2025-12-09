<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");


if (!isset($_POST["viaje_id"]) || !ctype_digit($_POST["viaje_id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id   = (int) $_POST["viaje_id"];
$email      = trim($_POST["email_invitado"] ?? "");
$usuario_id = $_SESSION["usuario_id"];

$viajeClass      = new Viaje();
$invitacionClass = new Invitacion();


$viaje = $viajeClass->obtenerViaje($viaje_id);

if (!$viaje || $viaje["usuario_id"] != $usuario_id) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}


$hoy        = date("Y-m-d");
$bloqueado  = ($hoy >= $viaje["fecha_inicio"]);
$enlace     = null; 

if (!$bloqueado) {
   
    $token = bin2hex(random_bytes(16));

    
    $invitacion_id = $invitacionClass->crearInvitacion($viaje_id, $email, $token);

    
    $enlace = "http://" . $_SERVER["HTTP_HOST"] . "/cotrip/plataforma/vista/ver_invitacion.php?token=" . $token;
}

include("../../sistema/inc/header.php");
?>

<style>
    .invite-result-container {
        width: 90%;
        max-width: 650px;
        margin: 35px auto 50px auto;
        background: white;
        padding: 26px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }

    .invite-result-title {
        margin-top: 0;
        font-size: 24px;
        margin-bottom: 10px;
        text-align: center;
    }

    .invite-result-subtitle {
        margin: 0;
        text-align: center;
        color: #666;
        font-size: 14px;
        margin-bottom: 18px;
    }

    .invite-link-box {
        background:#F7FFF7;
        padding: 14px 16px;
        border-radius: 10px;
        border: 2px solid #E0F2EF;
        font-size: 14px;
        word-break: break-all;
    }

    .invite-warning-box {
        background:#FFECEC;
        padding: 14px 16px;
        border-radius: 10px;
        border: 2px solid #F5C2C2;
        font-size: 14px;
        color: #8a1414;
    }

    .invite-meta {
        font-size: 13px;
        color: #777;
        margin-top: 8px;
        margin-bottom: 0;
    }

    .invite-actions {
        margin-top: 22px;
        text-align: center;
    }

    .btn-primary-small {
        display: inline-block;
        padding: 9px 18px;
        background: #0077ff;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: background 0.15s, transform 0.05s;
    }

    .btn-primary-small:hover {
        background: #005fd1;
        transform: translateY(-1px);
    }
</style>

<div class="invite-result-container">

    <?php if ($bloqueado): ?>

        <h2 class="invite-result-title">No se ha enviado la invitación</h2>
        <p class="invite-result-subtitle">
            Este viaje ya ha empezado, así que no se pueden añadir nuevos invitados.
        </p>

        <div class="invite-warning-box">
            <strong>Fecha de inicio del viaje:</strong>
            <?= htmlspecialchars($viaje["fecha_inicio"]) ?><br>
            <strong>Hoy:</strong> <?= htmlspecialchars($hoy) ?><br><br>
            A partir de la fecha de inicio no es posible crear nuevas invitaciones para mantener la coherencia del viaje.
        </div>

        <div class="invite-actions">
            <a href="/cotrip/plataforma/vista/gestionar_invitados.php?viaje_id=<?= $viaje_id ?>"
               class="btn-primary-small">
                Volver a gestionar invitados
            </a>
        </div>

    <?php else: ?>

        <h2 class="invite-result-title">Invitación enviada</h2>
        <p class="invite-result-subtitle">
            Comparte este enlace con la persona que quieres invitar al viaje.
        </p>

        <div class="invite-link-box">
            <?= htmlspecialchars($enlace) ?>
        </div>

        <p class="invite-meta">
            Viaje: <strong><?= htmlspecialchars($viaje["titulo"]) ?></strong><br>
            Destino: <?= htmlspecialchars($viaje["destino"]) ?>
        </p>

        <div class="invite-actions">
            <a href="/cotrip/plataforma/vista/gestionar_invitados.php?viaje_id=<?= $viaje_id ?>"
               class="btn-primary-small">
                Volver a gestionar invitados
            </a>
        </div>

    <?php endif; ?>

</div>

<?php include("../../sistema/inc/footer.php"); ?>


