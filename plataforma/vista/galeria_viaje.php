<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

// Validación básica del parámetro id
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id   = (int) $_GET["id"];
$usuario_id = $_SESSION["usuario_id"];

$viajeClass   = new Viaje();
$archivoClass = new Archivo_viaje();

$viaje = $viajeClass->obtenerViaje($viaje_id);

// Si el viaje no existe o el usuario no puede acceder, fuera
if (!$viaje || !$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$fotos       = $archivoClass->obtenerFotosViaje($viaje_id);
$esAnfitrion = ($viaje["usuario_id"] == $usuario_id);
$hoy         = date("Y-m-d");

include("../../sistema/inc/header.php");
?>

<style>
    .galeria-wrapper {
        width: 90%;
        max-width: 1000px;
        margin: 25px auto 40px auto;
    }

    .galeria-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-end;
        gap: 8px;
        margin-bottom: 15px;
    }

    .galeria-titulo {
        margin: 0;
        font-size: 26px;
    }

    .galeria-subtitulo {
        margin: 0;
        color: #666;
        font-weight: 500;
    }

    .galeria-form {
        background: white;
        padding: 18px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-top: 20px;
        margin-bottom: 28px;
    }

    .galeria-form h4 {
        margin: 0 0 10px 0;
        font-size: 18px;
    }

    .galeria-form-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-top: 6px;
    }

    .galeria-file-input {
        flex: 1;
        min-width: 210px;
    }

    .btn-primario {
        padding: 7px 16px;
        border: none;
        background: #0077ff;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.15s ease-in-out, transform 0.05s ease-in-out;
        white-space: nowrap;
    }

    .btn-primario:hover {
        background: #005fd1;
        transform: translateY(-1px);
    }

    .galeria-ayuda {
        font-size: 12px;
        color: #777;
        margin-top: 8px;
    }

    .alerta-info {
        background: #fff4d1;
        padding: 10px 12px;
        border-radius: 8px;
        color: #665200;
        font-size: 14px;
        margin-top: 18px;
    }

    .galeria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
        margin-top: 22px;
    }

    .galeria-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        position: relative;
    }

    .galeria-card img {
        width: 100%;
        display: block;
    }

    .galeria-eliminar {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(220, 0, 0, 0.85);
        color: white;
        padding: 3px 7px;
        font-size: 11px;
        border-radius: 4px;
        text-decoration: none;
    }

    .galeria-eliminar:hover {
        background: rgba(190, 0, 0, 0.9);
    }

    .galeria-vacia {
        margin-top: 22px;
        color: #777;
        font-size: 14px;
    }

    .galeria-volver {
        margin-top: 28px;
        font-size: 14px;
    }

    .galeria-volver a {
        text-decoration: none;
        color: #0077ff;
    }

    .galeria-volver a:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .galeria-wrapper {
            width: 94%;
        }
    }
</style>

<div class="galeria-wrapper">

    <div class="galeria-header">
        <h2 class="galeria-titulo">🖼 Galería de fotos</h2>
        <h3 class="galeria-subtitulo"><?= htmlspecialchars($viaje["titulo"]) ?></h3>
    </div>

    <!-- FORMULARIO DE SUBIDA (solo si el viaje ha empezado) -->
    <?php if ($hoy >= $viaje["fecha_inicio"]): ?>
        <form action="/cotrip/plataforma/controlador/archivo_viaje_subir_proc.php"
              method="POST"
              enctype="multipart/form-data"
              class="galeria-form">
            <h4>Subir nueva foto</h4>

            <div class="galeria-form-row">
                <input class="galeria-file-input"
                       type="file"
                       name="foto"
                       accept=".jpg,.jpeg,.png,.webp"
                       required>

                <input type="hidden" name="viaje_id" value="<?= $viaje_id ?>">

                <button type="submit" class="btn-primario">
                    Subir →
                </button>
            </div>

            <p class="galeria-ayuda">
                Formatos permitidos: JPG, JPEG, PNG, WEBP.
            </p>
        </form>
    <?php else: ?>
        <p class="alerta-info">
            ⚠ Podrás subir fotos cuando el viaje haya empezado (<?= htmlspecialchars($viaje["fecha_inicio"]) ?>).
        </p>
    <?php endif; ?>

    <!-- GRID DE FOTOS -->
    <?php if (count($fotos) === 0): ?>
        <p class="galeria-vacia">
            Todavía no hay fotos subidas para este viaje.
        </p>
    <?php else: ?>
        <div class="galeria-grid">
            <?php foreach ($fotos as $f): ?>
                <div class="galeria-card">
                    <img src="<?= htmlspecialchars($f['ruta']) ?>" alt="Foto del viaje">

                    <?php if ($esAnfitrion): ?>
                        <a href="/cotrip/plataforma/controlador/archivo_viaje_borrar_proc.php?id=<?= $f['id'] ?>&viaje=<?= $viaje_id ?>"
                           onclick="return confirm('¿Seguro que deseas borrar esta foto?');"
                           class="galeria-eliminar">
                            Eliminar
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <p class="galeria-volver">
        <a href="/cotrip/plataforma/vista/viaje_dashboard.php?id=<?= $viaje_id ?>">
            ← Volver al viaje
        </a>
    </p>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

