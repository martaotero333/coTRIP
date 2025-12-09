<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$usuario_id = $_SESSION["usuario_id"];

$gastoClass = new Gasto();
$gastos = $gastoClass->obtenerGastosUsuario($usuario_id);    
$totalPendiente = $gastoClass->totalUsuario($usuario_id);    

include("../../sistema/inc/header.php");
?>

<style>
    .gastos-container {
        width: 90%;
        max-width: 900px;
        margin: 30px auto;
    }

    .gastos-titulo {
        margin-bottom: 20px;
        font-size: 28px;
        font-weight: 600;
    }

    .gastos-total {
        background: #0077ff;
        color: white;
        padding: 18px 22px;
        border-radius: 10px;
        margin-bottom: 28px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }

    .gastos-total h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 500;
    }

    .gastos-tabla {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 5px rgba(0,0,0,0.07);
    }

    .gastos-tabla th {
        background: #f5f7fa;
        padding: 14px;
        border-bottom: 1px solid #e2e2e2;
        text-align: left;
        font-weight: 600;
        color: #444;
    }

    .gastos-tabla td {
        padding: 14px;
        border-bottom: 1px solid #efefef;
        font-size: 15px;
    }

    .estado-badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 13px;
    }

    .badge-pagado {
        background: #d4edda;
        color: #155724;
    }

    .badge-pendiente {
        background: #f8d7da;
        color: #721c24;
    }

    .gastos-vacio {
        margin-top: 25px;
        font-size: 15px;
        color: #777;
        text-align: center;
    }
</style>

<div class="gastos-container">

    <h2 class="gastos-titulo">Mis gastos</h2>

    <div class="gastos-total">
        <h3>Total pendiente: <?= number_format($totalPendiente, 2); ?> €</h3>
    </div>

    <?php if (count($gastos) === 0): ?>

        <p class="gastos-vacio">Actualmente no tienes gastos registrados.</p>

    <?php else: ?>

        <table class="gastos-tabla">
            <tr>
                <th>Viaje</th>
                <th>Concepto</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>

            <?php foreach ($gastos as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g["titulo_viaje"]); ?></td>
                    <td><?= htmlspecialchars($g["concepto"]); ?></td>
                    <td><?= number_format($g["cantidad"], 2); ?> €</td>
                    <td><?= htmlspecialchars($g["fecha"]); ?></td>

                    <td>
                        <?php if ($g["pagado"] == 1): ?>
                            <span class="estado-badge badge-pagado">Pagado ✔️</span>
                        <?php else: ?>
                            <span class="estado-badge badge-pendiente">Pendiente ❗</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</div>

<?php include("../../sistema/inc/footer.php"); ?>


