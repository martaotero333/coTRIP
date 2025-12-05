<?php
include("../../sistema/inc/header.php");

$viaje_id = $_GET["viaje_id"];

$viajeClass = new Viaje();
$gastosClass = new Gastos();

$viaje = $viajeClass->obtenerViaje($viaje_id);

if (!$viaje) {
    echo "<p>Viaje no encontrado.</p>";
    include("../../sistema/inc/footer.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];
$esAnfitrion = ($viaje["usuario_id"] == $usuario_id);

$gastos = $gastosClass->obtenerGastos($viaje_id);
$resumen = $gastosClass->resumenPorUsuario($viaje_id);
?>

<h2>💶 Gastos del viaje</h2>

<!-- ============================================================
     FORMULARIO AÑADIR GASTO
============================================================ -->
<div class="dashboard-block">
    <h3>Añadir gasto</h3>

    <form action="/cotrip/plataforma/controlador/gastos_proc.php" method="POST">
        <input type="hidden" name="viaje_id" value="<?= $viaje_id ?>">

        <label>Título</label>
        <input type="text" name="titulo" required>

        <label>Cantidad (€)</label>
        <input type="number" step="0.01" name="cantidad" required>

        <label>Categoría</label>
        <input type="text" name="categoria">

        <label>Fecha</label>
        <input type="date" name="fecha" required>

        <label>Descripción</label>
        <textarea name="descripcion"></textarea>

        <button class="btn-primary-small">Añadir gasto</button>
    </form>
</div>



<!-- ============================================================
     LISTADO DE GASTOS
============================================================ -->
<div class="dashboard-block">
    <h3>📄 Lista de gastos</h3>

    <?php if (count($gastos) == 0): ?>
        <p>No hay gastos registrados.</p>
    <?php else: ?>

        <table style="width:100%; border-collapse:collapse; margin-top:10px;">
            <tr style="border-bottom:1px solid #ccc;">
                <th>Título</th>
                <th>Cantidad</th>
                <th>Pagado por</th>
                <th>Categoría</th>
                <th>Fecha</th>
            </tr>

            <?php foreach ($gastos as $g): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td><?= htmlspecialchars($g["titulo"]) ?></td>
                    <td><?= number_format($g["cantidad"], 2) ?> €</td>
                    <td><?= htmlspecialchars($g["nombre"]) ?></td>
                    <td><?= htmlspecialchars($g["categoria"]) ?></td>
                    <td><?= $g["fecha"] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>
</div>



<!-- ============================================================
     RESUMEN POR USUARIO
============================================================ -->
<div class="dashboard-block">
    <h3>📊 Resumen por persona</h3>

    <?php if (count($resumen) == 0): ?>
        <p>No hay datos.</p>
    <?php else: ?>

        <table style="width:100%; border-collapse:collapse; margin-top:10px;">
            <tr style="border-bottom:1px solid #ccc;">
                <th>Persona</th>
                <th>Total pagado</th>
            </tr>

            <?php foreach ($resumen as $r): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td><?= htmlspecialchars($r["nombre"]) ?></td>
                    <td><?= number_format($r["total_pagado"] ?: 0, 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>
</div>


<!-- VOLVER -->
<div style="margin-top:20px;">
    <a class="btn-primary-small" 
       href="/cotrip/plataforma/vista/viaje_dashboard.php?id=<?= $viaje_id ?>">
        ⬅ Volver al viaje
    </a>
</div>

<?php include("../../sistema/inc/footer.php"); ?>
