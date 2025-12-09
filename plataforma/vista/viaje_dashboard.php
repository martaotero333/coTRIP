<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");
include("../../sistema/inc/header.php");

$viajeClass  = new Viaje();
$usuario_id  = $_SESSION["usuario_id"];

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id = (int) $_GET["id"];
$viaje    = $viajeClass->obtenerViaje($viaje_id);

if (!$viaje || !$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$esAnfitrion = ($viaje["usuario_id"] == $usuario_id);

$valoracionClass  = new Valoracion();
$comentarioClass  = new Comentario_viaje();
$archivoClass     = new Archivo_viaje();
$subplanClass     = new Subplan();
$usuarioClass     = new Usuario();

$media  = $valoracionClass->mediaViaje($viaje_id);
$votos  = $valoracionClass->totalVotos($viaje_id);
$ultimo = $comentarioClass->obtenerUltimo($viaje_id);
$fotosDashboard = $archivoClass->obtenerFotosViaje($viaje_id);
$subplanes      = $subplanClass->obtenerSubplanes($viaje_id);
$participantes  = $viajeClass->obtenerParticipantes($viaje_id);
$anfitrion      = $usuarioClass->obtenerUsuario($viaje["usuario_id"]);

$hoy = date("Y-m-d");
?>

<style>
    .viaje-dashboard {
        width: 90%;
        max-width: 1100px;
        margin: 25px auto 40px auto;
    }

    .viaje-header-img {
        width: 100%;
        max-height: 340px;
        object-fit: cover;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        margin-bottom: 20px;
    }

    .viaje-info-card {
        background: white;
        padding: 20px 22px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        margin-bottom: 22px;
    }

    .viaje-info-card h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 22px;
    }

    .viaje-info-card p {
        margin: 6px 0;
    }

    .dashboard-block {
        background: white;
        padding: 18px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-top: 18px;
    }

    .dashboard-block h3 {
        margin-top: 0;
        margin-bottom: 12px;
        font-size: 18px;
    }

    .comentario-resumen {
        background: #f9f9f9;
        margin-top: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .comentario-resumen strong {
        display: inline-block;
        margin-right: 4px;
    }

    .comentario-resumen .fecha {
        font-size: 12px;
        color: #777;
    }

    .carrusel-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .carrusel-btn {
        border: none;
        background: transparent;
        font-size: 24px;
        cursor: pointer;
        padding: 4px 6px;
    }

    .carrusel-viewport {
        width: 100%;
        max-width: 500px;
        height: 280px;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        background: #000;
    }

    .carrusel-foto {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .subplanes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 18px;
        margin-top: 15px;
    }

    .subplan-card {
        background: white;
        padding: 14px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.10);
        text-align: left;
        font-size: 14px;
    }

    .subplan-card strong {
        display: block;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .subplan-card img {
        width: 100%;
        margin-top: 8px;
        border-radius: 8px;
        max-height: 160px;
        object-fit: cover;
    }

    .participantes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }

    .participante-card {
        background: white;
        padding: 12px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.10);
        font-size: 14px;
    }

    .anfitrion-card {
        padding: 15px;
        background: white;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.10);
        font-size: 14px;
    }

    .btn-subplan {
        display: inline-block;
        margin-bottom: 10px;
    }
</style>

