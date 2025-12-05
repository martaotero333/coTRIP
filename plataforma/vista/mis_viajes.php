<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$viajeClass = new Viaje();
$usuario_id = $_SESSION["usuario_id"];

$misViajes       = $viajeClass->obtenerViajesCreados($usuario_id);
$viajesAceptados = $viajeClass->obtenerViajesAceptados($usuario_id);

// Saber si no hay nada en ninguna categoría
$noHayNada = (count($misViajes) == 0 && count($viajesAceptados) == 0);

include("../../sistema/inc/header.php");
?>

<style>
    .trips-page {
        width: 90%;
        max-width: 1100px;
        margin: 30px auto 40px auto;
    }

    .trips-title {
        text-align: center;
        margin: 10px 0 25px 0;
        font-size: 28px;
        font-weight: 600;
    }

    .trips-empty {
        background: white;
        padding: 28px 24px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .trips-empty h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 20px;
    }

    .trips-empty p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .btn-crear-viaje {
        display: inline-block;
        margin-top: 14px;
        padding: 10px 20px;
        background: #0077ff;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.15s, transform 0.05s;
    }

    .btn-crear-viaje:hover {
        background: #005fd1;
        transform: translateY(-1px);
    }

    .categories-wrapper {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .category-box {
        background: transparent;
    }

    .section-title {
        margin: 0 0 14px 2px;
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    .trips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
    }

    /* No tocamos .trip-card-v2, .trip-card-img, etc, asumimos que ya están en el CSS general */

    @media (max-width: 600px) {
        .trips-page {
            width: 94%;
        }
    }
</style>

<div class="trips-page">

    <h2 class="trips-title">Mis Viajes ✈️</h2>

    <!-- MENSAJE CUANDO NO HAY NADA -->
    <?php if ($noHayNada): ?>
        <div class="trips-empty">
            <h3>Aún no tienes viajes 😥</h3>
            <p>Puedes crear uno nuevo o aceptar alguna invitación.</p>

            <a href="/cotrip/plataforma/vista/viaje_crear.php" class="btn-crear-viaje">
                Crear viaje →
            </a>
        </div>
    <?php endif; ?>

    <div class="categories-wrapper">

        <!-- VIAJES COMO ANFITRIONA -->
        <?php if (count($misViajes) > 0): ?>
            <div class="category-box">
                <h3 class="section-title">📌 Viajes creados por ti</h3>

                <div class="trips-grid">
                    <?php foreach ($misViajes as $v):
                        $estado = $viajeClass->estadoViaje($v);
                        $statusClass =
                            $estado === "pendiente" ? "status-v2-pendiente" :
                            ($estado === "en curso" ? "status-v2-curso" : "status-v2-finalizado");
                        $imgSrc = $v['imagen'] ?: '/cotrip/images/fotoViajeDefault.jpg';
                    ?>

                        <div class="trip-card-v2">

                            <img src="<?= htmlspecialchars($imgSrc) ?>"
                                 class="trip-card-img"
                                 alt="Imagen del viaje">

                            <div class="trip-card-content">
                                <div class="trip-card-title"><?= htmlspecialchars($v['titulo']) ?></div>
                                <div class="trip-card-info">📍 <?= htmlspecialchars($v['destino']) ?></div>
                                <div class="trip-card-info">📅 <?= htmlspecialchars($v['fecha_inicio']) ?> → <?= htmlspecialchars($v['fecha_fin']) ?></div>

                                <span class="trip-status-v2 <?= $statusClass ?>">
                                    <?= strtoupper($estado) ?>
                                </span>

                                <a href="/cotrip/plataforma/controlador/viaje_dashboard_proc.php?id=<?= $v['id'] ?>"
                                   class="trip-card-btn">
                                    Ver viaje →
                                </a>
                            </div>

                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- VIAJES DONDE PARTICIPAS -->
        <?php if (count($viajesAceptados) > 0): ?>
            <div class="category-box">
                <h3 class="section-title">🌍 Viajes donde participas</h3>

                <div class="trips-grid">
                    <?php foreach ($viajesAceptados as $v):
                        $estado = $viajeClass->estadoViaje($v);
                        $statusClass =
                            $estado === "pendiente" ? "status-v2-pendiente" :
                            ($estado === "en curso" ? "status-v2-curso" : "status-v2-finalizado");
                        $imgSrc = $v['imagen'] ?: '/cotrip/images/fotoViajeDefault.jpg';
                    ?>

                        <div class="trip-card-v2">

                            <img src="<?= htmlspecialchars($imgSrc) ?>"
                                 class="trip-card-img"
                                 alt="Imagen del viaje">

                            <div class="trip-card-content">
                                <div class="trip-card-title"><?= htmlspecialchars($v['titulo']) ?></div>
                                <div class="trip-card-info">📍 <?= htmlspecialchars($v['destino']) ?></div>
                                <div class="trip-card-info">📅 <?= htmlspecialchars($v['fecha_inicio']) ?> → <?= htmlspecialchars($v['fecha_fin']) ?></div>

                                <span class="trip-status-v2 <?= $statusClass ?>">
                                    <?= strtoupper($estado) ?>
                                </span>

                                <a href="/cotrip/plataforma/controlador/viaje_dashboard_proc.php?id=<?= $v['id'] ?>"
                                   class="trip-card-btn">
                                    Ver viaje →
                                </a>
                            </div>

                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

