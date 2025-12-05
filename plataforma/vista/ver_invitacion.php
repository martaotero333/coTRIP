<?php
$pagina_publica = true;
require_once("../../sistema/inc/include_classes.php");

// Validar token
if (!isset($_GET["token"]) || trim($_GET["token"]) === "") {
    include("../../sistema/inc/header.php");
    echo '<div style="width:90%; max-width:600px; margin:30px auto; text-align:center;">
            <h2>Invitación no válida</h2>
            <p>No se ha proporcionado un token de invitación.</p>
          </div>';
    include("../../sistema/inc/footer.php");
    exit;
}

$token = $_GET["token"];

$invitacionClass = new Invitacion();
$viajeClass      = new Viaje();

$inv = $invitacionClass->obtenerPorToken($token);

if (!$inv) {
    include("../../sistema/inc/header.php");
    echo '<div style="width:90%; max-width:600px; margin:30px auto; text-align:center;">
            <h2>Invitación no válida</h2>
            <p>Esta invitación no existe o el enlace es incorrecto.</p>
          </div>';
    include("../../sistema/inc/footer.php");
    exit;
}

$viaje = $viajeClass->obtenerViaje($inv["viaje_id"]);

include("../../sistema/inc/header.php");
?>

<style>
    .inv-container {
        width: 90%;
        max-width: 650px;
        margin: 30px auto 40px auto;
    }

    .inv-title {
        margin-top: 0;
        font-size: 26px;
        margin-bottom: 18px;
    }

    .inv-card {
        background: white;
        padding: 20px 22px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        margin-bottom: 22px;
    }

    .inv-card p {
        margin: 6px 0;
    }

    .inv-actions {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .inv-estado-texto {
        margin-top: 10px;
        font-size: 15px;
    }
</style>

<div class="main-container inv-container">

    <h2 class="inv-title">Invitación a un viaje ✈️</h2>

    <?php if ($viaje): ?>
        <div class="inv-card">
            <p><strong>Viaje:</strong> <?= htmlspecialchars($viaje["titulo"]) ?></p>
            <p><strong>Destino:</strong> <?= htmlspecialchars($viaje["destino"]) ?></p>
            <p><strong>Fechas:</strong> <?= htmlspecialchars($viaje["fecha_inicio"]) ?> → <?= htmlspecialchars($viaje["fecha_fin"]) ?></p>
            <p><?= nl2br(htmlspecialchars($viaje["descripcion"])) ?></p>
        </div>
    <?php else: ?>
        <div class="inv-card">
            <p>El viaje asociado a esta invitación ya no existe.</p>
        </div>
    <?php endif; ?>

    <?php if ($viaje): ?>
        <?php if ($inv["estado"] == "pendiente"): ?>

            <div class="inv-actions">
                <a class="btn-primary-small"
                   href="/cotrip/plataforma/controlador/invitacion_aceptar_proc.php?token=<?= htmlspecialchars($token) ?>">
                   ✔ Aceptar invitación
                </a>

                <a class="btn-logout"
                   href="/cotrip/plataforma/controlador/invitacion_rechazar_proc.php?token=<?= htmlspecialchars($token) ?>">
                   ✖ Rechazar invitación
                </a>
            </div>

        <?php else: ?>

            <p class="inv-estado-texto">
                Esta invitación ya fue: <strong><?= htmlspecialchars($inv["estado"]) ?></strong>.
            </p>

        <?php endif; ?>
    <?php endif; ?>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

