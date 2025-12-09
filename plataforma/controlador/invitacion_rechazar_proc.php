<?php
require_once("../../sistema/inc/include_classes.php");


if (!isset($_GET["token"]) || trim($_GET["token"]) === "") {
    include("../../sistema/inc/header.php");
    echo "<div class='form-container'><h2>Error</h2><p>Token no válido.</p></div>";
    include("../../sistema/inc/footer.php");
    exit;
}

$token = trim($_GET["token"]);

$invitacionClass = new Invitacion();
$viajeClass      = new Viaje();

$inv = $invitacionClass->obtenerPorToken($token);


if (!$inv) {
    include("../../sistema/inc/header.php");
    echo "<div class='form-container'><h2>Invitación no válida</h2><p>Este enlace no corresponde a ninguna invitación.</p></div>";
    include("../../sistema/inc/footer.php");
    exit;
}

if ($inv["estado"] != "pendiente") {
    include("../../sistema/inc/header.php");
    ?>
    <div class="form-container">
        <h2>Invitación procesada</h2>
        <p>Esta invitación ya fue <strong><?= htmlspecialchars($inv["estado"]) ?></strong>.</p>

        <a href="/cotrip/index.php" class="btn-primary-small">Volver al inicio</a>
    </div>
    <?php
    include("../../sistema/inc/footer.php");
    exit;
}


$viaje = $viajeClass->obtenerViaje($inv["viaje_id"]);
$hoy   = date("Y-m-d");

if ($viaje && $hoy >= $viaje["fecha_inicio"]) {

    include("../../sistema/inc/header.php");
    ?>
    <div class="form-container">
        <h2>No disponible</h2>
        <p>
            Este viaje ya ha empezado (<?= htmlspecialchars($viaje["fecha_inicio"]) ?>).  
            Ya no es posible aceptar o rechazar invitaciones.
        </p>

        <a href="/cotrip/index.php" class="btn-primary-small">Volver al inicio</a>
    </div>
    <?php
    include("../../sistema/inc/footer.php");
    exit;
}


$invitacionClass->rechazar($inv["id"]);

include("../../sistema/inc/header.php");
?>

<style>
    .rechazo-box {
        background:#ffecec;
        padding:20px;
        border-radius:10px;
        border:2px solid #ffb3b3;
        text-align:center;
        font-size:15px;
        color:#8b0000;
        margin-bottom:18px;
    }
</style>

<div class="form-container">
    <h2>Invitación rechazada</h2>

    <div class="rechazo-box">
        ❌ Has rechazado la invitación correctamente.
    </div>

    <a href="/cotrip/index.php" class="btn-primary-small">Volver al inicio</a>
</div>

<?php include("../../sistema/inc/footer.php"); ?>

