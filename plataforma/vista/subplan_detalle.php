<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$subplanClass = new Subplan();
$viajeClass   = new Viaje();

$usuario_id = $_SESSION["usuario_id"];


if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$subplan_id = (int) $_GET["id"];
$subplan    = $subplanClass->obtenerSubplan($subplan_id);

if (!$subplan) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje = $viajeClass->obtenerViaje($subplan["viaje_id"]);


if (!$viaje || !$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje["id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$esAnfitrion = ($viaje["usuario_id"] == $usuario_id);


$ya_apuntado = $subplanClass->usuarioApuntado($subplan_id, $usuario_id);
$apuntados   = $subplanClass->obtenerApuntados($subplan_id);

include("../../sistema/inc/header.php");
?>

<style>
    .subplan-wrapper {
        width: 90%;
        max-width: 900px;
        margin: 25px auto 40px auto;
    }

    .subplan-title {
        font-size: 26px;
        margin-bottom: 10px;
    }

    .subplan-viaje-titulo {
        font-size: 14px;
        color: #777;
        margin-top: 0;
        margin-bottom: 18px;
    }

    .subplan-main-card {
        background: white;
        padding: 20px 22px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        margin-bottom: 24px;
    }

   
    .subplan-main-card img {
        width: 100%;
        max-width: 350px;
        margin: 0 auto 15px auto;
        border-radius: 12px;
        display: block;
        box-shadow: 0 2px 6px rgba(0,0,0,0.10);
    }

    .subplan-main-card p {
        margin: 6px 0;
        line-height: 1.5;
    }

    .subplan-desc {
        margin-top: 12px;
    }

    .subplan-desc strong {
        display: block;
        margin-bottom: 6px;
    }

    .subplan-block {
        background: white;
        padding: 18px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .subplan-block h3 {
        margin-top: 0;
        margin-bottom: 12px;
        font-size: 18px;
    }

    .subplan-apuntados-list {
        list-style: none;
        padding-left: 0;
        margin-top: 8px;
        margin-bottom: 14px;
    }

    .subplan-apuntados-list li {
        margin-bottom: 8px;
    }

    .subplan-back {
        margin-top: 22px;
    }

    
    .btn-logout {
        background: #c70000;
        color: white !important;
        padding: 8px 14px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.15s;
    }

    .btn-logout:hover {
        background: #a30000;
    }
</style>

<div class="subplan-wrapper">

    <h2 class="subplan-title">
        <?= htmlspecialchars($subplan['titulo']) ?> 🗂️
    </h2>
    <p class="subplan-viaje-titulo">
        Parte del viaje: <strong><?= htmlspecialchars($viaje["titulo"]) ?></strong>
    </p>

    <div class="subplan-main-card">

        <?php if (!empty($subplan["imagen"])): ?>
            <img src="<?= htmlspecialchars($subplan["imagen"]) ?>" alt="Imagen de la actividad">
        <?php endif; ?>

        <p><strong>📅 Fecha:</strong> <?= htmlspecialchars($subplan["fecha"]) ?></p>
        <p><strong>💶 Precio:</strong> <?= number_format($subplan["precio"], 2) ?> €</p>
        <p><strong>📍 Lugar:</strong> <?= htmlspecialchars($subplan["lugar"]) ?></p>

        <div class="subplan-desc">
            <strong>📌 Descripción:</strong>
            <p><?= nl2br(htmlspecialchars($subplan["descripcion"])) ?></p>
        </div>

    </div>

    
    <div class="subplan-block">
        <h3>💬 Comentarios de la actividad</h3>

        <a class="btn-primary-small"
           href="/cotrip/plataforma/vista/comentarios_subplan.php?id=<?= $subplan['id'] ?>">
           Ver comentarios →
        </a>
    </div>

    
    <div class="subplan-block">
        <h3>👥 Personas apuntadas</h3>

        <?php if (count($apuntados) == 0): ?>
            <p>Nadie se ha apuntado todavía.</p>
        <?php else: ?>
            <ul class="subplan-apuntados-list">
                <?php foreach ($apuntados as $p): ?>
                    <li>
                        <strong><?= htmlspecialchars($p["nombre"]) ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

       
        <?php if ($ya_apuntado): ?>

            <form action="/cotrip/plataforma/controlador/subplan_desapuntar_proc.php" method="POST">
                <input type="hidden" name="subplan_id" value="<?= $subplan_id ?>">
                <button class="btn-logout">❌ Desapuntarme</button>
            </form>

        <?php else: ?>

            <form action="/cotrip/plataforma/controlador/subplan_apuntar_proc.php" method="POST">
                <input type="hidden" name="subplan_id" value="<?= $subplan_id ?>">
                <button class="btn-primary-small">✔ Apuntarme</button>
            </form>

        <?php endif; ?>
    </div>

   
    <div class="subplan-back">
        <a class="btn-primary-small"
           href="/cotrip/plataforma/vista/viaje_dashboard.php?id=<?= $viaje['id'] ?>">
            ⬅ Volver al viaje
        </a>
    </div>

</div>

<?php include("../../sistema/inc/footer.php"); ?>