<div class="viaje-dashboard">

    <img src="<?= $viaje['imagen'] ?: '/cotrip/images/fotoViajeDefault.jpg' ?>"
         class="viaje-header-img"
         alt="Imagen del viaje">

    <div class="viaje-info-card">
        <h3><?= htmlspecialchars($viaje['titulo']) ?></h3>
        <p><strong>📍 Destino:</strong> <?= htmlspecialchars($viaje['destino']) ?></p>
        <p><strong>📅 Fechas:</strong> <?= htmlspecialchars($viaje['fecha_inicio']) ?> → <?= htmlspecialchars($viaje['fecha_fin']) ?></p>
        <p><strong>💶 Precio base:</strong> <?= number_format($viaje['precio_base'], 2) ?> €</p>

        <?php if ($votos > 0): ?>
            <p>
                <strong>⭐ Media del viaje:</strong>
                <?= number_format($media, 1) ?>/5 (<?= (int)$votos ?> votos)
            </p>
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($viaje['descripcion'])) ?></p>
    </div>

    <?php if ($esAnfitrion): ?>
        <div class="dashboard-block">
            <h3>👥 Gestión de invitados</h3>

            <a class="btn-primary-small"
               href="/cotrip/plataforma/vista/gestionar_invitados.php?viaje_id=<?= $viaje_id ?>">
               Gestionar invitados →
            </a>
        </div>
    <?php endif; ?>

    <?php if ($esAnfitrion): ?>
        <div class="dashboard-block">
            <h3>💶 Gestión de pagos</h3>

            <a class="btn-primary-small"
               href="/cotrip/plataforma/vista/pagos_viaje.php?id=<?= $viaje_id ?>">
               Gestionar pagos →
            </a>
        </div>
    <?php endif; ?>

    <?php if ($hoy >= $viaje["fecha_inicio"]): ?>
        <div class="dashboard-block">
            <h3>⭐ Valoración del viaje</h3>

            <a class="btn-primary-small"
               href="/cotrip/plataforma/vista/valorar_viaje.php?id=<?= $viaje['id'] ?>">
               Valorar viaje →
            </a>
        </div>
    <?php endif; ?>

    <div class="dashboard-block">
        <h3>💬 Foro del viaje</h3>

        <a class="btn-primary-small"
           href="/cotrip/plataforma/vista/comentarios_viaje.php?id=<?= $viaje_id ?>">
           Ver comentarios →
        </a>

        <?php if ($ultimo): ?>
            <div class="comentario-resumen">
                <strong><?= htmlspecialchars($ultimo['nombre']) ?></strong>
                <span class="fecha">(<?= htmlspecialchars($ultimo['fecha']) ?>)</span><br>
                <span><?= htmlspecialchars(substr($ultimo['mensaje'], 0, 80)) ?>...</span>
            </div>
        <?php endif; ?>
    </div>

    <?php if (count($fotosDashboard) > 0): ?>
        <div class="dashboard-block">
            <h3>🖼 Galería de fotos</h3>

            <div class="carrusel-wrapper">

                <button type="button"
                        onclick="moverCarrusel(-1)"
                        class="carrusel-btn">
                    ‹
                </button>

                <div id="carrusel-fotos" class="carrusel-viewport">
                    <?php foreach ($fotosDashboard as $f): ?>
                        <img src="<?= htmlspecialchars($f['ruta']) ?>"
                             class="carrusel-foto"
                             alt="Foto del viaje">
                    <?php endforeach; ?>
                </div>

                <button type="button"
                        onclick="moverCarrusel(1)"
                        class="carrusel-btn">
                    ›
                </button>

            </div>

            <p style="margin-top:10px;">
                <a class="btn-primary-small"
                   href="/cotrip/plataforma/vista/galeria_viaje.php?id=<?= $viaje_id ?>">
                    Ver todas las fotos →
                </a>
            </p>
        </div>
    <?php endif; ?>

    <!-- ⭐⭐ BOTÓN DE GALERÍA ARREGLADO ⭐⭐ -->
    <?php if ($hoy >= $viaje["fecha_inicio"]): ?>
    <div class="dashboard-block">
        <h3>📸 Galería del viaje</h3>

        <a class="btn-primary-small"
           href="/cotrip/plataforma/vista/galeria_viaje.php?id=<?= $viaje_id ?>">
            📸 Galería del viaje →
        </a>
    </div>
    <?php endif; ?>
    <!-- ⭐⭐ FIN DEL BOTÓN ARREGLADO ⭐⭐ -->

    <div class="dashboard-block">
        <h3>🗂 Subplanes / Actividades</h3>

        <?php if ($esAnfitrion): ?>
            <a class="btn-subplan"
               href="/cotrip/plataforma/vista/subplan_crear.php?viaje=<?= $viaje_id ?>">
                Crear actividad +
            </a>
        <?php endif; ?>

        <?php if (count($subplanes) == 0): ?>
            <p>No hay actividades aún.</p>
        <?php else: ?>
            <div class="subplanes-grid">
                <?php foreach ($subplanes as $s): ?>
                    <div class="subplan-card">
                        <strong><?= htmlspecialchars($s['titulo']) ?></strong>
                        📅 <?= htmlspecialchars($s['fecha']) ?><br>
                        💶 <?= number_format($s['precio'], 2) ?> €<br>
                        📍 <?= htmlspecialchars($s['lugar']) ?><br>

                        <?php if (!empty($s['imagen'])): ?>
                            <img src="<?= htmlspecialchars($s['imagen']) ?>" alt="Imagen actividad">
                        <?php endif; ?>

                        <a href="/cotrip/plataforma/vista/subplan_detalle.php?id=<?= $s['id'] ?>">
                            Ver detalle →
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-block">
        <h3>👥 Participantes</h3>

        <div class="anfitrion-card">
            <strong><?= htmlspecialchars($anfitrion['nombre']) ?></strong><br>
            <small>⭐ Anfitrión</small>
        </div>

        <?php if (count($participantes) == 0): ?>
            <p>No hay más particip.  
            </p>
        <?php else: ?>
            <div class="participantes-grid">
                <?php foreach ($participantes as $p): ?>
                    <div class="participante-card">
                        <strong><?= htmlspecialchars($p['nombre']) ?></strong><br>

                        <?php if (!empty($p['pais'])): ?>
                            <span>🌍 <?= htmlspecialchars($p['pais']) ?></span><br>
                        <?php endif; ?>

                        <?php if (!empty($p['idiomas'])): ?>
                            <span>🗣 <?= htmlspecialchars($p['idiomas']) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

<script>
(function() {
    const fotos = document.querySelectorAll(".carrusel-foto");
    if (!fotos.length) return;

    let indice = 0;

    function mostrarFoto(i) {
        fotos.forEach((img, idx) => {
            img.style.display = (idx === i) ? "block" : "none";
        });
    }

    window.moverCarrusel = function(dir) {
        indice = (indice + dir + fotos.length) % fotos.length;
        mostrarFoto(indice);
    };

    mostrarFoto(0);
})();
</script>






