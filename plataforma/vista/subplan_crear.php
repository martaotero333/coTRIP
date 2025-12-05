<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

// Validar parámetro viaje
if (!isset($_GET["viaje"]) || !ctype_digit($_GET["viaje"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id   = (int) $_GET["viaje"];
$usuario_id = $_SESSION["usuario_id"];

$viajeClass = new Viaje();
$viaje      = $viajeClass->obtenerViaje($viaje_id);

// Comprobar que el viaje existe y que el usuario es el anfitrión
if (!$viaje || $viaje["usuario_id"] != $usuario_id) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

include("../../sistema/inc/header.php");

// Para limitar la fecha de la actividad al rango del viaje (si está definido)
$minFecha = !empty($viaje["fecha_inicio"]) ? $viaje["fecha_inicio"] : "";
$maxFecha = !empty($viaje["fecha_fin"]) ? $viaje["fecha_fin"] : "";
?>

<style>
    .form-container {
        width: 90%;
        max-width: 650px;
        margin: 30px auto 50px auto;
        background: white;
        padding: 26px 28px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }

    .form-container h2 {
        margin-top: 0;
        margin-bottom: 18px;
        font-size: 24px;
        text-align: center;
    }

    .subplan-form label {
        display: block;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
        margin-top: 14px;
    }

    .subplan-form input[type="text"],
    .subplan-form input[type="date"],
    .subplan-form input[type="number"],
    .subplan-form input[type="file"],
    .subplan-form textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
        box-sizing: border-box;
    }

    .subplan-form textarea {
        min-height: 100px;
        resize: vertical;
    }

    .subplan-btn {
        display: inline-block;
        margin-top: 22px;
        padding: 11px 22px;
        background: #0077ff;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: background 0.15s, transform 0.05s;
    }

    .subplan-btn:hover {
        background: #005fd1;
        transform: translateY(-1px);
    }

    .subplan-help {
        font-size: 13px;
        color: #777;
        margin-top: 4px;
    }
</style>

<div class="form-container">

    <h2>Crear actividad del viaje 🗂️</h2>

    <form action="/cotrip/plataforma/controlador/subplan_guardar_proc.php"
          method="POST"
          enctype="multipart/form-data"
          class="subplan-form">

        <input type="hidden" name="viaje_id" value="<?= $viaje_id ?>">

        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="fecha">Fecha</label>
        <input
            type="date"
            id="fecha"
            name="fecha"
            value="<?= htmlspecialchars($viaje['fecha_inicio']) ?>"
            <?= $minFecha ? 'min="' . htmlspecialchars($minFecha) . '"' : '' ?>
            <?= $maxFecha ? 'max="' . htmlspecialchars($maxFecha) . '"' : '' ?>
            required
        >
        <?php if ($minFecha || $maxFecha): ?>
            <p class="subplan-help">
                La actividad debe estar entre <?= htmlspecialchars($viaje['fecha_inicio']) ?> y <?= htmlspecialchars($viaje['fecha_fin']) ?>.
            </p>
        <?php endif; ?>

        <label for="precio">Precio aproximado (€)</label>
        <input type="number" step="0.01" id="precio" name="precio">

        <label for="lugar">Lugar / Ubicación</label>
        <input type="text" id="lugar" name="lugar" placeholder="Ej: Mirador de San Pedro">

        <label for="imagen">Imagen del subplan</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <button type="submit" class="subplan-btn">Crear subplan</button>
    </form>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

